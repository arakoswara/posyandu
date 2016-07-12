<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PeriksaBalitaParentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no_reg' => 'required',

            'tgl_periksa' => 'required|date',

            'berat_badan' => 'required|float',

            'tinggi_badan' => 'required|float'
        ];
    }
}
