@extends('layouts.master')

@php
    $title = ($product->exists ? 'Edit' : 'Create') . ' Product';
@endphp

@section('title', $title)

@section('content')
<h1>{{ $title }}</h1>
<div class="text-left">
  @if ($product->exists)
  <form method="POST" action="{{ route('products.update', $product) }}">
    @method('PUT')
@else
  <form method="POST" action="{{ route('products.store') }}">
@endif
    @csrf
    <div class="form-group">
      <label for="name" class="@error('name') text-danger @enderror">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" aria-describedby="nameHelp" placeholder="Enter the product name" required maxlength="200">
      @error('name') <small id="nameHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="form-group">
      <label for="manufacturer" class="@error('manufacturer') text-danger @enderror">Manufacturer</label>
      <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $product->manufacturer) }}" aria-describedby="manufacturerHelp" placeholder="Enter the manufacturer name" required maxlength="200">
      @error('manufacturer') <small id="manufacturerHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>
    <div class="form-group">
      <label for="salePrice" class="@error('sale_price') text-danger @enderror">Sale Price</label>
      <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="salePrice" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" aria-describedby="salePriceHelp" placeholder="0,00">
      @error('sale_price') <small id="salePriceHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href={{ route('products.index') }} role="button">Back</a>
  </form>
</div>
@endsection