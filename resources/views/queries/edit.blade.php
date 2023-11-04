@extends('layouts.main')

@section('title', 'query edit')

@section('content') 
    <div class="section" style="text-align:center">{!!$title!!}</div> 
    {!!$debug!!}
<a id="rec_infoclick" style="font-size:xx-small">record object info</a>&nbsp;&nbsp;&nbsp;
<a id="edi_infoclick" style="font-size:xx-small">editor object info</a>
<a style="font-size:xx-small" onClick="$('#recordForm [name=\'sql_text\']').toggle();">record sql</a>
<details open>
    <summary></summary> 
    <div id="info"></div>
</details>
    <div id="record" style="border-radius:4px;border:1px silver solid;padding:3px;"> 
        {!!$model->openForm()!!}
        {!!$model->input("id")!!}{!!Collective\Html\FormFacade::input("hidden","_last_update_session",session("id"))!!}        
            <table>
                <tr>
                    <td>{!!$model->label("name")!!} {!!$model->input("name")!!}</td>
                    <td>{!!$model->label("description")!!} {!!$model->input("description",["style"=>"width:500px;"])!!}</td>                    
                    <td></td>
                    <td>
                        {!!\App\Libs\utils::Button(["type"=>"save","model"=>$model,"section"=>"subform"])!!}
                        {!!\App\Libs\utils::Button(["type"=>"delete","model"=>$model,"section"=>"subform"])!!}                        
                    </td>
                </tr>
            </table> 
        {!!$model->input("sql_text",["style"=>"width:100%;display:none"])!!}        
        {!!$model->closeForm()!!} 
        {!!App\Libs\utils::ErrorsDiv($errors)!!} 
    </div>                    
    <div id="editor" style="border-radius:4px;border:1px silver solid;padding:20px;overflow:auto">
    </div> 
    <div class="quedi_section_container">
        <div class='quedi_section_header'>
            <span id="check" style="padding:2px;"></span>&nbsp;&nbsp;&nbsp;
            <a id='preview_button'>{{trans('rdp.query_preview')}}</a>&nbsp;&nbsp;&nbsp;

        </div>
        <div id="preview" style="padding:20px;overflow:scroll;max-height:500px;"></div>
    </div> 
    <script>
        var test=0;
        var quedi;//the main query editor
        $(function(){
            quedi= new SVL.QueryEditor({//not putting var = this. = global
                action:'https://www.ridp.it/queries/editor',
                request:$("#recordForm [name='sql_text']").first().val(),//default is sql in the db record
                container:$('#editor'),
                done:function(){editor2check();},
                changed:function(){editor2check();editor2sql();},
            });
            quedi.load();
            
            function editor2check(){ 
                var target=$("#editor .quedi").first();                
                if(target){
                    target.css('border-color','yellow');
                    var post=$.post("https://www.ridp.it/queries/info/check",{_token:token,quedi_sql:SVL.request2sql($('#editor')),})
                    .done(function(){
                        var response=post.responseText;
                        $("#check").html(response>-1?'{{trans("rdp.record_count")}}: '+response:'{{trans("rdp.query_result_not_valid_sql")}}');
                        target.css('border-color',(post.responseText>-1)?'green':'red');
                    })
                    .fail(function(){target.css('style','border-color:grey');})
                }
            } 
            
            function editor2sql(){
                var $target=$("#recordForm [name='sql_text']");
                var backup=$target.val();
                var post=$.post("https://www.ridp.it/queries/info/sql",{_token:token,quedi_sql:SVL.request2sql($('#editor')),})
                .done(function(){$target.val(post.responseText).trigger('change.sv_input');})
                .fail(function(){$target.val(backup);})              
            }
            
            function editor2preview(){
                $('#preview').html(SVL.defaults.loading);
                var a=$.post("https://www.ridp.it/queries/preview",{_token:token,quedi_sql:SVL.request2sql($('#editor')),})
                .done(function(){$('#preview').html(a.responseText);})
                .fail(function(){$('#preview').html(a.responseText);})                
            }
            
            function editor2download(){
                var id=$("#recordForm [name='id']").val();
                if(id){
                    $.ajax({
                        url: "https://www.ridp.it/queries/download"+id,
                        method:"GET",
                        async:true,
                        headers:{ 'Accept': 'text/csv',
                                  'Content-Type': 'text/csv' },
                        data: {_token:token,quedi_sql:SVL.request2sql($('#editor')),name:$('#recordForm [name=\'name\']').val()},
                    })
                    .done(function(data) {
                        window.open(data); 
                //$("#frame").attr("src","https://www.ridp.it/queries/info/check");
                    })
                    .always(function(data, textStatus, errorThrown){
                        $("#preview").html(data.responseText);
                        console.log(data);
                        console.log(textStatus);
                        console.log(errorThrown);
                    });  
                }
            }            
            //temp:
            $("body")
                .on('click','#preview_button',function(){
                    editor2preview();
                })
                .on('click','#download_button',function(){
                    editor2download();
                })                
                .on('click',"#rec_infoclick",function(){
                    $('#info').html(SVL.defaults.loading);
                    var a=$.post("https://www.ridp.it/queries/info/debug",{_token:token,quedi_sql:SVL.request2sql($("#recordForm [name='sql_text']").val()),})
                    .done(function(){$('#info').html(a.responseText);})
                    .fail(function(){$('#info').html(a.responseText);})
                })
                .on('click',"#edi_infoclick",function(){
                    $('#info').html(SVL.defaults.loading);
                    var a=$.post("https://www.ridp.it/queries/info/debug",{_token:token,quedi_sql:SVL.request2sql($('#editor')),})
                    .done(function(){$('#info').html(a.responseText);})
                    .fail(function(){$('#info').html(a.responseText);})
                })
                ; 
                /*
                $("body").on('click','.quedi_toggle_section_header',function(){
                    $(this).next().first().slideToggle(300);
                });
                */
        });
    </script>    

@endsection