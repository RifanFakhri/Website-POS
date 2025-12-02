<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; 

class ProductStockController extends Controller
{
    /**
     * Menampilkan daftar stok produk.
     */
    public function index(Request $request)
    {
        // Data dummy untuk Category
        $categories = collect([
            (object)['id' => 1, 'name' => 'Elektronik'],
            (object)['id' => 2, 'name' => 'Pakaian'],
            (object)['id' => 3, 'name' => 'Makanan'],
        ]);

        // Ambil SEMUA produk untuk dropdown di modal
        $allProducts = Product::orderBy('name', 'asc')->get(['product_code', 'name']);

        // Ambil data stok dan eager load relasi produk
        $stocks = ProductStock::with('product')
            ->join('products', 'products.product_code', '=', 'product_stocks.product_code')
            ->select('product_stocks.*') 
            ->orderBy('products.created_at', 'desc');

        // Terapkan Search pada nama atau kode produk
        if ($search = $request->input('search')) {
            $stocks->where(function ($query) use ($search) {
                $query->where('products.name', 'like', "%{$search}%")
                      ->orWhere('products.product_code', 'like', "%{$search}%");
            });
        }

        // Terapkan Filter (berdasarkan category_id)
        if ($category_id = $request->input('category_id')) {
            if ($category_id != 'all') {
                $stocks->where('products.category_id', $category_id);
            }
        }
        
        $stocks = $stocks->paginate(10);
        $stocks->appends($request->except('page'));

        return view('pages.stocks', compact('stocks', 'categories', 'allProducts'));
    }

    /**
     * Menangani penambahan stok untuk produk yang sudah ada.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => ['required', 'string', 'exists:products,product_code'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::beginTransaction();
        try {
            // Cari atau buat entri stok berdasarkan product_code
            $stockEntry = ProductStock::firstOrNew(['product_code' => $request->product_code]);
            
            if (!$stockEntry->exists) {
                $stockEntry->product_code = $request->product_code;
                $stockEntry->stock = 0;
            }
            
            // Tambahkan kuantitas ke stok
            $stockEntry->stock += $request->quantity;
            $stockEntry->save();
            
            DB::commit();
            
            $productName = Product::where('product_code', $request->product_code)->value('name') ?? 'Produk';
            return redirect()->route('stocks.index')->with('success', 'Stok untuk **'.$productName.'** berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan stok: ' . $e->getMessage())->withInput();
        }
    }
}