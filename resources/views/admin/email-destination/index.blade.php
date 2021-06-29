@extends('layouts.admin')
@section('content')
    @can('email_destination_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.email-destination.create") }}">
                {{ trans('global.add') }} Email
            </a>
        </div>
    </div>
    @endcan

    <br>
    <div class="card">
        <div class="card-header">
            Email {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-HistoryShip">
                <thead>
                <tr>
                    <th width="10">
                        No
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;Email
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;Created
                    </th>
                    <th style="min-width: 100px;">
                        &nbsp;Updated
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($email as $key => $emails)
                    <tr>
                        <td>
                            {{$key + 1}}
                        </td>
                        <td>

                            @can('email_destination_edit')
                                <a class="btn btn-xs btn-info" href="{{ route('admin.email-destination.edit', $emails->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                            @endcan

                            @can('email_destination_delete')
                                <form action="{{ route('admin.email-destination.destroy', $emails->id) }}" method="POST"
                                      onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                      style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger"
                                           value="{{ trans('global.delete') }}">
                                </form>
                            @endcan
                        </td>
                        <td>
                            {{$emails->email}}
                        </td>
                        <td>
                            {{$emails->created_at}}
                        </td>
                        <td>
                            {{$emails->updated_at}}
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5"> No Data Found</td>
                        </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('email_destination_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.email-destination.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).data(), function (entry) {
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
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan
        });

    </script>
@endsection
