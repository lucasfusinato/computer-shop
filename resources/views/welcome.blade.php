@extends('layouts.master')

@section('title', 'Computer Shop')

@section('content')
    <h1>Computer Shop</h1>
    <div class="card-deck row d-flex justify-content-center">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Products</div>
            <div class="card-body">
                <h5 class="card-title">Product registration</h5>
                <p class="card-text">Create, read, update and delete products.</p>
                <a href={{ route('products.index') }} class="btn btn-primary stretched-link">Open</a>
            </div>
          </div>
    </div>
@endsection