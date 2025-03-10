<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'patient_name' => 'required|string|max:255|unique:order_requests,patient_name,' . $this->id,
            'patient_id' => 'required|string|max:255|unique:order_requests,patient_id,' . $this->id,
            'birthday' => 'required|date',
            'age' => 'required|integer',
            'gender' => 'required|string|max:10',
            'date_performed' => 'nullable|date',
            'date_released' => 'nullable|date',
            'programs' => 'nullable|array',
            'programs.*' => 'string',
            'order' => 'nullable|array',
            'order.*' => 'string',
            'sample_type' => 'required|string|max:255',
            'sample_container' => 'required|string|max:255',
            'collection_date' => 'required|date',
            'test_code' => 'nullable|string|max:255',
            'pathologist_full_name' => 'nullable|string|max:255',
            'pathologist_lic_no' => 'nullable|string|max:255',
            'physician_full_name' => 'nullable|string|max:255',
        ];
    }    
}
