@extends('layouts.master')

@php
    $title = ($order->exists ? 'Edit' : 'Create') . ' Order';
@endphp

@section('title', $title)

@section('content')
<h1>{{ $title }}</h1>
<div class="text-left">
  @if ($order->exists)
  <form method="POST" action="{{ route('orders.update', $order) }}">
    @method('PUT')
  @else
  <form method="POST" action="{{ route('orders.store') }}">
  @endif
    @csrf
    <div class="form-group col-md-6">
      <label for="client_id" class="@error('client_id') text-danger @enderror">Client</label>
      @if ($order->exists)
      <input type="hidden" class="form-control" name="client_id" value="{{ $order->client->id }}" required/>
      <input type="text" class="form-control" value="{{ $order->client->name }}" readonly/>
      @else
      <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" aria-describedby="clientHelp" required>
        <option value="">Select the order's client</option>
        @foreach ($clients as $client)
        <option value="{{ $client->id }}" @if ($client->id == old('client_id', $order->client_id)) selected @endif>{{ $client->name }}</option>
        @endforeach
      </select>
      @error('client_id') <small id="clientHelp" class="form-text text-danger">{{ $message }}</small> @enderror
      @endif
    </div>
    <div class="form-group col-md-6">
      <label for="total_price">Total Price</label>
      <input type="number" class="form-control" id="total_price" step="0.01" readonly/>
    </div>
    <table class="table table-striped table-sm" id="items">
      <thead>
        <tr>
          <th scope="col" width="50%">Product</th>
          <th scope="col" width="10%">Quantity</th>
          <th scope="col" width="10%">Unit Price</th>
          <th scope="col" width="10%">Total Discount</th>
          <th scope="col" width="10%">Total Price</th>
          <th scope="col" width="10%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr data-template="true">
          <td style="display: none;">
            <input type="hidden" class="form-control" name="items[][id]">
          </td>
          <td>
            <select class="form-control" name="items[][product_id]">
              <option value="">Select a product</option>
              @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - ({{ $product->manufacturer }})</option>
              @endforeach
            </select>
          </td>
          <td>
            <input type="number" class="form-control" name="items[][quantity]" placeholder="0" min="1" disabled>
          </td>
          <td>
            <input type="number" class="form-control" name="items[][unit_price]" placeholder="0,00" step="0.01" min="0" disabled>
          </td>
          <td>
            <input type="number" class="form-control" name="items[][total_discount]" placeholder="0,00" step="0.01" min="0" disabled>
          </td>
          <td>
            <input type="number" class="form-control" data-total-price="true" placeholder="0,00" step="0.01" readonly>
          </td>
          <td>
              <button class="btn btn-danger" data-delete="true">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <button type="submit" class="btn btn-primary" data-submit="true">Save</button>
    <a class="btn btn-secondary" href={{ route('orders.index') }} role="button">Back</a>
  </form>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
