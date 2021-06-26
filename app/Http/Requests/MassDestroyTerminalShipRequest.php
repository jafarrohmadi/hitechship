<?php

namespace App\Http\Requests;

use App\TerminalShip;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTerminalShipRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('terminal_ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:terminal_ships,id',
        ];
    }
}
