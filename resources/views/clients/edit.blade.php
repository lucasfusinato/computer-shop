@extends('layouts.master')

@php
    $title = ($client->exists ? 'Edit' : 'Create') . ' Client';
@endphp

@section('title', $title)

@section('content')
<h1>{{ $title }}</h1>
<div class="text-left">
  @if ($client->exists)
  <form method="POST" action="{{ route('clients.update', $client) }}">
    @method('PUT')
@else
  <form method="POST" action="{{ route('clients.store') }}">
@endif
    @csrf
    <div class="form-group">
      <label for="name" class="@error('name') text-danger @enderror">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $client->name) }}" aria-describedby="nameHelp" placeholder="Enter the client name" required maxlength="200">
      @error('name') <small id="nameHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
      <label for="cpf" class="@error('cpf') text-danger @enderror">CPF</label>
      <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf', $client->cpf) }}" aria-describedby="cpfHelp" placeholder="000.000.000-00" required minlength="14" maxlength="14">
      @error('cpf') <small id="cpfHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="form-group">
      <label for="cep" class="@error('cep') text-danger @enderror">CEP</label>
      <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep', $client->cep) }}" aria-describedby="cepHelp" placeholder="00000-000" minlength="9" maxlength="9">
      @error('cep') <small id="cepHelp" class="form-text text-danger">{{ $message }}</small> @enderror
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="state_id" class="@error('state_id') text-danger @enderror">State</label>
        <select class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id" aria-describedby="stateHelp" required>
          <option value="">Select the client address's state</option>
          @foreach ($states as $state)
            <option value="{{ $state->id }}" data-uf="{{ $state->uf }}" @if ($state->id == old('state_id', $client->state_id)) selected @endif>{{ $state->name }}</option>
          @endforeach
        </select>
        @error('state_id') <small id="stateHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>
      
      <div class="form-group col-md-6">
        <label for="city" class="@error('city') text-danger @enderror">City</label>
        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $client->city) }}" aria-describedby="cityHelp" placeholder="Enter the client address's city" required maxlength="200">
        @error('city') <small id="cityHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-5">
        <label for="district" class="@error('district') text-danger @enderror">District</label>
        <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district', $client->district) }}" aria-describedby="districtHelp" placeholder="Enter the client address's district" maxlength="200">
        @error('district') <small id="districtHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>
      
      <div class="form-group col-md-5">
        <label for="street" class="@error('street') text-danger @enderror">Street</label>
        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', $client->street) }}" aria-describedby="streetHelp" placeholder="Enter the client address's street" maxlength="200">
        @error('street') <small id="streetHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>
      
      <div class="form-group col-md-2">
        <label for="number" class="@error('number') text-danger @enderror">Number</label>
        <input type="number" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number', $client->number) }}" aria-describedby="numberHelp" placeholder="0" min="0">
        @error('number') <small id="numberHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="form-group col-md-2">
        <label for="default_discount" class="@error('default_discount') text-danger @enderror">Default Discount (%)</label>
        <input type="number" class="form-control @error('default_discount') is-invalid @enderror" id="default_discount" name="default_discount" value="{{ old('default_discount', $client->default_discount) }}" aria-describedby="defaultDiscountHelp" placeholder="0" min="0" max="100">
        @error('default_discount') <small id="defaultDiscountHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" href={{ route('clients.index') }} role="button">Back</a>
  </form>
</div>
@endsection

@section('scripts')
<script>
(function(document) {

  const findCepData = cep => {
    return new Promise((resolve, reject) => {
      const oHttpRequest = new XMLHttpRequest();
      oHttpRequest.onreadystatechange = function () {
          if (oHttpRequest.readyState === 4) {
              if (oHttpRequest.status === 200) {
                  const dados = JSON.parse(oHttpRequest.responseText);
                  if (dados.erro) {
                      reject();
                  } else {
                      resolve(dados);
                  }
              } else {
                  reject();
              }
          }
      };
      oHttpRequest.open('get', `https://viacep.com.br/ws/${cep}/json/`, true);
      oHttpRequest.send();
    });
  };

  const handleStateSelection = (stateOption, uf) => {
    if(stateOption.getAttribute('data-uf') === uf) {
      stateOption.setAttribute('selected', true);
    } else {
      stateOption.removeAttribute('selected');
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    const cepField = document.querySelector('#cep');
    const stateField = document.querySelector('#state_id');
    const stateFieldOptions = stateField.querySelectorAll('option');
    const cityField = document.querySelector('#city');
    const districtField = document.querySelector('#district');
    const streetField = document.querySelector('#street');

    cepField.addEventListener('change', () => {
      if(cepField.checkValidity()) {
        findCepData(cepField.value).then(cepData => {
          stateFieldOptions.forEach(stateOption => handleStateSelection(stateOption, cepData.uf));
          cityField.value = cepData.localidade;
          districtField.value = cepData.bairro;
          streetField.value = cepData.logradouro;
        });
      }
    });
    
  });
  
}(document));
</script>    
@endsection