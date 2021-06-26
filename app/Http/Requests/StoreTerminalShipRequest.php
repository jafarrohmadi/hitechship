<?php

namespace App\Http\Requests;

use App\TerminalShip;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTerminalShipRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('terminal_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ships.*'        => [
                'integer',
            ],
            'ships'          => [
                'required',
                'array',
            ],
            'terminals.*'    => [
                'integer',
            ],
            'terminals'      => [
                'array',
            ],
            'arrive_time'    => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'departure_time' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
