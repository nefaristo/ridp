//RDP.update({container:"#mainform_container",repeaters:"form",
var RDP = {   
    settings:{mode:"debug",generic_css_class: "sv_allInfo",fadeTime:333},
    infoTypes:{//the order influences the overposition of boxes. TTL of messages is < warnings is < errors, so errors first messages last
        errors:{css_class:"sv_error",TTL:10000,},
        warnings:{css_class:"sv_warning",TTL:5000,},
        messages:{css_class:"sv_info",TTL:2000,},
    },
    update: function (params) {//if objMethod defined, updates after
        var $container = (params.container instanceof jQuery)?params.container:$(params.container).first();//data source
        var $repeaters = ("repeater" in params) ? $container.find(params.repeater) : $container;
        var objMethod = params.objMethod;
        var responseType = (("responseType" in params) ? params.responseType : "json");
        var $statusBox = ("$statusBox" in params) ? $(params.$statusBox) : null;
        //PREPARES DATA: one operation for each repeater row (or one for the whole container):
        var ops = [];var abort=false;    
        $repeaters.each(function (index, row) { 
            if(objMethod=="delete" && $(this).attr("data-confirm_delete").length>0){
                abort=abort || !confirm($(this).attr("data-confirm_delete"));
            }
            if(row) ops.push({
                i: index,
                objClass: $(this).attr('data-class'),//defined in html form
                objMethod: objMethod,
                objData: $(this).find(":not(form)").serialize(),//exclude subforms
            });            
        })
        if(abort) return false;
        if ($statusBox) {
            $status=$("<div></div>").width(Math.max($statusBox.width(),20)).height(Math.max($statusBox.height(),20))
                .css("background-image", "url('/images/ajax-loader-small.gif')").css("background-size","cover");
            $statusBox.replaceWith($status);
            //var backupBGI = $statusBox.css("background-image");
            //$statusBox.css("background-image", "url('/images/ajax-loader-small.gif')");
        }
        var sent={ 
            _token: $repeaters.first().attr("data-csrf-token"), //laravel CSRF - not a global one because that might not be refreshed for a long time
            responseType: responseType,
            ops: ops,
        }
        if(RDP.settings.mode==="debug"){
            console.log("parameters",params);
            console.log("container:",$container,"repeaters:");
            console.log("sent:", sent);
        }
        //ACTUAL POST: 
        if(sent._token)$.post("/ops", sent)
        .done(function (data) {
            if(RDP.settings.mode==="debug"){console.log("received:", data);}
            //MESSAGES & CHANGES:
            $container.find('.sv_error,.sv_warning').remove();//filtered content is cleared from old messages                
            var timeToWait=0;//max time to wait for messages to disappear to do other stuff
            $repeaters.each(function (index, row) {//loop through lines
                var $row = $(row);
                var dataRow = data.ops[index];                      
                //MESSAGES: also stores the longest time               
                if(typeof dataRow!=="undefined"){
                    var temp_ttw=RDP.placeMessages(dataRow, $row);               
                    if(timeToWait<temp_ttw)timeToWait=temp_ttw;
                    //SUCCESSFUL OPS (PER REPEATER):                
                    if (dataRow.info.errors.length == 0) {//was if (dataRow.model.info.errors.length == 0) {
                        switch (dataRow.objMethod) {
                            case "D":case "delete"://successful delete:gives time to read message then removes form
                                $row.delay(RDP.infoTypes.messages.TTL).fadeOut(RDP.settings.fadeTime,function(){$(this).remove();});
                                break;
                            default: //successful save: remove changes from inputs       
                                $repeaters.find(":not(form)").children(".sv_input").removeClass("sv_changed");
                        }
                    }
                }
            })         
            //SUCCESSFUL OP (GLOBAL):
            //callback:
            if (data.summary.errors==0 && data.ops.length>0 && "afterDone" in  params) {
                //params.afterDone(data); if doesn't wait, otherwise:
                setTimeout(function(){params.afterDone(data)},timeToWait);
            };
            //"loading" restore:           
            if ($statusBox){
                if(data.summary.errors==0 && data.summary.messages>0){//wait for animations
                    $status.css("background-image", "url('/images/tick.png')");
                    setTimeout(
                        function (){$status.replaceWith($statusBox);},
                        //function (){$statusBox.css("background-image", backupBGI);},
                        RDP.infoTypes.messages.TTL
                    );//delay doesn't work with background
                }else{//w/o ops: no loading animation, just restores
                    $status.replaceWith($statusBox);
                    //$statusBox.css("background-image", backupBGI);
                }              
            }
        })
        .fail(function (data) {
            console.log(data);
            if ($statusBox)
                $status.replaceWith($statusBox);
                //$statusBox.css("background-image", backupBGI);
            RDP.message(data.responseText,{title:"server error:",type:"errors"});
        });    
        return false;
    },
    placeMessages: function (dataRow, $targetRow) {//single row, where to put field messages, where to put not addressed messages
        var result=0;//longest time to waix11 qt for messages to disappear
        $.each(RDP.infoTypes, function (type, metadata){//loop through types
            var messageBags=dataRow.info[type];//was: dataRow.model.info[type];??
            //takes the longest time
            if(messageBags.length==undefined){//jsoned laravel messagebags are []s (length==0) when empty, {}s (length==undefined) otherwise                
                if(result<RDP.infoTypes[type].TTL) result=RDP.infoTypes[type].TTL;
            }
            $.each(messageBags, function (name, messageBag) {//through single messageBag 
                //PREPARES VARIABLES FOR ADD/SELECT BOX: (NB exclude subforms)               
                var $input = $targetRow.find(":not(form) [data-field='" + name + "']:visible");//look for a field match. display=none<=>position is (0,0), so visible filter is added
                //if(!$input.length) $input=$targetRow.find(filter + " ." + name);//look for placeholder
                var $appendTo = $targetRow;
                var css = {position: "absolute", zIndex: "5"};             
                if ($input.length) {//field found: box is placed nearby it
                    var pos = $input.offset();
                    $.extend(css, {top: (pos.top + 10) + "px", left: (pos.left + 10) + "px"});
                } else {//field not found: box is the general one                  
                    if ($targetRow.find("." + RDP.settings.generic_css_class).length)
                        $appendTo = $targetRow.find("." + RDP.settings.generic_css_class);
                }
                //ACTUALLY ADD/SELECT BOX:
                var $box = $appendTo.find('.' + metadata.css_class + '.' + name);
                if ($box.length == 0) {
                    $box = $("<div class='" + metadata.css_class + " " + name + "'></div>");
                    $box.css(css)
                        .appendTo($appendTo)
                        .draggable().resizable()
                        .click(function () {
                            $(this).hide();
                        })
                }
                //ADDS CONTENT:
                $box.html(messageBag.join("<br>"));
                //SHOWING BOX:
                $box.stop(true, true).show(300);
                $box.delay(metadata.TTL).hide(RDP.settings.fadeTime);
            });
        });
        return result;
   },
    message: function (content,params) {//params:{title,type,TTL}
        var title="";if(params && "title" in params) title=params.title;
        var TTL=(params && "TTL" in params)?params.TTL:RDP.infoTypes[params.type].TTL;
        var $box = $("<div title='"+title+"' class='" + RDP.infoTypes[params.type].css_class + "'></div>")
            .css({
                position: "absolute", zIndex: "10",
                top: $(window).height()/2 -200, //left: (100) + "px",
                left: $(window).width()/2 - 200,
                "min-width": "200px",
                display: 'none',
            })
            .html(title + "<br>" + content)
            .draggable().resizable()
            .click(function () {
                $(this).hide();
            })
            .appendTo(document.body)
            .stop(true, true).show(400);
        $box.delay(RDP.infoTypes[params.type].TTL).hide(RDP.settings.fadeTime);
    },
    confirm: function(message,params){
        var title="";if(params && "title" in params) title=params.title;
        var $box = $("<div title='"+title+"' class='" + class_ + "'></div>")
            .css({
                style: "sv_confirm",
                position: "absolute", zIndex: "10",
                top: $(window).height()/2 -200, //left: (100) + "px",
                left: $(window).width()/2 - 200,
                "min-width": "200px",
                display: 'none',
            })
            .html((title?title + "<br>":"") + content)
            .dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                  "Delete all items": function() {
                    $( this ).dialog( "close" );
                  },
                  Cancel: function() {
                    $( this ).dialog( "close" );
                  }
                }
            })
            .appendTo(document.body);
    },
    refresh: function () {
//datepicker class=>datepickers:
//$(".datepicker").datepicker($.datepicker.regional[ "it" ]).datepicker("option", "dateFormat", "dd/mm/yy" );
        //disables submit on keypress in text inputs:    
        $(document).off('keyup.nosubmit keypress.nosubmit', '.sv_input');
        $(document).on('keyup.nosubmit keypress.nosubmit', '.sv_input', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        //hides immediately errors on focus on an input:
        $(document).off('focus.hideErrors');
        $(document).on('focus.hideErrors', '.sv_input', function () {
            $('.sv_error.' + $(this).attr('data-field')).stop(true, true).hide(RDP.settings.fadeTime);
            $('.sv_warning.' + $(this).attr('data-field')).stop(true, true).hide(RDP.settings.fadeTime);
        });
    },
    load: function(element,fieldType){//accepts selector|jquery|array of the two
        if(typeof fieldType=="undefined") fieldType="input";//or label
        if($.isArray(element)){
            element.each(function(i,el){RDP.load(el)});
        }else{
            $el=$(element);
        }
        var $form=$el.closest("form");
        $backup=$el;$el.css("opacity","0.5");
        $el.load("/field/"+$form.attr("data-class")+"/"+$el.attr("name")+"/"+fieldType,$form.find(":not(form)").serialize(),function(data,status){$el.css("opacity","1");});        
    },
}
$(function () {
    RDP.refresh();
})