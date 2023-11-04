<div id="model_header">
    <div style="height:5vh; display:flex;flex-direction:row;flex-wrap:nowrap;
    justify-content:space-around;align-items: center; align-content:space-around;">
        <div style="font-size:70%">

        </div>
        <div style="font-size: 110%;font-weight: bold;">
            Registro Italiano Dialisi Pediatrica
        </div>
        <div style="font-size:70%">
            <input type="button" value="x" name="closeWindowButton" onClick="window.close();" style="display: none"/>
        </div>
    </div> 
    <script>
        $(function(){
            if(window.opener!= null){console.log(window.opener);$("[name='closeWindowButton']").css("display","inline");}
        })
    </script>
</div>