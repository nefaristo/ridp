@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null && $parentModel->id)        
        <div class="forms_table" id="oldCenters">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label('center_id')!!}</div>
                    <div class="forms_td">{!!$newModel->label('end_date')!!}</div>
                    <div class="forms_td">
                        <!--<button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}"></button>-->
                    </div>
                </div>
            </div>
            <div class="forms_tbody">
                @foreach($models as $model)
                    {!!$model->openForm(["class"=>"forms_tr"])!!}
                        <div class="forms_td">       
                            <div class="sv_allInfo" style="width:50%;margin:5px auto"></div>
                            {!!$model->input('id',[],["placeholder"=>""])!!}
                            {!!$model->input('parent',["style"=>"display:none"],["placeholder"=>""])!!}
                            {!!$model->input('center_id',["style"=>"width:40vw;display:none"],["placeholder"=>""])!!}
                            {!!$model->input('other_center',[],["placeholder"=>""])!!}
                        </div>
                        <div class="forms_td">
                            {!!$model->input('end_date',[],["placeholder"=>""])!!}
                        </div>
                        <div class="forms_td">                       
                            <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>                            
                        </div>
                    {!!$model->closeForm()!!}
                @endforeach
                @if($newModel->permissions()["M"])
                    {!!$newModel->openForm(["class"=>"forms_tr new_line"])!!}                    
                        <div class="forms_td">
                            <div class="sv_allInfo" style="width:50%;margin:5px auto"></div>
                            {!!$newModel->input('id',[],["placeholder"=>""])!!}
                            {!!$newModel->input('parent',["value"=>$parentModel->id],["placeholder"=>""])!!}
                            {!!$newModel->input('center_id',["style"=>"width:40vw;"],["placeholder"=>""])!!}
                            {!!$newModel->input('other_center',["style"=>"width:100%;display:none"],["placeholder"=>""])!!}
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input('end_date')!!}
                        </div>
                        <div class="forms_td">                               
                            <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div>
                    {!!$newModel->closeForm()!!}
                @endif
            </div>
            <script>
                $(function(){
                    //IMMEDIATE:
                    //updates all lines:
                    $('#oldCenters .forms_tr').each(function(){oldCentersLineUpdate($(this));})
                    //EVENTS:
                    //overrides basic buttons:
                    $("#oldCenters .icon_button").off('click');
                    $("#oldCenters .icon_button").on('click',function(e){
                        e.preventDefault(); e.stopPropagation();
                        var objMethod=$(this).attr('data-op');  
                        console.log("patients oldcenters");
                        switch(objMethod){
                        case "save":
                            RDP.update({
                                container:$("#oldCenters"),repeater:".forms_tbody .forms_tr:not(.new_line)",
                                class:"Patients_oldcenter",objMethod:objMethod,
                                $statusBox:$(this),
                                afterDone:function(data){
                                    if (data.summary.errors==0)
                                       SVL.load({method:'get',target:$('#subform_container'),url:'/edits/Patients_oldcenter/{{$parentModel->id}}/end_date'});                                   
                                }
                            })
                            break;
                        case "delete":
                            $line=$(this).closest(".forms_tr:not(.new_line)");
                            if($line.length){
                                RDP.update({
                                    container:$line,
                                    class:"Patients_oldcenter",objMethod:objMethod,
                                    $statusBox:$(this),                        
                                })
                            }
                            break;
                        case "add":
                            RDP.update({
                                container:$(this).closest(".forms_tr.new_line"),
                                class:"Patients_oldcenter",objMethod:"save",
                                $statusBox:$(this), 
                                afterDone:function(data){
                                    if (data.ops.length>0 && data.summary.errors==0)                                        
                                        SVL.load({method:'get',target:$('#subform_container'),url:'/edits/Patients_oldcenter/{{$parentModel->id}}/end_date'});
                                }
                            })   
                            break; 
                        }                        
                    });
                    $("#oldCenters form:not(.new_line) .sv_input").on('focusout.autoSave',function(){
                        RDP.update({
                            container:$(this).closest("form"),
                            class:"Patients_oldcenter",objMethod:"save",                            
                        });
                    })                                            
                    //shows free old center choice (NB: delegated)
                    $('#oldCenters').on('change.center_id','.sv_input',function(){
                        oldCentersLineUpdate($(this).closest('.forms_tr'));
                    });                    
                    //FUNCTIONS:
                    function oldCentersLineUpdate($line){                
                        $in=$line.find("[name*='center_id']");//as in registry
                        $out=$line.find("[name*='other_center']");
                        if($in.val()){//registry val: hide&delete the non registry
                            $in.show();$out.hide();$out.val("");                        
                        }else{//non registry available + hide reg. if non registry compiled:
                            $out.show();$out.width($in.width());//same width
                            $in.css('display',$out.val()?'none':'inline-block');
                        }                    
                    }
                    function oldCentersLineAdd($line){
                        $newLine=$line.clone().removeClass('new_line').insertBefore($line);
                        $newLine.find('input, select').val('');
                        //oldCentersLineUpdate($newLine);
                        $line.find("[name='center_id']").val("")
                            .next("[name='other_center']").val("")
                            .next("[name='end_date']").val("");
                        oldCentersLineUpdate($line);
                    }                            
                })                
            </script>
        </div>        
    @endif
@endsection