<?php

namespace App\Http\Requests;

use App\Model\Category;
use Validator;

class CategoryRequest extends Request
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        Validator::extend('null_or_exist', function($attribute, $value, $parameters)
        {
            if($value == ''){
                return true;
            } elseif(Category::find($value)) {
                return true;
            } else {
                return false;
            }
        });

        return [
            'title' => 'required',
            'sort' => 'integer',
            'is_nav_show' => 'boolean',
            'parent_id' => 'nullOrExist',
        ];
    }

    public function attributes()
    {
        return [
            "title" => '栏目标题',
            'sort' => '栏目排序',
            'info' => '栏目简介',
            'parent_id' => '上级栏目',
            'cover' => '栏目封面',
            'thumb' => '栏目封面微缩图',
            'is_nav_show' => '导航显示',
            'keywords' => 'seo关键字',
            'description' => 'seo描述',
            'templet_all' => '带子分类模板',
            'templet_nosub' => '不带子分类模板',
            'templet_article' => '文章模板',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute不能为空.',
            'integer' => ':attribute只能为整数.',
            'boolean' => ':attribute格式错误.',
            'nullOrExist' => ':attribute不存在.',
        ];
    }
}
