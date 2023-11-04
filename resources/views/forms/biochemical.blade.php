@extends('forms.common.subform')
@section('form-data')
    @if ($model!=null) 
        <?php $short=["style"=>"width:60px;"];$medium=["style"=>"width:80px;"];?>
        {!!$model->input("parent")!!}
        <div>
            {!! trans("rdp.biochemical") ." - ". $model->htmlFormCompletion()!!}
        </div>
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>{!!$model->label("ap")!!}</td>
                            <td>{!!$model->input("ap",$medium)!!}</td>
                        </tr><tr>                        
                            <td>{!!$model->label("pth")!!}</td>
                            <td>{!!$model->input("pth",$medium)!!}</td>  
                        </tr><tr>
                            <td>{!!$model->label("ca")!!}</td>
                            <td>{!!$model->input("ca",$medium)!!}</td>
                        </tr><tr>
                            <td>{!!$model->label("p");!!}</td>
                            <td>{!!$model->input("p",$medium)!!}</td>                
                        </tr><tr>
                            <td>{!!$model->label("hco3")!!}</td>
                            <td>{!!$model->input("hco3",$medium)!!}</td> 
                        </tr><tr><td colspan="2"><hr></td><tr></tr>
                            <td>{!!$model->label("serum_creatinine")!!}</td>
                            <td>{!!$model->input("serum_creatinine",$medium)!!}</td>
                        </tr><tr>    
                            <td>{!!$model->label("urea")!!}</td>
                            <td>{!!$model->input("urea",$medium)!!}</td>                                                       
                        </tr><tr><td colspan="2"><hr></td></tr><tr>
                            <td>{!!$model->label("serum_protein");!!}</td>                        
                            <td>{!!$model->input("serum_protein",$medium)!!}</td>                
                        </tr><tr>    
                            <td>{!!$model->label("albuminemia");!!}</td>
                            <td>{!!$model->input("albuminemia",$medium)!!}</td>                                
                        </tr><tr>    
                            <td>{!!$model->label("haemoglobin")!!}</td>
                            <td>{!!$model->input("haemoglobin",$medium)!!}</td>
                        </tr>
                    </table>
                </td>
                <td style="border-left:1px solid grey; padding-left:5px;margin-left:5px;">   
                    <table>
                        <tr>
                            <td>{!!$model->label("renal_per_week_ktv_urea")!!}</td>
                            <td>{!!$model->input("renal_per_week_ktv_urea",$medium)!!}</td>
                        </tr><tr>
                            <td>{!!$model->label("dialytic_per_week_ktv_urea")!!}</td>
                            <td>{!!$model->input("dialytic_per_week_ktv_urea",$medium)!!}</td>
                        </tr><tr>                                            
                            <td>{!!$model->label("renal_creatinine_clearance")!!}</td>
                            <td>{!!$model->input("renal_creatinine_clearance",$medium)!!}</td>
                        </tr><tr>                        
                            <td style="max-width:100px;" >{!!$model->label("dialytic_creatinine_clearance")!!}</td>
                            <td>{!!$model->input("dialytic_creatinine_clearance",$medium)!!}</td>
                        </tr><tr>                         
                            <td>{!!$model->label("ultrafiltration")!!}</td>
                            <td>{!!$model->input("ultrafiltration",$medium)!!}</td>                
                        </tr><tr>
                            <td>{!!$model->label("diuresis")!!}</td>
                            <td>{!!$model->input("diuresis",$medium)!!}</td>
                        </tr><tr><td colspan="2"><hr></td></tr><tr>                        
                            <td>{!!$model->label("hcv");!!}</td>
                            <td>{!!$model->input("hcv",$medium)!!}</td>
                        </tr><tr>
                            <td>{!!$model->label("hb_s_ag");!!}</td>
                            <td>{!!$model->input("hb_s_ag",$medium)!!}</td>
                        </tr><tr>
                            <td>{!!$model->label("hiv")!!}</td>
                            <td>{!!$model->input("hiv",$medium)!!}</td>              
                        </tr>            
                    </table>
                </td>
            </tr>
        </table>   
    @endif
@endsection