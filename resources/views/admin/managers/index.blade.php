@extends('layouts.admin')
@section('content')
@can('manager_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.managers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.manager.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.manager.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Manager">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th style="min-width: 20px;">
                        {{ trans('cruds.manager.fields.id') }}
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;
                    </th>
                    <th style="min-width: 150px;">
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th style="min-width: 100px">
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <th>
                        {{ trans('cruds.manager.fields.user') }}
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('manager_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.managers.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.managers.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'actions', name: '{{ trans('global.actions') }}' },
{ data: 'manager_name', name: 'manager.name' },
{data: 'role_manager', name: 'role_manager'},
{ data: 'user', name: 'users.name' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  $('.datatable-Manager').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection
