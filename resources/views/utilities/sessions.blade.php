@extends('layouts.main_no_header')
@section('content')
        <div id="log_list">
            {!!$content!!}
        </div>
        <div id="dialog" title="-" style=";max-height: 50vh;">
            <p></p>
        </div>
        <script>
            $(function(){            
                $( "#log_list" ).on( "click",".icon_button.rightarrow", function(e) {
                    e.preventDefault();e.stopPropagation()
                    id=$(this).prev().html();
                    //$new=$("#dialog").clone(true); 
                    $new=$("#dialog"); 
                    $pos = $(this);
                    dialog=$new.dialog({
                        autoOpen: false,
                        modal: true,
                        position: { my: "left center", at: "right center", of: $(this)},
                        height: "auto",width:"50vw",
                        title:"{!!trans('rdp.operations')!!} - {!!trans('rdp.session')!!} "+id,
                    }); 
                    console.log(dialog);
                    dialog.dialog("open");
                    dialog.html(SVL.defaults.loading)
                    .load("/sessionLog/"+id);
                });                
            })
        </script>
@endsection

