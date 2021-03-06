<?php

namespace App\Http\Requests;

use Dogovor24\Authorization\Services\AuthUserService;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (new AuthUserService())->checkAuth();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text'           => 'required',
            'subject_id'     => 'required|integer|min:1',
            'subject_type'   => 'required|string|without_spaces',
            'payload'        => 'array',
            'payload.view'   => 'filled|array',
            'payload.view.*' => 'filled|integer|min:1',
        ];
    }
}
