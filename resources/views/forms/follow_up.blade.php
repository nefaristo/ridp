@extends('forms.common.form')
@section('form-data')
    @if ($model!=null)  
        {!!$model->input("parent"); !!}
        <table>
            <tr>
                <td>{!!$model->label("date");!!}</td>                    
                <td>{!!$model->input("date");!!}</td>
                <td>{!!$model->label("time");!!}</td>
                <td>{!!$model->input("time");!!}</td>                
                <td>{!!$model->label("transplantation_list");!!}</td>
                <td>{!!$model->input("transplantation_list");!!}</td>
            </tr>
            <tr><td>
                
            </td></tr>
        </table>        
    @endif
@endsection
@section('subforms')
    @if ($model && $model->id)
        <?php $children=$model->children();?>
        <ul class="sv_tabs">   
            @if($model->parentModel()->type=="PD" || $model->parentModel()->type=="HD")
                <li><a id="tab_clinical" onClick="return loadSubForm($(this));">{{trans('rdp.clinical')}}</a></li>
                <li><a id="tab_biochemical" onClick="return loadSubForm($(this));">{{trans('rdp.biochemical')}}</a></li>
                @if($model->parentModel()->type=="PD")
                    <li><a id="tab_pd_prescription" onClick="return loadSubForm($(this));">{{trans('rdp.pd_prescriptions')}}</a></li>
                @else
                    <li><a id="tab_hd_prescription" onClick="return loadSubForm($(this));">{{trans('rdp.hd_prescriptions')}}</a></li>
                @endif
            @endif 
            <li><a id="tab_therapy" onClick="return loadSubForm($(this));">{{trans('rdp.therapies')}}</a></li>                
        </ul>
        <div id="subform_target" style="width: 100%;overflow:auto"></div>
        <script>   
            function loadSubForm($element,url){; 
                if(SVL.exitConfirm($('#subform_container'))){
                    $element.closest("li").siblings().removeClass("sv_selected");
                    $element.closest("li").addClass("sv_selected");
                    switch($element.attr("id")){
                        case("tab_clinical"):
                            url='/edit/clinical/{{(($children["clinical"])?$children["clinical"]->id:"-")."/".$model->id}}';
                            break;
                        case("tab_biochemical"):
                            url='/edit/biochemical/{{(($children["biochemical"])?$children["biochemical"]->id:"-")."/".$model->id}}';
                            break;
                        case("tab_pd_prescription"):
                            url='/edit/pd_prescription/{{(($children["prescription"])?$children["prescription"]->id:"-")."/".$model->id}}';
                            break;
                        case("tab_hd_prescription"):
                            url='/edit/hd_prescription/{{(($children["prescription"])?$children["prescription"]->id:"-")."/".$model->id}}';                            
                            break;
                        case("tab_therapy"):
                            url='/edits/therapy/{{$model->id}}/id';
                            break;
                    }
                    if(url){SVL.load({target:$('#subform_target'),targetMode:"fill",filter:"#subform_container",method:'get',url:url});};
                }
                return false;
            }
            $(function(){
                
                loadSubForm($('#tab_clinical'));
            })           
        </script>
    @endif 
@endsection