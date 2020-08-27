@extends('layouts.master')

@section('title', 'Products')

@section('content')
<a class="btn btn-primary mb-1" href={{ route('products.create') }} role="button">Create</a>
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col" width="10%">#</th>
        <th scope="col" width="35%">Name</th>
        <th scope="col" width="35%">Manufacturer</th>
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
                    <td>
                        <a class="btn btn-warning" href={{ route('products.edit', $product) }} role="button">Edit</a>
                        <button class="btn btn-danger" onclick="document.getElementById('delete').submit()">Destroy</button>
                        <form id="delete" action={{ route('products.destroy', $product) }} method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No registered products</td>
            </tr>
        @endif
    </tbody>
  </table>
@endsection