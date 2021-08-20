@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.ship.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-Ship">
                    <thead>
                    <tr>
                        <th></th>
                        <th>
                            {{ trans('cruds.ship.fields.id') }}
                        </th>
                        <th style="min-width: 150px;">
                            &nbsp;
                        </th>
                        <th width="150">
                            Last Seen Time
                        </th>
                        <th width="150">
                            Last Seen Destination
                        </th>
                        <th width="150">
                            Last Seen Status
                        </th>
                        <th>
                            Subject
                        </th>
                        <th width="150">
                            Filename (.chr)
                        </th>
                        <th width="40">
                           Content
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ships as $key => $ship)
                        <tr>
                            <td></td>
                            <td>
                                {{$key +  1}}
                            </td>
                            <td>
                                Send Manual
                            </td>
                            <th width="150">
                                {{date('d F Y H:i', strtotime($ship->created_at))}}
                            </th>
                            <th width="150">
                                {{$ship->last_sent_destination ?? ''}}
                            </th>
                            <th width="150">
                                {{$ship->last_sent_status ?? ''}}
                            </th>
                            <th>
                                {{$ship->subject ?? ''}}
                            </th>
                            <th width="150">
                                {{$ship->filename_chr}}
                            </th>
                            <th width="40">
                                {{$ship->content}}
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
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

            $.extend(true, $.fn.dataTable.defaults, {
                order: [[1, 'desc']],
                pageLength: 100,
            });
            $('.datatable-Ship:not(.ajaxTable)').DataTable()

        })

    </script>
@endsection
