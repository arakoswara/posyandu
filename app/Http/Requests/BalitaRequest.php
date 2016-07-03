<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BalitaRequest extends Request
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
            
            'no_reg' => 'required|integer|unique:data_balita',

            'nama_balita' => 'required|min:6',

            'tgl_lahir' => 'required',

            'jenis_kelamin' => 'required',

            'nama_ayah' => 'required|min:3',

            'nama_ibu' => 'required|min:3'
        ];
    }
}
