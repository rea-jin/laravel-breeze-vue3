<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 検索時のクエリパラメータを取得
        $search = $request->input('search');
        if ($search) {
            // クエリパラメータが存在する場合は、nameカラムに対して部分一致検索を行う
            $products = Product::where('name', 'like', '%' . $search . '%')->orderBy('created_at', 'asc')->paginate(5);
        } else {
            // クエリパラメータが存在しない場合は、全てのレコードを取得する
            // $products = Product::all();
            $products = Product::orderBy('created_at', 'asc')->paginate(5);
        }
        // dd($products);
        return Inertia::render('Products/Index',[
            'products' => $products,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 商品作成画面を表示する
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        //
        // dd($request->all());
        $product = new Product($request->validated());
        $product->save();
        // return redirect('products');
        return redirect()->route('products.index')->with('success_str', '登録完了しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        return Inertia::render('Products/Edit',['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        //
        $product->update($request->validated());
        // return redirect('products');
        return redirect()->route('products.index')->with('success_str', '更新完了しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
        return redirect('products');
    }
}
