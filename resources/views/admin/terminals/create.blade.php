@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.terminal.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.terminals.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.terminal.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="ships">{{ trans('cruds.terminal.fields.ship') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('ships') ? 'is-invalid' : '' }}" name="ships[]"
                            id="ships" multiple>

                        @foreach($ships as $ship)
                            <option
                                value="{{ $ship->id }}" {{ in_array($ship->id, old('ships', [])) ? 'selected' : '' }}>{{ $ship->ship_ids .' - '. $ship->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('ships'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ships') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.ship_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('air_comm_blocked') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="air_comm_blocked" value="0">
                        <input class="form-check-input" type="checkbox" name="air_comm_blocked" id="air_comm_blocked"
                               value="1" {{ old('air_comm_blocked', 0) == 1 || old('air_comm_blocked') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="air_comm_blocked">{{ trans('cruds.terminal.fields.air_comm_blocked') }}</label>
                    </div>
                    @if($errors->has('air_comm_blocked'))
                        <div class="invalid-feedback">
                            {{ $errors->first('air_comm_blocked') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.air_comm_blocked_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('power_backup') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="power_backup" value="0">
                        <input class="form-check-input" type="checkbox" name="power_backup" id="power_backup"
                               value="1" {{ old('power_backup', 0) == 1 || old('power_backup') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="power_backup">{{ trans('cruds.terminal.fields.power_backup') }}</label>
                    </div>
                    @if($errors->has('power_backup'))
                        <div class="invalid-feedback">
                            {{ $errors->first('power_backup') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.power_backup_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('power_main') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="power_main" value="0">
                        <input class="form-check-input" type="checkbox" name="power_main" id="power_main"
                               value="1" {{ old('power_main', 0) == 1 || old('power_main') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="power_main">{{ trans('cruds.terminal.fields.power_main') }}</label>
                    </div>
                    @if($errors->has('power_main'))
                        <div class="invalid-feedback">
                            {{ $errors->first('power_main') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.power_main_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('sleep_schedule') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="sleep_schedule" value="0">
                        <input class="form-check-input" type="checkbox" name="sleep_schedule" id="sleep_schedule"
                               value="1" {{ old('sleep_schedule', 0) == 1 || old('sleep_schedule') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="sleep_schedule">{{ trans('cruds.terminal.fields.sleep_schedule') }}</label>
                    </div>
                    @if($errors->has('sleep_schedule'))
                        <div class="invalid-feedback">
                            {{ $errors->first('sleep_schedule') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.sleep_schedule_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('battery_low') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="battery_low" value="0">
                        <input class="form-check-input" type="checkbox" name="battery_low" id="battery_low"
                               value="1" {{ old('battery_low', 0) == 1 || old('battery_low') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="battery_low">{{ trans('cruds.terminal.fields.battery_low') }}</label>
                    </div>
                    @if($errors->has('battery_low'))
                        <div class="invalid-feedback">
                            {{ $errors->first('battery_low') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.battery_low_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('speeding_start') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="speeding_start" value="0">
                        <input class="form-check-input" type="checkbox" name="speeding_start" id="speeding_start"
                               value="1" {{ old('speeding_start', 0) == 1 || old('speeding_start') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="speeding_start">{{ trans('cruds.terminal.fields.speeding_start') }}</label>
                    </div>
                    @if($errors->has('speeding_start'))
                        <div class="invalid-feedback">
                            {{ $errors->first('speeding_start') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.speeding_start_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('speeding_end') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="speeding_end" value="0">
                        <input class="form-check-input" type="checkbox" name="speeding_end" id="speeding_end"
                               value="1" {{ old('speeding_end', 0) == 1 || old('speeding_end') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="speeding_end">{{ trans('cruds.terminal.fields.speeding_end') }}</label>
                    </div>
                    @if($errors->has('speeding_end'))
                        <div class="invalid-feedback">
                            {{ $errors->first('speeding_end') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.speeding_end_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('modem_registration') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="modem_registration" value="0">
                        <input class="form-check-input" type="checkbox" name="modem_registration"
                               id="modem_registration"
                               value="1" {{ old('modem_registration', 0) == 1 || old('modem_registration') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="modem_registration">{{ trans('cruds.terminal.fields.modem_registration') }}</label>
                    </div>
                    @if($errors->has('modem_registration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('modem_registration') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.modem_registration_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('geofence_in') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="geofence_in" value="0">
                        <input class="form-check-input" type="checkbox" name="geofence_in" id="geofence_in"
                               value="1" {{ old('geofence_in', 0) == 1 || old('geofence_in') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="geofence_in">{{ trans('cruds.terminal.fields.geofence_in') }}</label>
                    </div>
                    @if($errors->has('geofence_in'))
                        <div class="invalid-feedback">
                            {{ $errors->first('geofence_in') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.geofence_in_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem;">
                    <div class="form-check {{ $errors->has('geofence_out') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="geofence_out" value="0">
                        <input class="form-check-input" type="checkbox" name="geofence_out" id="geofence_out"
                               value="1" {{ old('geofence_out', 0) == 1 || old('geofence_out') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="geofence_out">{{ trans('cruds.terminal.fields.geofence_out') }}</label>
                    </div>
                    @if($errors->has('geofence_out'))
                        <div class="invalid-feedback">
                            {{ $errors->first('geofence_out') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.geofence_out_helper') }}</span>
                </div>
                <div class="form-group">
                    <div class="form-check {{ $errors->has('email_destination') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="email_destination" value="0">
                        <input class="form-check-input" type="checkbox" name="email_destination" id="email_destination"
                               value="1" {{ old('email_destination', 0) == 1 || old('email_destination') === null ? 'checked' : '' }}>
                        <label class="form-check-label"
                               for="email_destination">{{ trans('cruds.terminal.fields.destinasion_email') }}</label>
                    </div>
                    @if($errors->has('email_destination'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email_destination') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.terminal.fields.destinasion_email_helper') }}</span>
                </div>
                <div class="form-group" style="margin-bottom: 0rem">
                    <label for="email">{{ trans('cruds.terminal.fields.destinasion_email_list') }}</label>
                    <div class="clone-email">
                        <div class="form-inline">
                            <input class="form-control col-md-8 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   type="email" name="email[]" id="email">
                            <span class="col-md-1"></span>
                            <div class="btn btn-warning col-md-2 delete-clone-email" style="display: none">- Delete
                                Email
                            </div>
                        </div>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                        <br>
                    </div>
                    <span class="clone-last"></span>
                     
                </div>
                <div class="form-group">
                    <div class="btn btn-info add-clone-email">+ Add New Email</div>
                </div><br><br>

                <div class="form-group" style="margin-bottom: 0rem">
                    <label for="alert-email">{{ trans('cruds.terminal.fields.alert_email_list') }}</label>
                    <div class="clone-alert-email">
                        <div class="form-inline">
                            <input class="form-control col-md-8 {{ $errors->has('alertEmail') ? 'is-invalid' : '' }}"
                                   type="email" name="alertEmail[]" id="alertEmail">
                            <span class="col-md-1"></span>
                            <div class="btn btn-warning col-md-2 delete-clone-alert-email" style="display: none">- Delete
                                Email
                            </div>
                        </div>
                        @if($errors->has('alertEmail'))
                            <div class="invalid-feedback">
                                {{ $errors->first('alertEmail') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                        <br>
                    </div>
                    <span class="clone-alert-last"></span>
                    
                </div>
                <div class="form-group">
                    <div class="btn btn-info add-clone-alert-email">+ Add New Alert Email</div>
                </div><br>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var count = 1;
        $(document).ready(function () {
            $(".add-clone-email").on('click', function () {
                if (count >= 9) {
                    $(".add-clone-email").hide();
                }

                var clone = $('.clone-email:last').clone();
                // clone.removeClass('clone-email').addClass('clone-email'+count);
                clone.find("#email").attr({name: "email[]"});
                clone.find("#email").val("");
                clone.find(".delete-clone-email").show();
                clone.appendTo('.clone-last');
                count++;
            });
        });

        $(document).on('click', ".delete-clone-email", function () {
            $(this).closest(".clone-email").remove();
            count--;
        });

        var countAlert = 1;
        $(document).ready(function () {
            $(".add-clone-alert-email").on('click', function () {
                if (countAlert >= 9) {
                    $(".add-clone-alert-email").hide();
                }

                var clone = $('.clone-alert-email:last').clone();
                clone.find("#alertEmail").attr({name: "alertEmail[]"});
                clone.find("#alertEmail").val("");
                clone.find(".delete-clone-alert-email").show();
                clone.appendTo('.clone-alert-last');
                count++;
            });
        });

        $(document).on('click', ".delete-clone-alert-email", function () {
            $(this).closest(".clone-alert-email").remove();
            count--;
        });
    </script>
@endsection
