<?php

namespace app\Modules\Weather\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetWeatherBroadcastRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['date' => $this->route('date')]);
    }
}
