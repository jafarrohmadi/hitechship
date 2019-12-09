<?php
namespace App\Http\Requests;

use App\Terminal;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTerminalRequest extends FormRequest
{
    public function authorize ()
    {
        abort_if(Gate::denies('terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules ()
    {
        return [
            'name' => [
                'required',
            ],
            'ships.*' => [
                'integer',
            ],
            'ships' => [
                'required',
                'array',
            ],
        ];
    }

}
