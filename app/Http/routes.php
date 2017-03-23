<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//认证路由
use App\Http\Controllers\Home\indexController;

Route::get('/', 'Home\indexController@index');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');
// 注册路由...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


/*
||--------------------------------------------------------------------------------------
||     -------------------------- 前台路由组 ----------------------------2017/2/27
||--------------------------------------------------------------------------------------
 */

Route::group(['prefix' => '/', 'namespace' => 'Home'], function () {

    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 首页模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/index'], function () {
        //登陆
        Route::get('/login', ['middleware' => 'users', 'uses' => 'indexController@login']);
        //切换账号
        Route::get('/switchs', 'indexController@switchs');
        //注册
        Route::get('/register', ['middleware' => 'users', 'uses' => 'indexController@register']);
        //判断用户类型
        Route::get('/judge', 'indexController@judge');
        //注册成功
        Route::get('/registsucesstea', 'indexController@registsucesstea');
        //注册成功（学生）
        //Route::get('/registsucessstu', 'indexController@registsucessstu');
        //完善信息
        Route::post('/infotea', 'indexController@infotea');
        //完善信息 (学生)
        //Route::post('/infostu', 'indexController@infostu');
        //找回密码
        Route::get('/retrievepsd', 'indexController@retrievepsd');
        //设置密码
        Route::get('/resetpsd/{phone}', 'indexController@resetpsd');
        //活动赛事
        Route::get('/games', 'indexController@games');
        //搜索
        Route::post('/search', 'indexController@search');

        //介绍视频接口
        Route::get('/introVdo', 'indexController@introVdo');
        //获取赛事接口
        Route::get('/getgames/{type}/{pageNumber}/{pageSize}', 'indexController@getgames');
        //名师介绍接口
        Route::get('/getteachers', 'indexController@getteachers');
        //专题课程接口
        Route::get('/getSpecialLessons', 'indexController@getSpecialLessons');
        //点评课程接口
        Route::get('/getCommentlLessons', 'indexController@getCommentlLessons');
        //登陆验证接口
        Route::post('/getCheckRes', 'indexController@getCheckRes');
        //注册用户名验证接口
        Route::post('/getCheckUname', 'indexController@getCheckUname');
        //注册手机号验证接口
        Route::post('/getCheckUphone', 'indexController@getCheckUphone');
        //获取手机验证码接口
        Route::get('/getMessage/{phone}', 'indexController@getMessage');
        //手机验证码验证接口
        Route::get('/checkCode/{code}', 'indexController@checkCode');
        //邀请码验证接口
        Route::get('/getInviteCode/{code}', 'indexController@getInviteCode');
        //获取省接口
        Route::get('/getProvince', 'indexController@getProvince');
        //获取市接口
        Route::get('/getCity/{code}', 'indexController@getCity');
        //修改密码接口
        Route::post('/resetPassword', 'indexController@resetPassword');
        //搜索专题课程接口
        Route::get('/getCourseaa/{search}/{pageNumber}/{pageSize}/{ordder?}', 'indexController@getCourseaa');
        //搜索点评课程接口
        Route::get('/getCoursebb/{search}/{pageNumber}/{pageSize}/{ordder?}', 'indexController@getCoursebb');

        //移动端头像上传接口
        Route::post('/editHeadImg', 'indexController@editHeadImg');
        //获取手机验证码接口（移动端）
        Route::post('/getMessages', 'indexController@getMessages');
    });

    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 资源模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/resource', 'middleware' => 'checkLogin', 'namespace' => 'resource'], function () {
        //清空缓存接口
        Route::get('/wudi/flush', 'resourceController@flush');

        //资源首页
        Route::get('/', 'resourceController@index');
            //获取列表选项接口
            Route::get('/getRessels/{type}', 'resourceController@getRessels');
            //侧边栏选项接口
            Route::get('/getSidesels/{grade}/{subject}/{edition}/{book}', 'resourceController@getSidesels');
            //获取资源接口
            Route::post('/getResource', 'resourceController@getResource');
        //添加资源页
        Route::get('/uploadRes', 'resourceController@uploadRes');
            //获取资源类型接口
            Route::get('/getType/{type}/{grade?}/{subject?}/{edition?}/{book?}', 'resourceController@getType');
            //pass平台上传资源接口
            Route::post('/uploadResource', ['middleware' => 'check:true', 'uses' => 'resourceController@uploadResource']);
            //pass平台资源转换接口
            Route::post('/transformation', ['middleware' => 'check:true', 'uses' => 'resourceController@transformation']);
            //添加资源接口
            Route::post('/addResource','resourceController@addResource');

        //资源详情页
        Route::get('/resDetail/{id}', 'resourceController@resDetail');
            //获取视频播放数据接口
            Route::get('/getDetail/{id}','resourceController@getDetail');
            //获取下载接口
            Route::get('/getDown/{id}','resourceController@getDown');
            //获取资源相关推荐
            Route::get('getRealtion/{id}','resourceController@getRealtion');
            //收藏接口
            Route::get('addCollection/{resId}','resourceController@addCollection');
            //获取评论接口
            Route::get('getCommentInfo/{id}','resourceController@getCommentInfo');
            //发布评论
            Route::post('publishComment','resourceController@publishComment');
            //删除评论
            Route::get('deleteComment/{id}','resourceController@deleteComment');
            //点赞
            Route::get('addLike/{id}','resourceController@addLike');
    });
    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 用户模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/member', 'namespace' => 'member'], function () {
        /*
        ||--------------------------------------------------------------------------------------
        ||     --------------------- 个人主页路由组 && 公开 && 账号管理---------------
        ||--------------------------------------------------------------------------------------
         */
        //学员个人中心主页    0 学生学员&&    1  教师学员
        Route::get('/studentHomePage/{id}', ['middleware' => 'online', 'uses' => 'perSpaceController@studentHomePage']);
        //	名师个人中心主页  type 为 2
        Route::get('/teacherHomePage/{id}', ['middleware' => 'online', 'uses' => 'perSpaceController@teacherHomePage']);

        //名师个人主页 --- 用户信息
        Route::get('/getUserInfo/{id}', ['middleware' => 'online', 'uses' => 'perSpaceController@getUserInfo']);
        //名师个人主页 --- 获取总数getCount
        Route::post('/getCount', ['middleware' => 'online', 'uses' => 'perSpaceController@getCount']);
        //个人主页--资源分类 && 我的资源收藏
        Route::post('/getResourceType', ['middleware' => 'online', 'uses' => 'perSpaceController@getResourceType']) ;
        Route::post('/resourceStore/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' =>  'perSpaceController@resourceStore']);
        //个人主页--我的课程收藏
        Route::post('/courseStore/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' =>  'perSpaceController@courseStore']);
        //个人主页-- 我的试题收藏
        Route::post('/examStore/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@examStore']);
        //个人主页--獲取科目 && 我的试卷收藏
        Route::post('/getSubjects', ['middleware' => 'online', 'uses' => 'perSpaceController@getSubjects']);
        Route::post('/paperStore/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@paperStore']);
        //个人主页--我的问答收藏
        Route::post('/answerStore/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@answerStore']);
        //教师个人中心 --  我的资源
        Route::post('/myResource/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@myResource']);
        Route::post('/deleteMyResource', ['middleware' => 'online', 'uses' => 'perSpaceController@deleteMyResource']);
        //个人中心我的关注
        Route::get('/myFocus/{pageNumber}/{pageSize}/{id}', ['middleware' => 'online', 'uses' => 'perSpaceController@myFocus']);
        //个人中心我的粉丝&&我的好友（暂定 谁关注我就是我的好友）
        Route::get('/myFriends/{pageNumber}/{pageSize}/{id}', ['middleware' => 'online', 'uses' => 'perSpaceController@myFriends']);
        //查看是否关注
        Route::post('/followUser', ['middleware' => 'online', 'uses' => 'perSpaceController@followUser']);
        // 获取全部通知
        Route::post('/getNoticeInfo/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@getNoticeInfo']);
        // 获取评论回复
        Route::post('/getCommentInfo/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@getCommentInfo']);
        // 通知消息更改状态
        Route::get('/changeNoticeStatus/{type}', 'perSpaceController@changeNoticeStatus');
        // 删除消息
        Route::post('/deleteMessage', 'perSpaceController@deleteMessage');

        //个人中心获取我的问答接口
        Route::get('getQuestion/{type}/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@getQuestion']);
        //个人中心获取等待回答接口
        Route::get('getWaitAnswer/{type}/{pageNumber}/{pageSize}', ['middleware' => 'online', 'uses' => 'perSpaceController@getWaitAnswer']);
        //个人中心(公开)获取教师课程接口
        Route::get('getTeacherCourse/{type}/{pageNumber}/{pageSize}/{id}', 'perSpaceController@getTeacherCourse');
        //个人中心(公开)获取正在学习的课程接口
        Route::get('getStudentCourse/{type}/{pageNumber}/{pageSize}/{id}', 'perSpaceController@getStudentCourse');
        //个人中心(公开)获取教师问答接口
        Route::get('getTeacherAnswer/{type}/{pageNumber}/{pageSize}/{id}', 'perSpaceController@getTeacherAnswer');
        //个人中心获取课程问答接口(老师)getTeacherCourseQa
        Route::get('getTeacherCourseQa/{type}/{pageNumber}/{pageSize}/{id}', 'perSpaceController@getTeacherCourseQa');
        //个人中心获取课程问答接口(学生)getStudentCourseQa
        Route::get('getStudentCourseQa/{type}/{pageNumber}/{pageSize}/{id}', 'perSpaceController@getStudentCourseQa');

        /*===============================个人主页公开======================================*/
        //	教师个人主页--公开
        Route::get('/teacherHomePagePublic/{id}', ['middleware' => 'online', 'uses' => 'publicHomePageController@teacherHomePagePublic']);
        //	学生个人主页--公开
        Route::get('/studentHomePagePublic/{id}', ['middleware' => 'online', 'uses' => 'publicHomePageController@studentHomePagePublic']);

        /*=================================账号管理========================================*/

        //账号管理 -- 教师
        Route::get('/accountManagerTeacher/{id}', ['middleware' => 'online', 'uses' => 'accountManagerController@accountManagerTeacher']);
        //账号管理 -- 学生
        Route::get('/accountManagerStudent/{id}', ['middleware' => 'online', 'uses' => 'accountManagerController@accountManagerStudent']);
        //头像上传接口
        Route::post('/addImg', 'accountManagerController@addImg');
        //裁剪参数接口
        Route::post('/cutImg', 'accountManagerController@cutImg');
        //后台添加用户裁剪参数接口
        Route::post('/trimImg', 'accountManagerController@trimImg');
        //信息维护保存
        Route::post('/infoUphold', 'accountManagerController@infoUphold');
        //修改密码-- 检测当前密码 && 更新
        Route::post('/checkPassword', 'accountManagerController@checkPassword');
        //教师绑定学科 --查询 && 新增 && 查询绑定学科是否存在
        Route::post('/getBindSubjects', 'accountManagerController@getBindSubjects');
        //教师绑定学科 -- 获取 年级 科目 版本 册别
        Route::post('/bindSubjectsInfo', 'accountManagerController@bindSubjectsInfo');
        //教师绑定学科 -- 列表
        Route::post('/bindSubject/{pageNumber}/{pageSize}', 'accountManagerController@bindSubject');
        //教师绑定学科 -- 列表
        Route::post('/addCourse/{pageNumber}/{pageSize}', 'accountManagerController@addCourse');

        //修改手机号接口
        Route::post('/changePhone', 'accountManagerController@changePhone');
        //检查唯一性接口
        Route::post('/unique/{table}/{column}', 'accountManagerController@unique');

    });



    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 社区模块 ----------------------------
   ||--------------------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/community', 'namespace' => 'community'], function () {
        //社区首页
        Route::get('/', 'communityController@index');
        //首页新闻数据接口
        Route::get('/getlist','communityController@getlist');
        //首页最热视频推荐数据接口
        Route::get('/gethotvideo','communityController@gethotvideo');
        //首页名师列表数据接口
        Route::get('/getteacher','communityController@getteacher');
        //首页最新学员列表数据接口
        Route::get('/getstudent','communityController@getstudent');
        //在线老师接口
        Route::get('/getteachers','communityController@getteachers');
        //问答列表接口
        Route::get('/getquestions/{type}/{pageNumber}/{pageSize}/{subid}/{iswaitans}', 'communityController@getquestions');

        //创客 科目 下拉列表数据获取接口
        Route::get('/getSubjects', 'communityController@getSubjects');
        //老师所受科目 下拉列表数据获取接口
        Route::get('/getteaSubjects', 'communityController@getteaSubjects');

        //名师列表
        Route::get('/theteacher/', 'theteacherController@index');
        //名师列表获取数据接口
        Route::get('/gettheteacher/{type}/{pageNumber}/{pageSize}', 'theteacherController@gettheteacher');
        //26字母接口
        Route::get('/getfirstletter', 'theteacherController@getfirstletter');



        //新闻资讯列表
        Route::get('/newlist', 'newlistController@index');
        //新闻列表接口
        Route::get('/getnewlist/{pageNumber}/{pageSize}','newlistController@getnewlist');
        //新闻资讯详情
        Route::get('/newdetail/{id}', 'newlistController@newdetail');
        //新闻资讯详情数据接口
        Route::get('/getnewdetail/{id}','newlistController@getnewdetail');


        //问答回复提交
        Route::post('qesreply', 'communityController@qesreply');
        //问答评论提交
        Route::post('qesreplys', 'communityController@qesreplys');
        //是否采纳
        Route::get('cainaclick', 'communityController@cainaclick');



        //社区-热门视频详情页
        Route::get('videodetail/{id}', 'videodetailController@index');
        //社区-热门视频详情数据接口
        Route::get('/getvideodetail/{id}','videodetailController@getvideodetail');
        //点击播放量接口
        Route::get('/playAmount/{id}','videodetailController@playAmount');

        //问题反馈
        Route::get('feedback', 'communityController@feedback');
        Route::post('dofeedback', 'communityController@dofeedback');
        //学生提问
        Route::get('question', 'communityController@question');
        Route::post('doquestion', 'communityController@doquestion');
        //老师回答
        Route::get('answer/{qesId}/{msgId?}', 'communityController@answer');
        Route::post('doanswer', 'communityController@doanswer');
        //问答详细
        Route::get('askDetail/{qesId}', 'communityController@askDetail');
        //获取问答 点赞、收藏状态
        Route::get('getaskstatus/{qesId}', 'communityController@getaskstatus');
        //问答点赞
        Route::get('qesthumb/{qesId}', 'communityController@qesthumb');
        //问答收藏
        Route::get('qesfav/{qesId}', 'communityController@qesfav');
        //问答取消收藏
        Route::get('qesdefav/{qesId}', 'communityController@qesdefav');

        //问答评论提交
        Route::post('qescomment', 'communityController@qescomment');

        //问答评论获取
        Route::get('getqescomment/{qesId}', 'communityController@getqescomment');
        //问答评论删除
        Route::get('delqescomment/{cmId}', 'communityController@delqescomment');
        //问答评论点赞
        Route::get('favqescomment/{cmId}', 'communityController@favqescomment');

        //个人中心获取提问接口
        Route::get('getQuestion/{type}/{pageNumber}/{pageSize}', 'communityController@getQuestion');
        //个人中心获取收藏提问接口
        Route::get('getcolQuestion/{pageNumber}/{pageSize}', 'communityController@getcolQuestion');

        //个人主页获取提问接口
        Route::get('getQuestionb/{uid}/{usertype}/{type}/{pageNumber}/{pageSize}', 'communityController@getQuestionb');
        //个人主页获取我的收藏课程接口
        Route::get('/getCollectionInfo/{uid}/{pageNumber}/{pageSize}/{ord?}', 'communityController@getCollectionInfo');

    });




    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 老师课程模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/teacherCourse', 'namespace' => 'teacherCourse'], function () {

        //教师列表页
        Route::get('/list', 'teacherCourseController@teacherCourseList');
        //我的课程数据接口
        Route::get('/getListMyCourse/{type}/{pageNumber}/{pageSize}', 'teacherCourseController@getListMyCourse');
        //推荐课程数据接口
        Route::get('/getListRecCourse/{subjectId}/{pageNumber}/{pageSize}/{minId}', 'teacherCourseController@getListRecCourse');
        //全部课程数据接口
        Route::get('/getListAllCourse', 'teacherCourseController@getListAllCourse');
        //取年级
        Route::get('/getGradeCourse', 'teacherCourseController@getGradeCourse');
        //取学科
        Route::get('/getSubjectCourse', 'teacherCourseController@getSubjectCourse');
        //取册别
        Route::get('/getBookCourse', 'teacherCourseController@getBookCourse');
        //取版本
        Route::get('/getEditionCourse', 'teacherCourseController@getEditionCourse');
        //收藏课程
        Route::get('/collectionCourse/{courseId}', 'teacherCourseController@collectionCourse');
        //取消收藏课程
        Route::get('/collectionCourseDel/{courseId}', 'teacherCourseController@collectionCourseDel');
        //删除课程
        Route::get('/deleteCourse/{courseId}', 'teacherCourseController@deleteCourse');


        /* 上传课程接口集 */
        //获取课程归属接口
        Route::get('/getType', 'createCourseController@getType');
        //获取课程章节接口
        Route::get('/getChapter/{ids}', 'createCourseController@getChapter');
        //添加课程基本信息接口
        Route::post('/addCourseInfo', 'createCourseController@addCourseInfo');
        //添加课程接口
        Route::post('/addCourse', 'createCourseController@addCourse');
        //提交下发班级
        Route::post('/addSelclass', 'createCourseController@addSelclass');
        //获取资源列表接口
        Route::get('/getresource/{type}/{chapterId}', 'createCourseController@getresource');
        //获取编辑课程pagea信息
        Route::get('/geteditCourseInfo/{type}/{courseId}', 'createCourseController@geteditCourseInfo');
        //删除库数据接口
        Route::get('/deletedatabase/{type}/{id}', 'createCourseController@deletedatabase');
        //通知接口
        Route::get('/sendMsg/{courseId}', 'createCourseController@sendMsg');
        // 获取同步测验数据(课前导学)
        Route::get('/getTestInfoa/{id}', 'createCourseController@getTestInfoa');
        // 获取同步测验数据(课堂授课)
        Route::get('/getTestInfob/{id}', 'createCourseController@getTestInfob');
        // 获取同步测验数据(课后指导)
        Route::get('/getTestInfoc/{id}', 'createCourseController@getTestInfoc');






        //教师详情页
        Route::get('/teaDetail/{id}', 'teacherCourseController@teacherCourseDetail');
        //课程问答评论
        Route::post('/courseComment', 'teacherCourseDetailController@courseComment');
        //获取课程问答数据
        Route::get('/getCourseCommentAsk/{courseId}', 'teacherCourseDetailController@getCourseCommentAsk');
        //获取课程问答数据(课程目录)
        Route::get('/getCourseCommentCatalogAsk/{courseId}', 'teacherCourseCatalogController@getCourseCommentCatalogAsk');
        //共享笔记接口
        Route::get('/getCourseShareNote/{courseId}', 'teacherCourseCatalogController@getCourseShareNote');
        //我的笔记接口getCourseMyNote
        Route::get('/getCourseMyNotes/{courseId}', 'teacherCourseCatalogController@getCourseMyNotes');
        //提交笔记内容courseSubmitNote
        Route::post('/courseSubmitNote', 'teacherCourseCatalogController@courseSubmitNote');
        //提交贴士内容courseSubmitNote
        Route::post('/courseSubmitTips', 'teacherCourseCatalogController@courseSubmitTips');
        //获取贴士getCourseTips
        Route::get('/getCourseTips/{courseId}/{chapterId}', 'teacherCourseCatalogController@getCourseTips');
        //修改贴士promptEditContent
        Route::post('/promptEditContent', 'teacherCourseCatalogController@promptEditContent');
        //删除贴士
        Route::get('/deleteTips/{courseId}', 'teacherCourseCatalogController@deleteTips');
        //获取信息
        Route::get('/getCourseDataInfo', 'teacherCourseDetailController@getCourseDataInfo');
        //验证贴士发布老师courseTeacher
        Route::get('/courseTeacher/{courseId}/{chapterId}/{thisTime}', 'teacherCourseCatalogController@courseTeacher');
        //验证视频或者文档courseDocumentVideo
        Route::get('/courseDocumentVideo/{chapterId}', 'teacherCourseCatalogController@courseDocumentVideo');






        //教师课程目录详情页
        Route::get('/teaCatalog/{id}/{chapterId}', 'teacherCourseController@teacherCourseCatalog');
        //共享笔记接口(详情页)
        Route::get('/getCourseDetailShareNote/{courseId}', 'teacherCourseDetailController@getCourseDetailShareNote');
        //我的笔记接口(详情页)
        Route::get('/getCourseDetailMyNotes/{courseId}', 'teacherCourseDetailController@getCourseDetailMyNotes');
        //select2联动(笔记)
        Route::get('/getWholeChapter/{courseId}/{type}', 'teacherCourseDetailController@getWholeChapter');
        //select2联动(共享)
        Route::get('/getShareWholeChapter/{courseId}/{type}', 'teacherCourseDetailController@getShareWholeChapter');
        //删除笔记deleteNote
        Route::get('/deleteNote/{noteId}', 'teacherCourseDetailController@deleteNote');
        //修改笔记内容modifyComment
        Route::post('/modifyContent', 'teacherCourseDetailController@modifyContent');
        //笔记私有转公开privateNote
        Route::get('/privateNote/{noteId}', 'teacherCourseDetailController@privateNote');

        //获取课程章节目录接口
        Route::get('/getCourseChapter/{id}', 'teacherCourseCatalogController@getCourseChapter');
        //点击播放视频或资源接口
        Route::get('/getCourseVideo/{chapter}', 'teacherCourseCatalogController@getCourseVideo');
        //取视频数据接口getShowCourseVideo
        Route::get('/getShowCourseVideo/{chapter}', 'teacherCourseCatalogController@getShowCourseVideo');
        //默认取视频接口getDefaultInfo
        Route::get('/getDefaultInfo/{chapter}', 'teacherCourseCatalogController@getDefaultInfo');
        //尚未学习getCourseStudyNo
        Route::get('/getCourseStudyNo', 'teacherCourseDetailController@getCourseStudyNo');
        //完成学习getCourseStudyFinish
        Route::get('/getCourseStudyFinish', 'teacherCourseDetailController@getCourseStudyFinish');
        //正在学习getCourseStudySchedule
        Route::get('/getCourseStudySchedule', 'teacherCourseDetailController@getCourseStudySchedule');
        //获取年级班级接口getCourseGradeClass
        Route::post('/getCourseGradeClass', 'teacherCourseDetailController@getCourseGradeClass');




        // 获取同步测验数据(课前导学)
        Route::get('/getLeadLearn/{id}', 'synchroTestController@getLeadLearn');
        // 获取同步测验数据(课堂授课)
        Route::get('/getClassTeach/{id}', 'synchroTestController@getClassTeach');
        // 获取同步测验数据(课后指导)
        Route::get('/getAfterClass/{id}', 'synchroTestController@getAfterClass');
        // 同步练习(作业样式)获取试卷详细内容
        Route::get('/getPaperInfo/{id}', 'synchroTestController@getPaperInfo');
        // 同步练习(测验样式)获取试卷详细内容
        Route::get('/getTestPaperInfo/{id}', 'synchroTestController@getTestPaperInfo');
    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 学生课程模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/studentCourse', 'namespace' => 'studentCourse'], function () {

        //学生列表页
        Route::get('/list', 'studentCourseController@studentCourseList');
        //我的课程数据接口(等待学习)
        Route::get('/getListMyCourse', 'studentCourseController@getListMyCourse');
        //我的课程数据接口(正在学习)
        Route::get('/getListMyCourseUnderway', 'studentCourseController@getListMyCourseUnderway');
        //我的课程数据接口(学习完成)
        Route::get('/getListMyCourseFinish', 'studentCourseController@getListMyCourseFinish');
        //全部课程数据接口
        Route::get('/getListAllCourse', 'studentCourseController@getListAllCourse');
        //学生详情页
        Route::get('/stuDetail/{id}', 'studentCourseController@studentCourseDetail');
        //学生课程目录详情页
        Route::get('/stuCatalog/{id}/{chapterId}', 'studentCourseController@studentCourseCatalog');

        //课程播放courseOnPlay
        Route::get('/courseOnPlay/{chapter}/{courseId}', 'studentCourseCatalogController@courseOnPlay');
        //文档播放courseDocument
        Route::get('/courseDocument/{chapter}/{courseId}', 'studentCourseCatalogController@courseDocument');
        //获取贴士绑定时间courseTipsTime
        Route::get('/courseTipsTime/{chapter}/{courseId}', 'studentCourseCatalogController@courseTipsTime');




        //课程问答评论
        Route::post('/courseComment', 'studentCourseDetailController@courseComment');
        //获取课程问答数据
        Route::get('/getCourseCommentAsk/{courseId}', 'studentCourseDetailController@getCourseCommentAsk');
        //共享笔记接口(详情页)
        Route::get('/getCourseDetailShareNote/{courseId}', 'studentCourseDetailController@getCourseDetailShareNote');
        //select2联动(笔记)
        Route::get('/getWholeChapter/{courseId}/{type}', 'studentCourseDetailController@getWholeChapter');
        //我的笔记接口(详情页)
        Route::get('/getCourseDetailMyNotes/{courseId}', 'studentCourseDetailController@getCourseDetailMyNotes');
        //删除笔记deleteNote
        Route::get('/deleteNote/{noteId}', 'studentCourseDetailController@deleteNote');
        //修改笔记内容modifyComment
        Route::post('/modifyContent', 'studentCourseDetailController@modifyContent');
        //笔记私有转公开privateNote
        Route::get('/privateNote/{noteId}', 'studentCourseDetailController@privateNote');
        //获取信息
        Route::get('/getCourseDataInfo', 'studentCourseDetailController@getCourseDataInfo');


        //点击播放视频或资源接口
        Route::get('/getCourseVideo/{chapter}', 'studentCourseCatalogController@getCourseVideo');
        //取视频数据接口getShowCourseVideo
        Route::get('/getShowCourseVideo/{chapter}', 'studentCourseCatalogController@getShowCourseVideo');
        //默认取视频接口getDefaultInfo
        Route::get('/getDefaultInfo/{chapter}', 'studentCourseCatalogController@getDefaultInfo');
        //取贴士个数getCountTip
        Route::get('/getCountTip/{chapterId}', 'studentCourseCatalogController@getCountTip');
        //验证文档类型courseJudgeType
        Route::get('/courseJudgeType/{courseId}', 'studentCourseCatalogController@courseJudgeType');



        //获取课程章节目录接口
        Route::get('/getCourseChapter/{id}', 'studentCourseCatalogController@getCourseChapter');

        //获取课程问答数据(课程目录)
        Route::get('/getCourseCommentCatalogAsk/{courseId}', 'studentCourseCatalogController@getCourseCommentCatalogAsk');
        //共享笔记接口
        Route::get('/getCourseShareNote/{courseId}', 'studentCourseCatalogController@getCourseShareNote');
        //我的笔记接口getCourseMyNote
        Route::get('/getCourseMyNotes/{courseId}', 'studentCourseCatalogController@getCourseMyNotes');
        //提交笔记内容courseSubmitNote
        Route::post('/courseSubmitNote', 'studentCourseCatalogController@courseSubmitNote');




        // 获取同步测验数据(课前导学)
        Route::get('/getLeadLearn/{id}', 'synchroTestController@getLeadLearn');
        // 获取同步测验数据(课堂授课)
        Route::get('/getClassTeach/{id}', 'synchroTestController@getClassTeach');
        // 获取同步测验数据(课后指导)
        Route::get('/getAfterClass/{id}', 'synchroTestController@getAfterClass');

        // 获取作业试题详情 未答
        Route::get('/getPaperInfo/{id}', 'synchroTestController@getPaperInfo');
        // 获取作业试题详情 已答
        Route::get('/getHomeWorkInfo/{id}/{userId}', 'synchroTestController@getHomeWorkInfo');
        // 提交作业试题
        Route::post('/submitPaper', 'synchroTestController@submitPaper');
        // 保存作业答案
        Route::post('/saveHomework', 'synchroTestController@saveHomework');

        // 获取测验试题详情 未答
        Route::get('/getTestPaperInfo/{id}', 'synchroTestController@getTestPaperInfo');
        // 获取测验试题详情 已答
        Route::get('/getTestAnswerInfo/{id}/{userId}', 'synchroTestController@getTestAnswerInfo');
        // 提交测验试题
        Route::post('/submitTestPaper', 'synchroTestController@submitTestPaper');
        // 保存测验答案
        Route::post('/saveTestAnswer', 'synchroTestController@saveTestAnswer');
        // 将测验答案存入答案表
        Route::post('/moveAnswer', 'synchroTestController@moveAnswer');
    });

    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 课程点评模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/lessonComment', 'namespace' => 'lessonComment'], function () {
        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 名师主页 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //  名师主页
        Route::get('/teacher/{teacherID}', 'teacherHomepageController@index');
        //	获取名师信息
        Route::get('/getTeacherInfo/{teacherID}', 'teacherHomepageController@getTeacherInfo');
        //  获取名师视频
        Route::post('/getTeacherVideo', 'teacherHomepageController@getTeacherVideo');
        //  获取名师视频总数
        Route::post('/getTeacherVideoCount', 'teacherHomepageController@getTeacherVideoCount');

        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 学生主页 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //	名师主页
        Route::get('/student/{studentID}', 'studentHomepageController@index');
        //  获取学员信息
        Route::get('/getStuInfo/{studentID}', 'studentHomepageController@getStuInfo');
        //  获取总数
        Route::post('/getCount', 'studentHomepageController@getCount');
        //  获取课程视频
        Route::post('/getVideo', 'studentHomepageController@getVideo');
        //  获取课程视频总数
        Route::post('/getVideoCount', 'studentHomepageController@getVideoCount');
        //  查看是否关注
        Route::post('/getFirst', 'studentHomepageController@getFirst');

        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 点评详情 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //  已完成点评详情
        Route::get('/detail/{commentID}', 'commentDetailController@index');
        //  获取已完成点评信息
        Route::get('/getDetailInfo/{commentID}/{type}', 'commentDetailController@getDetailInfo');
        //  最新点评推荐
        Route::get('/getNewComment', 'commentDetailController@getNewComment');
        //  获取点评视频评论
        Route::get('/getApplyComment/{commentID}', 'commentDetailController@getApplyComment');
        //  评论点赞
        Route::post('/likesComment', 'commentDetailController@likesComment');
        //  递增视频观看数
        Route::post('/videoIncrement', ['middleware' => 'check', 'uses' => 'commentDetailController@videoIncrement']);
        //  待点评详情
        Route::get('/wait/{commentID}/{messageID?}', ['middleware' => 'check', 'uses' => 'commentDetailController@wait']);
        //  上传点评视频
        Route::get('/upload/{orderSn}/{messageID?}', ['middleware' => 'check', 'uses' => 'commentDetailController@uploadComment']);
        //  审核未通过重新上传视频
        Route::get('/reUploadComment/{commentID}/{messageID?}', ['middleware' => 'check', 'uses' => 'commentDetailController@reUploadComment']);
        //  完成点评视频上传
        Route::post('/finishComment', ['middleware' => 'check', 'uses' => 'commentDetailController@finishComment']);

        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 购买点评 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //	支付页面
        Route::get('/buy/{teacherID}', ['middleware' => 'check', 'uses' => 'buyCommentController@index']);
        //  生成订单
        Route::post('/generateOrder', ['middleware' => 'check', 'uses' => 'buyCommentController@generateOrder']);
        //  微信扫码
        Route::get('/scan/{orderID}', ['middleware' => 'check', 'uses' => 'buyCommentController@scan']);
        //  支付成功
        Route::get('/buySuccess/{orderID}', ['middleware' => 'check', 'uses' => 'buyCommentController@buySuccess']);
        //  上传视频
        Route::get('/buy/upload/{orderID}', ['middleware' => 'check', 'uses' => 'buyCommentController@upload']);
        //	审核未通过重新上传视频
        Route::get('/reUpload/{applyID}/{messageID?}', ['middleware' => 'check', 'uses' => 'buyCommentController@reUpload']);
        //  完成上传
        Route::post('/finishUpload', ['middleware' => 'check', 'uses' => 'buyCommentController@finishUpload']);

        //  pass平台上传资源
        Route::post('/uploadResource', ['middleware' => 'check:true', 'uses' => 'buyCommentController@uploadResource']);
        //  pass平台资源转换
        Route::post('/transformation', ['middleware' => 'check:true', 'uses' => 'buyCommentController@transformation']);
        //  pass平台获取文件信息
        Route::post('/getFileInfo', ['middleware' => 'check:true', 'uses' => 'buyCommentController@getFileInfo']);
        
        //  微信支付回调地址
        Route::any('/wxPayCallback', 'buyCommentController@wxPayCallback');
        //  微信扫码获取订单状态
        Route::get('/orderStatus/{orderID}', ['middleware' => 'check', 'uses' => 'buyCommentController@orderStatus']);

        //  支付宝异步回调页面
        Route::any('/alipayAsyncCallback', 'buyCommentController@alipayAsyncCallback');
        //  支付宝同步回调页面
        Route::any('/alipaySyncCallback', 'buyCommentController@alipaySyncCallback');
        //  支付宝支付
        Route::any('/alipay/{orderID}/{callback}', 'buyCommentController@alipay');

    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 关于我们模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/aboutUs', 'namespace' => 'aboutUs'], function () {
        //公司介绍
        Route::get('/firmintro/{type}', 'firmintroController@index');

        // 取数据接口
        Route::get('/getListone', 'firmintroController@getListone');
        Route::get('/getListtwo', 'firmintroController@getListtwo');
        Route::get('/getListthree', 'firmintroController@getListthree');
        Route::get('/getListfour', 'firmintroController@getListfour');
        Route::get('/getListfive', 'firmintroController@getListfive');
        Route::get('/getListsix', 'firmintroController@getListsix');

        //移动端关于我们接口(1 => 公司介绍 2 => 联系我们 3 => 常见问题)
        Route::get('/appAbouts/{type}', 'firmintroController@appAbouts');
    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 教师测评管理模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/evaluateManageTea', 'middleware' => 'checkLogin', 'namespace' => 'evaluateManageTea'], function () {

        // 测评管理首页
        Route::get('/index', 'evaluateManageTeaController@index');

        // 题库试卷详情页
        Route::get('/testPaperTea/{id}', 'evaluateManageTeaController@testPaperTea');

        // 教师批改 学生作业试卷
        Route::get('/homeScore/{id}/{userId}', 'evaluateManageTeaController@homeScore');
        Route::get('/getHomeScore/{id}/{userId}', 'evaluateManageTeaController@getHomeScore');
        Route::post('/submitHomeScore', 'evaluateManageTeaController@submitHomeScore');

        // 教师批改 学生测验试卷
        Route::get('/testScore/{id}/{userId}', 'evaluateManageTeaController@testScore');
        Route::get('/getTestScore/{id}/{userId}', 'evaluateManageTeaController@getTestScore');
        Route::post('/submitTestScore', 'evaluateManageTeaController@submitTestScore');

        // 获取在线题库的数据
        Route::post('/getOnlineQuestion', 'evaluateManageTeaController@getOnlineQuestion');

        // 获取试题布置的数据
        Route::post('/getTestInfo', 'evaluateManageTeaController@getTestInfo');

        // 获取试题批改的数据
        Route::post('/getQuestionInfo', 'evaluateManageTeaController@getQuestionInfo');
        // 试题批改 条件筛选数据
        Route::post('/getTeacherInfo', 'evaluateManageTeaController@getTeacherInfo');

        // 获取成绩查询的数据
        Route::post('/getQueryInfo', 'evaluateManageTeaController@getQueryInfo');

        // 获取年级信息
        Route::get('/getGradeInfo', 'evaluateManageTeaController@getGradeInfo');

        // 获取学科信息
        Route::get('/getSubjectInfo', 'evaluateManageTeaController@getSubjectInfo');

        // 获取班级信息
        Route::get('/getClassInfo', 'evaluateManageTeaController@getClassInfo');

        // 获取试卷详细内容
        Route::get('/getTestPaperDetail/{id}', 'evaluateManageTeaController@getTestPaperDetail');

        // 是否收藏试卷
        Route::get('/isCollectPaper/{id}', 'evaluateManageTeaController@isCollectPaper');

        // 收藏试卷
        Route::post('/collectPaper', 'evaluateManageTeaController@collectPaper');

        // 收藏试题
        Route::post('/collectQuestion', 'evaluateManageTeaController@collectQuestion');

        // 试题批改 查看单道解答题
        Route::post('/getSubjective', 'evaluateManageTeaController@getSubjective');

        // 试题批改 查看全部学生试卷详细
        Route::get('/getQuestionCorrectDetail/{paperId}/{classId}', 'evaluateManageTeaController@getQuestionCorrectDetail');

        // 试题批改 提交单道解答题
        Route::post('/submitSubject', 'evaluateManageTeaController@submitSubject');

        // 增加试卷浏览次数
        Route::post('/addPaperView', 'evaluateManageTeaController@addPaperView');

        // ================================================================================

		// 试卷详情页
		Route::get('/paperDetail/{importId}', 'evaluateManageTeaController@paperDetail');
		// 试卷编辑页
		Route::get('/editPaper/{lessonInfo}/{title}/{importId}', 'evaluateManageTeaController@editPaper');
		// 上传试卷图片
		Route::post('/uploadPaperImg', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@uploadPaperImg']);
		// 获取个人收藏引入试题
		Route::post('/importQues', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@importQues']);
		// Route::post('/importQues', 'evaluateManageTeaController@importQues');
		// 获取教师所属班级
		Route::post('/getTeacherClass', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getTeacherClass']);
		// Route::post('/getTeacherClass', 'evaluateManageTeaController@getTeacherClass');
		// 发布试卷
		Route::post('/publishPaper', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@publishPaper']);
		// Route::post('/publishPaper', 'evaluateManageTeaController@publishPaper');
		// 获取个人课程归属
		Route::post('/getLessonType', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getLessonType']);
		// Route::post('/getLessonType', 'evaluateManageTeaController@getLessonType');
		// 获取个人课程所属章节
		Route::post('/getLessonChapter', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getLessonChapter']);
		// Route::post('/getLessonChapter', 'evaluateManageTeaController@getLessonChapter');
		// 获取试卷
		Route::post('/getPaper', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getPaper']);
		// Route::post('/getPaper', 'evaluateManageTeaController@getPaper');
		// 试卷编辑引入完整试题
		Route::post('/importPaper', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@importPaper']);
		// Route::post('/importPaper', 'evaluateManageTeaController@importPaper');

		// 成绩查询详细 每个试卷进去后所有学生的分数
        Route::get('/scoreQuery/{id}/{classid}', 'evaluateManageTeaController@scoreQuery');
        //查询某个试卷下的所有考生的题型分数
        Route::post('/getAllScore', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getAllScore']);
        //Route::get('/getq1Score/{id}', 'evaluateManageTeaController@getq1Score');
        //查询某个试卷下的所有考生的具体题型答题错误或者正确情况
        Route::post('/getqScore', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getqScore']);
        //查询某个试卷下的所有考生的解答题分数
        Route::post('/getq5Score', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getq5Score']);

		// 成绩统计
		Route::get('/statistic/{paperId}', 'evaluateManageTeaController@statistic');
		// 获取试卷信息
		Route::post('/paperInfo', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@paperInfo']);
		// Route::post('/paperInfo', 'evaluateManageTeaController@paperInfo');
		// 获取试卷班级
		Route::post('/getPaperClass', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getPaperClass']);
		// Route::post('/getPaperClass', 'evaluateManageTeaController@getPaperClass');

        //查询某个试卷下全卷统计
        Route::post('/getAllScores', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getAllScores']);
		// Route::post('/getAllScores', 'evaluateManageTeaController@getAllScores');

        //获取某个试卷客观题答题详细
        Route::post('/getqScores', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getqScores']);

        //获取某个试卷主观题答题详细
        Route::post('/getq5Scores', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getq5Scores']);

        //获取某个试卷下的题型
        Route::post('/getPaperType', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getPaperType']);

        //导出某个试卷下全卷统计 参数试卷pid  班级classid（数组）
        Route::get('/paperExport/{pid}/{classid}', 'evaluateManageTeaController@paperExport');

        Route::post('/getOneQuestion', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getOneQuestion']);

        Route::post('/getQuestionAnswer', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageTeaController@getQuestionAnswer']);
    });
    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 学生测评管理模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/evaluateManageStu', 'middleware' => 'checkLogin', 'namespace' => 'evaluateManageStu'], function () {
        // 我的测验首页
        Route::get('/index', 'evaluateManageStuController@index');
        // 获取我的试题 getExamInfo
        Route::post('/getExamInfo', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageStuController@getExamInfo']);

        // 获取我的试题 getExamInfo
        Route::post('/getExamError', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageStuController@getExamError']);


        // 作业试题详情页 未答
        Route::get('/studentNoAnswer/{id}', 'evaluateManageStuController@studentNoAnswer');
        // 作业试题数据 未答
        Route::get('/getPaperInfo/{id}', 'evaluateManageStuController@getPaperInfo');
        // 作业试题详情页 已答
        Route::get('/studentPaperStu/{id}/{userId}', 'evaluateManageStuController@studentPaperStu');
        // 作业试题详情数据 已答
        Route::get('/getHomeWorkInfo/{id}/{userId}', 'evaluateManageStuController@getHomeWorkInfo');

        // 提交作业答案
        Route::post('/submitPaper', 'evaluateManageStuController@submitPaper');
        // 保存作业答案
        Route::post('/saveHomework', 'evaluateManageStuController@saveHomework');

        // 测验试题详情页 未答
        Route::get('/studentTestNoAnswer/{id}', 'evaluateManageStuController@studentTestNoAnswer');
        // 测验试题数据 未答
        Route::get('/getTestNoAnswer/{id}', 'evaluateManageStuController@getTestNoAnswer');
        // 测验试题详情页 已答
        Route::get('/studentTestPaperStu/{id}/{userId}', 'evaluateManageStuController@studentTestPaperStu');
        // 测验试题数据 已答
        Route::get('/getTestPaperStu/{id}/{userId}', 'evaluateManageStuController@getTestPaperStu');

        // 提交测验答案
        Route::post('/submitTestPaper', 'evaluateManageStuController@submitTestPaper');
        // 保存测验答案
        Route::post('/saveTestAnswer', 'evaluateManageStuController@saveTestAnswer');
        // 将测验答案存入答案表
        Route::post('/moveAnswer', 'evaluateManageStuController@moveAnswer');
        // 作业试题错题详细
        Route::get('/errorPaper/{id}/{userId}', 'evaluateManageStuController@errorPaper');
        // 作业试题详情数据
        Route::get('/getErrorInfo/{id}/{userId}', 'evaluateManageStuController@getErrorInfo');
        // 获取错题学科信息
        Route::get('/getSubjectInfo/{userId}', 'evaluateManageStuController@getSubjectInfo');
        // 删除每一个错题
        Route::post('/delQuestion', ['middleware' => 'CrossRequest', 'uses' => 'evaluateManageStuController@delQuestion']);
    });
    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 校长模块 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/principal', 'namespace' => 'principal'], function () {
        // 首页
        Route::get('/index', 'principalController@index');
    });
});
/*
||--------------------------------------------------------------------------------------
||     -------------------------- 后台台路由组 ----------------------------
||     -------------------------- 各位根据模块或者控制器再另建路由组 ----------------------------
||--------------------------------------------------------------------------------------
||----------后台路由命名规则 列表的路由：***List;添加的路由:add***;修改的路由edit***;删除的路由:del***;以便侧边栏选中判断----
 */
//后台登录
Route::get('/admin/login', ['middleware' => 'admins', function () {
    return view('admin.login');
}]);
Route::post('/admin/login', 'Auth\AuthController@adminLogin');


Route::post('admin/loginVideo/doUploadfile', 'Admin\loginVideo\loginVideoController@doUploadfile');
Route::post('admin/specialCourse/doUploadfile', 'Admin\specialCourse\SpecialDataController@doUploadfile');

//图片裁剪
Route::post('admin/resource/addImg', 'Admin\resource\resourceController@addImg');
Route::post('admin/resource/trimImg', 'Admin\resource\resourceController@trimImg');


//  支付宝异步回调退款
Route::any('/admin/order/alipayAsyncCallback', 'Admin\order\orderController@alipayAsyncCallback');
//  支付宝同步回调页面
//Route::any('/admin/order/alipaySyncCallback', 'Admin\order\orderController@alipaySyncCallback');


Route::group(['prefix' => '/admin', 'middleware' => 'adminauth', 'namespace' => 'Admin'], function () {

    //后台首页
    Route::get('index', 'IndexController@index');

    //页面跳转页面
    Route::get('message', function () {
        return view('admin.message');
    });
    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 权限管理 ----------------------------
    ||--------------------------------------------------------------------------------------
     */
    Route::group(['prefix' => '/auth', 'namespace' => 'auth'], function () {
        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 角色管理 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //  角色列表
        Route::get('/roleList', ['middleware' => 'permission:check.role', 'uses' => 'RoleController@roleList']);
        //  添加角色
        Route::get('/addRole', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@addRole']);
        //  创建角色
        Route::post('/createRole', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@createRole']);
        //  删除角色
        Route::get('/deleteRole/{roleID}', ['middleware' => 'permission:delete.role', 'uses' => 'RoleController@deleteRole']);
        //  编辑角色
        Route::get('/editRole/{roleID}', ['middleware' => 'permission:edit.role', 'uses' => 'RoleController@editRole']);
        //  修改角色
        Route::post('/updateRole', ['middleware' => 'permission:edit.role', 'uses' => 'RoleController@updateRole']);


        //  查看角色用户
        Route::get('/checkRoleUser/{roleID}', ['middleware' => 'permission:check.role', 'uses' => 'RoleController@checkRoleUser']);
        //  删除角色用户
        Route::get('/deleteRoleUser/{type}/{roleID}', ['middleware' => 'permission:delete.role', 'uses' => 'RoleController@deleteRoleUser']);
        //  添加角色用户
        Route::get('/addRoleUser/{roleID}', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@addRoleUser']);
        //  创建角色用户
        Route::post('/createRoleUser', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@createRoleUser']);
        //  获取部门或者职位信息
        Route::get('/getList/{departmentID?}', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@getList']);
        //  获取用户信息
        Route::get('/getUser/{type}/{id}/{roleID}', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@getUser']);


        //  查看角色权限
        Route::get('/checkRolePermission/{roleID}', ['middleware' => 'permission:check.role', 'uses' => 'RoleController@checkRolePermission']);
        //  添加角色权限
        Route::get('/addRolePermission/{roleID}', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@addRolePermission']);
        //  创建角色用户
        Route::post('/createRolePermission', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@createRolePermission']);
        //  获取操作权限信息
        Route::get('/getPermissionInfo/{modelName?}/{roleID?}', ['middleware' => 'permission:add.role', 'uses' => 'RoleController@getPermissionInfo']);

        /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 操作权限 ----------------------------
        ||--------------------------------------------------------------------------------------
         */
        //  操作权限列表
        Route::get('/permissionList', ['middleware' => 'level:1', 'uses' => 'PermissionController@permissionList']);
        //  添加操作权限
        Route::get('/addPermission', ['middleware' => 'level:1', 'uses' => 'PermissionController@addPermission']);
        //  创建操作权限
        Route::post('/createPermission', ['middleware' => 'level:1', 'uses' => 'PermissionController@createPermission']);
        //  删除操作权限
        Route::get('/deletePermission/{permissionID}', ['middleware' => 'level:1', 'uses' => 'PermissionController@deletePermission']);
        //  编辑操作权限
        Route::get('/editPermission/{permissionID}', ['middleware' => 'level:1', 'uses' => 'PermissionController@editPermission']);
        //  修改操作权限
        Route::post('/updatePermission', ['middleware' => 'level:1', 'uses' => 'PermissionController@updatePermission']);
    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 后台用户模块 ------------------------------
    ||--------------------------------------------------------------------------------------
     */

    Route::group(['prefix' => '/users', 'namespace' => 'users'], function () {

        //用户列表 教师 - 在校学生 - 离校学生
        Route::get('/teacherList', ['middleware' => 'permission:user.list', 'uses' => 'indexController@teacherList']);
        Route::get('/inStudentList', ['middleware' => 'permission:user.list', 'uses' => 'indexController@inStudentList']);
        Route::get('/outStudentList', ['middleware' => 'permission:user.list', 'uses' => 'indexController@outStudentList']);
        //查看详情
        Route::get('/show/{stat}/{id}', ['middleware' => 'permission:user.list', 'uses' => 'indexController@show']);
        //添加用户
        Route::get('/addUser', ['middleware' => 'permission:add.user', 'uses' => 'indexController@create']);
        //添加用户
        Route::post('/insert', ['middleware' => 'permission:add.user', 'uses' => 'indexController@insert']);
        //编辑用户
        Route::get('/editUser/{stat}/{id}', ['middleware' => 'permission:edit.user', 'uses' => 'indexController@edit']);
        //修改用户
        Route::post('/update/{stat}/{id}', ['middleware' => 'permission:edit.user', 'uses' => 'indexController@update']);
        //删除用户
        Route::get('/delUser/{id}', ['middleware' => 'permission:delete.user', 'uses' => 'indexController@delete']);
        //重置密码
        Route::any('/resetPass/{stat}/{id}', ['middleware' => 'permission:resetPass.user', 'uses' => 'indexController@resetPass']);
        //更改用户状态
        Route::get('/changeStatus/{id}/{status}', ['middleware' => 'permission:changeStatus.user', 'uses' => 'indexController@changeStatus']);
        //学生一键升级
        Route::get('/oneClickUpgrades', ['middleware' => 'permission:edit.user', 'uses' => 'indexController@oneClickUpgrades']);

        //展示管理员信息
        Route::get('/personDetail/{id}', ['uses' => 'indexController@personDetail']);
        //修改管理员信息
        Route::post('/updatePersonDetail/{id}', ['uses' => 'indexController@updatePersonDetail']);



        //检查唯一性接口
        Route::post('unique/{table}/{column}', 'indexController@unique');
        //获取授课班级
        Route::post('getClass', 'indexController@getClass');
        //获取授课详细
        Route::post('getSubject', 'indexController@getSubject');
        //修改授课
        Route::get('editTeach/{stat}/{id}', 'indexController@editTeach');
        Route::post('updateTeach/{id}', 'indexController@updateTeach');
        //删除授课
        Route::get('deleteTeach/{id}', 'indexController@deleteTeach');
    });

    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 导入导出模块 ------------------------------
    ||--------------------------------------------------------------------------------------
     */

    Route::group(['prefix' => '/excel', 'namespace' => 'excels'], function () {

        //导入用户信息
        Route::post('userInfoImport/{stat}', 'ExcelController@userInfoImport');
        //导出用户信息
        Route::post('userInfoExport', 'ExcelController@userInfoExport');
        //下载用户导入模板
        Route::get('userInfoTemplate/{type}', 'ExcelController@userInfoTemplate');

        //导出订单
        Route::post('orderExport', 'ExcelController@orderExport');

        //导出播放统计
        Route::post('specialCountExport', 'ExcelController@specialCountExport');

        //导出用户统计
        Route::post('userCountExport', 'ExcelController@userCountExport');

		//导出资源历史统计
		Route::post('resourcehistCountExport','ExcelController@resourcehistCountExport');

		//导出课程历史统计
		Route::post('coursehistCountExport','ExcelController@coursehistCountExport');

		//导出教师资源发布量排名统计
		Route::post('tresourceRankExport','ExcelController@tresourceRankExport');

		//导出教师课程发布量排名统计
		Route::post('tcourseRankExport','ExcelController@tcourseRankExport');


        // ===================== 导出近7日课程播放统计 =======================
        Route::post('courseCountExport', ['middleware' => 'permission:export.count', 'uses' => 'ExcelController@courseCountExport']);
        // ===================== 导出近30日课程播放统计 =======================
        Route::post('monthCountExport', ['middleware' => 'permission:export.count', 'uses' => 'ExcelController@monthCountExport']);
        // ===================== 导出近30日提问问题分类统计 =======================
        Route::post('questionCountExport', ['middleware' => 'permission:export.count', 'uses' => 'ExcelController@questionCountExport']);

    });


    /*
||--------------------------------------------------------------------------------------
||     -------------------------- 专题课程模块 ------------------------------
||--------------------------------------------------------------------------------------
 */
    Route::group(['prefix' => '/specialCourse', 'namespace' => 'specialCourse'], function () {
        //专题课程列表
        Route::get('specialCourseList', ['middleware' => 'permission:check.course', 'uses' => 'SpecialCourseController@specialCourseList']);
        //添加专题课程
        Route::get('addSpecialCourse', ['middleware' => 'permission:add.course', 'uses' => 'SpecialCourseController@addSpecialCourse']);
        Route::post('doAddSpecialCourse', ['middleware' => 'permission:add.course', 'uses' => 'SpecialCourseController@doAddSpecialCourse']);
        //编辑专题课程
        Route::get('editSpecialCourse/{id}', ['middleware' => 'permission:edit.course', 'uses' => 'SpecialCourseController@editSpecialCourse']);
        Route::post('doEditSpecialCourse', ['middleware' => 'permission:edit.course', 'uses' => 'SpecialCourseController@doEditSpecialCourse']);
        //删除专题课程
        Route::get('delSpecialCourse/{id}', ['middleware' => 'permission:del.course', 'uses' => 'SpecialCourseController@delSpecialCourse']);
        //多删除
        Route::post('/delMultiSpecialCourse', ['middleware' => 'permission:del.course', 'uses' => 'SpecialCourseController@delMultiSpecialCourse']);
        //专题课程状态
        Route::get('specialCourseState', ['middleware' => 'permission:edit.course', 'uses' => 'SpecialCourseController@specialCourseState']);
        //专题课程详情
        Route::get('detailSpecialCourse/{id}', ['middleware' => 'permission:del.course', 'uses' => 'SpecialCourseController@detailSpecialCourse']);
        //是否需要审核
        Route::get('isCheck/{status}',['uses' => 'SpecialCourseController@isCheck']);


        //专题章节列表
        Route::get('specialChapterList/{id}', ['middleware' => 'permission:check.course', 'uses' => 'SpecialChapterController@specialChapterList']);
        //课程章节状态
        Route::get('specialChapterState', 'SpecialChapterController@specialChapterState');
        //添加课程章节
        Route::get('addSpecialChapter/{id}', ['middleware' => 'permission:add.course', 'uses' => 'SpecialChapterController@addSpecialChapter']);
        //执行添加
        Route::post('doAddSpecialChapter', ['middleware' => 'permission:add.course', 'uses' => 'SpecialChapterController@doAddSpecialChapter']);
        //编辑章节
        Route::get('editSpecialChapter/{courseid}/{id}', ['middleware' => 'permission:edit.course', 'uses' => 'SpecialChapterController@editSpecialChapter']);
        //执行编辑
        Route::post('doEditSpecialChapter', 'SpecialChapterController@doEditSpecialChapter');
        //删除章节
        Route::get('delSpecialChapter/{courseid}/{id}', ['middleware' => 'permission:del.course', 'uses' => 'SpecialChapterController@delSpecialChapter']);


        //专题类型列表
        Route::get('specialTypeList', 'SpecialTypeController@specialTypeList');
        //添加
        Route::get('addSpecialType', 'SpecialTypeController@addSpecialType');
        //执行添加
        Route::post('doAddSpecialType', 'SpecialTypeController@doAddSpecialType');
        //编辑
        Route::get('editSpecialType/{id}', 'SpecialTypeController@editSpecialType');
        //执行编辑
        Route::post('doEditSpecialType', 'SpecialTypeController@doEditSpecialType');
        //删除
        Route::get('delSpecialType/{id}', 'SpecialTypeController@delSpecialType');


        //课程意见反馈
        Route::get('specialFeedbackList/{type}', 'SpecialFeedbackController@specialFeedbackList');
        //意见反馈状态
        Route::get('specialFeedbackState', 'SpecialFeedbackController@specialFeedbackState');
        //删除
        Route::get('delSpecialFeedback/{id}/{type}', 'SpecialFeedbackController@delSpecialFeedback');


        //课程资料列表
        Route::get('dataList/{id}', ['middleware' => 'permission:check.course', 'uses' => 'SpecialDataController@dataList']);
        //资料状态
        Route::get('courseDataState', 'SpecialDataController@courseDataState');
        //添加课程资料
        Route::get('addData/{id}', ['middleware' => 'permission:add.course', 'uses' => 'SpecialDataController@addData']);
        //执行添加
        Route::post('doAddData', ['middleware' => 'permission:add.course', 'uses' => 'SpecialDataController@doAddData']);
        //上传
//        Route::post('doUploadfile','SpecialDataController@doUploadfile');
        //编辑资料
        Route::get('editData/{id}', ['middleware' => 'permission:edit.course', 'uses' => 'SpecialDataController@editData']);
        Route::post('doEditData', 'SpecialDataController@doEditData');
        //删除资料
        Route::get('delData/{courseid}/{id}', ['middleware' => 'permission:del.course', 'uses' => 'SpecialDataController@delData']);

        //专题课程推荐
        Route::get('recommendSpecialCourseList', ['middleware' => 'permission:check.course', 'uses' => 'recommendCourseController@recommendSpecialCourseList']);
        //添加推荐位
        Route::get('addRecommendSpecialCourse/{id}', ['middleware' => 'permission:add.course', 'uses' => 'recommendCourseController@addRecommendSpecialCourse']);
        Route::post('doAddRecommendSpecialCourse', ['middleware' => 'permission:add.course', 'uses' => 'recommendCourseController@doAddRecommendSpecialCourse']);
        //编辑推荐
        Route::get('editRecommendSpecialCourse/{id}', ['middleware' => 'permission:edit.course', 'uses' => 'recommendCourseController@editRecommendSpecialCourse']);
        Route::post('doEditRecommendSpecialCourse', ['middleware' => 'permission:edit.course', 'uses' => 'recommendCourseController@doEditRecommendSpecialCourse']);
        //删除
        Route::get('delRecommendSpecialCourse/{id}', ['middleware' => 'permission:del.course', 'uses' => 'recommendCourseController@delRecommendSpecialCourse']);


        //问答管理
        Route::get('questionList/{id}','questionController@questionList');
        //查看提问详情
        Route::get('detailQuestion/{id}','questionController@detailQuestion');
        //删除问答
        Route::get('delQuestion/{id}','questionController@delQuestion');


        //课程笔记列表
        Route::get('notesList/{id}','notesController@notesList');
        //查看课程
        Route::get('detailNotes/{id}','notesController@detailNotes');
        //删除笔记
        Route::get('delNotes/{id}','notesController@delNotes');
    });


    /*
	||--------------------------------------------------------------------------------------
	||     -------------------------- 点评课程模块 ------------------------------
	||--------------------------------------------------------------------------------------
	 */
    Route::group(['prefix' => '/commentCourse', 'namespace' => 'commentCourse'], function () {
        //演奏视频列表
        Route::get('commentCourseList', ['middleware' => 'permission:check.commentcourse', 'uses' => 'commentCourseController@commentCourseList']);
        //审核状态
        Route::get('commentState', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'commentCourseController@commentState']);
        //课程状态
        Route::get('comcourseState', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'commentCourseController@comcourseState']);
        //添加演奏视频
        Route::get('addCommentCourse', 'commentCourseController@addCommentCourse');
        //编辑演奏视频
        Route::get('editCommentCourse/{id}', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'commentCourseController@editCommentCourse']);
        Route::post('doEditCommentCourse', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'commentCourseController@doEditCommentCourse']);
        //删除演奏视频
        Route::get('delCommentCourse/{id}', ['middleware' => 'permission:del.commentcourse', 'uses' => 'commentCourseController@delCommentCourse']);
        //审核未通过
        Route::post('noPassMsg', 'commentCourseController@noPassMsg');
        //视频详情
        Route::get('detailCommentCourse/{id}', 'commentCourseController@detailCommentCourse');
        //审核通过给名师发送短信提示
        Route::post('sendMessage', 'commentCourseController@sendMessage');


        //名师点评视频
        Route::get('teacherCourseList', ['middleware' => 'permission:check.commentcourse', 'uses' => 'teacherCourseController@teacherCourseList']);
        //名师审核状态
        Route::get('teacherState', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'teacherCourseController@teacherState']);
        //名师课程状态
        Route::get('teaccourseState', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'teacherCourseController@teaccourseState']);
        //名师点评详情
        Route::get('detailTeacherCommentCourse/{id}', 'teacherCourseController@detailTeacherCommentCourse');
        //编辑点评视频
        Route::get('editTeacherCourse/{id}', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'teacherCourseController@editTeacherCourse']);
        Route::post('doEditTeacherCourse', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'teacherCourseController@doEditTeacherCourse']);
        //删除名师点评
        Route::get('delTeacherCourse/{id}', ['middleware' => 'permission:del.commentcourse', 'uses' => 'teacherCourseController@delTeacherCourse']);
        //审核通过给学员发送短信提示
        Route::post('sendStudentMessage', 'teacherCourseController@sendStudentMessage');


        //点评视频推荐
        Route::get('recommendCourseList', ['middleware' => 'permission:check.commentcourse', 'uses' => 'recommendController@recommendCourseList']);
        //添加推荐位
        Route::get('addRecommendCourse/{id}', ['middleware' => 'permission:add.commentcourse', 'uses' => 'recommendController@addRecommendCourse']);
        Route::post('doAddRecommendCourse', ['middleware' => 'permission:add.commentcourse', 'uses' => 'recommendController@doAddRecommendCourse']);
        //编辑推荐
        Route::get('editRecommendCourse/{id}', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'recommendController@editRecommendCourse']);
        Route::post('doEditRecommendCourse', ['middleware' => 'permission:edit.commentcourse', 'uses' => 'recommendController@doEditRecommendCourse']);
        //删除
        Route::get('delRecommendCourse/{id}', ['middleware' => 'permission:del.commentcourse', 'uses' => 'recommendController@delRecommendCourse']);
    });


    /*
        ||--------------------------------------------------------------------------------------
        ||     -------------------------- 订单管理模块 ------------------------------
        ||--------------------------------------------------------------------------------------
         */
    Route::group(['prefix' => '/order', 'namespace' => 'order'], function () {
        //订单列表
        Route::get('orderList/{status}', ['middleware' => 'permission:check.order', 'uses' => 'orderController@orderList']);
        //订单状态
        Route::get('orderState', ['middleware' => 'permission:edit.order', 'uses' => 'orderController@orderState']);
        //删除订单
        Route::get('delOrder/{id}/{status}', ['middleware' => 'permission:del.order', 'uses' => 'orderController@delOrder']);
        //添加订单备注
        Route::post('remark', ['middleware' => 'permission:check.order', 'uses' => 'orderController@remark']);
        //修改应退金额
        Route::get('editRefundmoney/{id}/{status}', ['middleware' => 'permission:edit.Refundmoney', 'uses' => 'orderController@editRefundmoney']);
        //执行确认应退金额
        Route::post('doRefundmoney', ['middleware' => 'permission:edit.Refundmoney', 'uses' => 'orderController@doRefundmoney']);
        //修改已退金额
        Route::get('editRetiredmoney/{id}/{status}', ['middleware' => 'permission:edit.Retiredmoney', 'uses' => 'orderController@editRetiredmoney']);
        //执行确认已退金额
        Route::post('doRetiredmoney', ['middleware' => 'permission:edit.Retiredmoney', 'uses' => 'orderController@doRetiredmoney']);
        //备注列表
        Route::get('remarkList/{id}/{status?}', ['middleware' => 'permission:check.order', 'uses' => 'orderController@remarkList']);
        //删除备注
        Route::get('delRemark/{orderid}/{id}/{status?}', 'orderController@delRemark');
        //清除垃圾订单
        Route::get('deleteOrders', ['middleware' => 'permission:del.order', 'uses' => 'orderController@deleteOrders']);
        //修改实付金额
        Route::get('editPaymoney/{id}/{status}', ['middleware' => 'permission:edit.Paymoney', 'uses' => 'orderController@editPaymoney']);
        //执行修改实付金额
        Route::post('doEditPaymoney', ['middleware' => 'permission:edit.Paymoney', 'uses' => 'orderController@doEditPaymoney']);


        //退款列表
        Route::get('refundList/{orderSn}/{status?}', 'orderController@refundList');
        //退款状态
        Route::get('refundState', 'orderController@refundState');
        //删除退款
        Route::get('delRefund/{orderSn}/{id}', 'orderController@delRefund');

        //确认退款微信退款
        Route::get('weiXinRefund/{orderId}', 'orderController@weiXinRefund');
        //支付宝确认退款
        Route::any('alipayRefund/{orderId}', 'orderController@alipayRefund');

        //批量删除已退订单
        Route::post('deletes', 'orderController@deletes');
    });


    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 评论回复管理 ------------------------------
   ||--------------------------------------------------------------------------------------
   */
    Route::group(['prefix' => '/commentReply', 'namespace' => 'commentReply'], function () {

        //演奏点评视频评论列表
        Route::get('applyCommentList', ['middleware' => 'permission:commentReply.list', 'uses' => 'applyCommentController@applyCommentList']);
        //修改
        Route::get('editapplyComment/{id}', ['middleware' => 'permission:edit.commentReply', 'uses' => 'applyCommentController@editapplyComment']);
        //修改方法
        Route::post('editsapplyComment', ['middleware' => 'permission:edit.commentReply', 'uses' => 'applyCommentController@editsapplyComment']);
        //删除
        Route::get('delapplyComment/{id}', ['middleware' => 'permission:delete.commentReply', 'uses' => 'applyCommentController@delapplyComment']);
        //课程状态
        Route::get('applyCommentStatus', ['middleware' => 'permission:edit.commentReply', 'uses' => 'applyCommentController@applyCommentStatus']);
        //审核状态
        Route::get('applyCommentChecks', ['middleware' => 'permission:edit.commentReply', 'uses' => 'applyCommentController@applyCommentChecks']);
        //查看详情
        Route::get('lookapplyComment/{id}', ['middleware' => 'permission:edit.commentReply', 'uses' => 'applyCommentController@lookapplyComment']);


        //问答评论列表
        Route::get('questionCommentList', ['uses' => 'questionCommentController@questionCommentList']);
        //修改
        Route::get('editquestionComment/{id}', ['uses' => 'questionCommentController@editquestionComment']);
        //修改方法
        Route::post('editsquestionComment', ['uses' => 'questionCommentController@editsquestionComment']);
        //删除
        Route::get('delquestionComment/{id}', ['uses' => 'questionCommentController@delquestionComment']);
        //课程状态
        Route::get('questionCommentStatus', ['uses' => 'questionCommentController@questionCommentStatus']);
        //审核状态
        Route::get('questionCommentChecks', ['uses' => 'questionCommentController@questionCommentChecks']);
        //查看详情
        Route::get('lookquestionComment/{id}', ['uses' => 'questionCommentController@lookquestionComment']);


        //课程评论列表
        Route::get('courseCommentList', ['middleware' => 'permission:commentReply.list', 'uses' => 'courseCommentController@courseCommentList']);
        //修改
        Route::get('editcourseComment/{id}', ['middleware' => 'permission:edit.commentReply', 'uses' => 'courseCommentController@editcourseComment']);
        //修改方法
        Route::post('editscourseComment', ['middleware' => 'permission:edit.commentReply', 'uses' => 'courseCommentController@editscourseComment']);
        //删除
        Route::get('delcourseComment/{id}', ['middleware' => 'permission:delete.commentReply', 'uses' => 'courseCommentController@delcourseComment']);
        //课程状态
        Route::get('courseCommentStatus', ['middleware' => 'permission:edit.commentReply', 'uses' => 'courseCommentController@courseCommentStatus']);
        //审核状态
        Route::get('courseCommentChecks', ['middleware' => 'permission:edit.commentReply', 'uses' => 'courseCommentController@courseCommentChecks']);
        //查看详情
        Route::get('lookcourseComment/{id}', ['middleware' => 'permission:edit.commentReply', 'uses' => 'courseCommentController@lookcourseComment']);

    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 用户收藏管理 ------------------------------
    ||--------------------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/collection', 'namespace' => 'collection'], function () {

        //创客课程收藏列表
        Route::get('collectionList', ['middleware' => 'permission:collection.list', 'uses' => 'collectionController@collectionList']);
        //删除
        Route::get('delcollection/{id}', ['middleware' => 'permission:delete.collection', 'uses' => 'collectionController@delcollection']);


        //问答收藏列表
        Route::get('questionfavList', ['middleware' => 'permission:collection.list', 'uses' => 'questionfavController@questionfavList']);
        //删除
        Route::get('delquestionfav/{id}', ['middleware' => 'permission:delete.collection', 'uses' => 'questionfavController@delquestionfav']);

    });

    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 内容管理 ------------------------------
    ||--------------------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/contentManager', 'namespace' => 'contentManager'], function () {

        //banner列表
        Route::get('bannerList', ['middleware' => 'permission:contentManager.list', 'uses' => 'bannerController@bannerList']);
        //修改
        Route::get('editbanner/{id}', ['middleware' => 'permission:edit.contentManager', 'uses' => 'bannerController@editbanner']);
        //修改方法
        Route::post('editsbanner', ['middleware' => 'permission:edit.contentManager', 'uses' => 'bannerController@editsbanner']);
        //添加
        Route::get('addbanner', ['middleware' => 'permission:add.contentManager', 'uses' => 'bannerController@addbanner']);
        //添加方法
        Route::post('addsbanner', ['middleware' => 'permission:add.contentManager', 'uses' => 'bannerController@addsbanner']);
        //删除
        Route::get('delbanner/{id}', ['middleware' => 'permission:delete.contentManager', 'uses' => 'bannerController@delbanner']);
        //激活锁定
        Route::get('bannerStatus', ['middleware' => 'permission:edit.contentManager', 'uses' => 'bannerController@bannerStatus']);


        //合作伙伴列表
        Route::get('partnerList', ['middleware' => 'permission:contentManager.list', 'uses' => 'partnerController@partnerList']);
        //修改
        Route::get('editpartner/{id}', ['middleware' => 'permission:edit.contentManager', 'uses' => 'partnerController@editpartner']);
        //修改方法
        Route::post('editspartner', ['middleware' => 'permission:edit.contentManager', 'uses' => 'partnerController@editspartner']);
        //添加
        Route::get('addpartner', ['middleware' => 'permission:add.contentManager', 'uses' => 'partnerController@addpartner']);
        //添加方法
        Route::post('addspartner', ['middleware' => 'permission:add.contentManager', 'uses' => 'partnerController@addspartner']);
        //删除
        Route::get('delpartner/{id}', ['middleware' => 'permission:delete.contentManager', 'uses' => 'partnerController@delpartner']);
        //激活锁定
        Route::get('partnerStatus', ['middleware' => 'permission:edit.contentManager', 'uses' => 'partnerController@partnerStatus']);


        //热门视频列表
        Route::get('hotvideoList', ['middleware' => 'permission:contentManager.list', 'uses' => 'hotvideoController@hotvideoList']);
        //修改
        Route::get('edithotvideo/{id}', ['middleware' => 'permission:edit.contentManager', 'uses' => 'hotvideoController@edithotvideo']);
        //修改方法
        Route::post('editshotvideo', ['middleware' => 'permission:edit.contentManager', 'uses' => 'hotvideoController@editshotvideo']);
        //添加
        Route::get('addhotvideo', ['middleware' => 'permission:add.contentManager', 'uses' => 'hotvideoController@addhotvideo']);
        //添加方法
        Route::post('addshotvideo', ['middleware' => 'permission:add.contentManager', 'uses' => 'hotvideoController@addshotvideo']);
        //删除
        Route::get('delhotvideo/{id}', ['middleware' => 'permission:delete.contentManager', 'uses' => 'hotvideoController@delhotvideo']);
        //激活锁定
        Route::get('hotvideoStatus', ['middleware' => 'permission:edit.contentManager', 'uses' => 'hotvideoController@hotvideoStatus']);
        //上传资源
        Route::post('dohotvideo', 'hotvideoController@dohotvideo');


        //社区名师推荐
        Route::get('recteacherList', ['middleware' => 'permission:contentManager.list', 'uses' => 'recteacherController@recteacherList']);
        //修改
        Route::get('editrecteacher/{id}', ['middleware' => 'permission:edit.contentManager', 'uses' => 'recteacherController@editrecteacher']);
        //修改方法
        Route::post('editsrecteacher', ['middleware' => 'permission:edit.contentManager', 'uses' => 'recteacherController@editsrecteacher']);
        //添加
        Route::get('addrecteacher', ['middleware' => 'permission:add.contentManager', 'uses' => 'recteacherController@addrecteacher']);
        //添加方法
        Route::post('addsrecteacher', ['middleware' => 'permission:add.contentManager', 'uses' => 'recteacherController@addsrecteacher']);
        //删除
        Route::get('deleterecteacher/{id}', ['middleware' => 'permission:delete.contentManager', 'uses' => 'recteacherController@deleterecteacher']);


        //新闻资讯列表
        Route::get('newsList', ['middleware' => 'permission:contentManager.list', 'uses' => 'newsController@newsList']);
        //修改
        Route::get('editnews/{id}', ['middleware' => 'permission:edit.contentManager', 'uses' => 'newsController@editnews']);
        //修改方法
        Route::post('editsnews', ['middleware' => 'permission:edit.contentManager', 'uses' => 'newsController@editsnews']);
        //添加
        Route::get('addnews', ['middleware' => 'permission:add.contentManager', 'uses' => 'newsController@addnews']);
        //添加方法
        Route::post('addsnews', ['middleware' => 'permission:add.contentManager', 'uses' => 'newsController@addsnews']);
        //删除
        Route::get('delnews/{id}', ['middleware' => 'permission:delete.contentManager', 'uses' => 'newsController@delnews']);
        //锁定激活
        Route::get('newsStatus', ['middleware' => 'permission:edit.contentManager', 'uses' => 'newsController@newsStatus']);

    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 活动赛事 ------------------------------
    ||--------------------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/activity', 'namespace' => 'activity'], function () {
        //活动赛事列表
        Route::get('activityList', ['middleware' => 'permission:activity.list', 'uses' => 'activityController@activityList']);
        //添加
        Route::get('addactivity', ['middleware' => 'permission:add.activity', 'uses' => 'activityController@addactivity']);
        //添加方法
        Route::post('addsactivity', ['middleware' => 'permission:add.activity', 'uses' => 'activityController@addsactivity']);
        //编辑
        Route::get('editactivity/{id}', ['middleware' => 'permission:edit.activity', 'uses' => 'activityController@editactivity']);
        //编辑方法
        Route::post('editsactivity', ['middleware' => 'permission:edit.activity', 'uses' => 'activityController@editsactivity']);
        //删除
        Route::get('delactivity/{id}', ['middleware' => 'permission:delete.activity', 'uses' => 'activityController@delactivity']);
        //活动状态
        Route::get('activityStatus', ['middleware' => 'permission:edit.activity', 'uses' => 'activityController@activityStatus']);

    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 关于我们 ------------------------------
    ||--------------------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/aboutUs', 'namespace' => 'aboutUs'], function () {
        //公司介绍列表
        Route::get('firmIntroList', ['middleware' => 'permission:aboutus.list', 'uses' => 'firmIntroController@firmIntroList']);
        //编辑页面
        Route::get('editfirmIntro/{id}', ['middleware' => 'permission:edit.aboutus', 'uses' => 'firmIntroController@editfirmIntro']);
        //编辑方法
        Route::post('editsfirmIntro', ['middleware' => 'permission:edit.aboutus', 'uses' => 'firmIntroController@editsfirmIntro']);

        //友情链接列表
        Route::get('friendlinkList', ['middleware' => 'permission:aboutus.list', 'uses' => 'friendlinkController@friendlinkList']);
        //编辑页面
        Route::get('editfriendlink/{id}', ['middleware' => 'permission:edit.aboutus', 'uses' => 'friendlinkController@editfriendlink']);
        //编辑方法
        Route::post('editsfriendlink', ['middleware' => 'permission:edit.aboutus', 'uses' => 'friendlinkController@editsfriendlink']);
        //删除
        Route::get('delfriendlink/{id}', ['middleware' => 'permission:delete.aboutus', 'uses' => 'friendlinkController@delfriendlink']);
        //添加页面
        Route::get('addfriendlink', ['middleware' => 'permission:add.aboutus', 'uses' => 'friendlinkController@addfriendlink']);
        //添加方法
        Route::post('addsfriendlink', ['middleware' => 'permission:add.aboutus', 'uses' => 'friendlinkController@addsfriendlink']);
        //状态
        Route::get('frinendStatus', ['middleware' => 'permission:edit.aboutus', 'uses' => 'friendlinkController@frinendStatus']);

    });


    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 公司后台用户管理 ------------------------------
   ||--------------------------------------------------------------------------------------
   */
    Route::group(['prefix' => '/companyUser', 'namespace' => 'companyUser'], function () {

        //列表
        Route::get('companyUserList', ['middleware' => 'permission:companyUser.list', 'uses' => 'companyUserController@companyUserList']);
        //添加
        Route::get('addcompanyUser', ['middleware' => 'permission:add.companyUser', 'uses' => 'companyUserController@addcompanyUser']);
        //添加方法
        Route::post('addscompanyUser', ['middleware' => 'permission:add.companyUser', 'uses' => 'companyUserController@addscompanyUser']);
        //编辑
        Route::get('editcompanyUser/{id}', ['middleware' => 'permission:edit.companyUser', 'uses' => 'companyUserController@editcompanyUser']);
        //编辑方法
        Route::post('editscompanyUser', ['middleware' => 'permission:edit.companyUser', 'uses' => 'companyUserController@editscompanyUser']);
        //删除
        Route::get('delcompanyUser/{id}', ['middleware' => 'permission:delete.companyUser', 'uses' => 'companyUserController@delcompanyUser']);
        //状态
        Route::get('companyStatus', ['middleware' => 'permission:edit.companyUser', 'uses' => 'companyUserController@companyStatus']);
        //重置密码页面
        Route::get('resetPassword/{id}', ['middleware' => 'permission:edit.companyUser', 'uses' => 'companyUserController@resetPassword']);
        //密码提交修改
        Route::post('resetsPassword', ['middleware' => 'permission:edit.companyUser', 'uses' => 'companyUserController@resetsPassword']);
        //ajax部门取岗位接口
        Route::get('departPost', 'companyUserController@departPost');

        //系统设置
        Route::get('systemList',['middleware' => 'permission:companyUser.list', 'uses' => 'systemController@systemList']);
        //修改是否状态
        Route::get('status/{id}/{isTrue}',['middleware' => 'permission:edit.companyUser', 'uses' => 'systemController@status']);


    });




    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 问答管理 ------------------------------
   ||--------------------------------------------------------------------------------------
   */
    Route::group(['prefix'=>'/question','namespace'=>'question'],function(){
        //列表
        Route::get('questionList',['uses' => 'questionController@questionList']);
        //编辑
        Route::get('editquestion/{id}',['uses' => 'questionController@editquestion']);
        //编辑方法
        Route::post('editsquestion',['uses' => 'questionController@editsquestion']);
        //删除
        Route::get('delquestion/{id}',['uses' => 'questionController@delquestion']);
        //查看评论
        Route::get('viewquestion/{id}',['uses' => 'questionController@viewquestion']);
        //回复评判
        Route::get('replyquestion/{id}',['uses' => 'questionController@replyquestion']);

        //查看提问下的所有评论
        Route::get('replyList/{id}','questionController@replyList');
        //查看详情
        Route::get('deltailReply/{id}','questionController@deltailReply');
        //删除评论
        Route::get('delReply/{id}','questionController@delReply');

    });


    /*
  ||--------------------------------------------------------------------------------------
  ||     -------------------------- 科目管理 ------------------------------
  ||--------------------------------------------------------------------------------------
  */
    Route::group(['prefix' => '/subject', 'namespace' => 'subject'], function () {
        //列表
        Route::get('subjectList', ['uses' => 'subjectController@subjectList']);
        //添加
        Route::get('addsubject', ['uses' => 'subjectController@addsubject']);
        //添加方法
        Route::post('addssubject', ['uses' => 'subjectController@addssubject']);
        //编辑
        Route::get('editsubject/{id}', ['uses' => 'subjectController@editsubject']);
        //编辑方法
        Route::post('editssubject', ['uses' => 'subjectController@editssubject']);
        //删除
        Route::get('delsubject/{id}', ['uses' => 'subjectController@delsubject']);
        //验证唯一性
        Route::get('name_unique', ['uses' => 'subjectController@name_unique']);

    });


    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 意见反馈管理 ------------------------------
   ||--------------------------------------------------------------------------------------
   */
    Route::group(['prefix' => '/complaint', 'namespace' => 'complaint'], function () {
        //列表
        Route::get('complaintList', ['uses' => 'complaintController@complaintList']);
        //编辑
        Route::get('editcomplaint/{id}', ['uses' => 'complaintController@editcomplaint']);
        //编辑方法
        Route::post('editscomplaint', ['uses' => 'complaintController@editscomplaint']);
        //删除
        Route::get('delcomplaint/{id}', ['uses' => 'complaintController@delcomplaint']);
        //状态
        Route::get('complaintStatus', ['uses' => 'complaintController@complaintStatus']);

    });


    /*
  ||--------------------------------------------------------------------------------------
  ||     -------------------------- 部门岗位管理 ------------------------------
  ||--------------------------------------------------------------------------------------
  */
    Route::group(['prefix' => '/departmentPost', 'namespace' => 'departmentPost'], function () {

        Route::get('departmentList', ['middleware' => 'permission:departmentpost.list', 'uses' => 'departmentController@departmentList']);
        //添加
        Route::get('adddepartment', ['middleware' => 'permission:add.departmentpost', 'uses' => 'departmentController@adddepartment']);
        //添加方法
        Route::post('addsdepartment', ['middleware' => 'permission:add.departmentpost', 'uses' => 'departmentController@addsdepartment']);
        //编辑
        Route::get('editdepartment/{id}', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'departmentController@editdepartment']);
        //编辑方法
        Route::post('editsdepartment', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'departmentController@editsdepartment']);
        //删除
        Route::get('deldepartment/{id}', ['middleware' => 'permission:delete.departmentpost', 'uses' => 'departmentController@deldepartment']);
        //状态
        Route::get('departmentStatus', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'departmentController@departmentStatus']);


        //岗位列表
        Route::get('postList', ['middleware' => 'permission:departmentpost.list', 'uses' => 'postController@postList']);
        //添加
        Route::get('addpost', ['middleware' => 'permission:add.departmentpost', 'uses' => 'postController@addpost']);
        //添加方法
        Route::post('addspost', ['middleware' => 'permission:add.departmentpost', 'uses' => 'postController@addspost']);
        //编辑
        Route::get('editpost/{id}', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'postController@editpost']);
        //编辑方法
        Route::post('editspost', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'postController@editspost']);
        //删除
        Route::get('delpost/{id}', ['middleware' => 'permission:delete.departmentpost', 'uses' => 'postController@delpost']);
        //状态
        Route::get('postStatus', ['middleware' => 'permission:edit.departmentpost', 'uses' => 'postController@postStatus']);

    });


    /*
   ||--------------------------------------------------------------------------------------
   ||     -------------------------- 后台回收站模块 ------------------------------
   ||--------------------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/recycle', 'middleware' => 'permission:check.recycle' ,'namespace' => 'recycle'], function () {
        //专题课程列表
        Route::get('recycleCourseList', 'RecycleCourseController@recycleCourseList');
        //还原
        Route::get('editRecycleCourse/{id}', 'RecycleCourseController@editRecycleCourse');
        //彻底删除专题课程
        Route::get('delRecycleCourse/{id}', 'RecycleCourseController@delRecycleCourse');


        //问答课程列表
        Route::get('recycleQuestionList', 'RecycleCourseController@recycleQuestionList');
        //还原
        Route::get('editRecycleQuestion/{id}', 'RecycleCourseController@editRecycleQuestion');
        //彻底删除专题课程
        Route::get('delRecycleQuestion/{id}', 'RecycleCourseController@delRecycleQuestion');


        //演奏视频列表
        Route::get('recycleCommentCourseList', 'RecycleCourseController@recycleCommentCourseList');
        //还原
        Route::get('editRecycleCommentCourse/{id}', 'RecycleCourseController@editRecycleCommentCourse');
        //彻底删除
        Route::get('delRecycleCommentCourse/{id}', 'RecycleCourseController@delRecycleCommentCourse');


        //点评视频列表
        Route::get('recycleTeacherCourseList', 'RecycleCourseController@recycleTeacherCourseList');
        //还原
        Route::get('editRecycleTeacherCourse/{id}', 'RecycleCourseController@editRecycleTeacherCourse');
        //彻底删除
        Route::get('delRecycleTeacherCourse/{id}', 'RecycleCourseController@delRecycleTeacherCourse');


        //订单列表
        Route::get('recycleOrderList', 'RecycleCourseController@recycleOrderList');
        //还原
        Route::get('editRecycleOrder/{id}', 'RecycleCourseController@editRecycleOrder');
        //彻底删除
        Route::get('delRecycleOrder/{id}', 'RecycleCourseController@delRecycleOrder');


        //资源列表
        Route::get('recycleResourceList','RecycleCourseController@recycleResourceList');
        //还原
        Route::get('editRecycleResource/{id}','RecycleCourseController@editRecycleResource');
        //彻底删除
        Route::get('delRecycleResource/{id}','RecycleCourseController@delRecycleResource');

        //清空回收站
        Route::get('deleteRecycle', 'RecycleCourseController@deleteRecycle');
    });


    /*
    ||--------------------------------------------------------------------------------------
    ||     -------------------------- 后台通知管理 ------------------------------
    ||--------------------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/notice', 'namespace' => 'notice'], function () {
        // 通知列表
        Route::get('noticeList', ['middleware' => 'permission:list.notice', 'uses' => 'noticeController@noticeList']);
        // 发送通知页
        Route::get('addNotice', ['middleware' => 'permission:add.notice', 'uses' => 'noticeController@addNotice']);
        // 执行添加通知
        Route::post('doAddNotice', ['middleware' => 'permission:add.notice', 'uses' => 'noticeController@doAddNotice']);
        // 删除通知信息
        Route::get('delNotice/{id}', ['middleware' => 'permission:del.notice', 'uses' => 'noticeController@delNotice']);
        // 修改通知信息页
        Route::get('editNotice/{id}', ['middleware' => 'permission:edit.notice', 'uses' => 'noticeController@editNotice']);
        // 执行修改通知信息
        Route::post('doEditNotice', ['middleware' => 'permission:edit.notice', 'uses' => 'noticeController@doEditNotice']);

        // 通知模板列表
        Route::get('noticeTemList', ['middleware' => 'permission:list.noticeTem', 'uses' => 'noticeController@noticeTemList']);
        // 添加通知模板
        Route::get('addNoticeTem', ['middleware' => 'permission:add.noticeTem', 'uses' => 'noticeController@addNoticeTem']);
        // 执行添加通知模板
        Route::post('doAddNoticeTem', ['middleware' => 'permission:add.noticeTem', 'uses' => 'noticeController@doAddNoticeTem']);
        // 删除通知模板
        Route::get('delNoticeTem/{id}', ['middleware' => 'permission:del.noticeTem', 'uses' => 'noticeController@delNoticeTem']);
        // 通知模板修改页
        Route::get('editNoticeTem/{id}', ['middleware' => 'permission:edit.noticeTem', 'uses' => 'noticeController@editNoticeTem']);
        // 执行通知模板修改
        Route::post('doEditNoticeTem', ['middleware' => 'permission:edit.noticeTem', 'uses' => 'noticeController@doEditNoticeTem']);
    });

    /*
       ||--------------------------------------------------------------------------------------
       ||     -------------------------- 后台日志管理 ------------------------------
       ||--------------------------------------------------------------------------------------
       */

    Route::group(['prefix' => '/logs', 'namespace' => 'logs'], function () {

        //后台日志管理列表
        Route::get('logList', ['middleware' => 'permission:logs.list', 'uses' => 'indexController@logList']);
        //日志删除(单删除)
        Route::get('deleteLog/{tableName}/{id}/{time?}', ['middleware' => 'permission:delete.log', 'uses' => 'indexController@destroy']);
        //日志删除(多删除)
        Route::post('multiDelete/{tableName}/{time?}', ['middleware' => 'permission:delete.log', 'uses' => 'indexController@delete']);

    });


    /*
       ||--------------------------------------------------------------------------------------
       ||     -------------------------- 后台数据统计 ------------------------------
       ||--------------------------------------------------------------------------------------
       */

    Route::group(['prefix' => '/count', 'namespace' => 'count'], function () {

        // 课程播放数统计（近7日播放前十）
        Route::get('courseCountList', 'courseCountController@courseCountList');
        // 课程播放数统计（近30日播放前十）
        Route::get('monthCountList', 'courseCountController@monthCountList');
        // 近30日提问前十的问题分类列表（所属科目）
        Route::get('questionCountList', 'courseCountController@questionCountList');
        // 课程统计数计算
        Route::get('courseCount/{time?}', 'courseCountController@courseCount');


        //注册用户数展示列表
        Route::get('userCountList', 'userCountController@userCountList');
        //用户统计数计算
        Route::get('userCount/{time?}/{orders?}', 'userCountController@userCount');
        //订单数
        Route::get('orderCountList', 'userCountController@orderCountList');
        //专题课程播放统计
        Route::get('specialCountList', 'countController@specialCountList');
    });

    /*
       ||--------------------------------------------------------------------------------------
       ||     -------------------------- 后台登录视频推荐 ------------------------------
       ||--------------------------------------------------------------------------------------
       */

    Route::group(['prefix' => '/loginVideo', 'namespace' => 'loginVideo'], function () {
        //登录页面视频推荐列表
        Route::get('loginVideoList', 'loginVideoController@loginVideoList');
        //添加
        Route::get('addLoginVideo', 'loginVideoController@addLoginVideo');
        Route::post('doAddLoginVideo', 'loginVideoController@doAddLoginVideo');
        //上传
//        Route::post('doUploadfile','loginVideoController@doUploadfile');
        //修改
        Route::get('editLoginVideo/{id}', 'loginVideoController@editLoginVideo');
        Route::post('doEditLoginVideo', 'loginVideoController@doEditLoginVideo');
        //删除
        Route::get('delLoginVideo/{id}', 'loginVideoController@delLoginVideo');
    });


    /*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台资源管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
    Route::group(['prefix' => 'resource', 'namespace' => 'resource'], function () {
        //资源列表
        Route::get('resourceList', ['middleware' => 'permission:check.resource', 'uses' => 'resourceController@resourceList']);
        //更改上下架状态
        Route::get('status/{id}/{statusId}', ['middleware' => 'permission:edit.resource', 'uses' => 'resourceController@status']);
        //编辑资源
        Route::get('editResource/{id}', ['middleware' => 'permission:edit.resource', 'uses' => 'resourceController@editResource']);
        Route::post('doEditResource', ['middleware' => 'permission:edit.resource', 'uses' => 'resourceController@doEditResource']);
        //删除资源
        Route::get('delResource/{id}', ['middleware' => 'permission:del.resource', 'uses' => 'resourceController@delResource']);
        //duo删除资源
        Route::post('delMultiResource', ['middleware' => 'permission:del.resource', 'uses' => 'resourceController@delMultiResource']);
        //获取年级
        Route::get('getGrade', 'resourceController@getGrade');
        //获取科目
        Route::get('getSubject', 'resourceController@getSubject');
        //获取版本
        Route::get('getEdition', 'resourceController@getEdition');
        //获取册别
        Route::get('getBook', 'resourceController@getBook');
        //获取知识点
        Route::get('getChapter/{gradeId?}/{subjectId?}/{editionId?}/{bookId?}', 'resourceController@getChapter');
        //获取资源类型
        Route::get('getResourceType', 'resourceController@getResourceType');


        //获取资源评论
        Route::get('getCommentList/{id}', ['middleware' => 'permission:check.resource', 'uses' => 'commentController@getCommentList']);
        //评论详情
        Route::get('detailComment/{id}', ['middleware' => 'permission:check.resource', 'uses' => 'commentController@detailComment']);
        //删除评论
        Route::get('delComment/{id}', ['middleware' => 'permission:del.resource', 'uses' => 'commentController@delComment']);


        //资源类型列表
        Route::get('resourceTypeList', ['middleware' => 'permission:check.resource', 'uses' => 'resourceTypeController@resourceTypeList']);
        //添加资源类型
        Route::get('addResourceType', ['middleware' => 'permission:check.resource', 'uses' => 'resourceTypeController@addResourceType']);
        Route::post('doAddResourceType', ['middleware' => 'permission:check.resource', 'uses' => 'resourceTypeController@doAddResourceType']);
        //修改资源类型
        Route::get('editResourceType/{id}', ['middleware' => 'permission:edit.resource', 'uses' => 'resourceTypeController@editResourceType']);
        Route::post('doEditResourceType', ['middleware' => 'permission:edit.resource', 'uses' => 'resourceTypeController@doEditResourceType']);
        //删除资源类型
        Route::get('delResourceType/{id}', ['middleware' => 'permission:del.resource', 'uses' => 'resourceTypeController@delResourceType']);

    });



    /*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台基础信息管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
    Route::group(['prefix'=>'baseInfo','namespace'=>'baseInfo'],function(){
        //年级列表
        Route::get('gradeList','gradeController@gradeList');
        //添加
        Route::get('addGrade','gradeController@addGrade');
        Route::post('doAddGrade','gradeController@doAddGrade');
        //编辑
        Route::get('editGrade/{id}','gradeController@editGrade');
        Route::post('doEditGrade','gradeController@doEditGrade');
        //删除
        Route::get('delGrade/{id}','gradeController@delGrade');




        //班级列表
        Route::get('classList','classController@classList');
        //添加
        Route::get('addClass','classController@addClass');
        Route::post('doAddClass','classController@doAddClass');
        //编辑
        Route::get('editClass/{id}','classController@editClass');
        Route::post('doEditClass','classController@doEditClass');
        //删除
        Route::get('delClass/{id}','classController@delClass');



        //科目列表
        Route::get('subjectList','subjectController@subjectList');
        //添加
        Route::get('addSubject','subjectController@addSubject');
        Route::post('doAddSubject','subjectController@doAddSubject');
        //编辑
        Route::get('editSubject/{id}','subjectController@editSubject');
        Route::post('doEditSubject','subjectController@doEditSubject');
        //删除
        Route::get('delSubject/{id}','subjectController@delSubject');


        //版本列表
        Route::get('editionList','editionController@editionList');
        //添加
        Route::get('addEdition','editionController@addEdition');
        Route::post('doAddEdition','editionController@doAddEdition');
        //编辑
        Route::get('editEdition/{id}','editionController@editEdition');
        Route::post('doEditEdition','editionController@doEditEdition');
        //删除
        Route::get('delEdition/{id}','editionController@delEdition');


        //册别列表
        Route::get('bookList','bookController@bookList');
        //添加
        Route::get('addBook','bookController@addBook');
        Route::post('doAddBook','bookController@doAddBook');
        //编辑
        Route::get('editBook/{id}','bookController@editBook');
        Route::post('doEditBook','bookController@doEditBook');
        //删除
        Route::get('delBook/{id}','bookController@delBook');



    });

    /*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台知识点管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
    Route::group(['prefix'=>'chapter','namespace'=>'chapter'],function(){
        //知识点列表
        Route::get('chapterList','chapterController@chapterList');
        //添加
        Route::get('addChapter','chapterController@addChapter');
        Route::post('doAddChapter','chapterController@doAddChapter');
        //编辑
        Route::get('editChapter/{id}','chapterController@editChapter');
        Route::post('doEditChapter','chapterController@doEditChapter');
        //删除
        Route::get('delChapter/{id}','chapterController@delChapter');

        //查看知识点
        Route::get('seeChapter/{id}','chapterController@seeChapter');
        //添加知识点
        Route::get('addSee/{id}','chapterController@addSee');
        Route::post('doAddSee','chapterController@doAddSee');
        //编辑知识点
        Route::get('editSee/{id}','chapterController@editSee');
        Route::post('doEditSee','chapterController@doEditSee');
        //删除
        Route::get('delSee/{id}','chapterController@delSee');




    });

	/*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台数据统计管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
	Route::group(['prefix'=>'datacount','namespace'=>'datacount'],function(){
		//资源历史统计列表
		Route::get('resourcecountList','resourcecountlistController@resourcecountList');//按id降序排序

		//课程历史统计列表
		Route::get('coursecountList','coursecountlistController@coursecountList');//按id降序排序

		//教师资源发布量排名列表
		Route::get('tresourcerankList','tresourceranklistController@tresourcerankList');//按发布量降序排序

		//教师课程发布量排名列表
		Route::get('tcourserankList','tcourseranklistController@tcourseRankList');//按授课量降序排序

	});



    /*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台试卷管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
    Route::group(['prefix'=>'exam','namespace'=>'exam'],function(){
        //试卷列表
        Route::get('examList','examController@examList');
        //修改状态
        Route::get('status/{id}/{status}','examController@status');
        //删除试卷
        Route::get('delExam/{id}','examController@delExam');
    });


    /*
      ||--------------------------------------------------------------------------------------
      ||     -------------------------- 后台敏感词库管理 ------------------------------
      ||--------------------------------------------------------------------------------------
      */
    Route::group(['prefix'=>'sensitive','namespace'=>'sensitive'],function(){
       //敏感词库列表
        Route::get('sensitiveList','sensitiveController@sensitiveList');
        //添加敏感词
        Route::get('addSensitive','sensitiveController@addSensitive');
        Route::post('doAddSensitive','sensitiveController@doAddSensitive');
        //删除
        Route::get('delSensitive/{id}','sensitiveController@delSensitive');
        //批量删除
        Route::post('deletes','sensitiveController@deletes');
        //一键生成缓存
        Route::get('onekey','sensitiveController@onekey');
    });


});












