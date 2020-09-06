@extends('layouts.master')

@section('title', 'Products')

@section('content')
<h1>Products</h1>
<div class="text-left">
    <a class="btn btn-primary mb-1" href={{ route('products.create') }} role="button">Create</a>
<a class="btn btn-secondary mb-1" href={{ route('home') }} role="button">Home</a>
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col" width="10%">#</th>
        <th scope="col" width="30%">Name</th>
        <th scope="col" width="30%">Manufacturer</th>
        <th scope="col" width="10%">Sale Price</th>
        <th scope="col" width="20%">Actions</th>
      </tr>
    </thead>
    <tbody>
        @if (count($products) > 0)
            @foreach ($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->manufacturer }}</td>
                    <td>{{ isset($product->sale_price) ? number_format($product->sale_price, 2, ',', '.') : '' }}</td>
                    <td>
                        <a class="btn btn-warning" href={{ route('products.edit', $product) }} role="button">Edit</a>
                        <button class="btn btn-danger" onclick="document.getElementById('delete').submit()">Delete</button>
                        <form id="delete" action={{ route('products.destroy', $product) }} method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">No registered products</td>
            </tr>
        @endif
    </tbody>
  </table>
</div>
@endsection