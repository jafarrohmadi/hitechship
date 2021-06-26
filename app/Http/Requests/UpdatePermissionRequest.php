<?php

namespace App\Http\Requests;

use App\Permission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(Request $request)
    {
        return [
            'title' => [
                'required',
                'unique:permissions,title,' . $request->segment(3) . ',id,deleted_at,NULL',
            ],
        ];
    }
}
