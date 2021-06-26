<?php
namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize ()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules (Request $request)
    {
        return [
            'name' => [
                'required',
                'unique:users,name,NULL,id,deleted_at,NULL',
            ],
            'username' => [
                'required',
                'unique:users,username,NULL,id,deleted_at,NULL',
            ],
            'password' => [
                'required',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
            'terminals.*' => [
                'integer',
            ],
            'terminals' => [
                'array',
            ],
            'ships.*' => [
                'integer',
            ],
            'ships' => [
                'array',
            ],
        ];
    }

}
