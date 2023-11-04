@extends('forms.common.subform')
@section('form-data')
    @if ($model!=null)
        <?php $medium=["style"=>"width:60px;"];?>
        {!!$model->input("parent")!!}{!!$model->input("id")!!}
        <div>
            {!! trans("rdp.clinical") ." - ". $model->htmlFormCompletion()!!}
        </div>        
        <table><tr><td>
            <table>
                <tr>
                    <td>{!!$model->label("weight");!!}</td>
                    <td>{!!$model->input("weight",$medium)!!}</td>                
                    <td>{!!$model->label("height");!!}</td>
                    <td>{!!$model->input("height",$medium)!!}</td>                                
                    <td>{!!$model->label("ph");!!}</td>
                    <td>{!!$model->input("ph",$medium)!!}</td>
                    <td>{!!$model->label("bg");!!}</td>
                    <td>{!!$model->input("bg",$medium)!!}</td>                                
                    <td>{!!$model->label("bp_max");!!}</td>
                    <td>{!!$model->input("bp_max",$medium)!!}</td>                                
                    <td>{!!$model->label("bp_min");!!}</td>
                    <td>{!!$model->input("bp_min",$medium)!!}</td> 
                </tr>
                <tr>
                    <td>{!!$model->label("BSA");!!}</td>
                    <td><span id="BSA"></span></td>   
                </tr>
            </table>
        </td></tr></table>
        <script>                      
            $(function(){
                //bsa updated for weitht and height:
                var updateBSA=function(){
                    $.post('/modelMethod/Clinical/BSA',$("#BSA").closest('form').serialize())
                    .done(function(data){($("#BSA").html(data));console.log("data:",data);})                            
                }
                $("#subform_container").on("change.BSA","[name='weight'],[name='height']",function(e){
                    updateBSA();
                });
                updateBSA();
            })
        </script>
    @endif
@endsection