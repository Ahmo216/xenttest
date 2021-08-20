/********* jquery.noty.packaged.min.js *********/ 
!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],b):"object"==typeof exports?module.exports=b(require("jquery")):b(a.jQuery)}(this,function(a){"function"!=typeof Object.create&&(Object.create=function(a){function b(){}return b.prototype=a,new b});var b={init:function(b){if(this.options=a.extend({},a.noty.defaults,b),this.options.layout=this.options.custom?a.noty.layouts.inline:a.noty.layouts[this.options.layout],a.noty.themes[this.options.theme]?(this.options.theme=a.noty.themes[this.options.theme],this.options.theme.template&&(this.options.template=this.options.theme.template),this.options.theme.animation&&(this.options.animation=this.options.theme.animation)):this.options.themeClassName=this.options.theme,this.options=a.extend({},this.options,this.options.layout.options),this.options.id){if(a.noty.store[this.options.id])return a.noty.store[this.options.id]}else this.options.id="noty_"+(new Date).getTime()*Math.floor(1e6*Math.random());return this._build(),this},_build:function(){var b=a('<div class="noty_bar noty_type_'+this.options.type+'"></div>').attr("id",this.options.id);if(b.append(this.options.template).find(".noty_text").html(this.options.text),this.$bar=null!==this.options.layout.parent.object?a(this.options.layout.parent.object).css(this.options.layout.parent.css).append(b):b,this.options.themeClassName&&this.$bar.addClass(this.options.themeClassName).addClass("noty_container_type_"+this.options.type),this.options.buttons){var c;this.$bar.find(".noty_buttons").length>0?c=this.$bar.find(".noty_buttons"):(c=a("<div/>").addClass("noty_buttons"),null!==this.options.layout.parent.object?this.$bar.find(".noty_bar").append(c):this.$bar.append(c));var d=this;a.each(this.options.buttons,function(b,e){var f=a("<button/>").addClass(e.addClass?e.addClass:"gray").html(e.text).attr("id",e.id?e.id:"button-"+b).attr("title",e.title).appendTo(c).on("click",function(b){a.isFunction(e.onClick)&&e.onClick.call(f,d,b)})})}else this.$bar.find(".noty_buttons").remove();if(this.options.progressBar&&this.options.timeout){var e=a("<div/>").addClass("noty_progress_bar");null!==this.options.layout.parent.object?this.$bar.find(".noty_bar").append(e):this.$bar.append(e)}this.$message=this.$bar.find(".noty_message"),this.$closeButton=this.$bar.find(".noty_close"),this.$buttons=this.$bar.find(".noty_buttons"),this.$progressBar=this.$bar.find(".noty_progress_bar"),a.noty.store[this.options.id]=this},show:function(){var b=this;return b.options.custom?b.options.custom.find(b.options.layout.container.selector).append(b.$bar):a(b.options.layout.container.selector).append(b.$bar),b.options.theme&&b.options.theme.style&&b.options.theme.style.apply(b),"function"===a.type(b.options.layout.css)?this.options.layout.css.apply(b.$bar):b.$bar.css(this.options.layout.css||{}),b.$bar.addClass(b.options.layout.addClass),b.options.layout.container.style.apply(a(b.options.layout.container.selector),[b.options.within]),b.showing=!0,b.options.theme&&b.options.theme.style&&b.options.theme.callback.onShow.apply(this),a.inArray("click",b.options.closeWith)>-1&&b.$bar.css("cursor","pointer").on("click",function(a){b.stopPropagation(a),b.options.callback.onCloseClick&&b.options.callback.onCloseClick.apply(b),b.close()}),a.inArray("hover",b.options.closeWith)>-1&&b.$bar.one("mouseenter",function(){b.close()}),a.inArray("button",b.options.closeWith)>-1&&b.$closeButton.one("click",function(a){b.stopPropagation(a),b.close()}),a.inArray("button",b.options.closeWith)==-1&&b.$closeButton.remove(),b.options.callback.beforeShow&&b.options.callback.beforeShow.apply(b),"string"==typeof b.options.animation.open?(b.animationTypeOpen="css",b.$bar.css("min-height",b.$bar.innerHeight()),b.$bar.on("click",function(a){b.wasClicked=!0}),b.$bar.show(),b.options.callback.onShow&&b.options.callback.onShow.apply(b),b.$bar.addClass(b.options.animation.open).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){b.options.callback.afterShow&&b.options.callback.afterShow.apply(b),b.showing=!1,b.shown=!0,b.bindTimeout(),b.hasOwnProperty("wasClicked")&&(b.$bar.off("click",function(a){b.wasClicked=!0}),b.close())})):"object"==typeof b.options.animation.open&&null==b.options.animation.open?(b.animationTypeOpen="none",b.showing=!1,b.shown=!0,b.$bar.show(),b.bindTimeout(),b.options.callback.onShow&&b.options.callback.onShow.apply(b),b.$bar.queue(function(){b.options.callback.afterShow&&b.options.callback.afterShow.apply(b)})):(b.animationTypeOpen="anim",b.options.callback.onShow&&b.options.callback.onShow.apply(b),b.$bar.animate(b.options.animation.open,b.options.animation.speed,b.options.animation.easing,function(){b.options.callback.afterShow&&b.options.callback.afterShow.apply(b),b.showing=!1,b.shown=!0,b.bindTimeout()})),this},bindTimeout:function(){var a=this;a.options.timeout&&(a.options.progressBar&&a.$progressBar&&a.$progressBar.css({transition:"all "+a.options.timeout+"ms linear",width:"0%"}),a.queueClose(a.options.timeout),a.$bar.on("mouseenter",a.dequeueClose.bind(a)),a.$bar.on("mouseleave",a.queueClose.bind(a,a.options.timeout)))},dequeueClose:function(){var a=this;a.options.progressBar&&this.$progressBar.css({transition:"none",width:"100%"}),this.closeTimer&&(clearTimeout(this.closeTimer),this.closeTimer=null)},queueClose:function(a){var b=this;if(b.options.progressBar&&b.$progressBar.css({transition:"all "+b.options.timeout+"ms linear",width:"0%"}),!this.closeTimer)return b.closeTimer=window.setTimeout(function(){b.close()},a),b.closeTimer},close:function(){if(this.$progressBar&&this.$progressBar.remove(),this.closeTimer&&this.dequeueClose(),!(this.closed||this.$bar&&this.$bar.hasClass("i-am-closing-now"))){var b=this;if(this.showing&&("anim"==this.animationTypeOpen||"none"==this.animationTypeOpen))return void b.$bar.queue(function(){b.close.apply(b)});if(this.showing&&"css"==this.animationTypeOpen&&b.$bar.on("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){b.close()}),!this.shown&&!this.showing){var c=[];return a.each(a.noty.queue,function(a,d){d.options.id!=b.options.id&&c.push(d)}),void(a.noty.queue=c)}b.$bar.addClass("i-am-closing-now"),b.options.callback.onClose&&b.options.callback.onClose.apply(b),"string"==typeof b.options.animation.close?b.$bar.removeClass(b.options.animation.open).addClass(b.options.animation.close).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){b.options.callback.afterClose&&b.options.callback.afterClose.apply(b),b.closeCleanUp()}):"object"==typeof b.options.animation.close&&null==b.options.animation.close?b.$bar.dequeue().hide(0,function(){b.options.callback.afterClose&&b.options.callback.afterClose.apply(b),b.closeCleanUp()}):b.$bar.clearQueue().stop().animate(b.options.animation.close,b.options.animation.speed,b.options.animation.easing,function(){b.options.callback.afterClose&&b.options.callback.afterClose.apply(b)}).promise().done(function(){b.closeCleanUp()})}},closeCleanUp:function(){var b=this;b.options.modal&&(a.notyRenderer.setModalCount(-1),0!=a.notyRenderer.getModalCount()||a.noty.queue.length||a(".noty_modal").fadeOut(b.options.animation.fadeSpeed,function(){a(this).remove()})),a.notyRenderer.setLayoutCountFor(b,-1),0==a.notyRenderer.getLayoutCountFor(b)&&a(b.options.layout.container.selector).remove(),"undefined"!=typeof b.$bar&&null!==b.$bar?"string"==typeof b.options.animation.close?(b.$bar.css("transition","all 10ms ease").css("border",0).css("margin",0).height(0),b.$bar.one("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",function(){b.$bar.remove(),b.$bar=null,b.closed=!0,b.options.theme.callback&&b.options.theme.callback.onClose&&b.options.theme.callback.onClose.apply(b),b.handleNext()})):(b.$bar.remove(),b.$bar=null,b.closed=!0,b.handleNext()):b.handleNext()},handleNext:function(){var b=this;delete a.noty.store[b.options.id],b.options.theme.callback&&b.options.theme.callback.onClose&&b.options.theme.callback.onClose.apply(b),b.options.dismissQueue||(a.noty.ontap=!0,a.notyRenderer.render()),b.options.maxVisible>0&&b.options.dismissQueue&&a.notyRenderer.render()},setText:function(a){return this.closed||(this.options.text=a,this.$bar.find(".noty_text").html(a)),this},setType:function(a){return this.closed||(this.options.type=a,this.options.theme.style.apply(this),this.options.theme.callback.onShow.apply(this)),this},setTimeout:function(a){if(!this.closed){var b=this;this.options.timeout=a,b.$bar.delay(b.options.timeout).promise().done(function(){b.close()})}return this},stopPropagation:function(a){a=a||window.event,"undefined"!=typeof a.stopPropagation?a.stopPropagation():a.cancelBubble=!0},closed:!1,showing:!1,shown:!1};a.notyRenderer={},a.notyRenderer.init=function(c){var d=Object.create(b).init(c);return d.options.killer&&a.noty.closeAll(),d.options.force?a.noty.queue.unshift(d):a.noty.queue.push(d),a.notyRenderer.render(),"object"==a.noty.returns?d:d.options.id},a.notyRenderer.render=function(){var b=a.noty.queue[0];"object"===a.type(b)?b.options.dismissQueue?b.options.maxVisible>0?a(b.options.layout.container.selector+" > li").length<b.options.maxVisible&&a.notyRenderer.show(a.noty.queue.shift()):a.notyRenderer.show(a.noty.queue.shift()):a.noty.ontap&&(a.notyRenderer.show(a.noty.queue.shift()),a.noty.ontap=!1):a.noty.ontap=!0},a.notyRenderer.show=function(b){b.options.modal&&(a.notyRenderer.createModalFor(b),a.notyRenderer.setModalCount(1)),b.options.custom?0==b.options.custom.find(b.options.layout.container.selector).length?b.options.custom.append(a(b.options.layout.container.object).addClass("i-am-new")):b.options.custom.find(b.options.layout.container.selector).removeClass("i-am-new"):0==a(b.options.layout.container.selector).length?a("body").append(a(b.options.layout.container.object).addClass("i-am-new")):a(b.options.layout.container.selector).removeClass("i-am-new"),a.notyRenderer.setLayoutCountFor(b,1),b.show()},a.notyRenderer.createModalFor=function(b){if(0==a(".noty_modal").length){var c=a("<div/>").addClass("noty_modal").addClass(b.options.theme).data("noty_modal_count",0);b.options.theme.modal&&b.options.theme.modal.css&&c.css(b.options.theme.modal.css),c.prependTo(a("body")).fadeIn(b.options.animation.fadeSpeed),a.inArray("backdrop",b.options.closeWith)>-1&&c.on("click",function(){a.noty.closeAll()})}},a.notyRenderer.getLayoutCountFor=function(b){return a(b.options.layout.container.selector).data("noty_layout_count")||0},a.notyRenderer.setLayoutCountFor=function(b,c){return a(b.options.layout.container.selector).data("noty_layout_count",a.notyRenderer.getLayoutCountFor(b)+c)},a.notyRenderer.getModalCount=function(){return a(".noty_modal").data("noty_modal_count")||0},a.notyRenderer.setModalCount=function(b){return a(".noty_modal").data("noty_modal_count",a.notyRenderer.getModalCount()+b)},a.fn.noty=function(b){return b.custom=a(this),a.notyRenderer.init(b)},a.noty={},a.noty.queue=[],a.noty.ontap=!0,a.noty.layouts={},a.noty.themes={},a.noty.returns="object",a.noty.store={},a.noty.get=function(b){return!!a.noty.store.hasOwnProperty(b)&&a.noty.store[b]},a.noty.close=function(b){return!!a.noty.get(b)&&a.noty.get(b).close()},a.noty.setText=function(b,c){return!!a.noty.get(b)&&a.noty.get(b).setText(c)},a.noty.setType=function(b,c){return!!a.noty.get(b)&&a.noty.get(b).setType(c)},a.noty.clearQueue=function(){a.noty.queue=[]},a.noty.closeAll=function(){a.noty.clearQueue(),a.each(a.noty.store,function(a,b){b.close()})};var c=window.alert;return a.noty.consumeAlert=function(b){window.alert=function(c){b?b.text=c:b={text:c},a.notyRenderer.init(b)}},a.noty.stopConsumeAlert=function(){window.alert=c},a.noty.defaults={layout:"topRight",theme:"relax",type:"alert",text:"",progressBar:!1,dismissQueue:!0,template:'<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',animation:{open:{height:"toggle"},close:{height:"toggle"},easing:"swing",speed:500,fadeSpeed:"fast"},timeout:!1,force:!1,modal:!1,maxVisible:5,killer:!1,closeWith:["click"],callback:{beforeShow:function(){},onShow:function(){},afterShow:function(){},onClose:function(){},afterClose:function(){},onCloseClick:function(){}},buttons:!1},a(window).on("resize",function(){a.each(a.noty.layouts,function(b,c){c.container.style.apply(a(c.container.selector))})}),window.noty=function(b){return a.notyRenderer.init(b)},a.noty.layouts.bottom={name:"bottom",options:{},container:{object:'<ul id="noty_bottom_layout_container" />',selector:"ul#noty_bottom_layout_container",style:function(){a(this).css({bottom:0,left:"5%",position:"fixed",width:"90%",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:9999999})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none"},addClass:""},a.noty.layouts.bottomCenter={name:"bottomCenter",options:{},container:{object:'<ul id="noty_bottomCenter_layout_container" />',selector:"ul#noty_bottomCenter_layout_container",style:function(){a(this).css({bottom:20,left:0,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7}),a(this).css({left:(a(window).width()-a(this).outerWidth(!1))/2+"px"})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.bottomLeft={name:"bottomLeft",options:{},container:{object:'<ul id="noty_bottomLeft_layout_container" />',selector:"ul#noty_bottomLeft_layout_container",style:function(){a(this).css({bottom:20,left:20,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7}),window.innerWidth<600&&a(this).css({left:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.bottomRight={name:"bottomRight",options:{},container:{object:'<ul id="noty_bottomRight_layout_container" />',selector:"ul#noty_bottomRight_layout_container",style:function(){a(this).css({bottom:20,right:20,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7}),window.innerWidth<600&&a(this).css({right:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.center={name:"center",options:{},container:{object:'<ul id="noty_center_layout_container" />',selector:"ul#noty_center_layout_container",style:function(){a(this).css({position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7});var b=a(this).clone().css({visibility:"hidden",display:"block",position:"absolute",top:0,left:0}).attr("id","dupe");a("body").append(b),b.find(".i-am-closing-now").remove(),b.find("li").css("display","block");var c=b.height();b.remove(),a(this).hasClass("i-am-new")?a(this).css({left:(a(window).width()-a(this).outerWidth(!1))/2+"px",top:(a(window).height()-c)/2+"px"}):a(this).animate({left:(a(window).width()-a(this).outerWidth(!1))/2+"px",top:(a(window).height()-c)/2+"px"},500)}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.centerLeft={name:"centerLeft",options:{},container:{object:'<ul id="noty_centerLeft_layout_container" />',selector:"ul#noty_centerLeft_layout_container",style:function(){a(this).css({left:20,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7});var b=a(this).clone().css({visibility:"hidden",display:"block",position:"absolute",top:0,left:0}).attr("id","dupe");a("body").append(b),b.find(".i-am-closing-now").remove(),b.find("li").css("display","block");var c=b.height();b.remove(),a(this).hasClass("i-am-new")?a(this).css({top:(a(window).height()-c)/2+"px"}):a(this).animate({top:(a(window).height()-c)/2+"px"},500),window.innerWidth<600&&a(this).css({left:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.centerRight={name:"centerRight",options:{},container:{object:'<ul id="noty_centerRight_layout_container" />',selector:"ul#noty_centerRight_layout_container",style:function(){a(this).css({right:20,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7});var b=a(this).clone().css({visibility:"hidden",display:"block",position:"absolute",top:0,left:0}).attr("id","dupe");a("body").append(b),b.find(".i-am-closing-now").remove(),b.find("li").css("display","block");var c=b.height();b.remove(),a(this).hasClass("i-am-new")?a(this).css({top:(a(window).height()-c)/2+"px"}):a(this).animate({top:(a(window).height()-c)/2+"px"},500),window.innerWidth<600&&a(this).css({right:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.inline={name:"inline",options:{},container:{object:'<ul class="noty_inline_layout_container" />',selector:"ul.noty_inline_layout_container",style:function(){a(this).css({width:"100%",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:9999999})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none"},addClass:""},a.noty.layouts.top={name:"top",options:{},container:{object:'<ul id="noty_top_layout_container" />',selector:"ul#noty_top_layout_container",style:function(){a(this).css({top:0,left:"5%",position:"fixed",width:"90%",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:9999999})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none"},addClass:""},a.noty.layouts.topCenter={name:"topCenter",options:{},container:{object:'<ul id="noty_topCenter_layout_container" />',selector:"ul#noty_topCenter_layout_container",style:function(){a(this).css({top:20,left:0,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7}),a(this).css({left:(a(window).width()-a(this).outerWidth(!1))/2+"px"})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.topLeft={name:"topLeft",options:{},container:{object:'<ul id="noty_topLeft_layout_container" />',selector:"ul#noty_topLeft_layout_container",style:function(){a(this).css({top:20,left:20,position:"fixed",width:"310px",height:"auto",margin:0,padding:0,listStyleType:"none",zIndex:1e7}),window.innerWidth<600&&a(this).css({left:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.layouts.topRight={name:"topRight",options:{},container:{object:'<ul id="noty_topRight_layout_container" />',selector:"ul#noty_topRight_layout_container",style:function(){window.innerWidth<600&&a(this).css({right:5})}},parent:{object:"<li />",selector:"li",css:{}},css:{display:"none",width:"310px"},addClass:""},a.noty.themes.bootstrapTheme={name:"bootstrapTheme",modal:{css:{position:"fixed",width:"100%",height:"100%",backgroundColor:"#000",zIndex:1e4,opacity:.6,display:"none",left:0,top:0,wordBreak:"break-all"}},style:function(){var b=this.options.layout.container.selector;switch(a(b).addClass("list-group"),this.$closeButton.append('<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>'),this.$closeButton.addClass("close"),this.$bar.addClass("list-group-item").css("padding","0px").css("position","relative"),this.$progressBar.css({position:"absolute",left:0,bottom:0,height:4,width:"100%",backgroundColor:"#000000",opacity:.2,"-ms-filter":"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)",filter:"alpha(opacity=20)"}),this.options.type){case"alert":case"notification":this.$bar.addClass("list-group-item-info");break;case"warning":this.$bar.addClass("list-group-item-warning");break;case"error":this.$bar.addClass("list-group-item-danger");break;case"information":this.$bar.addClass("list-group-item-info");break;case"success":this.$bar.addClass("list-group-item-success")}this.$message.css({textAlign:"center",padding:"8px 10px 9px",width:"auto",position:"relative"})},callback:{onShow:function(){},onClose:function(){}}},a.noty.themes.defaultTheme={name:"defaultTheme",helpers:{borderFix:function(){if(this.options.dismissQueue){var b=this.options.layout.container.selector+" "+this.options.layout.parent.selector;switch(this.options.layout.name){case"top":a(b).css({borderRadius:"0px 0px 0px 0px"}),a(b).last().css({borderRadius:"0px 0px 5px 5px"});break;case"topCenter":case"topLeft":case"topRight":case"bottomCenter":case"bottomLeft":case"bottomRight":case"center":case"centerLeft":case"centerRight":case"inline":a(b).css({borderRadius:"0px 0px 0px 0px"}),a(b).first().css({"border-top-left-radius":"5px","border-top-right-radius":"5px"}),a(b).last().css({"border-bottom-left-radius":"5px","border-bottom-right-radius":"5px"});break;case"bottom":a(b).css({borderRadius:"0px 0px 0px 0px"}),a(b).first().css({borderRadius:"5px 5px 0px 0px"})}}}},modal:{css:{position:"fixed",width:"100%",height:"100%",backgroundColor:"#000",zIndex:1e4,opacity:.6,display:"none",left:0,top:0}},style:function(){switch(this.$bar.css({overflow:"hidden",background:"url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAoCAQAAAClM0ndAAAAhklEQVR4AdXO0QrCMBBE0bttkk38/w8WRERpdyjzVOc+HxhIHqJGMQcFFkpYRQotLLSw0IJ5aBdovruMYDA/kT8plF9ZKLFQcgF18hDj1SbQOMlCA4kao0iiXmah7qBWPdxpohsgVZyj7e5I9KcID+EhiDI5gxBYKLBQYKHAQoGFAoEks/YEGHYKB7hFxf0AAAAASUVORK5CYII=') repeat-x scroll left top #fff",position:"relative"}),this.$progressBar.css({position:"absolute",left:0,bottom:0,height:4,width:"100%",backgroundColor:"#000000",opacity:.2,"-ms-filter":"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)",filter:"alpha(opacity=20)"}),this.$message.css({textAlign:"center",padding:"8px 10px 9px",width:"auto",position:"relative"}),this.$closeButton.css({position:"absolute",top:4,right:4,width:10,height:10,background:"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",display:"none",cursor:"pointer"}),this.$buttons.css({padding:5,textAlign:"right",borderTop:"1px solid #ccc",backgroundColor:"#fff"}),this.$buttons.find("button").css({marginLeft:5}),this.$buttons.find("button:first").css({marginLeft:0}),this.$bar.on({mouseenter:function(){a(this).find(".noty_close").stop().fadeTo("normal",1)},mouseleave:function(){a(this).find(".noty_close").stop().fadeTo("normal",0)}}),this.options.layout.name){case"top":this.$bar.css({borderRadius:"0px 0px 5px 5px",borderBottom:"2px solid #eee",borderLeft:"2px solid #eee",borderRight:"2px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"});break;case"topCenter":case"center":case"bottomCenter":case"inline":this.$bar.css({borderRadius:"5px",border:"1px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"}),this.$message.css({textAlign:"center"});break;case"topLeft":case"topRight":case"bottomLeft":case"bottomRight":case"centerLeft":case"centerRight":this.$bar.css({borderRadius:"5px",border:"1px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"}),this.$message.css({textAlign:"left"});break;case"bottom":this.$bar.css({borderRadius:"5px 5px 0px 0px",borderTop:"2px solid #eee",borderLeft:"2px solid #eee",borderRight:"2px solid #eee",boxShadow:"0 -2px 4px rgba(0, 0, 0, 0.1)"});break;default:this.$bar.css({border:"2px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"})}switch(this.options.type){case"alert":case"notification":this.$bar.css({backgroundColor:"#FFF",borderColor:"#CCC",color:"#444"});break;case"warning":this.$bar.css({backgroundColor:"#FFEAA8",borderColor:"#FFC237",color:"#826200"}),this.$buttons.css({borderTop:"1px solid #FFC237"});break;case"error":this.$bar.css({backgroundColor:"red",borderColor:"darkred",color:"#FFF"}),this.$message.css({fontWeight:"bold"}),this.$buttons.css({borderTop:"1px solid darkred"});break;case"information":this.$bar.css({backgroundColor:"#57B7E2",borderColor:"#0B90C4",color:"#FFF"}),this.$buttons.css({borderTop:"1px solid #0B90C4"});break;case"success":this.$bar.css({backgroundColor:"lightgreen",borderColor:"#50C24E",color:"darkgreen"}),this.$buttons.css({borderTop:"1px solid #50C24E"});break;default:this.$bar.css({backgroundColor:"#FFF",borderColor:"#CCC",color:"#444"})}},callback:{onShow:function(){a.noty.themes.defaultTheme.helpers.borderFix.apply(this)},onClose:function(){a.noty.themes.defaultTheme.helpers.borderFix.apply(this)}}},a.noty.themes.metroui={name:"metroui",helpers:{},modal:{css:{position:"fixed",width:"100%",height:"100%",backgroundColor:"#000",zIndex:1e4,opacity:.6,display:"none",left:0,top:0}},style:function(){switch(this.$bar.css({overflow:"hidden",margin:"4px 0",borderRadius:"0",position:"relative"}),this.$progressBar.css({position:"absolute",left:0,bottom:0,height:4,width:"100%",backgroundColor:"#000000",opacity:.2,"-ms-filter":"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)",filter:"alpha(opacity=20)"}),this.$message.css({textAlign:"center",padding:"1.25rem",width:"auto",position:"relative"}),this.$closeButton.css({position:"absolute",top:".25rem",right:".25rem",width:10,height:10,background:"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",display:"none",cursor:"pointer"}),this.$buttons.css({padding:5,textAlign:"right",borderTop:"1px solid #ccc",backgroundColor:"#fff"}),this.$buttons.find("button").css({marginLeft:5}),this.$buttons.find("button:first").css({marginLeft:0}),this.$bar.on({mouseenter:function(){a(this).find(".noty_close").stop().fadeTo("normal",1)},mouseleave:function(){a(this).find(".noty_close").stop().fadeTo("normal",0)}}),this.options.layout.name){case"top":this.$bar.css({border:"none",boxShadow:"0 0 5px 0 rgba(0, 0, 0, 0.3)"});break;case"topCenter":case"center":case"bottomCenter":case"inline":this.$bar.css({border:"none",boxShadow:"0 0 5px 0 rgba(0, 0, 0, 0.3)"}),this.$message.css({textAlign:"center"});break;case"topLeft":case"topRight":case"bottomLeft":case"bottomRight":case"centerLeft":case"centerRight":this.$bar.css({border:"none",boxShadow:"0 0 5px 0 rgba(0, 0, 0, 0.3)"}),this.$message.css({textAlign:"left"});break;case"bottom":this.$bar.css({border:"none",boxShadow:"0 0 5px 0 rgba(0, 0, 0, 0.3)"});break;default:this.$bar.css({border:"none",boxShadow:"0 0 5px 0 rgba(0, 0, 0, 0.3)"})}switch(this.options.type){case"alert":case"notification":this.$bar.css({backgroundColor:"#fff",border:"none",color:"#1d1d1d"});break;case"warning":this.$bar.css({backgroundColor:"#FA6800",border:"none",color:"#fff"}),this.$buttons.css({borderTop:"1px solid #FA6800"});break;case"error":this.$bar.css({backgroundColor:"#CE352C",border:"none",color:"#fff"}),this.$message.css({fontWeight:"bold"}),this.$buttons.css({borderTop:"1px solid #CE352C"});break;case"information":this.$bar.css({backgroundColor:"#1BA1E2",border:"none",color:"#fff"}),this.$buttons.css({borderTop:"1px solid #1BA1E2"});break;case"success":this.$bar.css({backgroundColor:"#60A917",border:"none",color:"#fff"}),this.$buttons.css({borderTop:"1px solid #50C24E"});break;default:this.$bar.css({backgroundColor:"#fff",border:"none",color:"#1d1d1d"})}},callback:{onShow:function(){},onClose:function(){}}},a.noty.themes.relax={name:"relax",helpers:{},modal:{css:{position:"fixed",width:"100%",height:"100%",backgroundColor:"#000",zIndex:1e4,opacity:.6,display:"none",left:0,top:0}},style:function(){switch(this.$bar.css({overflow:"hidden",margin:"4px 0",borderRadius:"2px",position:"relative"}),this.$progressBar.css({position:"absolute",left:0,bottom:0,height:4,width:"100%",backgroundColor:"#000000",opacity:.2,"-ms-filter":"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)",filter:"alpha(opacity=20)"}),this.$message.css({textAlign:"center",padding:"10px",width:"auto",position:"relative"}),this.$closeButton.css({position:"absolute",top:4,right:4,width:10,height:10,background:"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",display:"none",cursor:"pointer"}),this.$buttons.css({padding:5,textAlign:"right",borderTop:"1px solid #ccc",backgroundColor:"#fff"}),this.$buttons.find("button").css({marginLeft:5}),this.$buttons.find("button:first").css({marginLeft:0}),this.$bar.on({mouseenter:function(){a(this).find(".noty_close").stop().fadeTo("normal",1)},mouseleave:function(){a(this).find(".noty_close").stop().fadeTo("normal",0)}}),this.options.layout.name){case"top":this.$bar.css({borderBottom:"2px solid #eee",borderLeft:"2px solid #eee",borderRight:"2px solid #eee",borderTop:"2px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"});break;case"topCenter":case"center":case"bottomCenter":case"inline":this.$bar.css({border:"1px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"}),this.$message.css({textAlign:"center"});break;case"topLeft":case"topRight":case"bottomLeft":case"bottomRight":case"centerLeft":case"centerRight":this.$bar.css({border:"1px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"}),this.$message.css({textAlign:"left"});break;case"bottom":this.$bar.css({borderTop:"2px solid #eee",borderLeft:"2px solid #eee",borderRight:"2px solid #eee",borderBottom:"2px solid #eee",boxShadow:"0 -2px 4px rgba(0, 0, 0, 0.1)"});break;default:this.$bar.css({border:"2px solid #eee",boxShadow:"0 2px 4px rgba(0, 0, 0, 0.1)"})}switch(this.options.type){case"alert":case"notification":this.$bar.css({backgroundColor:"#FFF",borderColor:"#dedede",color:"#444"});break;case"warning":this.$bar.css({backgroundColor:"#FFEAA8",borderColor:"#FFC237",color:"#826200"}),this.$buttons.css({borderTop:"1px solid #FFC237"});break;case"error":this.$bar.css({backgroundColor:"#FF8181",borderColor:"#e25353",color:"#FFF"}),this.$message.css({fontWeight:"bold"}),this.$buttons.css({borderTop:"1px solid darkred"});break;case"information":this.$bar.css({backgroundColor:"#78C5E7",borderColor:"#3badd6",color:"#FFF"}),this.$buttons.css({borderTop:"1px solid #0B90C4"});break;case"success":this.$bar.css({backgroundColor:"#BCF5BC",borderColor:"#7cdd77",color:"darkgreen"}),this.$buttons.css({borderTop:"1px solid #50C24E"});break;default:this.$bar.css({backgroundColor:"#FFF",borderColor:"#CCC",color:"#444"})}},callback:{onShow:function(){},onClose:function(){}}},a.noty.themes.semanticUI={name:"semanticUI",template:'<div class="ui message"><div class="content"><div class="header"></div></div></div>',animation:{open:{animation:"fade",duration:"800ms"},close:{animation:"fade left",duration:"800ms"}},modal:{css:{position:"fixed",width:"100%",height:"100%",backgroundColor:"#000",zIndex:1e4,opacity:.6,display:"none",left:0,top:0}},style:function(){switch(this.$message=this.$bar.find(".ui.message"),this.$message.find(".header").html(this.options.header),this.$message.find(".content").append(this.options.text),this.$bar.css({margin:"0.5em",position:"relative"}),this.options.icon&&this.$message.addClass("icon").prepend(a("<i/>").addClass(this.options.icon)),this.$progressBar.css({position:"absolute",left:0,bottom:0,height:4,width:"100%",backgroundColor:"#000000",opacity:.2,"-ms-filter":"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)",filter:"alpha(opacity=20)"}),this.options.size){case"mini":this.$message.addClass("mini");break;case"tiny":this.$message.addClass("tiny");break;case"small":this.$message.addClass("small");break;case"large":this.$message.addClass("large");break;case"big":this.$message.addClass("big");break;case"huge":
this.$message.addClass("huge");break;case"massive":this.$message.addClass("massive")}switch(this.options.type){case"info":this.$message.addClass("info");break;case"warning":this.$message.addClass("warning");break;case"error":this.$message.addClass("error");break;case"negative":this.$message.addClass("negative");break;case"success":this.$message.addClass("success");break;case"positive":this.$message.addClass("positive");break;case"floating":this.$message.addClass("floating")}},callback:{onShow:function(){this.$bar.addClass("transition"),this.$bar.transition(this.options.animation.open)},onClose:function(){this.$bar.transition(this.options.animation.close)}}},window.noty});

