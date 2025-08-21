<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'type'    => 'required|integer',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'title.required'   => 'Tiêu đề không được để trống.',
    //         'title.string'     => 'Tiêu đề phải là chuỗi ký tự.',
    //         'title.max'        => 'Tiêu đề không được quá 255 ký tự.',
    //         'content.required' => 'Nội dung không được để trống.',
    //         'content.string'   => 'Nội dung phải là chuỗi ký tự.',
    //         'type.required'    => 'Loại template không được để trống.',
    //         'type.number'      => 'Loại template phải là chuỗi ký tự.',
    //         'type.max'         => 'Loại template không được quá 50 ký tự.',
    //     ];
    // }
}
