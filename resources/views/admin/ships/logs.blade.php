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
                                <button type="button" class="open-AddBookDialog btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal"
                                        data-id={{$ship->id}} data-destination="{{$ship->last_sent_destination}}"
                                        data-subject="{{$ship->subject}}" data-filename="{{$ship->filename_chr}}"
                                        data-content="{{$ship->content}}" data-backdrop="static" data-keyboard="false">
                                    Send Manual
                                </button>
                            </td>
                            <th width="150">
                                {{date('d F Y H:i', strtotime($ship->created_at))}}
                            </th>
                            <th width="150">
                                @if($ship->last_sent_destination )

                                    @foreach(json_decode($ship->last_sent_destination , true) as $json)
                                        {{$json['email'] ?? $json}}<br>
                                    @endforeach
                                @endif
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Email Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('admin/ships/logs') }}" enctype="multipart/form-data" id="idForm">
                    @csrf
                    <div class="modal-body">
                        <span id="noClose"></span>
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="sin">Last Seen Destination</label>
                            <input class="form-control {{ $errors->has('last_seen_destination') ? 'is-invalid' : '' }}"
                                   type="text" name="last_seen_destination" id="last_seen_destination"
                                   value="{{ old('last_seen_destination', '') }}">
                            @if($errors->has('last_seen_destination'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('last_seen_destination') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.historyShip.fields.sin_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sin">Subject</label>
                            <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text"
                                   name="subject" id="subject" value="{{ old('subject', '') }}">
                            @if($errors->has('subject'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.historyShip.fields.sin_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sin">Filename Chr</label>
                            <input class="form-control {{ $errors->has('filename_chr') ? 'is-invalid' : '' }}"
                                   type="text" name="filename_chr" id="filename_chr"
                                   value="{{ old('filename_chr', '') }}">
                            @if($errors->has('filename_chr'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('filename_chr') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.historyShip.fields.sin_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sin">Content</label>
                            <input class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" type="text"
                                   name="content" id="content" value="{{ old('content', '') }}">
                            @if($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.historyShip.fields.sin_helper') }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Data</button>
                    </div>
                </form>
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


        $(document).on("click", ".open-AddBookDialog", function () {
            var id = $(this).data('id');

            var destination = $(this).data('destination');
            var subject = $(this).data('subject');
            var filename = $(this).data('filename');
            var content = $(this).data('content')

            var data = '';
            for (var n in destination) {
                if(destination[n]['email']) {
                    data = data + destination[n]['email'] + ';'
                }else{
                    data = data + destination[n] + ';'
                }
            }
            $('#last_seen_destination').val(data)
            $('#id').val(id)
            $('#subject').val(subject)
            $('#filename_chr').val(filename)
            $('#content').val(content)
        });

        $("#idForm").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.
            alert('Jangan di close, data lagi dalam proses pengiriman');
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data)
                {
                    $('#exampleModal').modal('hide')
                    console.log(data)

                    if(!alert('Data Sudah Terkirim')){window.location.reload();}
                    $('#noClose').html('');
                }
            });


        });


    </script>
@endsection
