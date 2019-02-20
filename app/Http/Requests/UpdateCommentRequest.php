<?php

namespace App\Http\Requests;

use Dogovor24\Authorization\Services\AuthUserService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            'payload'        => 'array',
            'payload.view'   => 'filled|array',
            'payload.view.*' => 'filled|integer|min:1',
            'payload.update'   => 'filled|array',
            'payload.update.*' => 'filled|integer|min:1',
            'payload.delete'   => 'filled|array',
            'payload.delete.*' => 'filled|integer|min:1',
        ];
    }
}
