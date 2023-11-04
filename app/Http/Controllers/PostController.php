<?php

namespace App\Http\Controllers; 
use App\Http\Controllers\Controller as BaseController; 
use Illuminate\Http\Request;


class PostController extends BaseController
{
    public function form(){
        return view("post.form");
    }    
    public function save (Request $request){
        $this->validate($request, [
           'titolo'=>'required|unique:posts|max:255',
           'testo'=>'required',
        ]);
    }    
}

