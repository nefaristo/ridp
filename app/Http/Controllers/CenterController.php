<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Center;
use Yajra\Datatables\Datatables;
use App\SVLibs\utils;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('centers');
    }
    
    public function feedTable(){
        $centers=Center::baseview()->select('id','code','centers_description' )->orderBy('code','asc');
        //$centers = Center::query();//select('id','code' , 'unit', 'address');
        //return utils::viewStuff($centers->toArray());
        return Datatables::of($centers)
            //->editColumn('centers_description', '<a class="dialogLink" >{{$centers_description}}</a>')           
            ->editColumn('centers_description', '<a href="" onClick="return link2Dialog(\'{{$id}}\');">{{$centers_description}}</a>')
                //route("centers\edit",["id"=>$id])
              //->addColumn('action', function ($centers) {return '<a href="'.$centers->id.'">aaa</a>';})
                //{{route(\'centers/edit\',[\'id\'=>$id])}}
            ->make(true);
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
        return utils::viewStuff($request);
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
        $center=  Center::where('id','=',$id)->firstOrFail();
        //return utils::viewStuff($center->toArray());
        return view('forms/center',['center'=>$center,'vs'=>  utils::viewStuff($center->toArray()), 'id'=>$id]);
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
