@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ship.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ships.update", [$ship->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="ship_ids">{{ trans('cruds.ship.fields.ship_ids') }}</label>
            <div class="form-group">
            @if(strpos($ship->ship_ids, "-"))
                <input class="form-control {{ $errors->has('ship_ids') ? 'is-invalid' : '' }}" type="text" name="ship_ids" id="ship_ids" value="{{ old('ship_ids', $ship->ship_ids) }}" required style="width: 30%;" readonly="true">
            @else
                <input class="form-control {{ $errors->has('ship_ids') ? 'is-invalid' : '' }}" type="text" name="ship_ids" id="ship_ids" value="{{ old('ship_ids', $ship->ship_ids) }}" required style="width: 30%; float: left;" readonly="true">
                <select name="ship_ids" class="form-control" value="{{ old('ship_ids', $ship->ship_ids) }}" style="width: 10%;">
                      <option selected disabled>ADD ID</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-A">A</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-B">B</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-C">C</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-D">D</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-E">E</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-F">F</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-G">G</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-H">H</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-I">I</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-J">J</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-K">K</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-L">L</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-M">M</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-N">N</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-O">O</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-P">P</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-Q">Q</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-R">R</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-S">S</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-T">T</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-U">U</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-V">V</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-W">W</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-X">X</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-Y">Y</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}-Z">Z</option>
                      <option value="{{ old('ship_ids', $ship->ship_ids) }}">NONE</option>
                </select>
            @endif
            </div>
                @if($errors->has('ship_ids'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ship_ids') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.ship_ids_helper') }}</span>
            </div>

            <div class="form-group">
                <!--<label class="required" for="name">{{ trans('cruds.ship.fields.name') }}</label>-->
                <label  for="name">{{ trans('cruds.ship.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $ship->name) }}" DISABLED>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.name_helper') }}</span>
            </div>
            <!--<div class="form-group">
                <label for="name">{{ trans('cruds.ship.fields.call_sign') }}</label>
                <input class="form-control {{ $errors->has('call_sign') ? 'is-invalid' : '' }}" type="text" name="call_sign" id="call_sign" value="{{ old('call_sign', $ship->call_sign) }}">
                @if($errors->has('call_sign'))
                    <div class="invalid-feedback">
                        {{ $errors->first('call_sign') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.call_sign_helper') }}</span>
            </div>-->
            <div class="form-group">
                <label for="owner">{{ trans('cruds.ship.fields.owner') }}</label>
                <input class="form-control {{ $errors->has('owner') ? 'is-invalid' : '' }}" type="text" name="owner" id="owner" value="{{ old('owner', $ship->owner) }}">
                @if($errors->has('owner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('owner') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.owner_helper') }}</span>
            </div>
            <!--<div class="form-group">
                <label for="region_name">{{ trans('cruds.ship.fields.region_name') }}</label>
                <input class="form-control {{ $errors->has('region_name') ? 'is-invalid' : '' }}" type="text" name="region_name" id="region_name" value="{{ old('region_name', $ship->region_name) }}">
                @if($errors->has('region_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('region_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.region_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="last_registration_utc">{{ trans('cruds.ship.fields.last_registration_utc') }}</label>
                <input class="form-control datetime {{ $errors->has('last_registration_utc') ? 'is-invalid' : '' }}" type="text" name="last_registration_utc" id="last_registration_utc" value="{{ old('last_registration_utc', $ship->last_registration_utc) }}">
                @if($errors->has('last_registration_utc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_registration_utc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.last_registration_utc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="long">{{ trans('cruds.ship.fields.long') }}</label>
                <input class="form-control {{ $errors->has('long') ? 'is-invalid' : '' }}" type="text" name="long" id="long" value="{{ old('long', $ship->long) }}">
                @if($errors->has('long'))
                    <div class="invalid-feedback">
                        {{ $errors->first('long') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.long_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ship.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Ship::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $ship->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.type_helper') }}</span>
            </div>-->

            <div class="form-group">
                <label for="last_registration_utc">Call Sign</label>
                <input class="form-control {{ $errors->has('call_sign') ? 'is-invalid' : '' }}" type="text" name="call_sign" id="call_sign" value="{{ old('call_sign', $ship->call_sign) }}" required>
                @if($errors->has('call_sign'))
                    <div class="invalid-feedback">
                        {{ $errors->first('call_sign') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.last_registration_utc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="last_registration_utc">Send To Pertamina</label>
                <input class="{{ $errors->has('send_to_pertamina') ? 'is-invalid' : '' }}" type="radio" name="send_to_pertamina" value="1" @if($ship->send_to_pertamina == 1) checked @endif> Enable
                <input class="{{ $errors->has('send_to_pertamina') ? 'is-invalid' : '' }}" type="radio" name="send_to_pertamina" value="0" @if($ship->send_to_pertamina == 0) checked @endif> Disable
                @if($errors->has('call_sign'))
                    <div class="invalid-feedback">
                        {{ $errors->first('call_sign') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.last_registration_utc_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="last_registration_utc">Additional Email Ship</label>
                <input class="form-control {{ $errors->has('additional_email_ship') ? 'is-invalid' : '' }}" type="text" name="additional_email_ship" id="call_sign" value="{{ $ship->additional_email_ship ?? str_replace(',', ";",str_replace('"', "",str_replace("]", "",str_replace("[", "",json_encode(\App\EmailDestination::pluck('email')->toArray()))))) }}">
                <span>Gunakan ; untuk menambah email</span>
                @if($errors->has('call_sign'))
                    <div class="invalid-feedback">
                        {{ $errors->first('call_sign') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.last_registration_utc_helper') }}</span>
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
