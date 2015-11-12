<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TransactionReport extends Request
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
            'fromDate'  => 'required|date_format:Y-m-d',
            'toDate'    => 'required|date_format:Y-m-d',
            'merchant'  => 'numeric',
            'acquirer'  => 'numeric',      
        ];
    }
}
