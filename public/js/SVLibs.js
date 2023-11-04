
$(function(){
    //1) FIX FOR JQUERY NOT DEEP-CLONING CHILDREN'S TEXTAREA AND SELECT VALUES:
    (function (original) {
        jQuery.fn.clone = function () {
          var result           = original.apply(this, arguments),
              my_textareas     = this.find('textarea').add(this.filter('textarea')),
              result_textareas = result.find('textarea').add(result.filter('textarea')),
              my_selects       = this.find('select').add(this.filter('select')),
              result_selects   = result.find('select').add(result.filter('select'));
          for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
          for (var i = 0, l = my_selects.length;   i < l; ++i) result_selects[i].selectedIndex = my_selects[i].selectedIndex;
          return result;
        };
    }) (jQuery.fn.clone);
    //2) EXTENSIONS 
    //FOR COPYING ATTRIBUTES (ALL AND ONLY)
    (function ($) { 
        // Define the function here
        $.fn.copyAllAttributes = function(sourceElement) {
            var that = this;// pointer to the destination element
            var allAttributes = ($(sourceElement) && $(sourceElement).length > 0) ?
                $(sourceElement).prop("attributes") : null;
            if (allAttributes && $(that) && $(that).length == 1) {
                $.each(allAttributes, function() {
                    // Ensure that class names are not copied but rather added
                    if (this.name == "class") {
                        $(that).addClass(this.value);
                    } else {
                        that.attr(this.name, this.value);
                    }

                });
            }
            return that;      
        }; 
        //serialize to object instead of serializeArray:
        $.fn.serializeObject = function () {            
            var o = {};
            var a = this.serializeArray();
            /* SVSV serializeArray works either for form's children or for single domobj, and this does accordingly.
             * In order to take children of non-form replace the 'var a...' line with the following two:
             * parent=(this.is('form')?this:this.children());//added SVSV 
             * var a = parent.serializeArray();//added SVSV
             */
            $.each(a, function () {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }      
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }                
            });
            return o;
        };
    })(jQuery);    
    //3) INIZIALIZATIONS:   
    SVL.reset();//for the whole document: sv_inputs, inputPlaceholders etc
    //4) EVENTS: sv_input class elements added sv_changed on change:
    $('body').off('change.sv_input','.sv_input');
    $('body').on('change.sv_input','.sv_input',function(){//sv_changed to element [and its form]
        if(SVL.value($(this))==$(this).attr("data-original-value")){
            $(this).removeClass('sv_changed');if($(this).closest('form'))$(this).closest('form').removeClass('sv_changed');
        }else{
            $(this).addClass('sv_changed');if($(this).closest('form'))$(this).closest('form').addClass('sv_changed');
        }   
    });

    //
}) 
var SVL={
    
    defaults:{
        loading:'<div class=\'sv_loader\'></div>',
        confirmOut:'the record has been changed. Do you really want to proceed?',
        toggleTime:333,//ms
    },
    
    load:function(params){//target is only non optional 
        var $target=("target" in params)?params.target:$(":root"); //TARGET default=all
        if((params.target).length==0){console.log("SVL.load: target missing from page",params.target);return false;}
        var $form=$target.is('form')?$target:($target.find('form')?$target.find('form').first():null);//FORM default=first form in target (itself included)|null
        var method=('method' in params)?params.method.toLowerCase():($form?$form.attr('method'):"post");//METHOD default=form|"post"
        var url=('url' in params)?params.url:($form?$form.attr('action'):null);//METHOD default=form|null
        var data=('data' in params)?params.data:((method=="post" && $form)?$form.serialize():null);//DATA default=form(if method=post)|null
        var backup=$target.html(); 
        loadRes=$.ajax({
            method:method,url:url,data:data,
            converters: {//override with original code commented to prevent execution of all page
                "text script":function(text){/*jQuery.globalEval( text );*/return text;}
            },
            beforeSend:function(){
                $target.html(('loading' in params)?params.loading:SVL.defaults.loading);
            },
        })
        .done(function(){
            //filter result by specified selector|by target id|dont' filter (whole response body) for
            $result=$(loadRes.responseText).find(('filter' in params)?params.filter:(($target.attr('id')!==undefined)?("#"+$target.attr('id')):document)).first();          
            if($result.length>0){
                if('targetMode' in params && params.targetMode=='fill'){//filling or replacement?
                    $target.html("");$target.append($result);
                }else{
                    $target.replaceWith($result);
                };    
            }else{
                $target.html("");
                console.log('SVL.load: empty result. target:',$target,'url:',url,'method:',method,"data:",data);
                console.log('raw response:',$(loadRes.responseText),
                "filtered by:",('filter' in params)?params.filter:(($target.attr('id')!==undefined)?("#"+$target.attr('id')):document),
                "result:",$result);
            }
            SVL.reset($target);//for events ecc. if there's some library element :
            if('afterDone' in params)params.afterDone(loadRes);
        })
        .fail(function() {                                
            $target.html("");
            $target.append($("<p></p>").html(('url' in params)?params.url:$form.attr("action")));
            $target.append($("<a>back</a>").click(function(){$target.html(backup);return false;}));
            $target.append($(loadRes.responseText));
            if('afterFail' in params)params.afterFail(loadRes);
        });
    },
    
    reset:function($target){
        if(typeof $target==='undefined')$target=$('body');
        //* "changed" removed from and values=>originalvalues|the other way around; 
        $target.find('form').addBack().each(function(index,element){$(this).removeClass('sv_changed');});
        $target.find('.sv_input').each(function(index,element){
            $(this).removeClass('sv_changed');            
            //if (typeof $(this).attr() !== typeof undefined && $(this).attr() !== false){// For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
            if ($(this).attr("data-original-value") !== undefined){
                $(this).val($(this).attr("data-original-value"));
            }else{
                $(this).attr("data-original-value",SVL.value($(this)));
            }
        });
        //* placeholder: link to a popup control
        SVL.inputPlaceholder('.sv_input_placeholder');//default inputPlaceholder class with default behaviour      
        SVL.details('.sv_pseudo_details');
    },        
    
    exitConfirm:function ($target){//
        if(typeof $target==='undefined')$target=$('body');
        if($target.is('form')){            
            return ($target.find('.sv_input.sv_changed').length==0) || confirm($target.attr('data-confirm_out')||SVL.defaults.confirmOut);
        }else{//recursive on children form: false if is no for even on subform           
            var res=true;
            $target.find('form').each(function(){res=res && SVL.exitConfirm($(this))});
            return res;
        }        
    },
    
    inputPlaceholder:function(selector,params){//hide selected content and show placeholder html, setting the switch
        //ie: "[name='id']", {htmlContent:function($placeholder),inputNull:function($input)}
        inputNull=(params && "inputNull" in params)?params.inputNull:
            function($input){return ($input.val()=='');};//by default input is empty when val=""
        htmlContent=(params && 'htmlContent' in params)?params.htmlContent:
            function($ph){//by default html is: the initial value for input=empty|an imploded array for input type=select|input value in other cases
                $input=$ph.next();
                if(inputNull($input)){
                    $ph.html($ph.data('empty_value_default'));
                }else{                  
                    var val=[];
                    if($input.is('select')){//list of the selected
                        $input.find('option:selected').each(function(){val.push($(this).text());});
                    }else{
                        val=SVL.value($input); //date etc included
                    }
                    $ph.html(Array.isArray(val)?val.join(', '):val);
                }
            };   
        $(function(){//initial settings:
            //the placeholder value is saved as default [and replaced by input value]: 
            $(selector).each(function(){
                $(this).data('empty_value_default',$(this).html());
                htmlContent($(this));
            })
            $(selector+' + * ').css('display','none');//the input is hidden (but better hide it statically in the first place)            
            //events: click shows input, focus on ph backups data, blur|[return]|[esc] hides input (esc=undo, revert its value to backup)
            $('body').off('click.ph');
            $('body').on('click.ph',selector,function(){
                $(this).next().css('position','absolute').css('zIndex','10');
                $(this).css('display','none');$(this).next().css('display','inline').focus();                     
            });    
            $('body').off('focus.ph');
            $('body').on('focus.ph',selector+' + *',function(){$(this).data("old",$(this).val());});
            $('body').off('change.ph');
            $('body').on('change.ph',selector+' + *',function(){htmlContent($(this).prev());});
            $('body').off('blur.ph');
            $('body').on('blur.ph keydown.ph',selector+'+ * ',function(){
                if(event.type=='blur' || event.type=='keydown' &&  (event.which==27 || event.which==13)){
                    if(event.which==27)$(this).val($(this).data("old"));                    
                    $(this).css('display','none');$(this).prev().css('display','inline'); 
                }
            });             
        })
    }, 
    
    details:function(selector,params){
        toggleTime=(params && "toggleTime" in params)?params.toggleTime:SVL.defaults.toggleTime;
        $(function(){
            $(selector).on('click.details');
            $(selector).on('click.details','.header',function(){
                //$(this).closest(selector).find('.body').toggle(toggleTime); 
                $(this).next('.body').toggle(toggleTime); 
            });          
        })
    },
    
    value:function($element,params){//unifies values, mainly for dates (local format) + radios etc (unchecked = 0 instead of empty
        mode=(params && ("mode" in params))?params.mode:"text";//text|number|...
        if($element.is(':checkbox') || $element.is(':radio')){
            return ($element.is(':checked')? $element.val() : (mode=="number"?0:"") );
        }else if($element.get(0).type=="date"){
            a=new Date($element.val()); if(a)return a.toLocaleDateString();
        };
        return $element.val();
    },
    
    utils:{
        width:function(obj){//ignore the fact that's hidden
            var clone = obj.clone();
            clone.css("visibility","hidden");
            $('body').append(clone);
            var width = clone.outerWidth();
            clone.remove();
            return width;
        }
    }
}

