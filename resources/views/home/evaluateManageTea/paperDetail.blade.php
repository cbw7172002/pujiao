@extends('layouts.layoutHome')

@section('title', '')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/editPaper.css')}}">
@endsection

@section('content')
<div class="addPaper" ms-controller="addPaper">

    <div class="addPaper_main" ms-if="!importId && !showImg">

        <div style="height: 20px; clear: both;"></div>

        <h1 class="addPaper_main_paperTitle" ms-html='title'></h1>

        <div style="height: 20px; clear: both;"></div>

        <div class="addPaper_main_ques" ms-repeat='taskQues' ms-attr-id="'testQues' + el.index">

            <div class="addPaper_main_ques_content ques_content_active" style="margin-bottom: 20px;">

                <div class='single_question' ms-click="changeModel('showAnswer', el.index)" ms-visible="editing === undefined || editing !== el.index">
                    <div class="single_title">
                        <div class='single_title_type'>
                            <span ms-html="el.index + '.'"></span>
                            <span ms-css-background="'#1F9EEA'" ms-if="el.type === 1" ms-html="'单选'"></span>
                            <span ms-css-background="'#85CCC8'" ms-if="el.type === 2" ms-html="'多选'"></span>
                            <span ms-css-background="'#FC7E8B'" ms-if="el.type === 3" ms-html="'判断'"></span>
                            <span ms-css-background="'#c477d5'" ms-if="el.type === 4" ms-html="'填空'"></span>
                            <span ms-css-background="'#F7BC5D'" ms-if="el.type === 5" ms-html="'解答'"></span>
                        </div>
                        <div class='single_title_content' ms-html='el.title'></div>
                        <div class='single_title_number' ms-html="'( ' + el.score + '分 )'"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="single_choice" ms-if="el.type < 3">
                        <p ms-repeat-choice="el.choice">
                            <span ms-questionindex="$index"></span>
                            <span ms-html="choice"></span>
                        </p>
                    </div>

                    <div style="height: 15px; clear: both;"></div>

                    <div class="single_answer">
                        <p>
                            <span>答案</span>
                            <span ms-if="el.type !== 2 && el.type !== 4" ms-html="el.type === 3 ? (el.answer ? '正确' : '错误') : el.answer"></span>
                            <span ms-if="el.type === 2 || el.type === 4" ms-fillmutlanswer="[el.answer, 1]"></span>
                        </p>

                        <div style="clear: both; height: 1px;"></div>

                        <p>
                            <span>解析</span>
                            <span ms-html='el.analysis || "略"'></span>
                        </p>
                    </div>

                    <div style="clear: both; height: 1px;"></div>
                </div>

            </div>

            <div style="height: 20px; clear: both;"></div>
        </div>

    </div>

    <div class="imgZoom" ms-if="showImg && !importId">
        <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
        <img ms-attr-src="imgZoom">
    </div>

    <div class="spinner" ms-if="importId && !showImg">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['evaluateManageTea/editPaper'], function (vm) {
            vm.importId = {{$importId}} || null;
            vm.init();
            avalon.scan();
		});
	</script>
@endsection