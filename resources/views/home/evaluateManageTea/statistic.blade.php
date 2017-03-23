@extends('layouts.layoutHome')

@section('title', '')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/evaluateManageTea/statistic.css')}}">
@endsection

@section('content')
<div class="contain_lessonDetail" ms-controller="statistic">
        <div style="width: 100%;height: 15px;"></div>
        <div class="contain_lessonDetail_top">
            <div class="contain_lessonDetail_top_breadcrumb">
                <div id="changeVideo" style="width: 1px;height: 1px;position: absolute;z-index: 1;top: 0px;"></div>
                <a href="">首页</a> >
                <a href="">成绩查询</a> > 试卷详情
            </div>
        </div>

        <div class="hide" ms-class="hide: (loading === 1 || loading === 2)" style="width: 1200px; height: auto; margin: 0 auto; background-color: #FFFFFF;">
            <div class="test_paper_title">
                <div style="width: 100%;height: 18px;"></div>
                <div class="test_paper_title_top" ms-text="paperInfo.title"></div>
                <div class="test_paper_title_bot" ms-text="paperInfo.gradeName + ' | ' + paperInfo.subjectName + ' | ' + paperInfo.editionName + ' | ' + paperInfo.bookName + ' | 上传日期：' + paperInfo.created_at"></div>
            </div>

            <div class="statistic_condition">
                <div class="class">
                    <div class="select" ms-click="changeModel('selectClass')"><span ms-text='condition.classSelectedText === "全部" ? condition.classSelectedText : condition.classSelectedText + "..."'></span><p>▲</p></div>
                    <div class="option" ms-if="condition.selectClass">
                        <p ms-selectclass="['全部', '全部']">
                            <span>全部</span>
                            <span class="nike" style="display: none;">√</span>
                        </p>
                        <p ms-repeat="classList"  ms-selectclass="[parseInt(el.id), el.gradeName + el.className]">
                            <span ms-text="el.gradeName + el.className"></span>
                            <span class="nike" style="display: none;">√</span>
                        </p>
                    </div>
                </div>
                <div class="question">
                    <span ms-class="questionActive: condition.quesType === 0" value="0" ms-questype ms-click="changeModel('quesType', 0)">全卷</span>
                    <span ms-if="parseInt(quesTypeStatus[1]) > 0" ms-class="questionActive: condition.quesType === 1" value="1" ms-questype ms-click="changeModel('quesType', 1)">单选题</span>
                    <span ms-if="parseInt(quesTypeStatus[2]) > 0" ms-class="questionActive: condition.quesType === 2" value="2" ms-questype ms-click="changeModel('quesType', 2)">多选题</span>
                    <span ms-if="parseInt(quesTypeStatus[3]) > 0" ms-class="questionActive: condition.quesType === 3" value="3" ms-questype ms-click="changeModel('quesType', 3)">判断题</span>
                    <span ms-if="parseInt(quesTypeStatus[4]) > 0" ms-class="questionActive: condition.quesType === 4" value="4" ms-questype ms-click="changeModel('quesType', 4)">填空题</span>
                    <span ms-if="parseInt(quesTypeStatus[5]) > 0" ms-class="questionActive: condition.quesType === 5" value="5" ms-questype ms-click="changeModel('quesType', 5)">解答题</span>
                </div>
            </div>

            <div class="statistic_chart_single" ms-if="!loading && (condition.quesType === 0 || condition.quesType > 3)">
                <div id="chart" ms-if="question[condition.quesType] !== false"></div>
                <div style="width: 100%; line-height: 220px; text-align: center;" ms-if="question[condition.quesType] === false">暂无数据</div>
            </div>

            <div class="statistic_chart_multiple" ms-if="!loading && condition.quesType > 0 && condition.quesType < 4">
                <div class="container" ms-if="question[condition.quesType] !== false">
                    <div id="line"></div>
                </div>
                <div class="container" ms-if="question[condition.quesType] !== false">
                    <select ms-duplex-string="checkQuesColumnChart">
                        <option ms-repeat="questionView[0].number" ms-attr-value="el" ms-text='"试题" + el'></option>
                    </select>
                    <div id="column"></div>
                </div>
                <div style="width: 100%; line-height: 220px; text-align: center;" ms-if="question[condition.quesType] === false">暂无数据</div>
            </div>

            <div style="clear: both; height: 10px" ms-if="!loading && condition.quesType > 0 && condition.quesType < 4"></div>

            <div class="statistic_table hide" ms-class="hide: loading || condition.quesType !== 0">
                <a class="export" ms-click="changeModel('export')">导出</a>
                <div class="scoreCount" ms-text="'试卷总分：' + (paperInfo.score || 0) + '分'"></div>
                <div class="status">
                    <span ms-class="statusActive: condition.submitStatus" ms-click="changeModel('submitStatus', true)" ms-text="'已提交(' + scoreView.submit.size() + ')'"></span>
                    <span ms-class="statusActive: !condition.submitStatus" ms-click="changeModel('submitStatus', false)" ms-text="'未提交(' + scoreView.unsubmit.size() + ')'"></span>
                </div>
                <div class="title first" style="border-top: 1px solid #D1D1D1;">
                    <span>班级</span>
                    <span>姓名</span>
                    <span ms-click="changeModel('quesSort', 1)">单选题<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                    <span ms-click="changeModel('quesSort', 2)">多选题<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                    <span ms-click="changeModel('quesSort', 3)">判断题<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                    <span ms-click="changeModel('quesSort', 4)">填空题<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                    <span ms-click="changeModel('quesSort', 5)">解答题<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                    <span ms-click="changeModel('quesSort')">总分<img src="/home/image/evaluateManageTea/statistic/switch.png"></span>
                </div>
                <div class="title content hide" ms-class="hide: !condition.submitStatus" ms-repeat="scoreView.submit" ms-css-background="el.late && '#F1F1F1'">
                    <span ms-text="el.gradeName + el.className"></span>
                    <span class="name" ms-text="el.username" ms-click="changeModel('jump', el.id, parseInt(el.type))"></span>
                    <span ms-text="parseInt(quesTypeStatus[1]) > 0 ? el.score1 : '-'">20</span>
                    <span ms-text="parseInt(quesTypeStatus[2]) > 0 ? el.score2 : '-'">20</span>
                    <span ms-text="parseInt(quesTypeStatus[3]) > 0 ? el.score3 : '-'">20</span>
                    <span ms-text="parseInt(quesTypeStatus[4]) > 0 ? el.score4 : '-'">10</span>
                    <span ms-text="parseInt(quesTypeStatus[5]) > 0 ? el.score5 : '-'">30</span>
                    <span ms-text="el.score || '-'" class="score">100</span>
                </div>
                <div class="title content hide" ms-class="hide: !condition.submitStatus">
                    <span class="average">平均分</span>
                    <span ms-text="scoreView.average.single || '-'"></span>
                    <span ms-text="scoreView.average.multiple || '-'"></span>
                    <span ms-text="scoreView.average.judge || '-'"></span>
                    <span ms-text="scoreView.average.fill || '-'"></span>
                    <span ms-text="scoreView.average.explain || '-'"></span>
                    <span style="border: none;" ms-text="scoreView.average.count || '-'"></span>
                </div>
                <div class="title content hide" ms-class="hide: condition.submitStatus" ms-repeat="scoreView.unsubmit">
                    <span ms-text="el.gradeName + el.className"></span>
                    <span class="name" ms-text="el.username" ms-click="changeModel('jump', el.id, 'unsubmit')"></span>
                    <span>-</span>
                    <span>-</span>
                    <span>-</span>
                    <span>-</span>
                    <span>-</span>
                    <span style="border: none;">-</span>
                </div>
            </div>
            
            <div class="single_question_statistic hide" ms-class="hide: loading || condition.quesType === 0 || question[condition.quesType] === false">
                <table border="0" cellspacing="0" cellpadding="0" border-color="red">
                    <tr class="title first">
                        <td>班级</td>
                        <td>姓名</td>
                        <td style="cursor: pointer;" ms-repeat="questionView[0].number" ms-text='el' ms-click="changeModel('showQuestion', $index)"></td>
                    </tr>
                    <tr class="title content" ms-repeat="questionView" ms-css-background="el.late && '#F1F1F1'">
                        <td ms-text="el.gradeName + el.className"></td>
                        <td class="name" ms-text="el.username" ms-click="changeModel('jump', el.userId, parseInt(el.type))"></td>
                        <td ms-repeat-correct="el.correct" ms-attr-class="condition.quesType < 4 && (parseInt(correct) ? 'right' : 'wrong')" ms-text="condition.quesType < 4 ? (parseInt(correct) ? '√' : '×') : correct"></td>
                    </tr>
                    <tr class="title">
                        <td colspan="2">答错人数</td>
                        <td style="color: black;" ms-repeat="questionWrong[condition.quesType]" ms-text="el"></td>
                    </tr>
                </table>
            </div>

            <div class="spinner hide" ms-class="hide: !loading">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>

            <div style="clear: both; height: 50px;"></div>
        </div>

        <div class="spinner hide" ms-class="hide: loading !== 1">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>

        <div class="hide" ms-class="hide: loading !== 2" style="width: 1200px; margin: 0 auto; height: 300px; background: white; text-align: center; line-height: 300px; font-size: 18px;">暂无数据，请刷新重试。</div>

        <div class="clear" style="height: 80px; text-align: center;"></div>

        <div class="import_ques hide" ms-class="hide: importWindow === false">
            <div class="import_ques_content">
                <p class="import_ques_content_title">　　　试题详情<span class="import_ques_content_close" ms-click="changeModel('showQuestion', false)">×</span></p>

                <div style="overflow: auto; height: 360px;">

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
                                <span ms-html="importQues.analysis || '略'"></span>
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
        </div>

    </div>
@endsection

@section('js')
	<script type="text/javascript">
		require(['evaluateManageTea/statistic'], function (vm) {
            vm.paperId = {{$paperId}} || null;
            vm.paperId && vm.init();
            avalon.scan();
		});
	</script>
@endsection