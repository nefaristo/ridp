@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null)
        <div class="forms_table">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label("date");!!}</div>
                    <div class="forms_td">{!!$newModel->label("type");!!}</div>
                    <div class="forms_td">{!!$newModel->label("symptoms");!!}</div>
                    <div class="forms_td"> </div>
                </div>
            </div>    
            <div class="forms_tbody">                
                @foreach($models as $model)            
                    {!!$model->openForm(['class'=>'forms_tr'])!!}
                        {!!$model->input("id"); !!}   
                        <div class="forms_td">{!!$model->input("date",[],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">
                            {!!$model->input("type",[],["placeholder"=>"-"]);!!}
                            <br>
                            {!!$model->input("specifics",[],["placeholder"=>"(".trans("rdp.no_specifics").")"]);!!}
                        </div>
                        <div class="forms_td">
                            {!!$model->input("symptoms",["style"=>"height:240px;"],["placeholder"=>trans("rdp.select_symptoms")]);!!}
                            <br>
                            {!!$model->input("symptoms_specifics",[],["placeholder"=>"(".trans("rdp.no_specifics").")"]);!!}
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
                        <div class="forms_tr" style="height:20px;margin-bottom:10px; border-bottom:solid grey 1px"></div>
                    {!!$newModel->openForm(['class'=>'forms_tr new_line'])!!}
                        {!!$newModel->input("id"); !!}
                        {!!$newModel->input("parent"); !!}
                        <div class="forms_td">
                            {!!$newModel->input("date");!!}
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input("type");!!}
                            <br>                            
                            {!!$newModel->input("specifics",[],["placeholder"=>$newModel->name("specifics")]);!!}
                        </div>
                        <div class="forms_td"> 
                            {!!$newModel->input("symptoms",["style"=>"height:240px;"],["placeholder"=>trans("rdp.select_symptoms")]);!!}
                            <br>
                            {!!$newModel->input("symptoms_specifics",[],["placeholder"=>$newModel->name("symptoms_specifics")]);!!}
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
                $(".sv_multiple_implode").each(function(){//add text w/ lists
                    var newEl=$("<span style='height:24px;' class='sv_multiple_imploded'></span>");
                    $(this).after(newEl);
                    $(this).css("position","absolute");
                    newEl.text(multiple2text($(this)));
                    $(this).hide();
                });
                $(".sv_multiple_imploded").on("click",function(e){//switch visibility
                    $(this).hide();
                    $(this).prev().css({'top':e.pageY-50,'left':e.pageX, 'position':'absolute'});
                    $(this).prev().show();
                });
                $(".sv_multiple_implode").on("keyup keypress blur",function(e){//refresh & switch visibility                
                    $(this).next().text(multiple2text($(this)));
                    $(this).hide();$(this).next().show();
                    return false;
                });
                function multiple2text(element){
                    var items = [];
                    element.find('option:selected').each(function(){ items.push($(this).text().toLowerCase()); });
                    return (items.length>0)?items.join(','):'{{trans("rdp.add")}}';
                }
            });
        </script>
    @endif
@endsection