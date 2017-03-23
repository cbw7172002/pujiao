<?php

namespace App\Http\Controllers\Admin\chapter;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;

class chapterController extends Controller{

    public function chapterList(Request $request){
       $query = DB::table('chapter as c');
        $label = [];
        $label['Grade'] = null;
        $label['Subject'] = null;
        $label['Book'] = null;
        $label['Edition'] = null;
        if($request['Grade']){
            $query = $query->where('gradeId',$request['Grade']);
        }
        if($request['Subject']){
            $query = $query->where('subjectId',$request['Subject']);
        }
        if($request['Book']){
            $query = $query->where('bookId',$request['Book']);
        }
        if($request['Edition']){
            $query = $query->where('editionId',$request['Edition']);
        }
        $label['Grade'] = $request->Grade;
        $label['Subject'] = $request->Subject;
        $label['Book'] = $request->Book;
        $label['Edition'] = $request->Edition;
//        if($request['type'] == 1){
//            $query = $query->where('r.id','like','%'.trim($request['search']).'%');
//        }

       $data = $query->leftJoin('studysection as x','x.id','=','c.sectionId')
           ->leftJoin('schoolgrade as g','g.id','=','c.gradeId')
           ->leftJoin('studysubject as s','s.id','=','c.subjectId')
           ->leftJoin('studyedition as e','e.id','=','c.editionId')
           ->leftJoin('studyebook as b','b.id','=','c.bookId')
           ->orderBy('id','desc')
           ->select('c.*','x.sectionName','g.gradeName','s.subjectName','e.editionName','b.bookName')
           ->where('c.status','=',0)
           ->groupBy('gradeName','subjectName','editionName','bookName')
           ->paginate(10);


        //年级
        $grade = DB::table('schoolgrade')->select('id','gradeName')->get();
        //学科
        $subject = DB::table('studysubject')->select('id','subjectName')->get();
        //册别
        $book = DB::table('studyebook')->select('id','bookname')->get();
        //版本
        $edition = DB::table('studyedition')->select('id','editionName')->get();

        $data->labels = $label;
        return view('admin.chapter.chapterList')->with('data',$data)->with('grade',$grade)->with('subject',$subject)->with('book',$book)->with('edition',$edition);
    }


    /**
     * 查看知识点
     */
    public function seeChapter($id){

        $datas = DB::table('chapter')->select()->where('id',$id)->first();

        if($datas){
            $data =  DB::table('chapter as c')
                ->leftJoin('studysection as x','x.id','=','c.sectionId')
                ->leftJoin('schoolgrade as g','g.id','=','c.gradeId')
                ->leftJoin('studysubject as s','s.id','=','c.subjectId')
                ->leftJoin('studyedition as e','e.id','=','c.editionId')
                ->leftJoin('studyebook as b','b.id','=','c.bookId')
                ->where('sectionId','=',$datas->sectionId)
                ->where('gradeId','=',$datas->gradeId)
                ->where('subjectId','=',$datas->subjectId)
                ->where('bookId','=',$datas->bookId)
                ->where('editionId','=',$datas->editionId)
                ->select('c.*','x.sectionName','g.gradeName','s.subjectName','e.editionName','b.bookName')
                ->paginate(10);
        }else{
            $data = [];
        }
        return view('admin.chapter.seeChapter')->with('data',$data);

    }



    /**
     * 添加知识点页面
     */
    public function addSee($id){
        $data = DB::table('chapter')->select()->where('id','=',$id)->first();
        return view('admin.chapter.addSee')->with('data',$data);
    }


    /**
     * 添加知识点
     */
    public function doAddSee(Request $request){
        $id = $request['id'];
        $datas = DB::table('chapter')->select()->where('id',$id)->first();

        $input['sectionId'] = $datas->sectionId;
        $input['gradeId'] = $datas->gradeId;
        $input['subjectId'] = $datas->subjectId;
        $input['bookId'] = $datas->bookId;
        $input['editionId'] = $datas->editionId;
        $input['chapterName'] = $request['chapterName'];

        //验证
        $validate = $this->validator1($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
//        $chapter = $input['chapterName'];
//        foreach($chapter as $cha){
//            $input['chapterName'] = $cha;
//            $res = DB::table('chapter')->insertGetId($input);
//        }

        $res = DB::table('chapter')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'chapter/seeChapter/' .$id]);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑知识点页面
     */
    public function editSee($id){
        $data = DB::table('chapter')->select()->where('id','=',$id)->first();
        return view('admin.chapter.editSee')->with('data',$data);
    }


    /**
     * 编辑知识点
     */
    public function doEditSee(){
        $input = Input::except('_token');
        //验证
        $res = DB::table('chapter')->where('id',$input['id'])->update($input);
        //验证
        $validate = $this->validator_edit1($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'chapter/seeChapter/'.$input['id']]);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除知识点
     */
    public function delSee($id){
        $res = DB::table('chapter')->where('id',$id)->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            return redirect()->back()->with('status', '删除成功！');
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }






