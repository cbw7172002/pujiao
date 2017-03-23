@extends('layouts.layoutHome')

@section('title', '课程下架了~')

@section('css')
    <style>
        .contain_lessonDetail{
            width: 100%;
            margin: 0 auto;
            height: auto;
        }
        .no_course{
            width: 1200px;
            min-height: 648px;
            margin: 0 auto;
            background-color: #f5f5f5;

            font-size:18px;
            text-align: center;
            line-height:600px;;
        }

        .no_course a{
            display: inline;
            color: #209eea;
        }
    </style>
@endsection

@section('content')
    <div class="contain_lessonDetail">
        <div class="no_course" ms-if="!haveCourse">
            该课程已下架，<a href="javascript:" onclick="history.back();">返回上一页</a>
        </div>
    </div>
@endsection

@section('js')

@endsection