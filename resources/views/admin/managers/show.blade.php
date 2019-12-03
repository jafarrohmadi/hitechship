@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.manager.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.managers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.manager.fields.id') }}
                        </th>
                        <td>
                            {{ $manager->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.manager.fields.name') }}
                        </th>
                        <td>
                            {{ $manager->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.manager.fields.user') }}
                        </th>
                        <td>
                            @foreach($manager->users as $key => $user)
                                <span class="label label-info">{{ $user->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.managers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>


    </div>
</div>
@endsection