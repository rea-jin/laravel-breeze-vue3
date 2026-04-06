<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use Inertia\Inertia;
use App\Http\Resources\OrderResource;
// use Symfony\Component\HttpFoundation\Request;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // validated() を使うには FormRequest が必要です。今は Request $request（通常のリクエスト）なので使えません。
        // $orders = OrderResource::collection(Order::paginate(5));
        // return Inertia::render('Orders/Index',[
        //     'orders' => $orders
        // ]);
        //$orders = OrderResource::collection(Order::paginate(5));
        if(empty($request->input('search_str'))) {
            $search_str=null;
            $orders = OrderResource::collection(
                Order::orderBy('id', 'desc')
                ->paginate(5)
            );
        }else{
            // customerはorderモデルに定義されているリレーションの名前
            $search_str=$request->input()['search_str'];
            $orders = Order::whereHas('customer', function ($query) use ($search_str) {
                $query->where('name', 'LIKE', '%' . $search_str . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

            $orders = OrderResource::collection($orders);
        }

        return Inertia::render('Orders/Index',[
            'orders' => $orders,
            'search_str' => $search_str,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $customers = Customer::all();
        $products = Product::all();
        return Inertia::render('Orders/Create',[
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new Order($request->input());
        $order->orderday = date("Y-m-d H:i:s");
        $order->save();
        return redirect()->route('orders.index')->with('success_str', '登録完了しました');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
