@extends('forms.common.subforms')
@section('form-data')
    @if ($model!=null) 
        <table class="form">
            <tr>
                <td colspan='6' style="text-align:center ">
                    {!!DBUtils::label("patients.birth_province");!!}
                    {!!DBUtils::select("patients.birth_province",null,$model->birth_province,['style'=>'width:100%;']);!!}
                    {!!DBUtils::label("patients.birth_date");!!}
                    {!!DBUtils::input("date","patients.birth_date",$model->birth_date);!!}
                </td>
            </tr>
            <tr><td colspan="4"><hr></td></tr>
        </table>
        <script>
            $(function(){
                //after_update($("[name='end_date']"));
                $(".sv_input").change(function(){
                   after_update($(this));
                });
                function after_update(element){
                    switch(element.attr("name")){}
                }
            });
        </script>
    @endif
@endsection