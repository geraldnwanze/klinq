<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthFormRequest extends FormRequest
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
        return match($this->route()->getName()){
            'auth.login' => $this->login(),
            'auth.register' => $this->register(),
            'auth.forgot-password' => $this->forgotPassword(),
            'auth.reset-password' => $this->resetPassword()
        };
    }

    public function passwordRules()
    {
        return Password::min(6)->letters()->numbers()->mixedCase()->symbols()->uncompromised();
    }

    public function login()
    {
        return [
            'email' => 'required|email|max:20',
            'password' => 'required|min:6|max:20'
        ];
    }

    public function register()
    {
        return [
            'name' => 'required|max:40',
            'email' => 'required|email|unique:users,email|max:20|email:dns',
            'password' => ['required', 'max:20', $this->passwordRules()]
        ];
    }

    public function forgotPassword()
    {
        return [
            'email' => 'required|max:20|email:dns'
        ];
    }

    public function resetPassword()
    {
        return [
            'password' => ['required', 'same:confirm_password', $this->passwordRules()],
            'confirm_password' => ['required', 'same:password', $this->passwordRules()]
        ];
    }
}
