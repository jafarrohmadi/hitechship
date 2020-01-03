<?php

namespace App\Http\Requests;

use App\Manager;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateManagerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:users,name,'.$this->segment(3)
            ],
            'username' => [
                'required',
                'unique:users',
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