function SvTree($root,settings){
    //node classes: sv_node[''|sv_expanded|sv_collapsed][sv_selected][sv_focused]
    //attrs:"data-svnodeid","data-svnodetype","data-svnoderecord"    
    this.$root=$root.attr("data-svnodeid",null).attr("data-svnoderecord",'{}');
    this.focusedKey=null;//stored with unique id in case of refreshing parents of focused node
    if(!$root.hasClass("sv_node")) $root.addClass("sv_node"); //dom element
    //settings:  
    this.settings=settings||{};//{nodes:{type1:see notes,...} [,settings:{loading:'text different from default'}]}
    if(!"loading" in this.settings) this.settings.loading=SVL.defaults.loading;
    if(!"toggleTime" in this.settings) this.settings.toggleTime=SVL.defaults.toggleTime;     
    this.loadLists=function($node,overwrite){//loads/reloads content+children of the node (default node: root)
        var type;if($node!==undefined)type=$node.parent().attr("data-svlisttype");//supposed to be there
        if($node===undefined) $node=this.$root;if(overwrite===undefined)overwrite=false;//defaults
        var parentRecord=JSON.parse($node.attr("data-svnoderecord"));            
        var thisTree=this;//saves for the 'each' loop, where 'this' is the metadata record
        //metadata children loop:
        $.each(this.settings.lists, function(listType, metadata ){if(metadata.parent==type){//parent (TODO faster filter?)
            //var proceed =;//additional conditions on parent record (undefined=true)
            if(metadata.filter===undefined||metadata.filter(parentRecord)){ 
                $ul=$node.find("ul[data-svlisttype='"+listType+"']");
                var newUl=($ul.length==0);//later use too
                if(newUl)$ul=$(document.createElement("ul")).attr("data-svlisttype",listType).appendTo($node);//empty list
                var url=metadata["url"];if(typeof url=="function") url=url(parentRecord);  
                //MdRecord={};MdRecord[listType]=metadata;//the ES6 construct "{[listType]:Metadata}" is not supported by IE11 
                if(newUl || overwrite || metadata.loadItems==='always'){//load only if it's a new or forced-to-overwrite list
                    if(url){// 
                        $ul.html(thisTree.settings.loading);//loading in progress
                        ajaxRes=$.ajax({url: encodeURI(url),method: 'get',dataType:'json',async:true}) 
                            .done(function(data){thisTree.loadItems($node.find("[data-svlisttype='"+listType+"']"),data);})//finds it because $newUl could be another one because of async
                            .fail(function(xhr,status,error){$node.find('.'+listType).text("unable to load data: " + status);})
                    }else{//no loader=> single "folder" node: records=[record of the parent] (if any)
                        thisTree.loadItems($ul,[parentRecord]);
                    }
                }
            };
        }});
    };     
    this.loadItems=function($ul,records){//actual rendering, called from load after loading or immediately
        var listType=$ul.attr("data-svlisttype");
        var metadata=this.settings.lists[listType];
        var content=(typeof metadata.content==="function")?metadata.content:function(record){return metadata.content;};
        $ul.html('');          
        $.each(records,function(key,record){//add element[s]            
            var $newNode= $(document.createElement("li")).addClass("sv_node")            
                //.attr("data-svnodetype",listType)//type: which branch is it
                .attr("data-svnodeid",listType+"_"+record[metadata["id"]?metadata["id"]:"id"])//id: which leaf of the branch
                .attr("data-svnoderecord",JSON.stringify(record))
                .append(//text & tooltip span
                    $(document.createElement("span")).html(content(record)))//end text & tooltip span                    
            if(metadata.loadItems!=='never')$newNode.addClass("sv_collapsed");
            $ul.append($newNode);
        });
        //refocus if the focused element were there:
        if(this.focusedKey!=null){
            $toBeFocused=$ul.find("[data-svnodeid='"+this.focusedKey+"']");
            if($toBeFocused.length)this.focus($toBeFocused.first());
        }        
    }
    this.refresh=function ($node){//actually, reloads the parent. Default: the focused node, root
        if($node===undefined){$node=this.$root.find(".sv_focused").first();}
        $parent=($node.length==0)?this.$root:$node.parents(".sv_node").first();
        this.loadLists(($parent.length==0)?this.$root:$parent,true);
    }; 
    this.focus=function($node){//remove the focus, [focus the specified one]
        this.$root.find(".sv_focused").removeClass("sv_focused");//remove everything else   
        this.focusedKey=null;
        if($node!==undefined){ 
            $node.addClass("sv_focused");
            this.focusedKey=$node.attr("data-svnodeid");
        }                      
    };     
    this.select=function($node,add){//clear selected only if asked, select this one if defined
        if(!add)this.$root.find(".sv_selected").removeClass("sv_selected");
        $node.addClass("sv_selected");
    }
    this.toggle=function($node){//open close children based on class,
        if($node.hasClass("sv_collapsed")){//opening:  (re)load lists, show and expand the node
            this.loadLists($node);//(re)load lists
            $node.children("ul").show(this.settings.toggleTime); //show
            $node.removeClass("sv_collapsed").addClass("sv_expanded");//switch class
        }else if($node.hasClass("sv_expanded")){//closing:show and collapse the node
            $node.children("ul").hide(this.settings.toggleTime);//hide
            $node.removeClass("sv_expanded").addClass("sv_collapsed");//switch class
        } 
    };     
    //events: 
    this.$root.on('click', "span", {svtree:this} , function(e){
        e.stopPropagation();
        $li=$(this).closest('.sv_node'); 
        if($li) e.data.svtree.toggle($li);//comment this line not to open children
        e.data.svtree.select($li,event.ctrlKey);
        e.data.svtree.focus($li);
    });       
    this.$root.on('click', "li", {svtree:this} , function(e){
            e.stopPropagation();
            e.data.svtree.toggle($(this));
        });  
            
}                


