<?php

namespace App\Http\Controllers\Home\member;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class publicHomePageController extends Controller
{
    /**
     * @param $hisId
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teacherHomePagePublic($hisId)
    {
        $mineId = \Auth::check() ? \Auth::user()->id : null;
        return view('home.member.teacherHomePagePublic', compact('hisId', 'mineId'));
    }
    /**
     * @param $hisId
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentHomePagePublic($hisId)
    {
        $mineId = \Auth::check() ? \Auth::user()->id : null;
        return view('home.member.studentHomePagePublic', compact('hisId', 'mineId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
