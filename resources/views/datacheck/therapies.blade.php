@extends('layouts.main')
@section('content')
    <div id="log" style="display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start">    
        <div style="font-size:110%;margin: auto;">
            <b>Utility correzione terapie </b>            
        </div>
        <div style="font-size:110%;margin: auto;">
            <button onClick='$("#help").toggle();' style="font-size: 100%;font-weight: bold">Read me first</button>
        </div>        
        <div id="help" style='display: none'>
            <span style="font-size:100%;">                
                <hr style="width:80%;float:center">                
                ➤ L'utility (visibile solo da segreteria) cerca il farmaco scritto in specifica terapie e aggiunge all'osservazione la terapia scelta da elenco. Se non viene scelta una terapia , la parola cercata viene cancellata dalle specifiche senza aggiungere terapie.<br>
                ➤ Le ricerche non modificano il database e si possono provare liberamente, ma il tasto rosso <b>"PROCEDI"</b>, che compare dopo una ricerca, <u>implica modifiche e cancellazioni di record multipli</u>, non semplicemente reversibili; quindi va usato con molta cautela e dopo aver controllato bene la preview.<br>Le terapie non selezionate con la casella di spunta non verranno comunque modificate.<br>
                ➤ La terapia non viene comunque aggiunta più di una volta per osservazione, quindi ad es. la sostituzione dei <b>sinonimi</b> o nomi commerciali (es "cinacalcet" e "mimpara" sost. da "Cincacalcet" in elenco) si può anche fare in due momenti diversi. <br>
                ➤ Fanno eccezione i nomi scritti con varie <b>abbreviazioni</b>: "acido folico" è presente anche come "a. folico",  "ac. folico" eccetera - sentiamoci prima di questo tipo di sostituzione.<br>
                ➤ Nei rarissimi casi in cui nella specifica accanto al farmaco compaia anche la <b>dose</b>, volendola copiare è bene fare l'operazione manualmente e non automaticamente, usando i link alle osservazioni che compaiono cliccando su "dettagli". <br>                
                ➤ Ignora pure eventuali virgole, "+" e congiunzioni rimanenti, le si può togliere a fine operazioni.
                <hr style="width:80%;float:center">
            </span>
        </div> 
        <hr>
        <form id="dataContainer">
            {!!Form::token()!!} 
            <div  class="ctrl_panel" style="border:1px; border-style: outset; ">
                <table>
                    <tr>
                         <td>Espressioni da cercare nelle specifiche</td><td>
                             <span class="newLine"><input name="text[]"><br></span>
                         </td>
                     </tr>
                     <tr>
                         <td>Terapia da sostituire:</td><td>{!!$drugSelect!!}</td>
                     </tr>
                     <tr>                                                
                         <td><button style="" id="find">CERCA </button></td>                        
                         <td></td>                         
                     </tr>

                 </table>  
             </div>
             <div id="results"></div>
        </form>
    </div>
    <script>
        $(function(){
            $("span.newLine").on("change","input",function(e){//change text search=>reset + [add one line]
                console.log($(this).parent() , $(this).parent().next(), $(this).parent().next().hasClass("expRow"));
                if($(this).val()){
                    $(this).parent().clone(true,true).insertAfter($(this).parent()).children("input").val("").focus();
                    $(this).parent().removeClass("newLine");$(this).parent().off("change","input");
                    //reset();
                }                
            })
            $("#dataContainer").on("change","[name='text[]'],[name='drug'],[name='details']",function(e){reset();callServer("list");})
            $("#find").on("click",function(e){   
                e.preventDefault();callServer("list");
            });
            $("#dataContainer").on("click",".toggle", function(e){//(buttons & checkboxes written by server routine)
                e.preventDefault();
                console.log("H",$(this),$(this).id,$(this).prop("id"));
                selectEm=($(this).text()=="☑");    
                console.log("J",$("#results").find("[name='"+$(this).prop("id")+"[]']"));
                $("#results").find("[name='"+$(this).prop("id")+"[]']").prop( "checked", selectEm);
                $(this).text((selectEm?"☐":"☑"));
            })
            $("#dataContainer").on("click",".details", function(e){//(buttons & checkboxes written by server routine)
                e.preventDefault();
                callServer("details","detailsOf="+$(this).val(),$(this).parent());
            })
             $("#dataContainer").on("click","#proceed",function(e){   
                e.preventDefault(); 
                message="L\'operazione cancellerà il testo evidenziato dalla specifica delle terapie selezionate " + ($("#drug").children(":selected").val()?"aggiungendo all'osservazione la terapia \'" + $("#drug").children(":selected").text() + "\'.":"senza sostituirla con alcuna terapia.")+ " Continuare?";
                if($("#results").html() && confirm(message))
                    callServer("proceed");
            });                        
            function reset(){
                $("#results").html("");
            }
            function callServer(op,params,$target){
                dataOut=$("#dataContainer").serialize()+(op?("&op="+op):"")+(params?("&"+params):"");
                console.log("PRE CALL ",$("#dataContainer").serialize());
                if($target===undefined){$target=$("#results");}
                $target.html(SVL.defaults.loading);
                $.post("therapies",dataOut)
                .done(function(data){
                    $target.html(data.html);
                })
                .fail(function(data){
                    $target.html(data.responseText);
                })
            }
        })
    </script>
@endsection