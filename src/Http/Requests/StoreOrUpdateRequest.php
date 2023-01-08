<?php

namespace KieranFYI\UserUI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreOrUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique(config('auth.providers.users.model'))
                    ->ignore($this->user)
            ],
            'password' => ['nullable', 'confirmed', Password::default()]
        ];
    }
}
