@extends('forms.common.form')
@section('form-data')
    @if ($model!=null) 
        {!!$model->input("parent"); !!}
        <table>
            <tr>
                <td>{!!$model->label("date");!!}</td>                    
                <td>{!!$model->input("date");!!}</td>                
                <td>{!!$model->label("type");!!}</td>                    
                <td colspan="2">{!!$model->input("type");!!}</td>                  
            </tr>
            <tr>                
                <td>{!!$model->label("surgical_technique");!!}</td>                    
                <td>{!!$model->input("surgical_technique");!!}</td>                
                <td>{!!$model->label("insertion_site_1");!!}</td>                    
                <td>{!!$model->input("insertion_site_1");!!}</td>
                <td>{!!$model->label("insertion_site_2");!!}</td>                    
                <td>{!!$model->input("insertion_site_2");!!}</td>                 
            </tr>
        </table><table>
            <tr>
                <td>{!!$model->label("omentectomy");!!}</td>                    
                <td>{!!$model->input("omentectomy");!!}</td>  
                <td>{!!$model->label("lumens_number");!!}</td>                    
                <td>{!!$model->input("lumens_number");!!}</td>                               
                <td>{!!$model->label("tunnel_orientation");!!}</td>                    
                <td>{!!$model->input("tunnel_orientation");!!}</td> 
            </tr>
            <tr>
                <td>{!!$model->label("use_after_days");!!}</td>                    
                <td>{!!$model->input("use_after_days",["style"=>"width:3em"]);!!}</td>                 
                <td>{!!$model->label("removal_date");!!}</td>                    
                <td>{!!$model->input("removal_date");!!}</td>                                 
                <td>{!!$model->label("removal_reason",NULL,["style"=>"display:none"]);!!}</td>                    
                <td>{!!$model->input("removal_reason",["style"=>"display:none"]);!!}</td>
            </tr>                     
        </table> 
        <script>
            $(function(){
                afterUpdate($(".sv_input[data-field='removal_date']"));
                $("#mainform_container").off("change.catheter",".sv_input");
                $("#mainform_container").on("change.catheter",".sv_input",function(){
                    afterUpdate($(this));                    
                });
                function afterUpdate($el){ 
                    switch($el.attr("data-field")){
                    case "removal_date":
                        $el.closest("form").find("[data-field='removal_reason']").css("display",$el.val()?"inline-block":"none");
                        break;
                    }
                }
            })
            
        </script>
    @endif
@endsection
@section('subforms')
    @if ($model && $model->id)
        <ul class="sv_tabs">    
            <li><a id="tab_catheter_medication" onClick="return loadSubForm($(this));">{{trans('rdp.medications')}}</a></li>
            <li><a id="tab_catheter_complication" onClick="return loadSubForm($(this));">{{trans('rdp.complications')}}</a></li>
        </ul>
        <div id="subform_target"></div>  
        <script>  
            function loadSubForm($element,url){; 
                if(SVL.exitConfirm($('#subform_container'))){
                    $element.closest("li").siblings().removeClass("sv_selected");
                    $element.closest("li").addClass("sv_selected");
                    switch($element.attr("id")){
                        case("tab_catheter_medication"):
                            url='/edits/catheter_medication/{{$model->id}}/date';
                            break;
                        case("tab_catheter_complication"):
                            url='/edits/catheter_complication/{{$model->id}}/date';
                            break;
                    }
                    if(url){SVL.load({target:$('#subform_target'),targetMode:"fill",filter:"#subform_container",method:'get',url:url});};
                }
                return false;
            }
            $(function(){/*loadSubForm($('#tab_catheter_medication'));*/
            })           
        </script>            
    @endif
@endsection