@extends('forms.common.subform')
@section('form-data')
    @if ($model!=null)  
        <?php $fill=["style"=>"width:100%;"];$medium=["style"=>"width:60px;"];?>        
        {!!$model->input("parent")!!}
        <div>
            {!! trans("rdp.pd_prescriptions") ." - ". $model->htmlFormCompletion()!!}
        </div>
        <table>
            <tr>
                <td colspan="4">{!!$model->label("loading_volume");!!}
                {!!$model->input("loading_volume",$medium)!!}
                {!!$model->label("standard_loading");!!}
                {!!$model->input("standard_loading",["style"=>"width:60px;","readonly"=>"readonly","tabindex"=>"-1"])!!}</td>
                <td>{!!$model->label("exchanges_number");!!}</td>
                <td>{!!$model->input("exchanges_number",$medium)!!}</td>
            </tr>
            <tr>
                <td>{!!$model->label("swab_type");!!}</td>
                <td>{!!$model->input("swab_type");!!}</td>                
                <td>{!!$model->label("average_glucose_concentration");!!}</td>
                <td>{!!$model->input("average_glucose_concentration",$medium)!!}</td>                
                <td>{!!$model->label("tidal_volume");!!}</td>
                <td>{!!$model->input("tidal_volume",$medium)!!}</td>
            </tr>
            <tr>
                <td>{!!$model->label("last_loading");!!}</td>                
                <td>{!!$model->input("last_loading",$medium)!!}</td>
                <td>{!!$model->label("solution_type");!!}</td>
                <td colspan="3">{!!$model->input("solution_type");!!}</td>                              
            </tr>                
            <tr>    
                <td>{!!$model->label("dialysis_duration");!!}</td>
                <td>{!!$model->input("dialysis_duration",$medium)!!}</td>  
                <td colspan="2">{!!$model->label("apd_peritoneal_fluid_discharge");!!}</td>
                <td>{!!$model->input("apd_peritoneal_fluid_discharge",$medium);!!}</td>                
            </tr>
        </table>
        <script>
            $(function(){
                var loadingRefresh=function(toVolume){//toVolume<=>from ml/m2 to ml
                    $standard=$('[name="standard_loading"]');$volume=$('[name="loading_volume"]');
                    if(toVolume){//initial DB=>UF value
                        $.post('/modelMethod/Pd_prescription/standard2volume',$standard.closest('form').serialize())
                            .done(function(data){$volume.val(data);})
                    }else{
                        if($volume.val()){//calculation on change UF value =>DB field
                            $.post('/modelMethod/Pd_prescription/volume2standard/'+$volume.val(),$standard.closest('form').serialize())
                                .done(function(data){
                                    $standard.val(data);
                                    if(!$standard.val()){
                                        RDP.message('{{trans("rdp.errors.pd_prescription_empty_bsa")}}');
                                        $('[name="loading_volume"]').val($('[name="loading_volume"]').attr('data-original-value'));
                                    }else{
                                        loadingRefresh(true);
                                    }
                                })                            
                        }
                    }                    
                }
                loadingRefresh(true);
                $('[name="standard_loading"]').closest('form').on('change',$('[name="loading_volume"]'),function(e){
                    loadingRefresh(false);
                })
                $('[name="standard_loading"]').closest('form').on('change',$('[name="standard_loading"]'),function(e){
                    loadingRefresh(true);
                })
            })
        </script>
    @endif
@endsection