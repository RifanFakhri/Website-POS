<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk (Hanya detail produk).
     */
    public function index(Request $request)
    {
        // Data kategori DIUBAH menjadi Laravel Collection
        $categories = collect([
            (object)['id' => 1, 'name' => 'Elektronik'],
            (object)['id' => 2, 'name' => 'Pakaian'],
            (object)['id' => 3, 'name' => 'Makanan'],
        ]);

        $products = Product::query();

        // 1. Filter Kategori
        if ($category_id = $request->input('category_id')) {
            if ($category_id != 'all') {
                $products->where('category_id', $category_id);
            }
        }
        
        // 2. Filter Pencarian (menggunakan closure untuk grouping OR)
        if ($search = $request->input('search')) {
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(10);
        $products->appends($request->except('page'));

        // Mengarah ke pages/products.blade.php
        return view('pages.products', compact('products', 'categories'));
    }

    /**
     * Menyimpan data produk baru (membuat stok awal 0) dengan Gambar.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Validasi Gambar
        ]);

        DB::beginTransaction();
        $imagePath = null; // Path sementara di storage/app/public/products
        try {
            // 1. Upload Gambar jika ada
            if ($request->hasFile('image')) {
                // Simpan file di storage/app/public/products
                $imagePath = $request->file('image')->store('public/products');
                // Simpan path yang dapat diakses publik (menggunakan /storage link)
                $validatedData['image'] = Storage::url($imagePath); 
            } else {
                $validatedData['image'] = null;
            }

            // 2. Buat Produk 
            $product = Product::create($validatedData);

            // 3. Inisialisasi Stok Awal
            ProductStock::firstOrCreate(
                ['product_code' => $product->product_code], 
                ['stock' => 0]
            );

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan! Stok diinisialisasi ke 0.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus gambar yang sudah terupload jika transaksi gagal
            if (isset($imagePath) && Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
            return back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Memperbarui detail produk dan gambar.
     */
    public function update(Request $request, Product $product)
    {
        // Inisialisasi variabel
        $validatedData = [];
        $newImagePath = null;

        // Path gambar lama di storage (dari URL publik ke path private storage)
        $oldImagePath = $product->image ? str_replace('/storage', 'public', $product->image) : null; 

        DB::beginTransaction();

        try {
            // 1. Validasi Data
            $validatedData = $request->validate([
                // PENTING: product_code sekarang terkirim karena 'disabled' dihapus di view
                'product_code' => ['required', 'string', 'max:50', Rule::unique('products', 'product_code')->ignore($product->id)], 
                'name' => ['required', 'string', 'max:255'],
                'category_id' => ['required', 'integer'],
                'price' => ['required', 'numeric', 'min:0'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Validasi Gambar
                'remove_image' => ['nullable', 'string'], // Menerima '1' jika dicentang
            ]);
            
            // Hapus 'remove_image' dari data update agar tidak di update ke database product
            $removeImageRequest = $validatedData['remove_image'] ?? null;
            unset($validatedData['remove_image']); 

            // 2. Upload Gambar Baru jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
                
                $newImagePath = $request->file('image')->store('public/products');
                $validatedData['image'] = Storage::url($newImagePath);
            } 
            // 3. Hapus Gambar Lama jika diceklis DAN TIDAK ADA upload baru
            else if ($removeImageRequest == '1') {
                 if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                 }
                $validatedData['image'] = null; // Set field image di DB menjadi NULL
            } 
            // 4. Pertahankan gambar lama jika tidak ada upload baru & tidak ada permintaan hapus
            else {
                // Hapus kolom 'image' dari $validatedData agar tidak menimpa dengan null atau data lama yang tidak perlu (Laravel akan menggunakan nilai yang sudah ada)
                if (isset($validatedData['image'])) {
                     unset($validatedData['image']);
                }
            }
            
            // 5. Update Produk
            $product->update($validatedData);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Detail produk berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            // Hapus gambar baru jika validasi gagal setelah upload
            if ($newImagePath && Storage::exists($newImagePath)) {
                Storage::delete($newImagePath);
            }
            // Kirim pesan error yang lebih jelas dari validation
            return back()->with('error', 'Gagal memperbarui produk: Terjadi kesalahan validasi input.')->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus gambar baru jika transaksi gagal
            if ($newImagePath && Storage::exists($newImagePath)) {
                Storage::delete($newImagePath);
            }
            
            return back()->with('error', 'Gagal memperbarui produk. Detail: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus produk dan stok terkait.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        // Konversi path URL publik ke path storage untuk menghapus file fisik
        $imagePath = $product->image ? str_replace('/storage', 'public', $product->image) : null;
        try {
            // Hapus gambar fisik terkait (jika ada)
            if ($imagePath && Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
            
            // Hapus stok terkait
            if (method_exists($product, 'stock')) {
                 $product->stock()->delete(); 
            }
            
            // Hapus produk
            $product->delete();
            
            DB::commit();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}