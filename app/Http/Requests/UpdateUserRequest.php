<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(Request $request, $id)
    {
        return [
            'name'    => [
                'required',
                'unique:users,name,'.$id.',id,deleted_at,NULL',
            ],
            'username'   => [
                'required',
                'unique:users,username,'.$id.',id,deleted_at,NULL',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles'   => [
                'required',
                'array',
            ],
            'terminals.*' => [
                'integer',
            ],
            'terminals'   => [
                'array',
            ],
        ];
    }
}
