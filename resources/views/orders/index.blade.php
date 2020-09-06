@extends('layouts.master')

@section('title', 'Orders')

@section('content')
<h1>Orders</h1>
<div class="text-left">
<a class="btn btn-primary mb-1" href={{ route('orders.create') }} role="button">Create</a>
<a class="btn btn-secondary mb-1" href={{ route('home') }} role="button">Home</a>
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col" width="10%">#</th>
        <th scope="col" width="45%">Client</th>
        <th scope="col" width="15%">Total Price</th>
        <th scope="col" width="15%">Date/Time</th>
        <th scope="col" width="15%">Actions</th>
      </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @foreach ($orders as $order)
                <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{{ $order->client->name }}</td>
                    <td>{{ number_format($order->totalPrice(), 2, ',', '.') }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <a class="btn btn-warning" href={{ route('orders.edit', $order) }} role="button">Edit</a>
                        <button class="btn btn-danger" onclick="document.getElementById('delete').submit()">Delete</button>
                        <form id="delete" action={{ route('orders.destroy', $order) }} method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10">No registered orders</td>
            </tr>
        @endif
    </tbody>
  </table>
</div>
@endsection