<?php

namespace App\Http\Requests;

use App\HistoryShip;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreHistoryShipRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('history_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'history_ids' => [
                'required',
            ],
            'receive_utc' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'message_utc' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
