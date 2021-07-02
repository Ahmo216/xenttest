tinymce.PluginManager.add("image",function(a){function b(a,b){function c(a,c){d.parentNode&&d.parentNode.removeChild(d),b({width:a,height:c})}var d=document.createElement("img");d.onload=function(){c(d.clientWidth,d.clientHeight)},d.onerror=function(){c()};var e=d.style;e.visibility="hidden",e.position="fixed",e.bottom=e.left=0,e.width=e.height="auto",document.body.appendChild(d),d.src=a}function c(a,b,c){function d(a,c){return c=c||[],tinymce.each(a,function(a){var e={text:a.text||a.title};a.menu?e.menu=d(a.menu):(e.value=a.value,b(e)),c.push(e)}),c}return d(a,c||[])}function d(b){return function(){var c=a.settings.image_list;"string"==typeof c?tinymce.util.XHR.send({url:c,success:function(a){b(tinymce.util.JSON.parse(a))}}):"function"==typeof c?c(b):b(c)}}function e(d){function e(){var a,b,c,d;a=j.find("#width")[0],b=j.find("#height")[0],a&&b&&(c=a.value(),d=b.value(),j.find("#constrain")[0].checked()&&k&&l&&c&&d&&(k!=c?(d=Math.round(c/k*d),b.value(d)):(c=Math.round(d/l*c),a.value(c))),k=c,l=d)}function f(){function b(b){function c(){b.onload=b.onerror=null,a.selection&&(a.selection.select(b),a.nodeChanged())}b.onload=function(){o.width||o.height||!r||p.setAttribs(b,{width:b.clientWidth,height:b.clientHeight}),c()},b.onerror=c}i(),e(),o=tinymce.extend(o,j.toJSON()),o.alt||(o.alt=""),""===o.width&&(o.width=null),""===o.height&&(o.height=null),o.style||(o.style=null),o={src:o.src,alt:o.alt,width:o.width,height:o.height,style:o.style,"class":o["class"]},a.undoManager.transact(function(){return o.src?(q?p.setAttribs(q,o):(o.id="__mcenew",a.focus(),a.selection.setContent(p.createHTML("img",o)),q=p.get("__mcenew"),p.setAttrib(q,"id",null)),void b(q)):void(q&&(p.remove(q),a.focus(),a.nodeChanged()))})}function g(a){return a&&(a=a.replace(/px$/,"")),a}function h(c){var d=c.meta||{};if(m&&m.value(a.convertURL(this.value(),"src")),tinymce.each(d,function(a,b){j.find("#"+b).value(a)}),!d.width&&!d.height){var e=this.value(),f=new RegExp("^(?:[a-z]+:)?//","i"),g=a.settings.document_base_url;g&&!f.test(e)&&e.substring(0,g.length)!==g&&this.value(g+e),b(this.value(),function(a){a.width&&a.height&&r&&(k=a.width,l=a.height,j.find("#width").value(k),j.find("#height").value(l))})}}function i(){function b(a){return a.length>0&&/^[0-9]+$/.test(a)&&(a+="px"),a}if(a.settings.image_advtab){var c=j.toJSON(),d=p.parseStyle(c.style);delete d.margin,d["margin-top"]=d["margin-bottom"]=b(c.vspace),d["margin-left"]=d["margin-right"]=b(c.hspace),d["border-width"]=b(c.border),j.find("#style").value(p.serializeStyle(p.parseStyle(p.serializeStyle(d))))}}var j,k,l,m,n,o={},p=a.dom,q=a.selection.getNode(),r=a.settings.image_dimensions!==!1;k=p.getAttrib(q,"width"),l=p.getAttrib(q,"height"),"IMG"!=q.nodeName||q.getAttribute("data-mce-object")||q.getAttribute("data-mce-placeholder")?q=null:o={src:p.getAttrib(q,"src"),alt:p.getAttrib(q,"alt"),"class":p.getAttrib(q,"class"),width:k,height:l},d&&(m={type:"listbox",label:"Image list",values:c(d,function(b){b.value=a.convertURL(b.value||b.url,"src")},[{text:"None",value:""}]),value:o.src&&a.convertURL(o.src,"src"),onselect:function(a){var b=j.find("#alt");(!b.value()||a.lastControl&&b.value()==a.lastControl.text())&&b.value(a.control.text()),j.find("#src").value(a.control.value()).fire("change")},onPostRender:function(){m=this}}),a.settings.image_class_list&&(n={name:"class",type:"listbox",label:"Class",values:c(a.settings.image_class_list,function(b){b.value&&(b.textStyle=function(){return a.formatter.getCssText({inline:"img",classes:[b.value]})})})});var s=[{name:"src",type:"filepicker",filetype:"image",label:"Source",autofocus:!0,onchange:h},m];a.settings.image_description!==!1&&s.push({name:"alt",type:"textbox",label:"Image description"}),r&&s.push({type:"container",label:"Dimensions",layout:"flex",direction:"row",align:"center",spacing:5,items:[{name:"width",type:"textbox",maxLength:5,size:3,onchange:e,ariaLabel:"Width"},{type:"label",text:"x"},{name:"height",type:"textbox",maxLength:5,size:3,onchange:e,ariaLabel:"Height"},{name:"constrain",type:"checkbox",checked:!0,text:"Constrain proportions"}]}),s.push(n),a.settings.image_advtab?(q&&(o.hspace=g(q.style.marginLeft||q.style.marginRight),o.vspace=g(q.style.marginTop||q.style.marginBottom),o.border=g(q.style.borderWidth),o.style=a.dom.serializeStyle(a.dom.parseStyle(a.dom.getAttrib(q,"style")))),j=a.windowManager.open({title:"Insert/edit image",data:o,bodyType:"tabpanel",body:[{title:"General",type:"form",items:s},{title:"Advanced",type:"form",pack:"start",items:[{label:"Style",name:"style",type:"textbox"},{type:"form",layout:"grid",packV:"start",columns:2,padding:0,alignH:["left","right"],defaults:{type:"textbox",maxWidth:50,onchange:i},items:[{label:"Vertical space",name:"vspace"},{label:"Horizontal space",name:"hspace"},{label:"Border",name:"border"}]}]}],onSubmit:f})):j=a.windowManager.open({title:"Insert/edit image",data:o,body:s,onSubmit:f})}a.addButton("image",{icon:"image",tooltip:"Insert/edit image",onclick:d(e),stateSelector:"img:not([data-mce-object],[data-mce-placeholder])"}),a.addMenuItem("image",{icon:"image",text:"Insert image",onclick:d(e),context:"insert",prependToContext:!0}),a.addCommand("mceImage",d(e))});