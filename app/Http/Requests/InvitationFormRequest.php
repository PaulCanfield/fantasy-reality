<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class InvitationFormRequest extends FormRequest
{
    public $errorBag = 'invitation';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('manage', $this->route('season') );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [ 'required', Rule::exists('users', 'email') ]
        ];
    }

    public function messages() {
        return [
            'email.exists' => 'The invited user must have an account.'
        ];
    }
}
