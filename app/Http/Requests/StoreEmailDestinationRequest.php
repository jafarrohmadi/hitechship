<?php

namespace App\Http\Requests;

use App\HistoryShip;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreEmailDestinationRequest extends FormRequest
{
    public function authorize()
    {
     //   abort_if(Gate::denies('history_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
            ],
        ];
    }
}