(function(document) {

  document.addEventListener('DOMContentLoaded', () => {

    const saveButton     = document.querySelector('[data-submit="true"]');
    const orderForm      = document.querySelector('form');
    const itemsTable     = document.querySelector('#items');
    const itemsTableBody = itemsTable.querySelector('tbody');
    const itemTemplate   = itemsTableBody.querySelector('[data-template="true"]');
    const totalPrice     = document.querySelector('#total_price');

    itemTemplate.remove();
    itemTemplate.removeAttribute('data-template');

    const updateOrderTotalPrice = () => {
      let totalPriceSum = 0;
      getTotalPriceFields().forEach(totalPriceField => {
        if(totalPriceField.value) {
          totalPriceSum += parseFloat(totalPriceField.value);
        }
      });
      totalPrice.value = totalPriceSum.toFixed(2);
    };
    
    const onClickSaveButton = event => {
      event.preventDefault();
      resetValidityItems();
      if(!orderForm.checkValidity()) {
        return orderForm.reportValidity();
      }
      if(!reportValidityFirstItem() && !reportValidityUniqueProduct() && !reportValidityTotalPrice()) {
        removeRowsWithoutProduct();
        reorganizeFieldNames();
        orderForm.submit();
      }
    };

    const hasItems = () => {
      return getProductFields().some(hasValue);
    };

    const resetValidityItem = productField => {
      productField.setCustomValidity('');
    };
    
    const resetValidityItems = () => {
      itemsTableBody.querySelectorAll('option[name],input[name]').forEach(resetValidityItem);
    };

    const reportValidityFirstItem = () => {
      let reportValidity = false;
      if(!hasItems()) {
        const productField = getProductFields().shift();
        productField.setCustomValidity('Informe pelo menos um item para o pedido.');
        productField.reportValidity();
        reportValidity = true;
      }
      return reportValidity;
    };

    const reportValidityUniqueProduct = () => {
      let reportValidity = false;
      const productFields = getProductFields().filter(productField => productField.value.trim().length);
      const products = productFields.map(productField => productField.value);
      const duplicity = products.findIndex((product, index) => products.indexOf(product) < index);
      if(duplicity >= 0) {
        const productField = productFields[duplicity];
        productField.setCustomValidity('Já existe um item para esse produto.');
        productField.reportValidity();
        reportValidity = true;
      }
      return reportValidity;
    };

    const reportValidityTotalPrice = () => {
      let reportValidity = false;
      getTotalPriceFields().filter(totalPriceField => hasValue(totalPriceField)).forEach(totalPriceField => {
        if(!reportValidity && parseFloat(totalPriceField.value) < 0) {
          const totalDiscountField = getRow(totalPriceField).querySelector('[name*="[total_discount]"]');
          totalDiscountField.setCustomValidity('O desconto não pode gerar preço negativo.');
          totalDiscountField.reportValidity();
          reportValidity = true;
        }
      });
      return reportValidity;
    };

    const removeRowsWithoutProduct = () => {
      getProductFields().filter(field => !hasValue(field)).forEach(field => {
        const row = getRow(field);
        row.querySelectorAll('select,input').forEach(fieldElement => fieldElement.removeAttribute('name'));
      });
    };

    const reorganizeFieldNames = () => {
      getProductFields().filter(field => field.getAttribute('name')).forEach((field, index) => {
        const row = getRow(field);
        row.querySelectorAll('select[name],input[name]').forEach(fieldElement => fieldElement.setAttribute('name', fieldElement.getAttribute('data-default-name').replace('[?]', `[${index}]`)));
      });
    };

    const deleteItem = productField => {
      getRow(productField).remove();
      updateDeleteState();
      updateOrderTotalPrice();
    };

    const getRow = productField => {
      return productField.parentElement.parentElement;
    };

    const hasValue = productField => {
      return productField && productField.value && productField.value.trim().length > 0;
    };

    const getProductFields = () => {
      return Array.prototype.slice.call(itemsTableBody.querySelectorAll('[name*="[product_id]"]'));
    };

    const getTotalPriceFields = () => {
      return Array.prototype.slice.call(itemsTableBody.querySelectorAll('[data-total-price="true"]'));
    };

    const updateExtraItem = () => {
      const lastProductField = getProductFields().pop();
      if(!lastProductField || hasValue(lastProductField)) {
        addItem();
      } else {
        updateDeleteState();
      }
    };

    const updateDeleteStateItem = productField => {
      const productFieldRow = getRow(productField);
      const itemDeleteButton = productFieldRow.querySelector('[data-delete="true"]');

      switch(true) {
        case productFieldRow == itemsTableBody.firstElementChild && itemsTableBody.childElementCount == 1:
          itemDeleteButton.setAttribute('title', 'Is not possible delete the unique item');
          itemDeleteButton.setAttribute('disabled', true);
          break;
        case productFieldRow == itemsTableBody.lastElementChild && (itemsTableBody.childElementCount > 2 || hasValue(itemsTableBody.firstElementChild.querySelector('[name*="[product_id]"]'))):
          itemDeleteButton.setAttribute('title', 'Is not possible delete the last item');
          itemDeleteButton.setAttribute('disabled', true);
          break;
        default:
          itemDeleteButton.removeAttribute('title');
          itemDeleteButton.removeAttribute('disabled');
          break;
      }
    };
    
    const updateDeleteState = () => {
      getProductFields().forEach(updateDeleteStateItem);
    };
    
    const addItem = (data, error) => {
      const item = itemTemplate.cloneNode(true);
      const id = itemsTableBody.childElementCount;

      const idField = item.querySelector('[name*="[id]"]');
      const itemProductField = item.querySelector('[name*="[product_id]"]');
      const quantityField = item.querySelector('[name*="[quantity]"]');
      const unitPriceField = item.querySelector('[name*="[unit_price]"]');
      const totalDiscountField = item.querySelector('[name*="[total_discount]"]');
      const totalPriceField = item.querySelector('[data-total-price="true"]');
      const itemDeleteButton = item.querySelector('[data-delete="true"]');

      const nameAttribute = (property, id) => {
        return `items[${id}][${property}]`;
      };

      const updateName = (element, name) => {
        element.setAttribute('name', nameAttribute(name, id));
        element.setAttribute('data-default-name', nameAttribute(name, '?'));
      };

      updateName(idField, 'id');
      updateName(itemProductField, 'product_id');
      updateName(quantityField, 'quantity');
      updateName(unitPriceField, 'unit_price');
      updateName(totalDiscountField, 'total_discount');
      itemProductField.removeAttribute('required');

      const updateFieldState = (elementField, required) => {
        if(hasValue(itemProductField)) {
          elementField.removeAttribute('disabled');
          elementField.setAttribute('required', true);
        } else {
          required = false;
          elementField.setAttribute('disabled', true);
          elementField.value = '';
        }
        if(!required) {
          elementField.removeAttribute('required');
        }
      };

      const onChangeProductFieldWithoutUpdateExtraItem = () => {
        resetValidityItem(itemProductField);
        updateFieldState(quantityField, true);
        updateFieldState(unitPriceField, true);
        updateFieldState(totalDiscountField, false);
        updateTotalPrice();
      };

      const onChangeProductField = () => {
        onChangeProductFieldWithoutUpdateExtraItem();
        updateExtraItem();
      };

      const onBlurProductField = () => {
        resetValidityItem(itemProductField);
      };

      const onBlurTotalDiscount = () => {
        resetValidityItem(totalDiscountField);
      };

      const onClickDeleteButton = () => {
        if(!itemDeleteButton.getAttribute('disabled')) {
          deleteItem(itemProductField);
        }
      };

      const updateTotalPrice = () => {
        if(hasValue(unitPriceField) && hasValue(quantityField)) {
          const totalPrice = (parseFloat(unitPriceField.value) * parseInt(quantityField.value)) - parseFloat(totalDiscountField.value || 0);
          totalPriceField.value = totalPrice.toFixed(2);
        } else {
          totalPriceField.value = '';
        }
        updateOrderTotalPrice();
      };

      itemProductField.addEventListener('change', onChangeProductField);
      itemProductField.addEventListener('blur', onBlurProductField);
      unitPriceField.addEventListener('change', updateTotalPrice);
      quantityField.addEventListener('change', updateTotalPrice);
      totalDiscountField.addEventListener('change', updateTotalPrice);
      totalDiscountField.addEventListener('input', onBlurTotalDiscount);
      totalDiscountField.addEventListener('blur', onBlurTotalDiscount);
      itemDeleteButton.addEventListener('click', onClickDeleteButton);
      itemsTableBody.appendChild(item);
      
      if(data) {
        itemProductField.querySelectorAll('option').forEach(option => {
          if(option.value == data.product_id) {
            option.setAttribute('selected', true);
          } else {
            option.removeAttribute('selected');
          }
        });
        itemProductField.setAttribute('readonly', true);
        idField.value = data.id;
        quantityField.value = data.quantity;
        unitPriceField.value = data.unit_price;
        if(data.total_discount > 0) {
          totalDiscountField.value = data.total_discount;
        }
        if(error) {
          itemProductField.classList.add('is-invalid');
        }
        onChangeProductFieldWithoutUpdateExtraItem();
      } else {
        updateDeleteState();
      }

    };

    saveButton.addEventListener('click', onClickSaveButton);

    @php
    $items = old('items', $order->exists ? $order->items : []);
    @endphp
    @foreach ($items as $i => $item)
      addItem({!! (is_array($item) ? collect($item) : $item)->toJson() !!}, {{ $errors->has("items.{$i}.*") ? 'true' : 'false' }});
    @endforeach
    updateExtraItem();

  });
  
}(document));
</script>    
@endsection