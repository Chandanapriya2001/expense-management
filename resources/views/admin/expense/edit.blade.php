@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('Expense') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.expenses.update", [$expense->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
              <label class="required" for="name">{{ trans('Item') }}</label>
              <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="item" id="name" value="{{ old('item', $expense->item) }}" required>
              @if($errors->has('name'))
                  <div class="invalid-feedback">
                      {{ $errors->first('name') }}
                  </div>
              @endif
             
          </div>
          <div class="form-group">
              <label class="required" for="email">{{ trans('Type') }}</label>
             <select name="type" id="" class="form-control select2">
              <option value="{{ $expense->type }}">{{ $expense->type }}</option>
              <option value="Income">Income</option>
              <option value="Expense">Expense</option>
             </select>
             
          </div>
          
        
          <div class="form-group">
              <label for="phone_no">{{ trans('Amount') }}</label>
              <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="text" name="amount" value="{{ old('amount', $expense->amount) }}">
             
              
          </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
