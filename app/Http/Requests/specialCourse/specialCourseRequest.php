<?php

namespace App\Http\Requests\specialCourse;

use App\Http\Requests\Request;

class specialCourseRequest extends Request
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
            'courseTitle'=>'required',
            'courseIntro'=>'required',
//            'coursePrice'=>'required|numeric'
        ];
    }
    public function messages()
    {
        return [
            'courseTitle.required'=>'请输入标题',
            'courseIntro.required'=>'请输入描述',
//            'coursePrice.required'=>'请输入课程价格',
//            'coursePrice.integer'=>'课程价格必须是整型'
//            'coursePrice.integer'=>'课程价格必须是数字'
        ];
    }
}
