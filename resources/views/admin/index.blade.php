@extends('layouts.layoutAdmin')
@section('content')
    <div class="main-content">
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home home-icon"></i>
                    <a href="{{url('/admin/index')}}">首页</a>
                </li>
                <li class="active">控制台</li>
            </ul><!-- .breadcrumb -->

            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                        <i class="icon-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- #nav-search -->
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                    控制台
                    <small>
                        <i class="icon-double-angle-right"></i>
                        查看
                    </small>
                </h1>
            </div><!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="icon-remove"></i>
                        </button>

                        <i class="icon-ok green"></i>

                        欢迎使用
                        <strong class="green">
                            启创教育云管理后台
                            <small></small>
                        </strong>
                    </div>

                    <div class="row">
                        <div class="space-6"></div>

                        <div class="col-sm-7 infobox-container">

                            <div class="infobox infobox-green  " style="width: 400px;">
                                <div class="infobox-icon">
                                    <i class="icon-comments"></i>
                                </div>

                                <div class="infobox-data">
                                    <span class="infobox-data-number"><a href="{{url('admin/specialCourse/specialFeedbackList/0')}}">0</a></span>
                                    <div class="infobox-content">有未处理课程反馈</div>
                                </div>
                            </div>
                            <div class="infobox infobox-green  " style="width: 400px;">
                                <div class="infobox-icon">
                                    <i class="icon-comments"></i>
                                </div>

                                <div class="infobox-data">
                                    <span class="infobox-data-number"><a href="{{url('admin/complaint/complaintList')}}">0</a></span>
                                    <div class="infobox-content">有未处理意见反馈</div>
                                </div>

                            </div>

                            <div class="infobox infobox-green  " style="width: 400px;">
                                <div class="infobox-icon">
                                    <i class="icon-comments"></i>
                                </div>

                                <div class="infobox-data">
                                    <span class="infobox-data-number"><a href="{{url('admin/specialCourse/specialCourseList')}}">0</a></span>
                                    <div class="infobox-content">有转码失败的视频</div>
                                </div>

                            </div>



                        </div>

                        <div class="vspace-sm"></div>

                    </div><!-- /row -->

                    <div class="hr hr32 hr-dotted"></div>

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
@endsection