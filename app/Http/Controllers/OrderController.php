<?php

namespace App\Http\Controllers;

use App\Order;
use App\Client;
use App\Product;
use App\Http\Requests\OrderForm;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all()->sortByDesc('created_at');
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->edit(new Order());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OrderForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderForm $request)
    {
        return $this->update($request, new Order());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $clients = Client::all()->sortBy('name');
        $products = Product::all()->sortBy('name');
        return view('orders.edit', compact('order', 'clients', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OrderForm  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderForm $request, Order $order)
    {
        $request->persist($order);
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }
}
