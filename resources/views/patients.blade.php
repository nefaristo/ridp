@extends('layouts.main')
@section('otherHeads')
    <script src="{{URL::asset('js/rdp_hash.js')}}?<?php echo time(); ?>"></script> 
@endsection
@section('title',trans('rdp.patients'))
@section('content')
<div style="height:85vh;display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start;">
    <div class="ctrl_panel" id="filter_box" style="">
        <table style="margin:auto">
            <tr>
                <td>{{trans('rdp.surname')}} </td>
                <td><input type="search" id="filter_patient_surname" style="width:16ch;"></td>
                <td>{{trans('rdp.name')}}</td>
                <td><input type="search" id="filter_patient_name" style="width:16ch;"></td>                
                <td>{{trans('rdp.patients_dob')}} </td>
                <td><!--<input type="date" id="filter_patient_dob" style="width:16ch;vertical-align:top">-->
                    <input type="date" class="_datepicker" id="filter_patient_dob" style="width:16ch;vertical-align:top">
                </td>
                <td><button onClick="window.open('/help/privacy#patient_search');" class="icon_button help" title="{{trans("rdp.popup_patient_search")}}" style="vertical-align:top"></button></td>
            </tr>
            <tr>
                <td colspan="7"  style="border-top: 1px solid grey"></td>
            </tr>
            <tr>
                <td>{{trans('rdp.patients_code')}} </td>
                <td><input type="search" id="filter_patient_code" onChange="MainTree.loadLists(undefined,true);" style="width:18ch;"></td>
                <td>{{trans('rdp.patients_with_open_treatments_only')}} </td>
                <td><input type="checkbox" id="filter_open_only" checked onChange="MainTree.loadLists(undefined,true);"></td>
                @if(Auth::user()->privilege<10)
                    <input type="hidden" id="filter_center" value="{{Auth::user()->center}}">
                @else
                    <td>{{trans('rdp.center')}}</td>
                    <td>
                        {!!session("center") . Form::select("email",\App\SVLibs\utils::listOptions("SELECT NULL as id, '' as description UNION (SELECT id, CONCAT(code,' (',city,')') AS description FROM centers ORDER BY code)"),session("center"),["id"=>"filter_center","onChange"=>"MainTree.loadLists(undefined,true);"]);!!}
                    </td>                
                @endif
            </tr>
        </table>
    </div>
    <div style="order:1; display: flex;flex-direction: row;align-items: stretch;justify-content: space-start;">
        <div id="MainTree" style="order:0;min-width:fit-content;overflow:auto;border:1px solid lightblue;border-radius: 5px;padding:2px;"></div>
        <div id = "mainform_target" style=";order:1; width:100%;margin:2px;overflow:auto;"></div>
    </div>
