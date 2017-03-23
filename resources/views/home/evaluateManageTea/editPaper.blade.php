@extends('layouts.layoutHome')

@section('title', '')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/editPaper.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('calendar/datedropper.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('calendar/timedropper.min.css')}}">
@endsection

@section('content')
<div class="addPaper" ms-controller="addPaper">

    <div id="fileDiv" class="fileButton"></div>
    <input type="text" value="" class="fileButton" id="md5container">

    <div class="addPaper_main hide" ms-class="hide: importId || showImg">

        <div class="addPaper_main_title">
            <h1 ms-class="addPaper_main_titleActive: quesStyle === 1" ms-click="changeModel('quesStyle', 1)">作业样式</h1>
            <h1 ms-class="addPaper_main_titleActive: quesStyle === 2" ms-click="changeModel('quesStyle', 2)">测验样式</h1>
        </div>

        <div class="addPaper_main_choice">
            <h2 ms-click="changeModel('addQues', 1)">单选题</h2>
            <h2 ms-click="changeModel('addQues', 2)">多选题</h2>
            <h2 ms-click="changeModel('addQues', 3)">判断题</h2>
            <h2 ms-click="changeModel('addQues', 4)">填空题</h2>
            <h2 ms-click="changeModel('addQues', 5)">解答题</h2>
            <div ms-click="changeModel('importQues', 1)">引入试题</div>
        </div>

        <h1 class="addPaper_main_paperTitle" ms-html='title'></h1>

        <div class="addPaper_main_ques" ms-class="editing: editing === el.index" ms-repeat='taskQues' ms-mouseover="changeModel('editShow', el.index)" ms-mouseout="changeModel('editShow')" ms-attr-id="'testQues' + el.index">

            <p class="paper_type" ms-if="quesStyle === 2 && el.type === 1" ms-visible="paperType[el.type] === el.index" ms-papertype="[1, el.index]">单选题</p>
            <p class="paper_type" ms-if="quesStyle === 2 && el.type === 2" ms-visible="paperType[el.type] === el.index" ms-papertype="[2, el.index]">多选题</p>
            <p class="paper_type" ms-if="quesStyle === 2 && el.type === 3" ms-visible="paperType[el.type] === el.index" ms-papertype="[3, el.index]">判断题</p>
            <p class="paper_type" ms-if="quesStyle === 2 && el.type === 4" ms-visible="paperType[el.type] === el.index" ms-papertype="[4, el.index]">填空题</p>
            <p class="paper_type" ms-if="quesStyle === 2 && el.type === 5" ms-visible="paperType[el.type] === el.index" ms-papertype="[5, el.index]">解答题</p>

            <div class="addPaper_main_ques_choice" ms-if="editing !== el.index">
            <span ms-visible="editShow === el.index && editing === undefined">
                <div ms-hover='ques_choice_hover' ms-click="changeModel('replace', el.type, $index)">替换</div>
                <div ms-hover='ques_choice_hover' ms-click="moveQues($index, 'down')">↓下移</div>
                <div ms-hover='ques_choice_hover' ms-click="moveQues($index, 'up')">↑上移</div>
                <div ms-hover='ques_choice_hover' ms-click="changeModel('removeQues', $index)">删除</div>
                <div ms-hover='ques_choice_hover' ms-click="changeModel('editing', el.index)">编辑</div>
            </span>
            </div>

            <div class="addPaper_main_ques_content" ms-class="ques_content_active: editShow === el.index && editing === undefined">

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

                    <div class="single_answer" ms-visible="showAnswer === el.index">
                        <p>
                            <span class="analysisBlock">答案</span>
                            <span ms-if="el.type !== 2 && el.type !== 4" ms-html="el.type === 3 ? (el.answer ? '正确' : '错误') : el.answer"></span>
                            <span ms-if="el.type === 2 || el.type === 4" ms-fillmutlanswer="[el.answer, 1]"></span>
                        </p>

                        <div style="clear: both; height: 1px;"></div>

                        <p>
                            <span class="analysisBlock">解析</span>
                            <span ms-html='el.analysis || "无"'></span>
                        </p>
                    </div>

                    <div style="clear: both; height: 1px;"></div>
                </div>

                <div class="single_question_edit" ms-visible="editing === el.index">
                    <div class="single_title_edit" ms-attr-id="'#titleEdit' + $index">
                        <p ms-html="el.index + '. 题干'"></p>

                        <div ms-attr-id="'title' + $index"
                             class="editable"
                             contenteditable="true"
                             ms-if="el.type !== 6"
                             ms-html="el.title"
                             ms-editable="[$index, 'title']"
                             ms-selection="el.type"
                             ms-click="changeModel('editWindow', 'title')">
                        </div>

                        <p>难易度 <span ms-questiondifficult="$index"></span></p>

                        <div class="edit_window window_title" ms-class="window_title_juge: el.type === 4" ms-if="window === 'title'">
                            <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'title']" ms-if="el.type !== 4">B</a>
                            <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'title']" ms-if="el.type !== 4">I</a>
                            <a ms-attr-href="'#titleEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'title']" ms-if="el.type !== 4">U</a>
                            <a class="edit_window_span" ms-selectfile="[$index, 'title']">
                                <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                            </a>
                            <a class="edit_window_span fill_window_span" ms-fill="[$index, 'answer']" ms-if="el.type === 4">
                                <img src="/home/image/evaluateManageTea/editPaper/test.png">
                            </a>
                            <a class="edit_window_span " ms-click="changeModel('reset', $index)" ms-if="el.type === 4">
                                <img src="/home/image/evaluateManageTea/editPaper/reset.png">
                            </a>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div ms-if="el.choice" class="single_choice_edit" ms-repeat-cho="el.choice" ms-attr-id="'#choiceEdit' + $index">
                        <div style="clear: both;">
                            <p ms-questionindex="$index"></p>

                            <div ms-attr-id="'choice' + $outer.$index + '-' + $index"
                                 class="editable"
                                 contenteditable="true"
                                 ms-html="cho"
                                 ms-editable="[$outer.$index + '-' + $index, 'choice']"
                                 ms-click="changeModel('editWindow', $outer.$index + '-' + $index)">
                            </div>

                            <p ms-if="el.type === 1" ms-singlechoice="[el.index, $index]"></p>
                            <p ms-if="el.type === 2" class="multi_choice" ms-multichoice="[el.index, $index]"></p>

                            <p ms-if="$index > 1" ms-click="changeModel('removeChoice', $index, el.index)">×</p>

                        </div>

                        <div class="edit_window window_choice" ms-if="window === $outer.$index + '-' + $index">
                            <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[1, $outer.$index + '-' + $index, 'choice']">B</a>
                            <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[2, $outer.$index + '-' + $index, 'choice']">I</a>
                            <a ms-attr-href="'#choiceEdit' + $index" class="edit_window_span" ms-exec="[3, $outer.$index + '-' + $index, 'choice']">U</a>
                            <a class="edit_window_span" ms-selectfile="[$outer.$index + '-' + $index, 'choice']">
                                <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                            </a>
                        </div>

                        <div style="clear: both;"></div>
                    </div>

                    <div class="add_single_question" ms-if="el.choice">
                        <p ms-click="changeModel('addChoice', $index)">+ 添加选项</p>
                    </div>

                    <div class="judge_choice_edit" ms-if="el.type === 3">
                        <p>正确</p>
                        <p ms-click="changeModel('judge', $index, 1)"><span ms-if="el.answer === 1"></span></p>
                        <p>错误</p>
                        <p ms-click="changeModel('judge', $index, 0)"><span ms-if="el.answer === 0"></span></p>
                    </div>

                    <div style="clear: both; height: 30px;" ms-if="el.type > 3"></div>

                    <div class="single_number_edit" ms-if="el.type !== 4">
                        <p>分数</p>
                        <input type="text" ms-duplex-number="el.score">
                        <p>分</p>
                    </div>

                    <div class="single_number_edit" ms-if="el.type === 4">
                        <p>每题</p>
                        <input type="text" ms-fillscore="$index">
                        <p>分</p>
                        <p>总分数 <span ms-text="el.score"></span></p>
                    </div>

                    <div class="single_number_edit" ms-if="el.type !== 5">
                        <p>答案</p>
                        <input type="text" ms-if="el.type === 1 || el.type === 3" ms-attr-value="el.type === 3 ? (el.answer ? '正确' : '错误') : el.answer" readonly ms-css-width="'200px'">
                        <input type="text" ms-if="el.type === 4" ms-fillanswer="[el.answer, $index]" readonly ms-css-width="'510px'">
                        <input type="text" ms-if="el.type === 2" ms-fillmutlanswer="[el.answer, 0]" readonly ms-css-width="'510px'">
                    </div>

                    <div class="single_explain_edit" ms-if="el.type === 5" ms-attr-id="'#answerEdit' + $index">
                        <p>答案</p>
                        <div ms-attr-id="'answer' + $index"
                             class="editable"
                             contenteditable="true"
                             ms-html="el.answer"
                             ms-editable="[$index, 'answer']"
                             ms-click="changeModel('editWindow', 'answer')">
                        </div>

                        <div class="edit_window window_answer"  ms-if="window === 'answer'">
                            <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'answer']">B</a>
                            <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'answer']">I</a>
                            <a ms-attr-href="'#answerEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'answer']">U</a>
                            <a class="edit_window_span" ms-selectfile="[$index, 'answer']">
                                <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                            </a>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="single_explain_edit" ms-attr-id="'#analysisEdit' + $index">
                        <p>解析</p>
                        <div placeholder="(选填)"
                             ms-attr-id="'analysis' + $index"
                             class="editable"
                             contenteditable="true"
                             ms-html="el.analysis"
                             ms-editable="[$index, 'analysis']"
                             ms-click="changeModel('editWindow', 'analysis')">
                        </div>

                        <div class="edit_window window_explain" ms-if="window === 'analysis'">
                            <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[1, $index, 'analysis']">B</a>
                            <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[2, $index, 'analysis']">I</a>
                            <a ms-attr-href="'#analysisEdit' + $index" class="edit_window_span" ms-exec="[3, $index, 'analysis']">U</a>
                            <a class="edit_window_span" ms-selectfile="[$index, 'analysis']">
                                <img src="/home/image/evaluateManageTea/editPaper/edit_img.png">
                            </a>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="single_edit_complete">
                        <button ms-click="changeModel('editing', 'cancel', $index)">取消</button>
                        <button ms-click="changeModel('editing', 'complete', $index)">完成</button>
                    </div>
                </div>

            </div>
        </div>

        <div name='addQues' id='addQues' style="height: 30px;"></div>

        <div class="addPaper_main_publish" ms-visible="taskQues.size() > 0 && !importing && !editing">
            <button id="testButton" ms-html="'发布'" ms-click="publish"></button>
        </div>

        <div class="import_ques" ms-if="importWindow">
            <div class="import_ques_content hide" ms-class="hide: importWindow >= 3">
                <p class="import_ques_content_title">　　　替换试题<span class="import_ques_content_close" ms-click="changeModel('importQues', null)">×</span></p>

                <div style="overflow: auto; height: 360px;">

                    <div class="import_ques_content_condition">
                        <div class="import_ques_content_condition_difficult" style="margin-right: 20px;">
                            难易度
                            <select ms-duplex-number="importDifficult" ms-attr-disabled="importing">
                                <option ms-attr-value="3">困难</option>
                                <option ms-attr-value="2">一般</option>
                                <option ms-attr-value="1">简单</option>
                            </select>
                        </div>
                        <div class="import_ques_content_condition_difficult" ms-visible="importWindow === 1">
                            题型
                            <select  ms-duplex-number="importType" ms-attr-disabled="importing">
                                <option ms-attr-value="1">单选题</option>
                                <option ms-attr-value="2">多选题</option>
                                <option ms-attr-value="3">判断题</option>
                                <option ms-attr-value="4">填空题</option>
                                <option ms-attr-value="5">解答题</option>
                            </select>
                        </div>
                        <div class="import_ques_content_condition_confirm" ms-attr-disabled="importing" ms-click="changeModel('replaceQues')">确认替换</div>
                    </div>

                    <div class="import_ques_content_page" ms-importpage="importQuesCount"></div>

                    <div class="import_ques_content_ques" ms-visible="!importing && importQues">
                        <div class="description">
                            <p ms-html="'题号：' + importQues.id"></p>
                            <p ms-questiontype="importQues.type"></p>
                            <p ms-html="'日期：' + importQues.created_at"></p>
                            <p ms-html="'难易度：' + ((importQues.difficult === 1) ? '简单' : ((importQues.difficult === 2) ? '一般' : '困难'))"></p>
                        </div>

                        <div class="title" ms-html="importQues.title"></div>

                        <div class="choice" ms-visible="importQues.type < 3" ms-repeat="importQues.choice">
                            <p>
                                <span ms-questionindex="$index"></span>
                                <span ms-html="el"></span>
                            </p>
                        </div>

                        <div class="split"></div>

                        <div class="answer">
                            <p>
                                <span class="analysisBlock">答案</span>
                                <span ms-html="((typeof importQues.answer === 'number') ? (importQues.answer === 1 ? '正确' : '错误') : importQues.answer)"></span>
                            </p>
                        </div>

                        <div class="answer">
                            <p>
                                <span class="analysisBlock">解析</span>
                                <span ms-html="importQues.analysis"></span>
                            </p>
                        </div>
                    </div>

                    <div class="spinner" ms-if="importing">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>

                    <p class="import_ques_content_tip" ms-if="!importing && !importQues">暂无数据</p>

                </div>
            </div>

            <div class="import_ques_content publish_window hide" ms-class="hide: importWindow !== 3">

                <p class="import_ques_content_title" id="paperTitle">　　　发布试卷<span class="import_ques_content_close" ms-click="changeModel('importQues', null)">×</span></p>

                <div style="clear: both; height: 20px;"></div>

                <div ms-if="!importing">
                    <div class="publish_block">
                        <span class="title" ms-if="!paperTitileWanring">试卷标题</span>
                        <span class="title" ms-if="paperTitileWanring" style="color: red;">请输入试卷标题</span>
                        <div>
                            <input type="text" ms-duplex="title">
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_block">
                        <span class="title">试卷内容</span>
                        <div>
                            <p ms-if="typeCount['1'].count > 0"><span ms-html="'单选题' + typeCount['1'].count + '道'"></span> <span ms-html="'共' + typeCount['1'].score + '分'"></span></p>
                            <p ms-if="typeCount['2'].count > 0"><span ms-html="'多选题' + typeCount['2'].count + '道'"></span> <span ms-html="'共' + typeCount['2'].score + '分'"></span></p>
                            <p ms-if="typeCount['3'].count > 0"><span ms-html="'判断题' + typeCount['3'].count + '道'"></span> <span ms-html="'共' + typeCount['3'].score + '分'"></span></p>
                            <p ms-if="typeCount['4'].count > 0"><span ms-html="'填空题' + typeCount['4'].count + '道'"></span> <span ms-html="'共' + typeCount['4'].score + '分'"></span></p>
                            <p ms-if="typeCount['5'].count > 0"><span ms-html="'解答题' + typeCount['5'].count + '道'"></span> <span ms-html="'共' + typeCount['5'].score + '分'"></span></p>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_block">
                        <span class="title">总分值</span>
                        <div ms-html="scoreCount + '分'"></div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_block">
                        <span class="title">提交时限</span>
                        <div>
                            <input type="text" id="pickdate" ms-duplex="pickdate" style="width: 100px;">
                            <input type="text" id="picktime" ms-duplex="picktime" style="width: 80px;">
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_block" ms-if="quesStyle === 2">
                        <span class="title">完成时间</span>
                        <div>
                            <select ms-duplex-number="completeTime">
                                <option ms-attr-value="20">20分钟</option>
                                <option ms-attr-value="30">30分钟</option>
                                <option ms-attr-value="40">40分钟</option>
                                <option ms-attr-value="50">50分钟</option>
                                <option ms-attr-value="60">60分钟</option>
                            </select>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_block">
                        <span class="title" ms-if="!selectClassWarning">下发班级(多选)</span>
                        <span class="title" ms-if="selectClassWarning" style="color: red;">请选择下发班级</span>
                        <div ms-if="classInfo !== null && classInfo.size() > 0" class="publish_class">
                            <p class="publish_class_block" ms-repeat="classInfo" ms-selectclass="el.classId" ms-html="el.gradeName + el.className" ms-attr-title="el.gradeName + el.className"></p>
                        </div>
                        <div ms-if="classInfo === null || classInfo.size() < 1">暂无绑定班级</div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="publish_submit" ms-if="!sumitFail" ms-click="submitPaper">确认提交</div>
                    <div class="publish_submit" ms-if="sumitFail" style="background: white; color: red; width: 300px;">试卷发布失败，请重试</div>
                </div>

                <div class="spinner" ms-if="importing" style="margin: 150px auto;">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>

            </div>
        </div>

    </div>

    <div class="imgZoom hide" ms-class="hide: !showImg">
        <div class="imgZoom_close" ms-click="enlarge(true)">×</div>
        <img ms-attr-src="imgZoom">
    </div>

    <div class="spinner hide" ms-class="hide: !importId">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
@endsection

@section('js')
	<script type="text/javascript" src="{{asset('/calendar/datedropper.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('/calendar/timedropper.min.js')}}"></script>
	<script type="text/javascript">
		require(['evaluateManageTea/editPaper'], function (vm) {

            vm.userId = {{$userId}} || {{\Auth::user() -> id}};
            vm.lessonInfo = '{{$lessonInfo}}' || null;
            vm.title = '{{$title}}' || '默认试卷标题';
            vm.importId = {{$importId}} || null;
            vm.init();

            $('#pickdate').dateDropper({
                animate: false,
                format: 'Y-m-d',
                maxYear: new Date().getFullYear(),
                minYear: new Date().getFullYear()
            });

            $("#picktime").timeDropper({
                meridians: false,
                format: 'HH:mm',
            });

            avalon.scan();
		});
	</script>
@endsection