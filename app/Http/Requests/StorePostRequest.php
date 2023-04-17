<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [           
            'title' => 'required',
            'slug' => 'required',
            'summary' => 'required|max:255',
            'blog_category' => 'required',
            'blog_tag' => 'required',
            'blog_author' => 'required',
            'publish_date' => 'required',
            'publish_time' => 'required',
            'body' => 'required',
            'meta_title' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return  [
            'title.required' => 'Please enter a title',
            'slug.required' => 'Please enter a slug',
            'summary.required' => 'Please enter a summary',
            'summary.max' => 'Maximum :max character',
            'blog_category.required' => 'Please select category(s)',
            'blog_tag.required' => 'Please select tag(s)',
            'blog_author.required' => 'Please select author(s)',
            'publish_date' => 'Select date',
            'publish_time' => 'Select time',
            'meta_title.required' => 'Please enter meta title',
            'body.required' => 'Please enter post details',
        ];
    }
}
