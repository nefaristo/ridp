@extends('layouts.main')
@section('otherHeads')     
@endsection

@section('title',trans('rdp.centers'))

@section('content') 
<div style="overflow:auto;">
    <div id="MainTree" style="resize:both;width:200px;height:500px;overflow:auto;float:left;border:1px solid grey;margin:2px;"></div>
    <div id = "mainform_target" style="height:500px;margin:2px;overflow:auto;"></div>
</div>
<script> 
    function loadForm(pars){
        if(SVL.exitConfirm($('#mainform_target'))){//checks for dirty data within the element            
            SVL.load({target:$('#mainform_target'),filter:'#mainform_container',targetMode:'fill',url:pars['url'],method:"GET",afterDone:function(data){console.log(data);},});
        }        
        return false;
    }
    function standardTitle(pars){//{href:url_to_call,oper:add|edit,type:patient|etc,id:unique_among_type,text:string_for_user
        return function(record){
            pars.href= '/' +pars.oper + '/' + pars.type + '/' + record[pars.id];
            return '<a href="' + pars.href + '" onClick="loadForm({url:\'' + '/' + pars.oper + '/' + pars.type + '/' + record[pars.id] + '\'});return false;">'
                + ((typeof pars.text=="function")?pars.text(record):pars.text)
                + '</a>';
        };
    } 
    $(function(){   
        MainTree=new SvTree($("#MainTree"),{
            lists:{ 
                centers:{
                    url: "/FeedTree/centers/-/city",
                    //title: titleShortcut({oper:'edit',type:'center',id:'{id}',text:'{short_desc}',href:'{long_desc}'}),
                    content: standardTitle({oper:'edit',type:'center',id:'id',text:function(record){return record.short_desc;},}),
                    loadItems:'never',
                }, 
                users:{
                    parent:'centers',
                    url: function(record){return '/FeedTree/users/center=record.id/priority';},
                    content: standardTitle({oper:'edit',type:'user',id:'id',text:function(record){return record.username;},}),
                    loadItems:'never',
                },
            }
        });
        MainTree.loadLists(); 
    });    
</script> 
@endsection