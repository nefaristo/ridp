@extends('forms.common.form')
@section('form-data')
    @if ($model!=null)
        {!!$model->input("parent")!!} 
        <table>
            <tr>
                <td>{!!$model->label("start_date");!!}</td>
                <td>{!!$model->input("start_date",["style"=>"width:10em;"]);!!}</td>
                <td>{!!$model->label("type");!!}</td>
                <td>{!!$model->input("type");!!}</td>
                <td>{!!$model->label("former_treatment");!!}</td>
                <td>{!!$model->input("former_treatment");!!}</td>                 
            </tr> 
            <tr>
                <td>{!!$model->label("beginning_hospitalization_days");!!}</td>
                <td>{!!$model->input("beginning_hospitalization_days",["style"=>"width:4em;"]);!!}</td>                
                <td class="pd_only" style="display:none">{!!$model->label("pd_training_days");!!}</td>
                <td class="pd_only" style="display:none">{!!$model->input("pd_training_days",["style"=>"width:4em;"]);!!}</td>
                <td class="hd_only" style="display:none">{!!$model->label("hd_vascular_access_setup_days");!!}</td>
                <td class="hd_only" style="display:none">{!!$model->input("hd_vascular_access_setup_days",["style"=>"width:4em;"]);!!}</td>
                <td class="tx_only" style="display:none">{!!$model->label("tx_from");!!}</td>
                <td class="tx_only" style="display:none">{!!$model->input("tx_from");!!}</td>                
            </tr>
            <tr>
                <td>{!!$model->label("creatinine_clearance");!!}</td>
                <td>{!!$model->input("creatinine_clearance",["style"=>"width:5em;"]);!!}</td>
                <td>{!!$model->label("urea_clearance");!!}</td>
                <td>{!!$model->input("urea_clearance",["style"=>"width:5em;"]);!!}</td>                                
                <td>{!!$model->label("residual_diuresis");!!}</td>
                <td>{!!$model->input("residual_diuresis",["style"=>"width:4em;"]);!!}</td>                                              
            </tr>
            <tr><td colspan="6"><hr></td></tr>                    
            <tr>
                <td>{!!$model->label("end_date");!!}</td>
                <td>{!!$model->input("end_date");!!}</td>           
                <td class="end_date_filled" style="display:none">{!!$model->label("end_cause",null);!!}</td>
                <td colspan="5" class="end_date_filled" style="display:none">{!!$model->input("end_cause",['style'=>'width:100%;']);!!}</td>
            </tr>
            <tr id="end_cause_tx_failure" style="display:none">             
                <td>{!!$model->label("tx_failure_cause");!!}</td>
                <td>{!!$model->input("tx_failure_cause");!!}</td>
                <td colspan="2">{!!$model->label("tx_failure_cause_note");!!}</td>
                <td colspan="2">{!!$model->input("tx_failure_cause_note",["style"=>"width:100%"]);!!}</td>
            </tr>              
            <tr id="end_cause_other_technique" style="display:none">
                <td>{!!$model->label("technique_change_cause");!!}</td>
                <td colspan="5">{!!$model->input("technique_change_cause",['style'=>'width:100%;']);!!}</td>
            </tr>
            <tr id="end_cause_new_center" style="display:none">
                <td>{!!$model->label("new_center");!!}</td>
                <td colspan="5">{!!$model->input("new_center",['style'=>'width:100%;']);!!}</td>
            </tr>                       
            <tr id="technique_change_cause_other" style="display:none">
                <td>{!!$model->label("technique_change_cause_specification");!!}</td>
                <td colspan="5">{!!$model->input("technique_change_cause_specification",['style'=>'width:100%;']);!!}</td> 
            </tr> 
        </table>
        <script>            
            $(function(){
                //override update parameter: refresh all after successful save                
                mainform_params.afterDone=function(data){
                    $.get('/edit/treatment/{{$model->id}}')
                    .done(function(data){
                        $("#mainform_container").replaceWith($(data).find("#mainform_container"));
                        SVL.reset($("#mainform_container"));
                        if(typeof MainTree!="undefined")MainTree.refresh();
                    });                    
                },                
                //"root" events are type and end_date, immediately called once
                after_update($("[name='type']"));after_update($("[name='end_date']"));
                $("#mainform_container").off("change.treatment_fields_change",".sv_input");
                $("#mainform_container").on("change.treatment_fields_change",".sv_input",function(){
                    after_update($(this));
                });                                
                function after_update($element){                  
                    switch($element.attr("name")){                        
                        //type=>PD training|HD access:
                        case "type":
                            switch($("[name='type']").val()){
                                case "PD":
                                    $(".pd_only").show(); $(".hd_only").hide();$(".tx_only").hide();
                                    $("[name='hd_vascular_access_setup_days']").val(0);
                                    break;
                                case "HD":
                                    $(".pd_only").hide(); $(".hd_only").show();$(".tx_only").hide();
                                    $("[name='pd_training_days']").val(0);
                                    break;
                                case "TX":
                                    $(".pd_only").hide(); $(".hd_only").hide();$(".tx_only").show();
                                    $("[name='hd_vascular_access_setup_days']").val(0);
                                    $("[name='pd_training_days']").val(0); 
                                };     
                                RDP.load("[name='former_treatment']");                                                              
                            break;
                        //end date=>end_cause=>technique change etc:
                        case "end_date":
                            if($element.val()){
                                $(".end_date_filled").show();                            
                            }else{
                                $(".end_date_filled").hide();
                            }
                            after_update($("[name='end_cause']"));//cascade refresh                            
                            break;
                        case "end_cause":                            
                            if($element.val()== {{config("rdp.misc.treatment_end_cause.death")}} ){//death
                                //if it wasn't the same before, reminder to save+compile death form:
                                if($element.val()!=$element.attr("data-initial-value")){
                                    RDP.message("{{trans('rdp.death_form_not_present')}}",{style:"warning"});
                                }
                                $("#end_cause_death").show();                                
                                if(!$("#subform_container").length){//loads only if not loaded
                                    SVL.load({
                                        target:$('#subform_target'),targetMode:'fill',filter:'#subform_container',
                                        url:'/edits/death/{{$model->parent}}',method:'get',
                                        afterDone:function(){
                                            if($('#subform_target #subform_container') && $('#subform_target #subform_container').children().length==0)
                                                $('#subform_target').html("{{trans('rdp.death_form_not_present')}}");
                                        },
                                    });
                                }
                            }else{//not death
                                $("#end_cause_death").hide();
                            }
                            if($element.val()== {{config("rdp.misc.treatment_end_cause.technique_change")}} ){//technique change
                                $("#end_cause_other_technique").show();
                            }else{
                                $("#end_cause_other_technique").hide();
                                $("[name='technique_change_cause']").val(0);
                            } 
                            if($element.val()=={{config("rdp.misc.treatment_end_cause.center_change")}} ){
                                $("#end_cause_new_center").show();
                            }else{
                                $("#end_cause_new_center").hide();                                
                            }
                            if($element.val()=={{config("rdp.misc.treatment_end_cause.tx_failure")}}){//
                                $("#end_cause_tx_failure").show();
                            }else{
                                $("#end_cause_tx_failure").hide();
                                $("[name='tx_failure_cause']").val('0');
                                $("[name='tx_failure_cause_note']").val('');
                            }                                                        
                            after_update($("[name='technique_change_cause']"));//cascade refresh
                            break; 
                        case "technique_change_cause":
                            if($element.val()== {{config("rdp.misc.treatment_end_cause.other_causes")}} ){
                                $("#technique_change_cause_other").show();
                            }else{
                                $("#technique_change_cause_other").hide();
                                $("[name='technique_change_cause_specification']").val("");
                            }
                            break;
                    }
                }
            });
        </script>
    @endif
@endsection
@section('subforms')
    <div id="end_cause_death" style="display:none">
        <div id="subform_target"></div>
    </div>  
@endsection

 