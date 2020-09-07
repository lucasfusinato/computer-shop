@extends('layouts.master')

@section('title', 'Clients')

@section('content')
<h1>Clients</h1>
<div class="text-left">
    <a class="btn btn-primary mb-1" href={{ route('clients.create') }} role="button">Create</a>
<a class="btn btn-secondary mb-1" href={{ route('home') }} role="button">Home</a>
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col" width="5%">#</th>
        <th scope="col" width="15%">Name</th>
        <th scope="col" width="10%">CPF</th>
        <th scope="col" width="7.5%">CEP</th>
        <th scope="col" width="10%">State</th>
        <th scope="col" width="7.5%">City</th>
        <th scope="col" width="7.5%">District</th>
        <th scope="col" width="10%">Street</th>
        <th scope="col" width="5%">Number</th>
        <th scope="col" width="5%">Discount</th>
        <th scope="col" width="12.5%">Actions</th>
      </tr>
    </thead>
    <tbody>
        @if (count($clients) > 0)
            @foreach ($clients as $client)
                <tr>
                    <th scope="row">{{ $client->id }}</th>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->cpf }}</td>
                    <td>{{ $client->cep }}</td>
                    <td>{{ $client->state->name }}</td>
                    <td>{{ $client->city }}</td>
                    <td>{{ $client->district }}</td>
                    <td>{{ $client->street }}</td>
                    <td>{{ $client->number }}</td>
                    <td>{{ $client->default_discount > 0 ? $client->default_discount . '%' : '' }}</td>
                    <td>
                        <a class="btn btn-warning" href={{ route('clients.edit', $client) }} role="button">Edit</a>
                        <button class="btn btn-danger" onclick="document.getElementById('delete_{{ $client->id }}').submit()">Delete</button>
                        <form id="delete_{{ $client->id }}" action={{ route('clients.destroy', $client) }} method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="11">No registered clients</td>
            </tr>
        @endif
    </tbody>
  </table>
</div>
@endsection