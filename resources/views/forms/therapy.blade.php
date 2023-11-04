@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null)    
        <a id="copy_therapies_from_previous" title="{{trans('rdp.therapies_copy')}}">
                    <img src="/images/copy.png"> {{trans('rdp.therapies_copy')}}   
                    </a><span></span>
        <div class="forms_table"> 
            <div class="forms_thead"> 
                <div class="forms_tr"> 
                    <div class="forms_td">
                        {!!$newModel->label("type",trans("rdp.drug"));!!}
                    </div>
                    <div class="forms_td">
                        {!!$newModel->label("dose");!!}                         
                    </div>
                    <div class="forms_td"> </div>  
                </div>
            </div>    
            <div class="forms_tbody">
                @foreach($models as $model) 
                    {!!$model->openForm(["class"=>"forms_tr"])!!}
                            <div class="forms_td">
                                {!!$model->input("id"); !!}                              
                                <span class="type_spec_via_group">
                                    {!!$model->group("type_spec_via_group",NULL);!!}
                                </span>
                            </div>
                            <div class="forms_td">                                      
                                <span class="dose_group">                                    
                                    {!!$model->group("dose_group",NULL);!!}
                                </span>                                                                
                            </div> 
                            <div class="forms_td" style="display:flex">
                                <div>
                                    <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                                </div><div>  
                                    <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                                </div>
                            </div>             
                    {!!$model->closeForm()!!}   
                @endforeach                      
                @if($newModel->permissions()["M"]) 
                    <div class="forms_tr"><div class="forms_td" style="border:0px">
                            {{trans("rdp.add")}}
                    </div></div>
                    {!!$newModel->openForm(["class"=>"forms_tr new_line"])!!}                                               
                        <div class="forms_td">
                            {!!$newModel->input("id"); !!} {!!$newModel->input("parent"); !!}                              
                            <span class="type_spec_via_group newModel">
                                {!!$newModel->group("type_spec_via_group",NULL,true);!!}
                            </span>
                        </div>
                        <div class="forms_td">                                      
                            <span class="dose_group newModel">                                    
                                {!!$newModel->group("dose_group",NULL,true);!!}
                            </span>                                                                
                        </div>  
                        <div class="forms_td">
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div> 
                        {!!$newModel->closeForm()!!}                        
                @endif
            </div>
        </div>
        <script>
            $(function(){
                var updateGroup=function($element){
                    var dataOut=$element.closest('form').serialize();
                    var name=$element.attr('name');//for later
                    var groupName="dose_group";
                    var $group=$element.closest("."+groupName).addBack("."+groupName);               
                    if($group.length==0){
                        groupName="type_spec_via_group";$group=$element.closest("."+groupName).addBack("."+groupName);
                    }  
                    $group.html(SVL.defaults.loading);
                    var name_new=(name ? ("/"+ name) :"/NULL") + ($group.hasClass('newModel')?"/1":"/0");//newmodel w/o placeholder
                    $.post('/modelMethod/Therapy/group/' + groupName + name_new,dataOut)
                        .done(function(data){
                            $group.html(data);$group.find
                            $group.find('input,select').each(function(){$(this).trigger('change.sv_input').trigger('change.ph');})
                            if(groupName=="type_spec_via_group"){updateGroup($group.closest(".forms_tr").find(".dose_group"));}
                        })
                        .fail(function(data){RDP.message(data.responseText,{title:"update therapy group",style:"error"});})                  
                }
                $(".dose_group,.type_spec_via_group").on("change.inputGroup","input,select",function(){           
                    updateGroup($(this));
                    //RDP.load($(this));
                });    
                $("#copy_therapies_from_previous").on('click',function(e){
                    e.preventDefault();
                    $form=$("#mainform_container form").first();
                    console.log($form,$form.attr("action"));
                    if($form){
                        $(this).next().show().html(SVL.defaults.loading);
                        //alert($form.find("[name='id']").val());
                        console.log("sending",$form.serializeArray());
                        $.ajax({
                            url:'/modelMethod/follow_up/copyTherapies',                            
                            data:$form.serializeArray(), //filter:document,
                            dataType: "json",
                            $serviceTarget:$(this).next(),
                        })
                        .done(function(data){
                            //console.log("data",data);  
                            this.$serviceTarget.html(data.message);                            
                        })
                        .fail(function(xhr, status, error){
                            this.$serviceTarget.html( status);    
                            //this.serviceTarget.html("ERror:"+data.responseText);
                        })
                        .always(function(data){
                            //this.serviceTarget.html(this.backup);
                            loadSubForm($('#tab_therapy'));
                            this.$serviceTarget.delay(5000).fadeOut();
                        });
                    };
                    //
                    $refresh_target= $(this).parents("#subform_target");
                });
            });
        </script>
    @endif
@endsection