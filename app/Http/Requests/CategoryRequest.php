<?php

namespace App\Http\Requests;

class CategoryRequest extends Request
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'sort' => 'integer',
        ];
    }

    public function attributes()
    {
        return [
            "title" => '栏目标题',
            'sort' => '栏目排序',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute不能为空.',
            'integer' => ':attribute只能为整数.',
        ];
    }
}
