<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDataSourceRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [//
        ];
    }

    /**
     * @return array|mixed
     */
    public function getBindParams() {
        $bindParams = $this->get('bindParams');
        return $bindParams ? json_decode($bindParams, TRUE) : [];
    }

    /**
     * @return boolean
     */
    public function isDebug(): bool {
        return (boolean) $this->get('debug', FALSE);
    }
}