//CHECK AND DECOMMENT
    /*
    QueryEditor:function(settings){
        this.data={};//root,[request,done,changed,waiting]: dom root, dom containing the request, loaded and changed callbacks, waiting message
        //settings to data:
        var sel=['action','request','done','changed','loading'];
        for(var i=0;i<sel.length;i++)
            if(sel[i] in settings) this.data[sel[i]]=settings[sel[i]];
            this.data.root=$('<div></div>');          
            settings.container.append(this.data.root);  
        //if request is done by post variables, reorder request by section etc:
        //add all events for the elements:
        this.addEvents=function(){
            var parent=this;
            //add/del/submit events:
            function quediCloneLine($original){//cloning and modifications
                newLine=$original.closest('.quedi_new_line')//clone new_line and set class
                    .clone(true,true)
                    .addClass('quedi_line').removeClass('quedi_new_line').css('display','');                        
                newLine.find('.quedi_wannabe_sql').attr('name','quedi_sql[]').removeClass('quedi_wannabe_sql');//validates fields marked for request
                newLine.find('.quedi_wannabe_visible').css('display','inline').removeClass('quedi_wannabe_visible');//show elements marked for visibility
                newLine.find('.quedi_dont_copy').remove();//remove elements Thusly Marked 
                //newLine.find('.quedi_change_add_line').addClass('quedi_change_del_line').removeClass('quedi_change_add_line');//removes adding, add deleting functions                                           
                //newLine.find('.quedi_click_add_line').remove();//remove add click button
                //add after the last/at the beginning
                ul=$original.closest('.quedi_section').find('ul').first();
                lastLine=ul.find('.quedi_line').last();  
                if(lastLine.length){
                    newLine.insertAfter(lastLine);                                                   
                }else{
                    newLine.prependTo(ul);                            
                }
                if('changed' in parent.data) parent.data.changed();//final callback 
            }   
            this.data.root
                //link in new line => add line:
                .on('click','.quedi_new_line a',function(){quediCloneLine($(this));})  
                //select and input in new lines => add line and reset its value:
                .on('change','.quedi_select_section .quedi_new_line select,.quedi_order_section .quedi_new_line select',function(){quediCloneLine($(this));$(this).val('');})
                //select in new line in from section => also reload:
                .on('change','.quedi_from_section .quedi_new_line select',function(){quediCloneLine($(this));$(this).val('');parent.load(parent.data.root);})
                //select in new line in where section => a hidden element
                .on('change','.quedi_where_section .quedi_new_line select',function(){                
                    var look="[name='"+$(this).find('option:selected').first().val()+"']";
                    quediCloneLine($(look));
                    $(this).val('');
                })
                //notify any change:
                .on('change','.quedi_line *',function(){if('changed' in parent.data) parent.data.changed();})
                //remove lines:
                .on('click','.quedi_click_delete_line',function(){
                    $(this).closest('.quedi_line').remove();
                    if('changed' in parent.data) parent.data.changed();//callback
                }) 
                //remove lines in from => also reload:
                .on('click','.quedi_from_section .quedi_click_delete_line',function(){parent.load(parent.data.root);})
                //show/hide switchable input+text:
                .on('click blur','.quedi_toggle',function(){//input first, link after
                    var $other;
                    if($(this).is("a") && event.type=='click'){//it's the link which is visible:
                        $other=$(this).prev();
                        $other.val(($(this).html()==$(this).attr('data-val-default'))?'':$(this).html().replace(/\'/g, ''));                      
                    }
                    if($(this).is("input") && event.type=='blur'){
                        $other=$(this).next();                        
                        $other.html($(this).val()!=''?'\''+$(this).val()+'\'':$other.attr('data-val-default'));                                              
                    }
                    if($other) {$(this).toggle();$other.toggle();$other.focus();};//toggle visibilities
                })                
            ;
            //sortable:
            var oldContainer;
            $(".quedi_where_section>ul, .quedi_where_section>ul ul").sortable({
                group:'where',
                handle:'.quedi_handle',
                draggedClass:'quedi_dragged',
                afterMove: function($placeholder,container,$closestItemOrContainer){                    
                    if(oldContainer != container){
                        if(oldContainer)oldContainer.el.removeClass("active");
                        container.el.addClass("active");
                        oldContainer = container;
                  }
                },                
            });                      
            $(".quedi_select_section ul").sortable({
                group:'select',
                handle:'.quedi_handle',
                draggedClass:'quedi_dragged',
                pullPlaceholder:'<li class="placeholder">PLACEHOLDER</i>',
            });
            $(".quedi_order_section ul").sortable({
                group:'order',
                handle:'.quedi_handle',
                draggedClass:'quedi_dragged',
            });            
        }            
        this.load=function(request){//can define/redifine the request source dom
            sql=SVL.request2sql((typeof request==='undefined')?this.data.request:request);//takes the sql from the request dom
            this.data.root.html(('loading' in this.data)?this.data.loading:SVL.defaults.loading);//message 
            var parent=this;//for inside .done or .fail etc
            var jqxhr  = $.post(parent.data.action,{_token:token,quedi_sql:sql})//actual loading
            .done(function(){           
                newQuedi=$(jqxhr.responseText).find('.quedi').first();
                if(newQuedi.length){
                    parent.data.root.replaceWith(newQuedi);//it can call standalone views getting the first editor
                    parent.data.root=newQuedi; 
                    parent.addEvents();
                    if('done' in parent.data)parent.data.done();//call back
                    SVL.inputPlaceholder('.sv_input_placeholder');//placeholders refresh
                }else{               
                    parent.data.root.html(parent.data.action+': unexpected server response (no quedi class found)');
                }             
            })
            .fail(function() {
                parent.data.root.html(jqxhr.responseText);
            })                
        }        
    },
    
    request2sql:function(request){//sql or quedi element
        var sql='';var val;
        if($.type(request)=='string'){sql=request;}
        else{
            ['.quedi_select_section','.quedi_from_section','.quedi_where_section','.quedi_order_section']
                .forEach(function(obj){
                    $(request).find(obj).first().find("[name='quedi_sql[]']").each(function(){
                        val=$(this).val();
                        if($(this).is('[type="text"]') || $(this).is('[type="date"]')) val='\''+val.replace(/'/g,"")+'\'';
                        sql+=val + ' ';
                    });
                });
        }
        return sql;
    },    
    */