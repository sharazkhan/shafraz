// Copyright Zikula Foundation 2010 - license GNU/LGPLv3 (or at your option, any later version).
Zikula.define("UI");Zikula.UI.Key=Class.create(HotKey,{initialize:function($super,b,c,a){a=Object.extend({ctrlKey:false},a||{});$super(b,c,a)}});Zikula.UI.Tooltips=function(b,a){$A(b).each(function(c){new Zikula.UI.Tooltip($(c),null,a)})};Zikula.UI.Tooltip=Class.create(Control.ToolTip,{initialize:function($super,a,c,b){b=Object.extend({className:"z-tooltip",offsetTop:20,offsetLeft:15,iframeshim:Zikula.Browser.IE},b||{});if(!c){if(a.hasAttribute("title")){c=a.readAttribute("title");a.store("title",c);a.writeAttribute("title","");if(c.startsWith("#")){this.tooltipContent=$(c.replace("#",""));document.body.insert(this.tooltipContent);c=this.tooltipContent}}}$super(a,c,b)},position:function($super,a){var e=this.container.getDimensions(),b=document.viewport.getDimensions(),c=document.viewport.getScrollOffsets(),d={v:$value(this.options.offsetTop),h:$value(this.options.offsetLeft)},h=a?Event.pointerX(a):this.sourceContainer.getLayout().get("left"),g=a?Event.pointerY(a):this.sourceContainer.getLayout().get("top"),f={left:"auto",right:"auto",top:"auto",bottom:"auto"};if(h+e.width+(d.h*2)<b.width||e.width+d.h>b.width){f.left=(h+d.h+c.left).toUnits()}else{f.right=(b.width-h+d.h).toUnits()}if(g+e.height+d.v<c.top+b.height||e.height+d.v>b.height){f.top=(g+d.v).toUnits()}else{f.top=(b.height+c.top-e.height-d.v).toUnits()}this.container.setStyle(f)},destroy:function($super){if(this.sourceContainer){this.sourceContainer.writeAttribute("title",this.sourceContainer.retrieve("title"))}if(this.tooltipContent){this.sourceContainer.insert(this.tooltipContent)}$super()}});Zikula.UI.WindowTemplate=function(a){return{container:new Element("div",{className:"z-window-container"}),header:new Element("div",{className:"z-window-header"}),title:new Element("div",{className:"z-window-title"}).update("&nbsp;"),close:new Element("div",{className:"z-window-close z-window-control"}),minimize:new Element("div",{className:"z-window-minimize z-window-control"}).hide(),maximize:new Element("div",{className:"z-window-maximize z-window-control"}).hide(),body:new Element("div",{className:"z-window-body"}),indicator:new Element("div",{className:"z-window-indicator"}),footer:new Element("div",{className:"z-window-footer"}).update("&nbsp;")}};Zikula.UI.Window=Class.create(Control.Window,{initialize:function($super,a,b){this.setWindowType(a);this.window=Zikula.UI.WindowTemplate(b);a=this.initContainer(a,b);b=Object.extend({className:"z-window",minmax:true,width:400,initMaxHeight:400,offset:[0,0],indicator:this.window.indicator,overlayOpacity:0.3,method:"get",modal:false,destroyOnClose:false,iframeshim:Zikula.Browser.IE,closeOnClick:this.window.close,draggable:this.window.header,insertRemoteContentAt:this.window.body,autoClose:0},b||{});if(b.modal){b.minmax=false;Control.Modal.InstanceMethods.beforeInitialize.bind(this)()}$super(a,b);if(this.draggable){this.draggable.options.scroll=window}this.finishContainer();this.setWindowMaxSize();this.setWindowMaxSizeHandler=this.setWindowMaxSize.bindAsEventListener(this);Event.observe(window,"resize",this.setWindowMaxSizeHandler,false);this.openHandler=this.open.bindAsEventListener(this);this.closeHandler=this.close.bindAsEventListener(this);this.key=new Zikula.UI.Key("esc",this.closeHandler,{element:this.container})},bringToFront:function($super){$super();if(!this.container.hasClassName("active")){$$(".z-window.active").invoke("removeClassName","active");this.container.addClassName("active");this.focusWindow()}},setWindowMaxSize:function(){var a=document.viewport.getDimensions();this.container.setStyle({maxWidth:(a.width-this.container.getOutlineSize("h")-this.options.offset[0]).toUnits(),maxHeight:(a.height-this.container.getOutlineSize()-this.options.offset[1]).toUnits()})},getTopOffset:function(){return this.window.header.getHeight()+this.window.header.getOutlineSize()},getBottomOffset:function(){return this.window.footer.getHeight()+this.window.footer.getOutlineSize()},getWindowHeight:function(){if(this.calculated){return this.container.getHeight()}var b=this.container.getHeight(),d=this.getTopOffset(),a=this.window.body.getHeight(),c=this.getBottomOffset();if(this.windowType=="ajax"&&this.options.initMaxHeight){b=this.options.initMaxHeight}else{if(b<d+a+c){b=b+d+c}}this.calculated=true;return b},open:function(c){if(this.isOpen){this.bringToFront();return false}if(this.notify("beforeOpen")===false){return false}if(this.options.closeOnClick){if(this.options.closeOnClick===true){this.closeOnClickContainer=$(document.body)}else{if(this.options.closeOnClick=="container"){this.closeOnClickContainer=this.container}else{if(this.options.closeOnClick=="overlay"){Control.Overlay.load();this.closeOnClickContainer=Control.Overlay.container}else{this.closeOnClickContainer=$(this.options.closeOnClick)}}}this.closeOnClickContainer.observe("click",this.closeHandler)}if(this.href&&!this.options.iframe&&!this.remoteContentLoaded){this.remoteContentLoaded=true;if(this.href.match(/\.(jpe?g|gif|png|tiff?)$/i)){var a=new Element("img");a.observe("load",function(d){this.getRemoteContentInsertionTarget().insert(d);this.position();if(this.notify("onRemoteContentLoaded")!==false){if(this.options.indicator){this.hideIndicator()}this.finishOpen()}}.bind(this,a));a.writeAttribute("src",this.href)}else{if(!this.ajaxRequest){if(this.options.indicator){this.showIndicator()}this.ajaxRequest=new Zikula.Ajax.Request(this.href,{method:this.options.method,parameters:this.options.parameters,onComplete:function(d){this.notify("onComplete",d);this.ajaxRequest=false}.bind(this),onSuccess:function(d){this.getRemoteContentInsertionTarget().insert(d.responseText);this.notify("onSuccess",d);if(this.notify("onRemoteContentLoaded")!==false){if(this.options.indicator){this.hideIndicator()}this.finishOpen()}}.bind(this),onFailure:function(d){this.notify("onFailure",d);if(this.options.indicator){this.hideIndicator()}}.bind(this),onException:function(d,f){this.notify("onException",d,f);if(this.options.indicator){this.hideIndicator()}}.bind(this)})}}return true}else{if(this.options.iframe&&!this.remoteContentLoaded){this.remoteContentLoaded=true;if(this.options.indicator){this.showIndicator()}this.getRemoteContentInsertionTarget().insert(Control.Window.iframeTemplate.evaluate({href:this.href}));var b=this.container.down("iframe");b.onload=function(){this.notify("onRemoteContentLoaded");if(this.options.indicator){this.hideIndicator()}b.onload=null}.bind(this)}}if(Object.isNumber(this.options.autoClose)&&this.options.autoClose>0){this.autoClose=this.close.bind(this).delay(this.options.autoClose)}this.finishOpen(c);return true},finishOpen:function($super,d){$super(d);this.container.setHeight(this.getWindowHeight());var a={position:"absolute",maxHeight:"none",top:this.getTopOffset().toUnits(),bottom:this.getBottomOffset().toUnits()};if(this.indicator){this.window.indicator.setStyle(a);if(Zikula.Browser.IE){var c=this.container,b=this.indicator;new PeriodicalExecuter(function(e){if(c.down("iframe")){var f=c.down("iframe");if(f.document.readyState=="complete"){e.stop();b.hide()}}else{e.stop();b.hide()}},1)}}if(this.window.body.down("iframe")){this.window.body.addClassName("iframe")}this.window.body.setStyle(a);this.initialWidth=this.container.getWidth();this.ensureInBounds();this.position();this.focusWindow();return true},ensureInBounds:function(){if(!this.isOpen){return}var b=document.viewport.getDimensions(),a=document.viewport.getScrollOffsets(),e=this.container.cumulativeOffset(),c=this.container.getDimensions(),d={};if(e[1]<a[1]){d.top=a[1].toUnits()}else{if(e[1]+c.height>a[1]+b.height){d.top=(a[1]+b.height-c.height).toUnits()}}if(e[0]<a[0]){d.left=a[0].toUnits()}else{if(e[0]+c.width>a[0]+b.width){d.left=(a[0]+b.width-c.width).toUnits()}}if(d.top||d.left){this.container.setStyle(d)}},close:function($super,b){if(Object.isNumber(this.autoClose)){window.clearTimeout(this.autoClose)}this.restore(b);this.pos={};if(this.initialWidth){this.container.setStyle({width:this.initialWidth.toUnits()})}$super.defer(b);if(this.options.destroyOnClose){this.destroy()}var c=Control.Window.windows.clone(),a;while(a=c.pop()){if(a.isOpen){a.bringToFront();break}}return true},toggleMax:function(a){if(this.container.hasClassName("z-maximized")){this.restore(a);this.restorePosition(a)}else{this.maximize(a)}},toggleMin:function(a){if(this.container.hasClassName("z-minimized")){this.restore(a);this.restorePosition(a)}else{this.minimize(a)}},maximize:function(a){this.savePosition();this.restore(a);this.container.addClassName("z-maximized");$(document.body).setStyle({overflow:"hidden"});if(this.draggable){Draggable._dragging[this.container]=true}},minimize:function(a){this.savePosition();this.restore(a);this.container.addClassName("z-minimized");if(this.draggable){this.draggable.options.constraint="horizontal"}},restore:function(a){this.container.removeClassName("z-minimized");this.container.removeClassName("z-maximized");$(document.body).setStyle({overflow:"visible"});if(this.draggable){this.draggable.options.constraint=false;Draggable._dragging[this.container]=false}},savePosition:function(){if(!this.container.hasClassName("z-minimized")&&!this.container.hasClassName("z-maximized")){var a=this.container.getDimensions(),b=this.container.viewportOffset();this.pos={top:b[1],left:b[0],width:a.width.toUnits(),height:a.height.toUnits()}}},restorePosition:function(){if(this.pos){var a=document.viewport.getScrollOffsets();this.pos.top=(a[1]+this.pos.top).toUnits();this.pos.left=(a[0]+this.pos.left).toUnits();this.container.setStyle(this.pos)}},setWindowType:function(a){this.windowType="string";if(Object.isElement(a)){this.windowType="element";if(a.hasAttribute("href")){var b=a.readAttribute("href");if(b.startsWith("#")){this.windowType="relelement"}else{this.windowType="ajax"}}}},focusWindow:function(){try{this.container.focus()}catch(a){}},applyDraggable:function($super){$super();if(this.options.iframe){this.window.body.insert({top:new Element("div",{"class":"iframe-overlay"}).hide()});this.draggable.options.onStart=function(a){a.element.down(".iframe-overlay").show()};this.draggable.options.onEnd=function(a){a.element.down(".iframe-overlay").hide()}}},applyResizable:function($super){$super();var b=this.container.down(".resizable_handle"),a=function(c){c.stop()};if(b){Resizables.addObserver({onStart:function(){$(document.body).observe("selectstart",a);$(document.body).observe("mousedown",a);b.addClassName("onresize")},onEnd:function(){$(document.body).stopObserving("selectstart",a);$(document.body).stopObserving("mousedown",a);b.removeClassName("onresize")}})}},initContainer:function(b){if(this.windowType=="relelement"){this.insertContainer();var c=b.readAttribute("href");var a=c.match(/^#(.+)$/);if(a&&a[1]){this.window.body.insert($(a[1]).show());this.window.container.id="Zikula_UI_Window_"+a[1];b.writeAttribute("href","#"+this.window.container.id)}}else{if(this.windowType=="element"){this.insertContainer();this.window.body.insert(b.show());this.window.container.id="Zikula_UI_Window_"+b.identify();b=this.window.container}}return b},insertContainer:function(){if(!this.container){$(document.body).insert(this.window.container)}this.window.container.insert(this.window.header);this.window.header.insert(this.window.title);this.window.header.insert(this.window.minimize);this.window.header.insert(this.window.maximize);this.window.header.insert(this.window.close);this.window.container.insert(this.window.body);this.window.container.insert(this.window.footer);this.window.container.insert(this.window.indicator.hide());this.inserted=true},finishContainer:function(){this.window.container.writeAttribute("tabindex","-1");if(this.options.title){this.window.title.update(this.options.title)}else{if(this.sourceContainer&&this.sourceContainer.hasAttribute("title")){this.window.title.update(this.sourceContainer.readAttribute("title"))}}if(this.options.draggable){this.window.container.addClassName("z-draggable")}if(this.options.modal){this.window.container.addClassName("z-modal")}if(this.options.resizable){this.window.container.addClassName("z-resizable")}if(this.options.minmax){this.window.minimize.show().observe("click",this.toggleMin.bindAsEventListener(this));this.window.maximize.show().observe("click",this.toggleMax.bindAsEventListener(this))}},createDefaultContainer:function(a){if(!this.container){this.window.container.id="Zikula_UI_Window_"+this.numberInSequence;this.container=this.window.container;$(document.body).insert(this.container);this.insertContainer();if(typeof(a)=="string"&&$(a)==null&&!a.match(/^#(.+)$/)&&!a.match(Control.Window.uriRegex)){this.window.body.update(a)}}}});Zikula.UI.Dialog=Class.create(Zikula.UI.Window,{initialize:function($super,a,c,b){b=Object.extend({className:"z-window z-dialog",callback:Prototype.emptyFunction},b||{});b.afterClose=this.notifyCallback.curry(false);$super(a,b);this.window.footer.addClassName("z-buttons");this.buttons={};this.insertButtons(c)},open:function($super,a){this.isNotified=false;$super(a)},focusWindow:function(){try{this.buttons[Object.keys(this.buttons)[0]].focus()}catch(a){}},notifyCallback:function(a){if(!this.isNotified){this.options.callback(a);this.isNotified=typeof a.close!=="undefined"?a.close:true}},insertButtons:function(a){$A(a).each(function(b){this.button(b)}.bind(this))},button:function(b){var d=b.action||this.notifyCallback.bindAsEventListener(this),c={},a=Object.keys(b).intersect($w("id class lang dir title style disabled accesskey tabindex name value type"));a.each(function(f){c[f]=b[f]});b.close=typeof b.close!=="undefined"?b.close:true;var e=new Element("button",c).update(b.label);this.window.footer.insert(e);this.buttons[e.identify()]=e;if(Object.isFunction(d)){e.observe("click",d.curry(b))}if(b.close){e.observe("click",this.close.bindAsEventListener(this))}}});Zikula.UI.Alert=function(d,c,a){a=Object.extend({destroyOnClose:true,title:c},a);var b=new Zikula.UI.AlertDialog(d,a);b.open();return b};Zikula.UI.AlertDialog=Class.create(Zikula.UI.Dialog,{initialize:function($super,a,b){b=Object.extend({className:"z-window z-dialog z-alert",minmax:false},b||{});$super(a,this.defaultButtons(this.notifyCallback.bind(this)),b)},defaultButtons:function(a){return[{label:Zikula.__("Ok"),"class":"z-btgreen"}]}});Zikula.UI.IfConfirmed=function(c,b,d,a){return Zikula.UI.Confirm.curry(c,b,d,a)};Zikula.UI.Confirm=function(d,c,e,a){a=Object.extend({destroyOnClose:true,title:c,callback:e},a);var b=new Zikula.UI.ConfirmDialog(d,a);b.open();return b};Zikula.UI.ConfirmDialog=Class.create(Zikula.UI.Dialog,{initialize:function($super,a,b){b=Object.extend({className:"z-window z-dialog z-confirm",minmax:false},b||{});$super(a,this.defaultButtons(this.notifyCallback.bind(this)),b)},defaultButtons:function(a){return[{label:Zikula.__("Ok"),action:a.curry(true),"class":"z-btgreen"},{label:Zikula.__("Cancel"),action:a.curry(false),"class":"z-btred"}]}});Zikula.UI.FormDialog=Class.create(Zikula.UI.Dialog,{initialize:function($super,a,c,b){b=Object.extend({className:"z-window z-dialog z-form",width:500,ajaxRequest:false,resizable:true,callback:c},b||{});$super(a,b.buttons||this.defaultButtons(this.notifyCallback.bind(this)),b)},focusWindow:function(){try{this.container.down("form").focusFirstElement()}catch(a){}},serialize:function(a){try{return this.container.down("form").serialize(a)}catch(b){return a?{}:""}},notifyCallback:function(c){if(!this.isNotified){var f=this.container.down("form"),b={},a;if(c&&c.name){b[c.name]=c.value}if(f.action&&f.readAttribute("action")!="#"&&c){this.isNotified=true;if(this.options.ajaxRequest){f.request({parameters:b,onComplete:this.options.callback.bind(this)})}else{if(c&&c.name){var d=c.name=="submit"?"submit":"hidden";f.insert(new Element("input",{type:d,name:c.name,value:c.value}))}try{f.submit()}catch(g){f.submit.click()}}}else{if(c){a=Object.extend(f.serialize(true),b)}else{a=false}this.options.callback(a);this.isNotified=typeof c.close!=="undefined"?c.close:true}}},defaultButtons:function(a){return[{label:Zikula.__("Submit"),type:"submit",name:"submit",value:"submit","class":"z-btgreen"},{label:Zikula.__("Cancel"),action:a.curry(false),"class":"z-btred"}]}});Zikula.UI.SelectMultiple=Class.create(Control.SelectMultiple,{initialize:function($super,a,c){if(c&&c.afterChange){this.origAfterChange=c.afterChange}c=Object.extend({nameSelector:"label",valueSeparator:",",excludeValues:[],opener:null,title:Zikula.__("Select multiple"),windowTitle:null,okLabel:Zikula.__("Ok"),afterChange:this.afterChange.bind(this)},c||{});a=$(a);if(!c.value){var d=a.select("option[selected]");c.value=d.pluck("value").join(c.valueSeparator);d.invoke("writeAttribute","selected",false)}var b=this.buildContainer(a,c);$super(a,b,c)},buildContainer:function(b,e){var g=e.opener||null,f=b.identify(),a=f+"_opener",d=f+"_options";if(!g){g=new Element("a",{id:a,href:"#"+d,title:e.windowTitle||e.title}).update(e.title);b.insert({after:g})}else{g=$(g);g.writeAttribute("href","#"+d)}var c=new Element("div",{id:d,"class":"z-select-multiple z-form"});$(g).insert({after:c});e.excludeValues=$A(Object.isArray(e.excludeValues)?e.excludeValues:[e.excludeValues])||[];b.select("option").each(function(h){if(!e.excludeValues.include(h.value)){c.insert(new Element("div",{"class":"z-formrow"}).insert(new Element("label",{"for":h.identify()+"m"}).update(h.text)).insert(new Element("input",{id:h.identify()+"m",name:b.name+"[]",type:"checkbox",value:h.value,checked:h.selected})))}});this.dialog=new Zikula.UI.Dialog(g,[{label:e.okLabel}],{position:"relative"});return c},afterChange:function(a){this.checkboxes.each(function(b){if(b.checked){b.up("div.z-formrow").addClassName("selected")}else{b.up("div.z-formrow").removeClassName("selected")}});if(this.origAfterChange){this.origAfterChange(a)}}});Zikula.UI.Tabs=Class.create(Control.Tabs,{initialize:function($super,b,a){a=Object.extend({equal:false,containerClass:"z-tabs",setClassOnContainer:true,activeClassName:"active"},a||{});$(b).addClassName(a.containerClass);$super(b,a);this.containers.values().invoke("addClassName",a.containerClass+"-content");if(this.options.equal){this.alignTabs()}},setActiveTab:function($super,a){$super(a);if(this.options.equal){this.alignTabs()}},alignTabs:function(){this.containers.values().invoke("show");this.maxHeight=this.containers.values().invoke("getContentHeight").max();this.containers.values().invoke("hide");this.activeContainer.show();this.containers.values().invoke("setStyle",{minHeight:this.maxHeight.toUnits()})}});Zikula.UI.Accordion=Class.create({initialize:function(b,a){this.options=Object.extend({equal:false,height:null,headerSelector:".z-acc-header",containerClass:"z-accordion",activeClassName:"z-acc-active",contentClassName:null,active:null,activateOnHash:false,saveToCookie:false},a||{});this.accordion=$(b);if(this.options.saveToCookie){this.cookie="z-accordion:"+this.accordion.identify()}this.accordion.addClassName(this.options.containerClass);this.initPanels()},initPanels:function(){this.headers=this.accordion.select(this.options.headerSelector);if(!this.headers||this.headers.length===0){return}this.contents=this.headers.map(function(b){return b.next()});if(!this.options.active&&this.options.saveToCookie){this.options.active=Zikula.Cookie.get(this.cookie)}if(this.options.activateOnHash&&window.location.hash){var a=window.location.hash.replace("#","");if(this.headers.include($(a))){this.options.active=this.headers.indexOf($(a))}else{if(this.headers[a]){this.options.active=a}}}if(this.options.equal||this.options.height){this.alignPanels()}this.headers.each(function(c,b){this.contents[b].hide();if(this.options.height){this.contents[b].setStyle({height:this.options.height.toUnits(),overflow:"auto"})}if(this.options.contentClassName){this.contents[b].addClassName(this.options.contentClassName)}c.observe("click",this.click.bindAsEventListener(this))}.bind(this));if(this.options.active){if(this.headers.include($(this.options.active))){this.options.active=this.headers.indexOf($(this.options.active))}else{if(Object.isElement(this.headers[this.options.active])){this.options.active=this.options.active}else{this.options.active=null}}}this.setActivePanel(this.options.active||this.headers.first(),true)},click:function(a){var b=a.findElement(this.options.headerSelector);if(!b||!this.headers.include(b)){return}this.setActivePanel(b)},setActivePanel:function(a,b){if(this.animating==true){return}var c;if(Object.isElement(a)&&this.headers.include(a)){c=this.headers.indexOf(a)}else{if(Object.isElement(this.headers[a])){c=a;a=this.headers[a]}else{return}}if(c==this.activePanel){return}if(b||Object.isUndefined(this.activePanel)){[a,a.next().show()].invoke("addClassName",this.options.activeClassName);this.activePanel=c;if(this.options.saveToCookie){Zikula.Cookie.set(this.cookie,this.activePanel)}return}this.animate(c,this.activePanel)},animate:function(a,c){this.effects=[];var b=$H({sync:true,scaleContent:false,transition:Effect.Transitions.sinoidal});new Effect.Parallel([new Effect.BlindUp(this.contents[c],b),new Effect.BlindDown(this.contents[a],b)],{duration:0.3,queue:{position:"end",scope:"accordionAnimation"},beforeStart:function(){this.animating=true;[this.headers[c],this.contents[c]].invoke("removeClassName",this.options.activeClassName)}.bind(this),afterFinish:function(){this.animating=false;[this.headers[a],this.contents[a]].invoke("addClassName",this.options.activeClassName);this.activePanel=a;if(this.options.saveToCookie){Zikula.Cookie.set(this.cookie,this.activePanel)}if(this.options.height){this.contents[a].setStyle({height:this.options.height.toUnits(),overflow:"auto"})}}.bind(this)})},next:function(){this.setActivePanel(this.headers[this.activePanel+1]||this.headers.first())},previous:function(){this.setActivePanel(this.headers[this.activePanel-1]||this.headers.last())},first:function(){this.setActivePanel(this.headers.first())},last:function(){this.setActivePanel(this.headers.last())},alignPanels:function(){if(!this.options.height){this.options.height=this.contents.invoke("getHeight").max()}$A(this.contents).invoke("setStyle",{height:this.options.height.toUnits(),overflow:"auto"})}});Zikula.UI.Panels=Class.create({initialize:function(b,a){this.options=Object.extend({equal:false,height:null,minheight:null,headerSelector:".z-panel-header",containerClass:"z-panels",activeClassName:"z-panel-active",headerClassName:"z-panel-header",contentClassName:null,active:[],saveToCookie:false,effectDuration:1},a||{});this.panels=$(b);if(this.options.saveToCookie){this.cookie="z-panels:"+this.panels.identify()}if(!Object.isArray(this.options.active)){this.options.active=[this.options.active].flatten()}this.panels.addClassName(this.options.containerClass);this.activePanels=[];this.animating=[];this.initPanels()},initPanels:function(){this.headers=this.panels.select(this.options.headerSelector);if(!this.headers||this.headers.length===0){return}this.contents=this.headers.map(function(a){return a.next()});if(!this.options.active.size()&&this.options.saveToCookie){this.options.active=Zikula.Cookie.get(this.cookie)}if(this.options.equal||this.options.height){this.alignPanels()}this.headers.each(function(b,a){if(this.options.minheight){var c=this.contents[a].getContentHeight();this.contents[a].setStyle({height:this.options.minheight.toUnits()});if(c<=this.options.minheight){return}this.contents[a].store("fullheight",c)}else{this.contents[a].hide()}if(this.options.contentClassName){this.contents[a].addClassName(this.options.contentClassName)}b.addClassName(this.options.headerClassName);b.addClassName("z-pointer");b.observe("click",this.click.bindAsEventListener(this))}.bind(this));if(this.options.active.size()){this.options.active.each(function(a){this.expand(a,true)}.bind(this))}},getPanelIndex:function(a){var b;if(Object.isElement($(a))&&this.headers.include($(a))){b=this.headers.indexOf($(a))}else{if(Object.isElement(this.headers[a])){b=a}else{b=null}}return b},click:function(a){var b=a.findElement(this.options.headerSelector);if(!b||!this.headers.include(b)){return}this.toggle(b)},toggle:function(a,b){a=this.getPanelIndex(a);if(this.activePanels.include(a)){this.collapse(a,b)}else{this.expand(a,b)}},expand:function(a,b){var c=this.getPanelIndex(a);if(this.animating[c]==true){return}a=this.headers[c];if(this.activePanels.include(c)){return}this.activePanels.push(c);if(this.options.saveToCookie){Zikula.Cookie.set(this.cookie,this.activePanels)}if(b){if(this.options.minheight){a.next().setStyle({height:"auto"})}[a,a.next().show()].invoke("addClassName",this.options.activeClassName);return}this.animate(c,false)},collapse:function(a,b){var c=this.getPanelIndex(a);if(this.animating[c]==true){return}a=this.headers[c];if(!this.activePanels.include(c)){return}this.activePanels=this.activePanels.without(c);if(this.options.saveToCookie){Zikula.Cookie.set(this.cookie,this.activePanels)}if(b){if(this.options.minheight){a.next().setStyle({height:this.options.minheight.toUnits()})}[a,a.next().hide()].invoke("removeClassName",this.options.activeClassName);return}this.animate(c,true)},animate:function(d,c){this.effects=[];var b={duration:this.options.effectDuration,scaleContent:false,beforeStart:function(){this.animating[d]=true;if(!c){[this.headers[d],this.contents[d]].invoke("addClassName",this.options.activeClassName);if(this.options.height){this.contents[d].setStyle({height:this.options.height.toUnits(),overflow:"auto"})}}}.bind(this),afterFinish:function(){this.animating[d]=false;if(c){[this.headers[d],this.contents[d]].invoke("removeClassName",this.options.activeClassName);if(this.options.minheight){this.contents[d].setStyle({height:this.options.minheight.toUnits()}).show()}}}.bind(this)};if(this.options.minheight){var a=this.contents[d];if(c){b.scaleFrom=100;b.scaleTo=Math.round(this.options.minheight*100/a.retrieve("fullheight"))}else{b.scaleFrom=Math.round(a.getContentHeight()*100/a.retrieve("fullheight"));b.scaleTo=100}a.setStyle({height:a.retrieve("fullheight").toUnits()});if(!c){a.hide()}}if(c){new Effect.BlindUp(this.contents[d],b)}else{new Effect.BlindDown(this.contents[d],b)}},expandAll:function(){this.headers.each(function(a){this.expand(a)}.bind(this))},collapseAll:function(){this.headers.each(function(a){this.collapse(a)}.bind(this))},alignPanels:function(){if(!this.options.height){this.options.height=this.contents.invoke("getHeight").max()}$A(this.contents).invoke("setStyle",{height:this.options.height.toUnits(),overflow:"auto"})}});Zikula.UI.ContextMenu=Class.create(Control.ContextMenu,{initialize:function($super,a,c){Control.ContextMenu.load();this.options=Object.extend({leftClick:false,disableOnShiftKey:true,disableOnAltKey:true,selectedClassName:"selected",activatedClassName:"activated",animation:true,animationCycles:2,animationLength:300,delayCallback:true},c||{});this.activated=false;this.items=this.options.items||[];this.container=$(a);var b=this.options.leftClick?"click":(Prototype.Browser.Opera?"click":"contextmenu");this.container.observe(b,function(d){if(!Control.ContextMenu.enabled||(!this.options.leftClick&&Prototype.Browser.Opera&&!d.ctrlKey)){return}this.open(d)}.bindAsEventListener(this))}});