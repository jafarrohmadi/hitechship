@can('terminal_ship_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.terminal-ships.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.terminalShip.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.terminalShip.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-TerminalShip">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.terminalShip.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.terminalShip.fields.ship') }}
                        </th>
                        <th>
                            {{ trans('cruds.terminalShip.fields.terminal') }}
                        </th>
                        <th>
                            {{ trans('cruds.terminalShip.fields.arrive_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.terminalShip.fields.departure_time') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terminalShips as $key => $terminalShip)
                        <tr data-entry-id="{{ $terminalShip->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $terminalShip->id ?? '' }}
                            </td>
                            <td>
                                @foreach($terminalShip->ships as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($terminalShip->terminals as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $terminalShip->arrive_time ?? '' }}
                            </td>
                            <td>
                                {{ $terminalShip->departure_time ?? '' }}
                            </td>
                            <td>
                                @can('terminal_ship_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.terminal-ships.show', $terminalShip->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('terminal_ship_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.terminal-ships.edit', $terminalShip->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('terminal_ship_delete')
                                    <form action="{{ route('admin.terminal-ships.destroy', $terminalShip->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('terminal_ship_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.terminal-ships.massDestroy') }}",
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
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-TerminalShip:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection