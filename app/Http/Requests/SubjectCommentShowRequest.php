<?php

namespace App\Http\Requests;

use App\Subject;
use Dogovor24\Authorization\Services\AuthUserService;
use Illuminate\Foundation\Http\FormRequest;

class SubjectCommentShowRequest extends FormRequest
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
            'subject_type'  => 'required',
            'subject_id'    => 'required|integer|min:1'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(!Subject::where('subject_type','=',$this->subject_type)->where('subject_id','=',$this->subject_id)->exists()){
                $validator->errors()->add('subject type/id', 'not found');
            }
        });
    }
}
