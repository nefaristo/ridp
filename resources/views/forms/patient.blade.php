@extends('forms.common.form')
@section('form-data') 
    @if ($model!=null)
        <table>
            <tr>
                <td colspan='4' style="text-align:center">
                    @if($model->code)
                        {!!$model->label("code")!!}
                        {!!$model->input("code",["readonly"])!!}
                        {!!$model->label("birth_date")!!}
                        {!!$model->input("birth_date",$model->code?["readonly"]:[])!!}                           
                        {!!$model->label("gender")!!}
                        {!!$model->input("gender")!!}
                    @else                    
                        <div style="margin:10px auto; border:1px grey solid; padding: 5px; margin:10px auto;">
                            {!!$model->label("surname",trans("rdp.surname"),["class"=>"sv_required"])!!}
                            <input id="patient_surname" type="text" style="width:10em;">                                           
                            {!!$model->label("name",trans("rdp.name"),["class"=>"sv_required"])!!}
                            <input id="patient_name" type="text" style="width:10em;">
                            {!!$model->label("birth_date")!!}
                            {!!$model->input("birth_date",$model->code?["readonly"]:[])!!}   
                            <button onClick="window.open('/help/privacy#patient_privacy');" class="icon_button help" title="{{trans("rdp.popup_patient_insertion_privacy")}}"></button>                         
                            <!--
                            <div style="font-size: 85%;border-top: 1px grey solid;border-bottom: 1px grey solid;padding:5px;">
                                {!!trans("rdp.patients_code_generation_warning")!!}                                
                            </div>
                            -->
                            <div class="sv_warning ui-draggable ui-draggable-handle ui-resizable" style="position: relative; z-index: 5; top: 2px; left: 8px; display:block ;" onClick="$(this).remove();return false;">
                                {!!trans("rdp.patients_code_generation_warning")!!}                                
                            </div>
                            <hr>
                            {!!$model->label("code")!!} 
                            {!!$model->input("code",["readonly"])!!}                             
                            {!!$model->label("gender")!!}
                            {!!$model->input("gender")!!}
                            
                        </div>
                    @endif                                                        
                </td>
            </tr>                
            <tr><td colspan="4"><hr></td></tr>
            <tr>
                <td>{!!$model->label("birth_place")!!}</td>
                <td>{!!$model->input("birth_place",['style'=>'width:100%;'])!!}</td>
                <td>{!!$model->label("birth_province")!!}</td>            
                <td>{!!$model->input("birth_province",['style'=>'width:100%;'])!!}</td>
            </tr>         
            <tr>
                <td>{!!$model->label("residence_place")!!}</td>
                <td>{!!$model->input("residence_place",['style'=>'width:100%;'])!!}</td>            
                <td>{!!$model->label("residence_province")!!}</td>
                <td>{!!$model->input("residence_province",['style'=>'width:100%;'])!!}</td>
            </tr>                 
            <tr>
                <td>{!!$model->label("parent")!!}  </td>
                <td colspan="3">
                    {!!$model->input("parent",["style"=>"width:100%"])!!}                    
                </td>
            </tr> 
            @if($model->id)
                <tr>
                    <td colspan="4">
                        {!!$model->label("oldcenters")!!}
                        <a id="oldCentersLink"> <button class="icon_button rightarrow" style="height:14px"></button> </a>
                        <div id="oldCenterTarget" style="border:1px solid graytext;overflow:auto;width:100%;">
                            <div id="subform_container"></div> 
                        </div>
                    </td>
                </tr>             
            @endif
            <tr><td colspan="4"><hr></td></tr>
            <tr>
                <td>{!!$model->label("prd")!!}</td>
                <td colspan="3">{!!$model->input("prd",['style'=>'width:100%;'])!!}</td>
            </tr> 
            <tr>
                <td>{!!$model->label("prd_specification")!!}</td>
                <td colspan="3">{!!$model->input("prd_specification",['style'=>'width:100%;'])!!}</td>
            </tr>
            <tr> 
                <td>{!!$model->label("comorbidities")!!}</td>
                <td colspan="3">{!!$model->input("comorbidities",['style'=>'width:50vw;height:20vh;'],["placeholder"=>trans('rdp.comorbidities_placeholder')])!!}</td>
            </tr>
            <tr>
                <td>
                    {!!$model->label("comorbidity_specification",null,[])!!}<br>                    
                </td>
                <td colspan="3">
                    {!!$model->input("comorbidity_specification",['style'=>'width:100%;'])!!}
                </td>
            </tr>  
            <tr><td colspan="4"><hr></td></tr>
            <tr>
                <td colspan="4" style="text-align: center">
                    {!!$model->label("last_complete_update")!!}
                    {!!$model->input("last_complete_update")!!}
                </td>
            </tr>                                                         
        </table>
        <script>
            $(function(){
                //HASH OF THE INITIAL DATA:
                $('#mainform_container').off("focusout.hash"); 
                $('#mainform_container').on("focusout.hash","#patient_name,#patient_surname,[name='birth_date']",function(){
                    if(!$("[name='birth_date']").attr("readonly")){
                        var inputs=[$("#patient_surname"),$("#patient_name"),$("[name='birth_date']")];
                        if(inputs[0].val() && inputs[1].val() && inputs[2].val()){
                            $("[name='code']").val(patientHash(inputs[0].val()+inputs[1].val()+inputs[2].val().toString()));
                        }else{
                            {$("[name='code']").val("");}
                        } 
                    }
                });
                //OLD CENTERS SHOW/LOAD:
                var oldCentersLoad=function (){
                    $('#oldCenterTarget').show();
                    SVL.load({method:'get',target:$('#subform_container'),targetMode:"fill",filter:".forms_table",url:'/edits/Patients_oldcenter/{{$model->id}}/end_date'});                    
                }
                $('#oldCentersLink').on('click',function(e){
                    e.preventDefault();
                    oldCentersLoad();    
                    $(this).remove();//disposable open link 
                }); 
            });
        </script>
    @endif
@endsection