</div>
<script> 
    function treeRootFilter(){
        codeFilter=($("#filter_patient_code").val())?("code like '*" + $("#filter_patient_code").val() + "*'"):"";                
        openFilter=($("#filter_open_only").is(':checked'))?"(treats_total=0 OR treats_open>0)":"";
        centerFilter=$("#filter_center").val()?"parent=" + $("#filter_center").val():"";
        result=$.grep([codeFilter,openFilter,centerFilter],function(el,index){return el!="";}).join(" AND ");//last parameter:orderby
        if(!result){result="-";}//see workaround for filter null (server side)
        result="/FeedTree/patients/" + result + "/code"; //url/filter/orderby        
        return result;
    } 
    function loadForm(pars){
        if(SVL.exitConfirm($('#mainform_target'))){//checks for dirty data within the element            
            SVL.load({target:$('#mainform_target'),filter:'#mainform_container',targetMode:'fill',url:pars['url'],method:"GET",afterDone:function(data){console.log(data);},});
        }        
        return false;
    }
    function standardTitle(pars){//{href:url_to_call,oper:add|edit,type:patient|etc,id:unique_among_type,text:string_for_user         
        return function(record){//SvTree.loadItems looks for content=value|function(record)                    
            pars.href= '/' +pars.oper + '/' + pars.type + '/' + record[pars.id];        
            return '<a href="' + pars.href + '" onClick="loadForm({url:\'' + '/' + pars.oper + '/' + pars.type + '/' + record[pars.id] + '\'});return false;">'
                + ((typeof pars.text=="function")?pars.text(record):pars.text)
                + '</a>';
        };
    } 
    $(function(){    
        MainTree=new SvTree($("#MainTree"),{
            loading:'<div class="sv_loader" style="width: 10px;height: 10px;" />',
            lists:{
                addpatient:{
                    content: 
                        '<a href="/add/patient/{{session("center")}}" onClick="loadForm({url:\'/add/patient/{{session("center")}}\' });return false;">' +
                            '{{trans("rdp.patients_add")}}'+ 
                        '</a>',
                    //content: standardTitle({oper:'add',type:'patient',id:'{{session("center")}}',text:'{{trans("rdp.patients_add")}}',href:'add patient to {{session("center")}}'}),                     
                    //url: '/add/patient/',
                    loadItems:'never',
                }, 
                patients:{ 
                    url: treeRootFilter,
                    content: standardTitle({oper:'edit',type:'patient',id:'id',text:function(record){return record.code;},}),
                },
                    treatments:{
                        parent:'patients',
                        //url: '/FeedTree/treatments/parent={id}/start_date',
                        url: function(parent){return '/FeedTree/treatments/parent='+parent["id"]+'/start_date';},
                        //content: titleShortcut({oper:'edit',type:'treatment',id:'{id}',text:'{title}',href:'treatments {id}'}),
                        content: standardTitle({oper:'edit',type:'treatment',id:'id',text:function(record){return record.title;},}),
                        loadItems:'always', 
                    }, 
                    addtreatment:{
                        parent:'patients',
                        //content: titleShortcut({oper:'add',type:'treatment',id:'{id}',text:'{{trans("rdp.treatments_add")}}',href:'add treatment to {id}'}), 
                        content: standardTitle({oper:'add',type:'treatment',id:'id',text:'{{trans("rdp.treatments_add")}}'}), 
                        loadItems:'never',
                    },                
                        follow_up_folder:{
                            parent:'treatments',
                            content: '{{trans('rdp.follow_ups')}}',
                        },
                            follow_ups:{
                                parent:'follow_up_folder',                    
                                url: function(parent){return '/FeedTree/follow_ups/parent='+parent['id']+'/date';},
                                //content: titleShortcut({oper:'edit',type:'follow_up',id:'{id}',text:'{{"{desc_".\App::getLocale()."}"}}',href:'followups {id}'}),
                                content: standardTitle({oper:'edit',type:'follow_up',id:'id',text:function(record){return record['{{"desc_".\App::getLocale()}}'];},}),
                                loadItems:'never',
                            }, 
                            addfollowup:{
                                parent:'follow_up_folder',
                                //content: titleShortcut({oper:'add',type:'follow_up',id:'{id}',text:'{{trans("rdp.add")}}',href:'add follow up to {id}'}), 
                                content: standardTitle({oper:'add',type:'follow_up',id:'id',text:'{{trans("rdp.add")}}'}), 
                                loadItems:'never',
                            }, 
                        pd_connections_folder:{
                            parent:'treatments',
                            filter:function(parent){return parent.type=='PD';},
                            content: '{{trans("rdp.connections")}}',
                        }, 
                            pd_connections:{
                                parent:'pd_connections_folder',                    
                                url: function(parent){return '/FeedTree/pd_connections/parent='+parent['id']+'/date';},
                                //content: titleShortcut({oper:'edit',type:'pd_connection',id:'{id}',text:'{{"{date_".\App::getLocale()."}"}}',href:'connections {id}'}),
                                content: standardTitle({oper:'edit',type:'pd_connection',id:'id',text:function(record){return record['{{"date_".\App::getLocale()}}'];},}),
                                loadItems:'never',
                            }, 
                            add_pd_connection:{
                                parent:'pd_connections_folder',
                                //content: titleShortcut({oper:'add',type:'pd_connection',id:'{id}',text:'{{trans("rdp.add")}}',href:'add connection to {id}'}), 
                                content: standardTitle({oper:'add',type:'pd_connection',id:'id',text:'{{trans("rdp.add")}}'}), 
                                loadItems:'never',
                            },
                        catheters_folder:{
                            parent:'treatments',
                            filter:function(parent){return parent.type=='PD';},
                            content: '{{trans("rdp.catheters")}}',
                        },      
                            catheters:{
                                parent:'catheters_folder',                    
                                url: function(parent){return '/FeedTree/catheters/parent='+parent['id']+'/date';},
                                //content: titleShortcut({oper:'edit',type:'catheter',id:'{id}',text:'{{"{date_".\App::getLocale()."}"}}'}),
                                content: standardTitle({oper:'edit',type:'catheter',id:'id',text:function(record){return record['{{"date_".\App::getLocale()}}'];},}),
                                loadItems:'never',
                            },
                            add_catheter:{
                                parent:'catheters_folder',
                                //content: titleShortcut({oper:'add',type:'catheter',id:'{id}',text:'{{trans("rdp.add")}}',href:'add catheter to {id}'}), 
                                content: standardTitle({oper:'add',type:'catheter',id:'id',text:'{{trans("rdp.add")}}'}), 
                                loadItems:'never',
                            },                        
                        hd_accesses_folder:{
                            parent:'treatments',
                            filter:function(parent){return parent.type=='HD';},
                            content: '{{trans("rdp.accesses")}}',
                        }, 
                            hd_accesses:{
                                parent:'hd_accesses_folder',                    
                                url: function(parent){return '/FeedTree/hd_accesses/parent='+parent['id']+'/date';},
                                //content: titleShortcut({oper:'edit',type:'hd_access',id:'{id}',text:'{{"{date_".\App::getLocale()."}"}}',href:'access {id}'}),
                                content: standardTitle({oper:'edit',type:'hd_access',id:'id',text:function(record){return record['{{"date_".\App::getLocale()}}'];},}),
                                loadItems:'never',
                            }, 
                            add_hd_access:{
                                parent:'hd_accesses_folder',
                                //content: titleShortcut({oper:'add',type:'hd_access',id:'{id}',text:'{{trans("rdp.add")}}',href:'add access to {id}'}), 
                                content: standardTitle({oper:'add',type:'hd_access',id:'id',text:'{{trans("rdp.add")}}'}), 
                                loadItems:'never',
                            },                        
                        peritonites_folder:{
                            parent:'treatments',
                            filter:function(parent){return parent.type=='PD';},
                            content: '{{trans("rdp.peritonites")}}',
                        },                   
                            peritonites:{
                                parent:'peritonites_folder',
                                url: function(parent){return '/FeedTree/peritonites/parent='+parent['id']+'/date';},
                                //content: titleShortcut({oper:'edit',type:'peritonitis',id:'{id}',text:'{{"{date_".\App::getLocale()."}"}}',href:'peritonitis {id}'}),
                                content: standardTitle({oper:'edit',type:'peritonitis',id:'id',text:function(record){return record['{{"date_".\App::getLocale()}}'];},}),
                                loadItems:'never',
                            },
                            add_peritonites:{
                                parent:'peritonites_folder',
                                //content: titleShortcut({oper:'add',type:'peritonitis',id:'{id}',text:'{{trans("rdp.add")}}',href:'add peritonitis to {id}'}), 
                                content: standardTitle({oper:'add',type:'peritonitis',id:'id',text:'{{trans("rdp.add")}}'}), 
                                loadItems:'never',
                            },
                        complications:{
                            parent:'treatments',
                            //content: titleShortcut({oper:'edits',type:'complication',id:'{id}/date',text:'{{trans("rdp.complications")}}',href:'complications of {id}'}),
                            content: standardTitle({oper:'edits',type:'complication',id:'id',text:'{{trans("rdp.complications")}}'}),
                            loadItems:'never',
                        }, 
                        pets:{
                            parent:'treatments',
                            filter:function(parent){return parent.type=='PD';},
                            //content: titleShortcut({oper:'edits',type:'peritoneal_equilibration_test',id:'{id}/date',text:'{{trans("rdp.pets")}}',href:'peritoneal equilibration tests of {id}'}),
                            content: standardTitle({oper:'edits',type:'peritoneal_equilibration_test',id:'id',text:'{{trans("rdp.pets")}}'}),
                            loadItems:'never',
                        }, 
            }
        });
        MainTree.loadLists();
        $("#test").on("click",function(e){
            e.preventDefault();
            alert(MainTree.focusedKey);
        })
        $("#filter_box").on("blur.filter","#filter_patient_name, #filter_patient_surname, #filter_patient_dob",function(e){
            e.stopPropagation();
            console.log($(this));
            if($("#filter_patient_name").val() && $("#filter_patient_surname").val() && $("#filter_patient_dob").val()){
                var inputString= $("#filter_patient_surname").val()+$("#filter_patient_name").val()+$("#filter_patient_dob").val().toString();
                $("#filter_patient_code").val(patientHash(inputString,13));$("#filter_open_only").prop("checked",false);
                $("#filter_patient_code").change();
            }
        })    
    });    
</script>
@endsection