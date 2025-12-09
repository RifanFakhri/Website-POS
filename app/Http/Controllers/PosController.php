<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection; 
use App\Models\ProductStock; // Pastikan ProductStock diimpor

class PosController extends Controller
{
    /**
     * Tampilkan halaman Point of Sale (POS) dengan daftar produk yang difilter.
     */
    public function index(Request $request)
    {
        // ... (DATA DUMMY CATEGORIES tetap sama) ...
        $categories = collect([
            (object)['id' => 1, 'name' => 'Elektronik'],
            (object)['id' => 2, 'name' => 'Pakaian'],
            (object)['id' => 3, 'name' => 'Makanan'],
            (object)['id' => 4, 'name' => 'Minuman'],
            (object)['id' => 5, 'name' => 'Makanan Ringan'],
            (object)['id' => 6, 'name' => 'Alat Tulis Kantor (ATK)'],
            (object)['id' => 7, 'name' => 'Produk Kebersihan'],
        ]);
        // ------------------------------------------------------------------------

        $products = Product::query();
        
        // Tambahkan EAGER LOADING untuk STOK
        $products->with('stock');
        
        // 1. Filter Kategori (menggunakan category_id)
        if ($category_id = $request->input('category_id')) {
            if ($category_id != 'all') {
                $products->where('category_id', $category_id);
            }
        } 

        // 2. Filter Pencarian (menggunakan search)
        if ($search = $request->input('search')) {
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%");
            });
        }
        
        // Tambahkan filter HANYA produk yang stoknya > 0 (Opsional, tapi disarankan untuk POS)
        // $products->whereHas('stock', function($query) {
        //     $query->where('stock', '>', 0);
        // });
        
        $products = $products->orderBy('created_at', 'asc')->paginate(16);
        $products->appends($request->except('page'));

        return view('pages.index', compact('products', 'categories'));
    }
}