    /**
     * 添加页面
     */
    public function addChapter(){

        //年级
        $grade = DB::table('schoolgrade')->select('id','gradeName')->get();
        //学科
        $subject = DB::table('studysubject')->select('id','subjectName')->get();
        //册别
        $book = DB::table('studyebook')->select('id','bookname')->get();
        //版本
        $edition = DB::table('studyedition')->select('id','editionName')->get();

        return view('admin.chapter.addChapter')->with('grade',$grade)->with('subject',$subject)->with('book',$book)->with('edition',$edition);
    }



    /**
     * 添加
     */
    public function doAddChapter(Request $request){
        $input = Input::except('_token');
        $input['sectionId'] = '1';
        //验证
        $validate = $this->validator($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }

        $res = DB::table('chapter')->insertGetId($input);
        if($res){
//            $this -> OperationLog("新增了年级ID为{$res}的信息", 1);
            return redirect('admin/message')->with(['status'=>'添加成功','redirect'=>'chapter/chapterList']);
        }else{
            return redirect()->back()->withInput()->withErrors('添加失败！');
        }

    }


    /**
     * 编辑页面
     */
    public function editChapter($id){

        $data = DB::table('chapter')->select()->where('id','=',$id)->first();

        //年级
        $grade = DB::table('schoolgrade')->select('id','gradeName')->get();
        //学科
        $subject = DB::table('studysubject')->select('id','subjectName')->get();
        //册别
        $book = DB::table('studyebook')->select('id','bookname')->get();
        //版本
        $edition = DB::table('studyedition')->select('id','editionName')->get();

        return view('admin.chapter.editChapter')->with('data',$data)->with('grade',$grade)->with('subject',$subject)->with('book',$book)->with('edition',$edition);

    }


    /**
     * 编辑
     */
    public function doEditChapter(Request $request){
        $id = $request['id'];
        $datas = DB::table('chapter')->select()->where('id',$id)->first();
//        dd($datas);
        $input = Input::except('_token','id');
//        dd($input);
        //验证
        $validate = $this->validator_edit($input);
        if($validate->fails()){
            return Redirect() -> back() -> withInput( $request -> all() ) -> withErrors( $validate );
        }
        $res = DB::table('chapter')
            ->where('gradeId',$datas->gradeId)
            ->where('subjectId',$datas->subjectId)
            ->where('bookId',$datas->bookId)
            ->where('editionId',$datas->editionId)
            ->update($input);

        if($res !== false){
//            $this -> OperationLog("修改了后台用户ID为{$request['id']}的信息", 1);
            return redirect('admin/message')->with(['status'=>'编辑成功','redirect'=>'chapter/chapterList']);
        }else{
            return redirect()->back()->withInput()->withErrors('编辑失败！');
        }
    }


    /**
     * 删除
     */
    public function delChapter($id){
        $datas = DB::table('chapter')->select()->where('id',$id)->first();

        $res = DB::table('chapter')
            ->where('gradeId',$datas->gradeId)
            ->where('subjectId',$datas->subjectId)
            ->where('bookId',$datas->bookId)
            ->where('editionId',$datas->editionId)
            ->delete();
        if($res){
//            $this -> OperationLog("删除了后台用户ID为{$id}的信息", 1);
            return redirect('admin/message')->with(['status'=>'删除成功','redirect'=>'chapter/chapterList']);
        }else{
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }



    /**
     * 验证(添加)
     */
    protected function validator(array $data){
        $rules = [
//            'chapterName' => 'required',
//            'sectionId' => 'required',
            'gradeId' => 'required',
            'subjectId' => 'required',
            'bookId' => 'required',
            'editionId' => 'required',
        ];
        $messages = [
//            'chapterName.required' => '请输入册别',
//            'sectionId.required' => '请选择学段',
            'gradeId.required' => '请选择年级',
            'subjectId.required' => '请选择学科',
            'bookId.required' => '请选择册别',
            'editionId.required' => '请选择版本',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit(array $data){
        $rules = [
//            'chapterName' => 'required',
//            'sectionId' => 'required',
            'gradeId' => 'required',
            'subjectId' => 'required',
            'bookId' => 'required',
            'editionId' => 'required',
        ];
        $messages = [
//            'chapterName.required' => '请输入册别',
//            'sectionId.required' => '请选择学段',
            'gradeId.required' => '请选择年级',
            'subjectId.required' => '请选择学科',
            'bookId.required' => '请选择册别',
            'editionId.required' => '请选择版本',
        ];

        return \Validator::make($data, $rules, $messages);
    }


    /**
     * 验证(添加)
     */
    protected function validator1(array $data){
        $rules = [
            'chapterName' => 'required',
        ];
        $messages = [
            'chapterName.required' => '请输入知识点',
        ];

        return \Validator::make($data, $rules, $messages);
    }



    /**
     * 验证(修改)
     */
    protected function validator_edit1(array $data){
        $rules = [
            'chapterName' => 'required',
        ];
        $messages = [
            'chapterName.required' => '请输入知识点',
        ];

        return \Validator::make($data, $rules, $messages);
    }


}


