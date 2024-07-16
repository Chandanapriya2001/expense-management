@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('Expense') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Contact">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('SlNo') }}
                        </th>
                        <th>
                            {{ trans('User') }}
                        </th>
                        <th>
                            {{ trans('Item') }}
                        </th>
                        <th>
                            {{ trans('Type') }}
                        </th>
                        <th>
                            {{ trans('Amount') }}
                        </th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $count=0;?>
                    @foreach($expenses as $key => $contact)
                        <tr data-entry-id="{{ $contact->id }}">
                            <td>

                            </td>
                            <td>
                                {{ ++$count }}
                            </td>
                            <td>
                                {{ $contact->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $contact->item ?? '' }}
                            </td>
                            <td>
                                {{ $contact->type ?? '' }}
                            </td>
                            <td>
                                {{ $contact->amount ?? '' }}
                            </td>
                            
                           

                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3"></th>
                        <th colspan="1">{{ trans('Total') }}</th>
                        <th colspan="1">{{ $total }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.expenses.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Contact:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection