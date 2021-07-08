<!--@can('history_ship_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.history-ships.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.historyShip.title_singular') }}
            </a>
        </div>
    </div>
@endcan-->

<div class="card">
    <div class="card-header">
        {{ trans('cruds.historyShip.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-HistoryShip">
                <thead>
                    <tr>
<th></th>
                        <th>
                            {{ trans('cruds.historyShip.fields.id') }}
                        </th>
                        <th>Action</th>
                        <th>
                                Display To Map
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.history_ids') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.min') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.sin') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.region_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.receive_utc') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.message_utc') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.ship') }}
                        </th>
                        <th>
                            {{ trans('cruds.ship.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.payload') }}
                        </th>
                        <th>
                            {{ trans('cruds.historyShip.fields.ota_message_size') }}
                        </th>
                        <!--<th>
                            &nbsp;
                        </th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach($historyShips as $key => $historyShip)
                        <tr data-entry-id="{{ $historyShip->id }}">
                            <td></td>
                            <td>
                                {{ $historyShip->id ?? '' }}
                            </td>
                            <td>
                                <a class='btn btn-xs btn-primary' href="{{url('admin/history-ships/'. $historyShip->id .'/edit')}}">Edit</a>
                            </td>
                            <td>
                                {!! $historyShip->display_to_map == 1 ? "<a class='btn btn-xs btn-primary' href='". url('/admin/change-display/'.$historyShip->id)."'>Show</a>": "<a class='btn btn-xs btn-info' href='". url('/admin/change-display/'.$historyShip->id)."'>Hide</a>" !!}
                            </td>
                            <td>
                                {{ $historyShip->history_ids ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->min ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->sin ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->region_name ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->receive_utc ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->message_utc ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->ship->ship_ids ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->ship->name ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->payload ?? '' }}
                            </td>
                            <td>
                                {{ $historyShip->ota_message_size ?? '' }}
                            </td>
                            <!--<td>
                                @can('history_ship_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.history-ships.show', $historyShip->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('history_ship_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.history-ships.edit', $historyShip->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('history_ship_delete')
                                    <form action="{{ route('admin.history-ships.destroy', $historyShip->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>-->

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
@can('history_ship_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.history-ships.massDestroy') }}",
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
    pageLength: 10,
  });
  $('.datatable-HistoryShip:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