/********* notify.js *********/ 
/**
 * @example Notify.create('Normale Nachricht');
 * @example Notify.create('Fehler Benachrichtgung', 'error', true);
 */
var Notify = function ($, PushJS) {
    'use strict';

    var me = {

        validTypes: ['default', 'notice', 'success', 'warning', 'error', 'push'],

        settings: {
            storageKeyPrefix: 'notification_',
            storageKeyProgressBar: 'notification_progressbar'
        },

        defaults: {
            layout: 'topRight',
            theme: 'notification',
            maxVisible: 5,
            timeout: 10000,
            progressBar: true,
            animation: {
                open: {height: 'toggle'},
                close: {height: 'toggle'},
                easing: 'swing',
                speed: 250
            },
            closeWith: [], // Überschreiben
            template: '<div class="noty_message noselect"><div class="noty_text"></div><div class="close-icon"></div></div>'
        },

        init: function () {
            // Notifications nicht in IFrames anzeigen
            if (me.isIframe()) {
                return;
            }

            // Eigene Default-Einstellungen in Noty-Defaults integrieren
            $.noty.defaults = $.extend({}, $.noty.defaults, me.defaults);
            $.noty.defaults.callback.onClose = function () {
                me.closeNotificationInOtherTabs(this.options.id);
            };

            // Init abbrechen, wenn in Beleg-Positionen oder Positionen-Popup
            var action = $('body').data('action');
            if (typeof action === 'undefined' || action === 'positionen' || action === 'positioneneditpopup') {
                return;
            }

            // Wenn Seite geladen wird > Geöffnete Benachrichtigungen aus LocalStorage wiederherstellen
            me.restoreFromLocalStorage();

            // Auf Änderungen im LocalStorage horchen
            window.addEventListener('storage', me.storageHandler, false);

            $(document).on('click', '.notification .close-icon', function () {
                var notiId = $(this).parents('.noty_bar').prop('id');
                me.close(notiId);
            });
        },

        /**
         * @param {string} key
         *
         * @return {boolean}
         */
        has: function (key) {
            return $.noty.get(key) !== false;
        },

        /**
         * @param {string} key
         *
         * @return {object} noty-Objekt
         */
        get: function (key) {
            return $.noty.get(key);
        },

        /**
         * @return {string[]}
         */
        keys: function () {
            return Object.keys($.noty.store);
        },

        /**
         * @param {string|null} type [default|notice|success|warning|error|push]
         * @param {string|null} title
         * @param {string|null} message
         * @param {boolean|null} hasPriority
         * @param {object|null} options
         */
        create: function (type, title, message, hasPriority, options) {
            if (typeof options !== 'object' || options === null) {
                options = {};
            }
            var data = options;

            if (typeof type === 'undefined' || type === null) {
                type = 'default';
            }
            if (me.validTypes.indexOf(type) === -1) {
                type = 'default';
            }
            if (typeof title === 'undefined' || title === null) {
                title = '';
            }
            if (typeof message === 'undefined' || message === null) {
                message = '';
            }
            if (typeof hasPriority === 'undefined' || hasPriority === null) {
                hasPriority = false;
            }
            if (title === '' && message === '') {
                return;
            }

            if (hasPriority === true) {
                me.playSound();
                data.progressBar = false;
                data.timeout = false; // Sticky machen
                data.sticky = true;
                data.force = true; // An den Anfang setzen
            }

            data.text = '';
            if (title !== '') {
                data.text += '<h6>' + title + '</h6>';
            }
            if (message !== '') {
                data.text += message;
            }

            data.type = type;

            // Buttons aufbereiten
            if (typeof data.buttons !== 'undefined' && typeof data.buttons === 'object') {
                data.buttons.forEach(function (button) {
                    if (typeof button.text === 'undefined' || typeof button.link === 'undefined') {
                        console.warn('Could not create Notify button. Required property \'text\' oder \'link\' is missing');
                        return;
                    }
                    if (typeof button.addClass === 'undefined') {
                        button.addClass = 'btn notification-button';
                    } else {
                        button.addClass += ' btn notification-button';
                    }
                });
            }

            if (data.type === 'push') {
                me.createPushNotification(title, message, hasPriority);
            } else {
                me.createFromData(data);
            }
        },

        /**
         * Benachrichtigung erzeugen
         *
         * @param {object} data
         */
        createFromData: function (data) {
            // ID, zur Wiedererkennung über alle Tabs/Fenster, generieren und zuweisen
            if (typeof data.id === 'undefined' || data.id === null) {
                data.id = me.generateRandomId();
            }
            if (typeof data.timestamp === 'undefined' || data.timestamp === null) {
                data.timestamp = Date.now();
            }
            if (typeof data.type === 'undefined') {
                data.type = 'default';
            }

            switch (data.type) {
                case 'default':
                    data.type = 'alert';
                    break;
                case 'notice':
                    data.type = 'information';
                    break;
                case 'push':
                    return;
            }

            if (me.has(data.id)) {
                // Es gibt schon eine Notification mit dieser ID > Notification aktualisieren
                me.updateNotificationInOwnTab(data.id, data);
                me.updateNotificationInOtherTabs(data.id, data);
            } else {
                // Neue Notification anlegen
                me.createNotificationInOwnTab(data.id, data);
                me.createNotificationInOtherTabs(data.id, data);
            }
        },

        /**
         * Benachrichtigung schließen
         *
         * @param {string} key
         */
        close: function (key) {
            me.closeNotificationInOwnTab(key);
            me.closeNotificationInOtherTabs(key);
        },

        /**
         * Alle Benachrichtigungen schließen
         */
        closeAll: function () {
            me.closeAllNotificationsInOwnTab();
            me.closeAllNotificationsInOtherTabs();
        },

        /* ------\/------ Private Methoden ------\/------ */


        /**
         * Geöffnete Benachrichtigungen wiederherstellen
         */
        restoreFromLocalStorage: function () {
            var restored = me.collectFromLocalStorage();

            // Zeitliche Reihenfolge wiederherstellen
            restored.sort(function (a, b) {
                return a.timestamp - b.timestamp;
            });

            // Benachrichtigungen erzeugen
            restored.forEach(function (data) {
                me.createNotificationInOwnTab(data.id, data);
            });
        },

        /**
         * Benachrichtigungen aus LocalStorage holen
         *
         * @return {Array}
         */
        collectFromLocalStorage: function () {
            var notifications = [];

            for (var key in localStorage) {
                if (key === me.settings.storageKeyProgressBar) {
                    continue;
                }
                if (key.substr(0, 13) !== me.settings.storageKeyPrefix) {
                    continue;
                }
                if (localStorage.hasOwnProperty(key)) {
                    var store = localStorage.getItem(key);
                    var data = JSON.parse(store);

                    // Push-Benachrichtigungen nicht wiederherstellen
                    if (typeof data.type !== 'undefined' && data.type === 'push') {
                        continue;
                    }

                    notifications.push(data);
                }
            }

            return notifications;
        },

        /**
         * Ton abspielen
         */
        playSound: function () {
            try {
                var bell = new Audio('./sound/pling.mp3');
                bell.play();
            } catch (e) {
                // Sound abspielen funktioniert auf neueren Chromes nicht mehr:
                // https://developers.google.com/web/updates/2017/09/autoplay-policy-changes
            }
        },

        /**
         * Benachrichtigung im eigenen Fenster/Tab erstellen
         *
         * @param {string} key
         * @param {object} data
         */
        createNotificationInOwnTab: function (key, data) {
            if (typeof key === 'string' && typeof data === 'object') {
                var item = noty(data);
                if (data.sticky === true) {
                    item.$bar.addClass('sticky');
                }

                // Events für Fortschrittsbalken
                if (item.$progressBar && item.options.progressBar) {
                    item.$bar.on('mouseenter', function () {
                        me.resetProgressBarInOtherTabs(item.options.id);
                    });
                    item.$bar.on('mouseleave', function () {
                        me.startProgressBarInOtherTabs(item.options.id);
                    });
                }

                // Buttons wiederherstellen
                if (typeof item.options.buttons !== 'undefined' && typeof item.options.buttons === 'object') {
                    item.options.buttons.forEach(function (button) {

                        // Data-Attribute wiederherstellen
                        var $button = $('#' + button.id);
                        $.each(button, function (property, value) {
                            if (property.substr(0, 5) !== 'data-') {
                                return;
                            }
                            var dataName = property.substr(5);
                            $button.data(dataName, value);
                        });

                        // onClick-Methode wiederherstellen
                        button.onClick = function ($noty) {
                            var event = jQuery.Event('notification-button:clicked');
                            $(document).trigger(event, button);

                            if (!event.isDefaultPrevented()) {
                                $noty.close();
                                window.location.href = button.link;
                            }
                        };
                    });
                }

                // Custom Event feuern
                $(document).trigger('notification:created', data);
            }
        },

        /**
         * Benachrichtigung in allen anderen Fenstern/Tabs erstellen
         *
         * @param {string} key
         * @param {object} data
         */
        createNotificationInOtherTabs: function (key, data) {
            if (typeof key === 'string' && typeof data === 'object') {
                localStorage.setItem(key, JSON.stringify(data));
            }
        },

        /**
         * Browser-Benachrichtigung erzeugen
         *
         * @param {string} title
         * @param {string|null} message
         * @param {boolean} hasPriority
         */
        createPushNotification: function (title, message, hasPriority) {
            if (typeof PushJS === 'undefined') {
                throw 'push.js wurde nicht gefunden!';
            }
            if (typeof title === 'undefined' || title === null) {
                message = '';
            }
            if (typeof message === 'undefined' || message === null) {
                message = '';
            }
            if (typeof hasPriority === 'undefined') {
                hasPriority = false;
            }
            if (title === '' && message === '') {
                return;
            }

            var data = {
                icon: './js/images/push-icon.png',
                onClick: function () {
                    window.focus();
                    this.close();
                }
            };
            if (message !== '') {
                data.body = message;
            }
            if (hasPriority === false) {
                data.tag = 'default';
            }

            // Nicht-Prio-Nachrichten löschen
            // (Prio-Nachrichten werden gestacked)
            PushJS.close('default');

            // Push-Nachricht erzeugen
            PushJS.create(title, data);
        },

        /**
         * Vorhandene Benachrichtigung aktualisieren
         *
         * @param {string} notifyId ID der Notification
         * @param {object} notifyData
         */
        updateNotificationInOwnTab: function (notifyId, notifyData) {
            me.updateNotificationType(notifyId, notifyData.type);
            me.updateNotificationText(notifyId, notifyData.text);
            // @todo me.updateNotificationButtons(notifyId, notifyData.buttons);
        },

        /**
         * Vorhandene Benachrichtigung aktualisieren
         *
         * @param {string} key ID der Notification
         * @param {object} data
         */
        updateNotificationInOtherTabs: function (key, data) {
            if (typeof key === 'string' && typeof data === 'object') {
                localStorage.setItem(key, JSON.stringify(data));
            }
        },

        /**
         * Benachrichtigung im eigenen Fenster/Tab schließen
         *
         * @param {string} key
         */
        closeNotificationInOwnTab: function (key) {
            if (typeof key === 'undefined') {
                return;
            }
            $.noty.close(key);
        },

        /**
         * Benachrichtigung in allen anderen Fenstern/Tabs schließen
         *
         * @param {string} key
         */
        closeNotificationInOtherTabs: function (key) {
            localStorage.removeItem(key);
        },

        /**
         * Alle Benachrichtigungen im eigenen Fenster/Tab schließen
         */
        closeAllNotificationsInOwnTab: function () {
            $.noty.closeAll();
        },

        /**
         * Alle Benachrichtigungen in allen anderen Fenstern/Tabs schließen
         */
        closeAllNotificationsInOtherTabs: function () {
            for (var key in localStorage) {
                if (key.substr(0, 13) !== me.settings.storageKeyPrefix) {
                    continue;
                }
                if (localStorage.hasOwnProperty(key)) {
                    me.closeNotificationInOtherTabs(key);
                }
            }
        },

        /**
         * Text einer vorhandenen Benachrichtigung aktualisieren
         *
         * @param {string} notifyId
         * @param {string} text
         */
        updateNotificationText: function(notifyId, text) {
            var existing = me.get(notifyId);
            if (existing === false) {
                return;
            }

            existing.$message.find('.noty_text').html(text);
        },

        /**
         * Typ einer vorhandenen Benachrichtigung aktualisieren
         *
         * @param {string} notifyId
         * @param {string} type
         */
        updateNotificationType: function(notifyId, type) {
            var existing = me.get(notifyId);
            if (existing === false) {
                return;
            }

            var newOuterClassName = 'noty_container_type_' + type;
            var $outer = existing.$bar;
            if (!$outer.hasClass(newOuterClassName)) {
                var classList = $outer.attr('class').split(/\s+/);
                $.each(classList, function(index, className) {
                    if (className.substring(0, 20) === 'noty_container_type_') {
                        $outer.removeClass(className);
                    }
                });
                $outer.addClass(newOuterClassName);
            }

            var newInnerClassName = 'noty_type_' + type;
            var $inner = existing.$bar.find('.noty_bar');
            if (!$inner.hasClass(newInnerClassName)) {
                var innerClasses = $inner.attr('class').split(/\s+/);
                $.each(innerClasses, function (index, className) {
                    if (className.substring(0, 10) === 'noty_type_') {
                        $inner.removeClass(className);
                    }
                });
                $inner.addClass(newInnerClassName);
            }
        },

        /**
         * Fortschrittsbalken im eigenen Tab/Fenster zurücksetzen
         *
         * @param {string} notifyId
         */
        resetProgressBarInOwnTab: function (notifyId) {
            var $noty = $.noty.get(notifyId);
            if (typeof $noty !== 'object') {
                return;
            }

            // Nicht alle Benachrichtigungen haben einen Fortschrittsbalken
            if ($noty.options.progressBar && $noty.$progressBar) {
                $noty.dequeueClose();
            }
        },

        /**
         * Fortschrittsbalken im eigenen Tab/Fenster wieder starten
         *
         * @param {string} notifyId
         */
        startProgressBarInOwnTab: function (notifyId) {
            var $noty = $.noty.get(notifyId);
            if (typeof $noty !== 'object') {
                return;
            }

            // Nicht alle Benachrichtigungen haben einen Fortschrittsbalken
            if ($noty.options.progressBar && $noty.$progressBar) {
                $noty.queueClose($noty.options.timeout);
            }
        },

        /**
         * Fortschrittsbalken in anderen Tabs/Fenstern zurücksetzen
         *
         * @param {string} notifyId
         */
        resetProgressBarInOtherTabs: function (notifyId) {
            var data = {
                id: notifyId,
                date: Date.now(),
                action: 'reset'
            };

            localStorage.setItem(me.settings.storageKeyProgressBar, JSON.stringify(data));
        },

        /**
         * Fortschrittsbalken in anderen Tabs/Fenstern wieder starten
         *
         * @param {string} notifyId
         */
        startProgressBarInOtherTabs: function (notifyId) {
            var data = {
                id: notifyId,
                date: Date.now(),
                action: 'start'
            };

            localStorage.setItem(me.settings.storageKeyProgressBar, JSON.stringify(data));
        },

        /**
         * Horcht auf Änderungen im LocalStorage
         *
         * @param {StorageEvent} e
         */
        storageHandler: function (e) {
            // LocalStorage wurde komplett geleert
            if (typeof e === 'undefined') {
                return;
            }

            // Fortschrittsbalken zurücksetzen/neustarten
            if (e.key === me.settings.storageKeyProgressBar) {
                if (e.newValue === null) {
                    return;
                }
                var progressData = JSON.parse(e.newValue);
                if (progressData.action === 'reset') {
                    me.resetProgressBarInOwnTab(progressData.id);
                }
                if (progressData.action === 'start') {
                    me.startProgressBarInOwnTab(progressData.id);
                }

                localStorage.removeItem(me.settings.storageKeyProgressBar);
                return;
            }

            // Nur auf bestimmten Key horchen
            if (e.key.substr(0, 13) !== me.settings.storageKeyPrefix) {
                return;
            }

            // LocalStorage(-Key) wurde gelöscht > Notification schließen
            if (e.newValue === null) {
                me.close(e.key);
                return;
            }

            var received = JSON.parse(e.newValue);
            if (received === null) {
                return;
            }

            // Daten wurden empfangen
            if (typeof received === 'object' && typeof received.id === 'string') {
                if (me.has(received.id)) {
                    // Vorhandene Notification aktualisieren
                    me.updateNotificationInOwnTab(received.id, received);
                } else {
                    // Notification erstellen
                    me.createNotificationInOwnTab(received.id, received);
                }
            }
        },

        /**
         * Zufällige ID generieren
         *
         * @return {string}
         */
        generateRandomId: function () {
            return me.settings.storageKeyPrefix + Math.floor(Math.random() * Math.floor(9999999999));
        },

        /**
         * @return {boolean}
         */
        isIframe: function () {
            return window.location !== window.parent.location;
        }
    };

    return {
        has: me.has,
        get: me.get,
        keys: me.keys,
        init: me.init,
        create: me.create,
        createFromData: me.createFromData,
        close: me.close,
        closeAll: me.closeAll
    };

}(jQuery, Push);

$(document).ready(Notify.init);


