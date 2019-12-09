<?php

namespace App\Http\Requests;

use App\Manager;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreManagerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'username' => [
                'required',
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'users.*' => [
                'integer',
            ],
            'users'   => [
                'required',
                'array',
            ],
        ];
    }
}
