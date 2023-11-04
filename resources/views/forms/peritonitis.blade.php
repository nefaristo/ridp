@extends('forms.common.form')
@section('form-data')
    @if ($model!=null) 
        {!!$model->input("parent"); !!}
        <table>
            <tr>
                <td colspan="4">
                    {!!$model->label("date");!!}&nbsp;{!!$model->input("date");!!}
                    &nbsp;{!!$model->label("hospitalization_days");!!}&nbsp;{!!$model->input("hospitalization_days");!!}
                    &nbsp;{!!$model->label("dialysis_protocol_modification");!!}&nbsp;{!!$model->input("dialysis_protocol_modification");!!}
                </td>
            </tr>            
            <tr style="vertical-align: top;border-top: solid 1px #8888ff;padding-top: 5px;padding-bottom: 5px;margin-top:5px;">
                <td>{!!$model->label("diagnoses");!!}</td>
                <td>{!!$model->input("diagnoses",['style'=>'width:50vw;height:20vh;'],["placeholder"=>trans("rdp.peritonitis_diagnoses_placeholder")])!!}</td>                               
                <td>{!!$model->label("diagnosis_description",NULL,["class"=>"peritonitis_diagnosis_description","style"=>"display:none"]);!!}</td>
                <td>{!!$model->input("diagnosis_description",["class"=>"peritonitis_diagnosis_description","style"=>"display:none;width:100%;height:4vh"]);!!}</td>
            </tr>            
            <tr style="vertical-align: top;border-top: solid 1px #8888ff;padding-top: 5px;padding-bottom: 5px;margin-top:5px;">
                <td colspan="4" style="text-align: center;">
                    <p class="subtitle">{{trans("rdp.risk_factors")}}</p>
                </td> 
            </tr>            
            <tr> 
                <td>{!!$model->label("nasal_swab_germ");!!}</td>
                <td>{!!$model->input("nasal_swab_germ");!!}</td>
                <td>{!!$model->label("exit_site_infection",trans("rdp.exit_site_infection"));!!}</td>
                <td>{!!$model->input("exit_site_infection");!!}</td>
            </tr><tr>
                <td>{!!$model->label("exit_site_swab_germ");!!}</td>
                <td>{!!$model->input("exit_site_swab_germ");!!}</td>
                <td>{!!$model->label("leakage");!!}</td>
                <td>{!!$model->input("leakage");!!}</td>
            </tr><tr>
                <td>{!!$model->label("other_risk_factors");!!}</td>
                <td colspan="3">{!!$model->input("other_risk_factors",["style"=>"width:100%;"]);!!}</td>                 
            </tr>
                <td colspan="4" style="text-align: center;">
                    <p class="subtitle">{{trans("rdp.peritoneal_liquid_culture")}}</p>
                </td>             
            <tr style="vertical-align: top;border-top: solid 1px #8888ff;padding-top: 5px;padding-bottom: 5px;margin-top:5px;">
                <td>
                    {!!$model->label("germ_peritoneal_liquid_culture_1",trans("rdp.peritoneal_liquid_culture"). " 1");!!}
                </td>
                <td colspan="3">
                    {!!$model->input("date_peritoneal_liquid_culture_1");!!}
                    {!!$model->label("germ_peritoneal_liquid_culture_1",trans("rdp.germ"));!!}
                    {!!$model->input("germ_peritoneal_liquid_culture_1");!!}
                </td>
            </tr>            
            <tr>
                <td>
                    {!!$model->label("germ_peritoneal_liquid_culture_2",trans("rdp.peritoneal_liquid_culture"). " 2");!!}
                </td>
                <td colspan="3">                    
                    {!!$model->input("date_peritoneal_liquid_culture_2");!!}
                    {!!$model->label("germ_peritoneal_liquid_culture_2",trans("rdp.germ"));!!}
                    {!!$model->input("germ_peritoneal_liquid_culture_2");!!}
                </td>                                    
            </tr> 
            <tr>
                <td>{!!$model->label("non_culture_execution_reason");!!}</td>                    
                <td colspan="3">{!!$model->input("non_culture_execution_reason",["style"=>"width:100%;"]);!!}</td>
            </tr>                        
        </table>
        <script>
            $(function(){
                //immediate:
                diagnoses_change();
                //events:
                $("#mainform_container").on('change.diagnoses','[data-field="diagnoses"]', function(){diagnoses_change();});
                //show&load/hide subform:
                if(false && $("[name='id']").val()!=""){
                    $("#subform_section").show();
                    SVF.Load('#subform',{url:'/edits/peritonitis_therapy/{{$model->id}}'});return false;
                }else{
                    $("#subform_section").hide();
                }
                //functions:
                function diagnoses_change(){             
                    if(($.inArray('8',$("select[data-field='diagnoses']").val()))>-1){
                        $(".peritonitis_diagnosis_description").show();
                    }else{
                        $(".peritonitis_diagnosis_description").hide();
                        $("[name='diagnosis_description']").text("");
                    }
                }
            });
        </script>
    @endif
@endsection
@section('subforms')
    @if ($model && $model->id)
        <ul class="sv_tabs">                      
            <li><a id="tab_peritonitis_therapy" onClick="return loadSubForm($(this));">{{trans('rdp.therapies')}}</a></li>
        </ul>
        <div id="subform_target"></div>
        <script>            
            function loadSubForm($element){; 
                if(SVL.exitConfirm($('#subform_container'))){
                    $element.closest("li").siblings().removeClass("sv_selected");
                    $element.closest("li").addClass("sv_selected");
                    switch($element.attr("id")){
                        case("tab_peritonitis_therapy"):
                            url='/edits/peritonitis_therapy/{{$model->id}}';
                            break;                        
                    }
                    if(url){SVL.load({target:$('#subform_target'),targetMode:"fill",filter:"#subform_container",method:'get',url:url});};
                }
                return false;
            }
            $(function(){loadSubForm($('#tab_peritonitis_therapy'));})  
        </script>            
    @endif
@endsection