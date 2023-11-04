@extends('layouts.main_content_only')
@section('otherHeads')
    <script src="{{URL::asset('js/rdp_hash.js')}}?<?php echo time(); ?>"></script>
@endsection
@section('content')        
    {{$patients->count()}} patients 
    <input type="button" id="convert_all" value="encode">
    <input type="button" id="deconvert_all" value="decode">
    <input type="button" id="save_all" value="save">
    <!--<input type="button" id="export_all" value="export to excel">-->
    <?php $patLimit=2000;//2000 is ok for the real thing
    $i=0;?>        
    <a id="refresh" onclick="location.reload();return false;" style="display:none">refresh</a>
    <div style="height:300px; overflow: auto;">
        <div id="conversion_table" style="display:table">
            <div style="display:table-row">                    
                <div style="display:table-cell;border: 1px grey solid">id</div>
                <div style="display:table-cell;border: 1px grey solid;">old code</div>
                <div style="display:table-cell;border: 1px grey solid;">encoding source</div>
                <div style="display:table-cell;border: 1px grey solid;">new code</div>
                <div style="display:table-cell;border: 1px grey solid;"></div>
            </div>
            <div style="display:table-row-group;height:200px;overflow:auto"> 
                <?php $i=0;?>                        
                @foreach($patients as $pat)
                    <?php if($i++<=$patLimit){?>    
                        {{$pat->openForm(["id"=>$pat->id,"class"=>"conversion_table_row","style"=>"display:table-row"])}}                      
                            <div style="display:table-cell;border: 1px grey solid">                                    
                                <input type="text" name="id" value="{{$pat->id}}" class="sv_input" readonly>                                    
                            </div>
                            <div class="user_old_code" style="display:table-cell;border: 1px grey solid">                                    
                                <input type="text" name="old_code" readonly class="sv_input" value="{{$pat->old_code}}">
                            </div>
                            <div style="display:table-cell;border: 1px grey solid">                                    
                                <span name="pre_code">{{$pat->old_code?(substr($pat->old_code,0,4).substr($pat->birth_date,0,10)):""}}</span>
                            </div>
                            <div class="user_code" style="display:table-cell;border: 1px grey solid">                            
                                <input type="text" name="code"  class="sv_input" value="{{$pat->code}}">
                            </div>     
                            <div class="result sv_error" style="display:table-cell;border: 1px grey solid;width:20px"></div>
                        {{$pat->closeForm()}}
                    <?php }?>                            
                @endforeach
            </div> 
        </div>
    </div>
    <script>
        $(function(){
            //JS CONVERT/DECONVERT
            $("#convert_all").on('click.convert',function(e){
                e.preventDefault(); e.stopPropagation();   
                $("#conversion_table").find(".conversion_table_row").each(function(){  
                    var oldcode=$(this).find("[name='old_code']").val();//old if present
                    var precode=$(this).find("[name='pre_code']").html();//source of encryption
                    var code=$(this).find("[name='code']").val();//code as it is in the db
                    var newcode=(precode.length>0)?patientHash(precode,10):code;//new code (db code if oldcode is empty, encrypted precode otherwise)                        
                    console.log((oldcode?("encrypted:" +oldcode+"=>"+precode+"=>"):"untouched: ") + newcode);
                    $(this).find("[name='code']").val(newcode);
                });
            });
            $("#deconvert_all").on('click.deconvert',function(e){
                e.preventDefault(); e.stopPropagation();   
                $("#conversion_table").find(".conversion_table_row").each(function(){  
                    var oldcode=$(this).find("[name='old_code']").val();//old if present
                    var code=$(this).find("[name='code']").val();//code as it is in the db
                    var newcode=(oldcode.length>0)?oldcode:code;//old code where present
                    console.log((oldcode?("retrieved:" +oldcode+"=>"):"untouched: ")+newcode);
                    $(this).find("[name='code']").val(newcode);
                });
            });                
            //JS SAVE
            var saveCounter=0;
            function saveEncodingRow($row,maxRows){
                if($row && --maxRows>0){
                    return RDP.update({
                        container:$row,
                        objMethod:"save",
                        $statusBox:$row.find(".result"),
                        afterDone:function(data){
                            console.log("Row " + (saveCounter++) + " saved correctly.")
                            if($row.next())saveEncodingRow($row.next(),maxRows)
                        },
                    });
                }
            }
            //button=all, error=from that row on
            $("#save_all").on('click.encode',function(e){
                e.preventDefault(); e.stopPropagation();                    
                saveEncodingRow($("#conversion_table .conversion_table_row").first(),{{$patLimit}});
            });               
            $(".conversion_table_row").on('click.save_start','.sv_error',function(e){
                e.preventDefault(); e.stopPropagation();                    
                saveEncodingRow($(this).closest(".conversion_table_row").first(),{{$patLimit}});
            });
        })
    </script>           
@endsection
