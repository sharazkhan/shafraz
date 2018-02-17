function fadeSlideShow(a){
    this.setting=a;
    a=null;
    var b=this.setting;
    b.fadeduration=b.fadeduration?parseInt(b.fadeduration):500;
    b.curimage=b.persist?fadeSlideShow.routines.getCookie("gallery-"+b.wrapperid):0;
    b.curimage=b.curimage||0;
    b.currentstep=0;
    b.totalsteps=b.imagearray.length*(b.displaymode.cycles>0?b.displaymode.cycles:Infinity);
    b.fglayer=0,b.bglayer=1;
    b.oninit=b.oninit||function(){};
    
    b.onslide=b.onslide||function(){};
    
    if(b.displaymode.randomize)b.imagearray.sort(function(){
        return.5-Math.random()
        });
    var c=[];
    b.longestdesc="";
    for(var d=0;d<b.imagearray.length;d++){
        c[d]=new Image;
        c[d].src=b.imagearray[d][0];
        if(b.imagearray[d][3]&&b.imagearray[d][3].length>b.longestdesc.length)b.longestdesc=b.imagearray[d][3]
            }
            var e=fadeSlideShow_descpanel.controls[0];
    b.closebutton=b.descreveal=="always"?'<img class="close" src="'+e[0]+'" style="float:right;cursor:hand;cursor:pointer;width:'+e[1]+"px;height:"+e[2]+'px;margin-left:2px" title="Hide Description" />':"";
    var f=this;
    jQuery(document).ready(function(a){
        var b=f.setting;
        var c=fadeSlideShow.routines.getFullHTML(b.imagearray);
        b.$wrapperdiv=a("#"+b.wrapperid).css({
            position:"relative",
            visibility:"visible",
            background:"black",
            overflow:"hidden",
            width:b.dimensions[0],
            height:b.dimensions[1]
            }).empty();
        if(b.$wrapperdiv.length==0){
            alert('Error: DIV with ID "'+b.wrapperid+'" not found on page.');
            return
        }
        b.$gallerylayers=a('<div class="gallerylayer"></div><div class="gallerylayer"></div>').css({
            position:"absolute",
            left:0,
            top:0,
            width:"100%",
            height:"100%",
            background:"white"
        }).appendTo(b.$wrapperdiv);
        //alert(fadeSlideShow_descpanel.controls[2][0]);
        var d=a('<img src="'+fadeSlideShow_descpanel.controls[2][0]+'" style="position:absolute;width:'+fadeSlideShow_descpanel.controls[2][1]+";height:"+fadeSlideShow_descpanel.controls[2][2]+'" />').css({
            left:b.dimensions[0]/2-fadeSlideShow_descpanel.controls[2][1]/2,
            top:0
        }).appendTo(b.$wrapperdiv);
        var e=b.$gallerylayers.html(c).find("img").hide().eq(b.curimage);
        if(b.longestdesc!=""&&b.descreveal!="none"){
            fadeSlideShow.routines.adddescpanel(a,b);
            if(b.descreveal=="always"){
                b.$descpanel.css({
                    top:0
                });
                b.$descinner.click(function(a){
                    if(a.target.className=="close"){
                        f.showhidedescpanel("hide")
                        }
                    });
            b.$restorebutton.click(function(b){
                f.showhidedescpanel("show");
                a(this).css({
                    visibility:"hidden"
                })
                })
            }else if(b.descreveal=="ondemand"){
            b.$wrapperdiv.bind("mouseenter",function(){
                f.showhidedescpanel("show")
                });
            b.$wrapperdiv.bind("mouseleave",function(){
                f.showhidedescpanel("hide")
                })
            }
        }
    b.$wrapperdiv.bind("mouseenter",function(){
        b.ismouseover=false
        });
    b.$wrapperdiv.bind("mouseleave",function(){
        b.ismouseover=false
        });
    if(e.get(0).complete){
        d.hide();
        f.paginateinit(a);
        f.showslide(b.curimage)
        }else{
        d.hide();
        f.paginateinit(a);
        e.bind("load",function(){
            f.showslide(b.curimage)
            })
        }
        b.oninit.call(f);
    a(window).bind("unload",function(){
        if(f.setting.persist)fadeSlideShow.routines.setCookie("gallery-"+b.wrapperid,b.curimage);
        jQuery.each(f.setting,function(a){
            if(f.setting[a]instanceof Array){
                for(var b=0;b<f.setting[a].length;b++){
                    if(f.setting[a][b].tagName=="DIV")f.setting[a][b].innerHTML=null;
                    f.setting[a][b]=null
                    }
                }
            });
    f=f.setting=null
    })
})
}(function(a,b){
    function p(a){
        return"scrollTo"in a&&a.document?a:a.nodeType===9?a.defaultView||a.parentWindow:false
        }
        function o(a,b){
        var c={};
        
        q.each(bT.concat.apply([],bT.slice(0,b)),function(){
            c[this]=a
            });
        return c
        }
        function n(a,b,c){
        var d,e,f;
        b=b&&b[0]?b[0].ownerDocument||b[0]:t;
        if(a.length===1&&typeof a[0]==="string"&&a[0].length<512&&b===t&&!bn.test(a[0])&&(q.support.checkClone||!bo.test(a[0]))){
            e=true;
            if(f=q.fragments[a[0]])if(f!==1)d=f
                }
                if(!d){
            d=b.createDocumentFragment();
            q.clean(a,b,d,c)
            }
            if(e)q.fragments[a[0]]=f?d:1;
        return{
            fragment:d,
            cacheable:e
        }
    }
    function m(a,b){
    var c=0;
    b.each(function(){
        if(this.nodeName===(a[c]&&a[c].nodeName)){
            var b=q.data(a[c++]),d=q.data(this,b);
            if(b=b&&b.events){
                delete d.handle;
                d.events={};
                
                for(var e in b)for(var f in b[e])q.event.add(this,e,b[e][f],b[e][f].data)
                    }
                }
    })
}
function l(a){
    return!a||!a.parentNode||a.parentNode.nodeType===11
    }
    function k(a,b){
    return"live."+(a&&a!=="*"?a+".":"")+b.replace(/\./g,"`").replace(/ /g,"&")
    }
    function j(a){
    var b,c=[],d=[],e=arguments,f,g,h,i,j,k;
    g=q.data(this,"events");
    if(!(a.liveFired===this||!g||!g.live||a.button&&a.type==="click")){
        a.liveFired=this;
        var l=g.live.slice(0);
        for(i=0;i<l.length;i++){
            g=l[i];
            g.origType.replace(U,"")===a.type?d.push(g.selector):l.splice(i--,1)
            }
            f=q(a.target).closest(d,a.currentTarget);
        j=0;
        for(k=f.length;j<k;j++)for(i=0;i<l.length;i++){
            g=l[i];
            if(f[j].selector===g.selector){
                h=f[j].elem;
                d=null;
                if(g.preType==="mouseenter"||g.preType==="mouseleave")d=q(a.relatedTarget).closest(g.selector)[0];
                if(!d||d!==h)c.push({
                    elem:h,
                    handleObj:g
                })
                }
                }
        j=0;
    for(k=c.length;j<k;j++){
        f=c[j];
        a.currentTarget=f.elem;
        a.data=f.handleObj.data;
        a.handleObj=f.handleObj;
        if(f.handleObj.origHandler.apply(f.elem,e)===false){
            b=false;
            break
        }
    }
    return b
}
}
function i(a,b,c){
    c[0].type=a;
    return q.event.handle.apply(b,c)
    }
    function h(){
    return true
    }
    function g(){
    return false
    }
    function f(){
    return(new Date).getTime()
    }
    function e(a,c,d,f,g,h){
    var i=a.length;
    if(typeof c==="object"){
        for(var j in c)e(a,j,c[j],f,g,d);return a
        }
        if(d!==b){
        f=!h&&f&&q.isFunction(d);
        for(j=0;j<i;j++)g(a[j],c,f?d.call(a[j],j,g(a[j],c)):d,h);
        return a
        }
        return i?g(a[0],c):b
    }
    function d(a,b){
    b.src?q.ajax({
        url:b.src,
        async:false,
        dataType:"script"
    }):q.globalEval(b.text||b.textContent||b.innerHTML||"");
    b.parentNode&&b.parentNode.removeChild(b)
    }
    function c(){
    if(!q.isReady){
        try{
            t.documentElement.doScroll("left")
            }catch(a){
            setTimeout(c,1);
            return
        }
        q.ready()
        }
    }
var q=function(a,b){
    return new q.fn.init(a,b)
    },r=a.jQuery,s=a.$,t=a.document,u,v=/^[^<]*(<[\w\W]+>)[^>]*$|^#([\w-]+)$/,w=/^.[^:#\[\.,]*$/,x=/\S/,y=/^(\s|\u00A0)+|(\s|\u00A0)+$/g,z=/^<(\w+)\s*\/?>(?:<\/\1>)?$/,A=navigator.userAgent,B=false,C=[],D,E=Object.prototype.toString,F=Object.prototype.hasOwnProperty,G=Array.prototype.push,H=Array.prototype.slice,I=Array.prototype.indexOf;
q.fn=q.prototype={
    init:function(a,c){
        var d,e;
        if(!a)return this;
        if(a.nodeType){
            this.context=this[0]=a;
            this.length=1;
            return this
            }
            if(a==="body"&&!c){
            this.context=t;
            this[0]=t.body;
            this.selector="body";
            this.length=1;
            return this
            }
            if(typeof a==="string")if((d=v.exec(a))&&(d[1]||!c))if(d[1]){
            e=c?c.ownerDocument||c:t;
            if(a=z.exec(a))if(q.isPlainObject(c)){
                a=[t.createElement(a[1])];
                q.fn.attr.call(a,c,true)
                }else a=[e.createElement(a[1])];
            else{
                a=n([d[1]],[e]);
                a=(a.cacheable?a.fragment.cloneNode(true):a.fragment).childNodes
                }
                return q.merge(this,a)
            }else{
            if(c=t.getElementById(d[2])){
                if(c.id!==d[2])return u.find(a);
                this.length=1;
                this[0]=c
                }
                this.context=t;
            this.selector=a;
            return this
            }else if(!c&&/^\w+$/.test(a)){
            this.selector=a;
            this.context=t;
            a=t.getElementsByTagName(a);
            return q.merge(this,a)
            }else return!c||c.jquery?(c||u).find(a):q(c).find(a);
        else if(q.isFunction(a))return u.ready(a);
        if(a.selector!==b){
            this.selector=a.selector;
            this.context=a.context
            }
            return q.makeArray(a,this)
        },
    selector:"",
    jquery:"1.4.2",
    length:0,
    size:function(){
        return this.length
        },
    toArray:function(){
        return H.call(this,0)
        },
    get:function(a){
        return a==null?this.toArray():a<0?this.slice(a)[0]:this[a]
        },
    pushStack:function(a,b,c){
        var d=q();
        q.isArray(a)?G.apply(d,a):q.merge(d,a);
        d.prevObject=this;
        d.context=this.context;
        if(b==="find")d.selector=this.selector+(this.selector?" ":"")+c;
        else if(b)d.selector=this.selector+"."+b+"("+c+")";
        return d
        },
    each:function(a,b){
        return q.each(this,a,b)
        },
    ready:function(a){
        q.bindReady();
        if(q.isReady)a.call(t,q);else C&&C.push(a);
        return this
        },
    eq:function(a){
        return a===-1?this.slice(a):this.slice(a,+a+1)
        },
    first:function(){
        return this.eq(0)
        },
    last:function(){
        return this.eq(-1)
        },
    slice:function(){
        return this.pushStack(H.apply(this,arguments),"slice",H.call(arguments).join(","))
        },
    map:function(a){
        return this.pushStack(q.map(this,function(b,c){
            return a.call(b,c,b)
            }))
        },
    end:function(){
        return this.prevObject||q(null)
        },
    push:G,
    sort:[].sort,
    splice:[].splice
    };
    
q.fn.init.prototype=q.fn;
q.extend=q.fn.extend=function(){
    var a=arguments[0]||{},c=1,d=arguments.length,e=false,f,g,h,i;
    if(typeof a==="boolean"){
        e=a;
        a=arguments[1]||{};
        
        c=2
        }
        if(typeof a!=="object"&&!q.isFunction(a))a={};
        
    if(d===c){
        a=this;
        --c
        }
        for(;c<d;c++)if((f=arguments[c])!=null)for(g in f){
        h=a[g];
        i=f[g];
        if(a!==i)if(e&&i&&(q.isPlainObject(i)||q.isArray(i))){
            h=h&&(q.isPlainObject(h)||q.isArray(h))?h:q.isArray(i)?[]:{};
            
            a[g]=q.extend(e,h,i)
            }else if(i!==b)a[g]=i
            }
            return a
    };
    
q.extend({
    noConflict:function(b){
        a.$=s;
        if(b)a.jQuery=r;
        return q
        },
    isReady:false,
    ready:function(){
        if(!q.isReady){
            if(!t.body)return setTimeout(q.ready,13);
            q.isReady=true;
            if(C){
                for(var a,b=0;a=C[b++];)a.call(t,q);
                C=null
                }
                q.fn.triggerHandler&&q(t).triggerHandler("ready")
            }
        },
bindReady:function(){
    if(!B){
        B=true;
        if(t.readyState==="complete")return q.ready();
        if(t.addEventListener){
            t.addEventListener("DOMContentLoaded",D,false);
            a.addEventListener("load",q.ready,false)
            }else if(t.attachEvent){
            t.attachEvent("onreadystatechange",D);
            a.attachEvent("onload",q.ready);
            var b=false;
            try{
                b=a.frameElement==null
                }catch(d){}
            t.documentElement.doScroll&&b&&c()
            }
        }
},
isFunction:function(a){
    return E.call(a)==="[object Function]"
    },
isArray:function(a){
    return E.call(a)==="[object Array]"
    },
isPlainObject:function(a){
    if(!a||E.call(a)!=="[object Object]"||a.nodeType||a.setInterval)return false;
    if(a.constructor&&!F.call(a,"constructor")&&!F.call(a.constructor.prototype,"isPrototypeOf"))return false;
    var c;
    for(c in a);return c===b||F.call(a,c)
    },
isEmptyObject:function(a){
    for(var b in a)return false;return true
    },
error:function(a){
    throw a
    },
parseJSON:function(b){
    if(typeof b!=="string"||!b)return null;
    b=q.trim(b);
    if(/^[\],:{}\s]*$/.test(b.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,"")))return a.JSON&&a.JSON.parse?a.JSON.parse(b):(new Function("return "+b))();else q.error("Invalid JSON: "+b)
        },
noop:function(){},
globalEval:function(a){
    if(a&&x.test(a)){
        var b=t.getElementsByTagName("head")[0]||t.documentElement,c=t.createElement("script");
        c.type="text/javascript";
        if(q.support.scriptEval)c.appendChild(t.createTextNode(a));else c.text=a;
        b.insertBefore(c,b.firstChild);
        b.removeChild(c)
        }
    },
nodeName:function(a,b){
    return a.nodeName&&a.nodeName.toUpperCase()===b.toUpperCase()
    },
each:function(a,c,d){
    var e,f=0,g=a.length,h=g===b||q.isFunction(a);
    if(d)if(h)for(e in a){
        if(c.apply(a[e],d)===false)break
    }else for(;f<g;){
        if(c.apply(a[f++],d)===false)break
    }else if(h)for(e in a){
        if(c.call(a[e],e,a[e])===false)break
    }else for(d=a[0];f<g&&c.call(d,f,d)!==false;d=a[++f]);
    return a
    },
trim:function(a){
    return(a||"").replace(y,"")
    },
makeArray:function(a,b){
    b=b||[];
    if(a!=null)a.length==null||typeof a==="string"||q.isFunction(a)||typeof a!=="function"&&a.setInterval?G.call(b,a):q.merge(b,a);
    return b
    },
inArray:function(a,b){
    if(b.indexOf)return b.indexOf(a);
    for(var c=0,d=b.length;c<d;c++)if(b[c]===a)return c;return-1
    },
merge:function(a,c){
    var d=a.length,e=0;
    if(typeof c.length==="number")for(var f=c.length;e<f;e++)a[d++]=c[e];else for(;c[e]!==b;)a[d++]=c[e++];
    a.length=d;
    return a
    },
grep:function(a,b,c){
    for(var d=[],e=0,f=a.length;e<f;e++)!c!==!b(a[e],e)&&d.push(a[e]);
    return d
    },
map:function(a,b,c){
    for(var d=[],e,f=0,g=a.length;f<g;f++){
        e=b(a[f],f,c);
        if(e!=null)d[d.length]=e
            }
            return d.concat.apply([],d)
    },
guid:1,
proxy:function(a,c,d){
    if(arguments.length===2)if(typeof c==="string"){
        d=a;
        a=d[c];
        c=b
        }else if(c&&!q.isFunction(c)){
        d=c;
        c=b
        }
        if(!c&&a)c=function(){
        return a.apply(d||this,arguments)
        };
        
    if(a)c.guid=a.guid=a.guid||c.guid||q.guid++;
    return c
    },
uaMatch:function(a){
    a=a.toLowerCase();
    a=/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version)?[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||!/compatible/.test(a)&&/(mozilla)(?:.*? rv:([\w.]+))?/.exec(a)||[];
    return{
        browser:a[1]||"",
        version:a[2]||"0"
        }
    },
browser:{}
});
A=q.uaMatch(A);
if(A.browser){
    q.browser[A.browser]=true;
    q.browser.version=A.version
    }
    if(q.browser.webkit)q.browser.safari=true;
if(I)q.inArray=function(a,b){
    return I.call(b,a)
    };
    
u=q(t);
if(t.addEventListener)D=function(){
    t.removeEventListener("DOMContentLoaded",D,false);
    q.ready()
    };
else if(t.attachEvent)D=function(){
    if(t.readyState==="complete"){
        t.detachEvent("onreadystatechange",D);
        q.ready()
        }
    };
(function(){
    q.support={};
    
    var b=t.documentElement,c=t.createElement("script"),d=t.createElement("div"),e="script"+f();
    d.style.display="none";
    d.innerHTML="   <link/><table></table><a href='/a' style='color:red;float:left;opacity:.55;'>a</a><input type='checkbox'/>";
    var g=d.getElementsByTagName("*"),h=d.getElementsByTagName("a")[0];
    if(!(!g||!g.length||!h)){
        q.support={
            leadingWhitespace:d.firstChild.nodeType===3,
            tbody:!d.getElementsByTagName("tbody").length,
            htmlSerialize:!!d.getElementsByTagName("link").length,
            style:/red/.test(h.getAttribute("style")),
            hrefNormalized:h.getAttribute("href")==="/a",
            opacity:/^0.55$/.test(h.style.opacity),
            cssFloat:!!h.style.cssFloat,
            checkOn:d.getElementsByTagName("input")[0].value==="on",
            optSelected:t.createElement("select").appendChild(t.createElement("option")).selected,
            parentNode:d.removeChild(d.appendChild(t.createElement("div"))).parentNode===null,
            deleteExpando:true,
            checkClone:false,
            scriptEval:false,
            noCloneEvent:true,
            boxModel:null
        };
        
        c.type="text/javascript";
        try{
            c.appendChild(t.createTextNode("window."+e+"=1;"))
            }catch(i){}
        b.insertBefore(c,b.firstChild);
        if(a[e]){
            q.support.scriptEval=true;
            delete a[e]
        }
        try{
            delete c.test
            }catch(j){
            q.support.deleteExpando=false
            }
            b.removeChild(c);
        if(d.attachEvent&&d.fireEvent){
            d.attachEvent("onclick",function k(){
                q.support.noCloneEvent=false;
                d.detachEvent("onclick",k)
                });
            d.cloneNode(true).fireEvent("onclick")
            }
            d=t.createElement("div");
        d.innerHTML="<input type='radio' name='radiotest' checked='checked'/>";
        b=t.createDocumentFragment();
        b.appendChild(d.firstChild);
        q.support.checkClone=b.cloneNode(true).cloneNode(true).lastChild.checked;
        q(function(){
            var a=t.createElement("div");
            a.style.width=a.style.paddingLeft="1px";
            t.body.appendChild(a);
            q.boxModel=q.support.boxModel=a.offsetWidth===2;
            t.body.removeChild(a).style.display="none"
            });
        b=function(a){
            var b=t.createElement("div");
            a="on"+a;
            var c=a in b;
            if(!c){
                b.setAttribute(a,"return;");
                c=typeof b[a]==="function"
                }
                return c
            };
            
        q.support.submitBubbles=b("submit");
        q.support.changeBubbles=b("change");
        b=c=d=g=h=null
        }
    })();
q.props={
    "for":"htmlFor",
    "class":"className",
    readonly:"readOnly",
    maxlength:"maxLength",
    cellspacing:"cellSpacing",
    rowspan:"rowSpan",
    colspan:"colSpan",
    tabindex:"tabIndex",
    usemap:"useMap",
    frameborder:"frameBorder"
};

var J="jQuery"+f(),K=0,L={};

q.extend({
    cache:{},
    expando:J,
    noData:{
        embed:true,
        object:true,
        applet:true
    },
    data:function(c,d,e){
        if(!(c.nodeName&&q.noData[c.nodeName.toLowerCase()])){
            c=c==a?L:c;
            var f=c[J],g=q.cache;
            if(!f&&typeof d==="string"&&e===b)return null;
            f||(f=++K);
            if(typeof d==="object"){
                c[J]=f;
                g[f]=q.extend(true,{},d)
                }else if(!g[f]){
                c[J]=f;
                g[f]={}
            }
            c=g[f];
        if(e!==b)c[d]=e;
        return typeof d==="string"?c[d]:c
        }
    },
removeData:function(b,c){
    if(!(b.nodeName&&q.noData[b.nodeName.toLowerCase()])){
        b=b==a?L:b;
        var d=b[J],e=q.cache,f=e[d];
        if(c){
            if(f){
                delete f[c];
                q.isEmptyObject(f)&&q.removeData(b)
                }
            }else{
        if(q.support.deleteExpando)delete b[q.expando];else b.removeAttribute&&b.removeAttribute(q.expando);
        delete e[d]
    }
}
}
});
q.fn.extend({
    data:function(a,c){
        if(typeof a==="undefined"&&this.length)return q.data(this[0]);
        else if(typeof a==="object")return this.each(function(){
            q.data(this,a)
            });
        var d=a.split(".");
        d[1]=d[1]?"."+d[1]:"";
        if(c===b){
            var e=this.triggerHandler("getData"+d[1]+"!",[d[0]]);
            if(e===b&&this.length)e=q.data(this[0],a);
            return e===b&&d[1]?this.data(d[0]):e
            }else return this.trigger("setData"+d[1]+"!",[d[0],c]).each(function(){
            q.data(this,a,c)
            })
        },
    removeData:function(a){
        return this.each(function(){
            q.removeData(this,a)
            })
        }
    });
q.extend({
    queue:function(a,b,c){
        if(a){
            b=(b||"fx")+"queue";
            var d=q.data(a,b);
            if(!c)return d||[];
            if(!d||q.isArray(c))d=q.data(a,b,q.makeArray(c));else d.push(c);
            return d
            }
        },
dequeue:function(a,b){
    b=b||"fx";
    var c=q.queue(a,b),d=c.shift();
    if(d==="inprogress")d=c.shift();
    if(d){
        b==="fx"&&c.unshift("inprogress");
        d.call(a,function(){
            q.dequeue(a,b)
            })
        }
    }
});
q.fn.extend({
    queue:function(a,c){
        if(typeof a!=="string"){
            c=a;
            a="fx"
            }
            if(c===b)return q.queue(this[0],a);
        return this.each(function(){
            var b=q.queue(this,a,c);
            a==="fx"&&b[0]!=="inprogress"&&q.dequeue(this,a)
            })
        },
    dequeue:function(a){
        return this.each(function(){
            q.dequeue(this,a)
            })
        },
    delay:function(a,b){
        a=q.fx?q.fx.speeds[a]||a:a;
        b=b||"fx";
        return this.queue(b,function(){
            var c=this;
            setTimeout(function(){
                q.dequeue(c,b)
                },a)
            })
        },
    clearQueue:function(a){
        return this.queue(a||"fx",[])
        }
    });
var M=/[\n\t]/g,N=/\s+/,O=/\r/g,P=/href|src|style/,Q=/(button|input)/i,R=/(button|input|object|select|textarea)/i,S=/^(a|area)$/i,T=/radio|checkbox/;
q.fn.extend({
    attr:function(a,b){
        return e(this,a,b,true,q.attr)
        },
    removeAttr:function(a){
        return this.each(function(){
            q.attr(this,a,"");
            this.nodeType===1&&this.removeAttribute(a)
            })
        },
    addClass:function(a){
        if(q.isFunction(a))return this.each(function(b){
            var c=q(this);
            c.addClass(a.call(this,b,c.attr("class")))
            });
        if(a&&typeof a==="string")for(var b=(a||"").split(N),c=0,d=this.length;c<d;c++){
            var e=this[c];
            if(e.nodeType===1)if(e.className){
                for(var f=" "+e.className+" ",g=e.className,h=0,i=b.length;h<i;h++)if(f.indexOf(" "+b[h]+" ")<0)g+=" "+b[h];e.className=q.trim(g)
                }else e.className=a
                }
                return this
        },
    removeClass:function(a){
        if(q.isFunction(a))return this.each(function(b){
            var c=q(this);
            c.removeClass(a.call(this,b,c.attr("class")))
            });
        if(a&&typeof a==="string"||a===b)for(var c=(a||"").split(N),d=0,e=this.length;d<e;d++){
            var f=this[d];
            if(f.nodeType===1&&f.className)if(a){
                for(var g=(" "+f.className+" ").replace(M," "),h=0,i=c.length;h<i;h++)g=g.replace(" "+c[h]+" "," ");
                f.className=q.trim(g)
                }else f.className=""
                }
                return this
        },
    toggleClass:function(a,b){
        var c=typeof a,d=typeof b==="boolean";
        if(q.isFunction(a))return this.each(function(c){
            var d=q(this);
            d.toggleClass(a.call(this,c,d.attr("class"),b),b)
            });
        return this.each(function(){
            if(c==="string")for(var e,f=0,g=q(this),h=b,i=a.split(N);e=i[f++];){
                h=d?h:!g.hasClass(e);
                g[h?"addClass":"removeClass"](e)
                }else if(c==="undefined"||c==="boolean"){
                this.className&&q.data(this,"__className__",this.className);
                this.className=this.className||a===false?"":q.data(this,"__className__")||""
                }
            })
    },
hasClass:function(a){
    a=" "+a+" ";
    for(var b=0,c=this.length;b<c;b++)if((" "+this[b].className+" ").replace(M," ").indexOf(a)>-1)return true;return false
    },
val:function(a){
    if(a===b){
        var c=this[0];
        if(c){
            if(q.nodeName(c,"option"))return(c.attributes.value||{}).specified?c.value:c.text;
            if(q.nodeName(c,"select")){
                var d=c.selectedIndex,e=[],f=c.options;
                c=c.type==="select-one";
                if(d<0)return null;
                var g=c?d:0;
                for(d=c?d+1:f.length;g<d;g++){
                    var h=f[g];
                    if(h.selected){
                        a=q(h).val();
                        if(c)return a;
                        e.push(a)
                        }
                    }
                return e
            }
            if(T.test(c.type)&&!q.support.checkOn)return c.getAttribute("value")===null?"on":c.value;
        return(c.value||"").replace(O,"")
        }
        return b
    }
    var i=q.isFunction(a);
    return this.each(function(b){
    var c=q(this),d=a;
    if(this.nodeType===1){
        if(i)d=a.call(this,b,c.val());
        if(typeof d==="number")d+="";
        if(q.isArray(d)&&T.test(this.type))this.checked=q.inArray(c.val(),d)>=0;
        else if(q.nodeName(this,"select")){
            var e=q.makeArray(d);
            q("option",this).each(function(){
                this.selected=q.inArray(q(this).val(),e)>=0
                });
            if(!e.length)this.selectedIndex=-1
                }else this.value=d
            }
        })
}
});
q.extend({
    attrFn:{
        val:true,
        css:true,
        html:true,
        text:true,
        data:true,
        width:true,
        height:true,
        offset:true
    },
    attr:function(a,c,d,e){
        if(!a||a.nodeType===3||a.nodeType===8)return b;
        if(e&&c in q.attrFn)return q(a)[c](d);
        e=a.nodeType!==1||!q.isXMLDoc(a);
        var f=d!==b;
        c=e&&q.props[c]||c;
        if(a.nodeType===1){
            var g=P.test(c);
            if(c in a&&e&&!g){
                if(f){
                    c==="type"&&Q.test(a.nodeName)&&a.parentNode&&q.error("type property can't be changed");
                    a[c]=d
                    }
                    if(q.nodeName(a,"form")&&a.getAttributeNode(c))return a.getAttributeNode(c).nodeValue;
                if(c==="tabIndex")return(c=a.getAttributeNode("tabIndex"))&&c.specified?c.value:R.test(a.nodeName)||S.test(a.nodeName)&&a.href?0:b;
                return a[c]
                }
                if(!q.support.style&&e&&c==="style"){
                if(f)a.style.cssText=""+d;
                return a.style.cssText
                }
                f&&a.setAttribute(c,""+d);
            a=!q.support.hrefNormalized&&e&&g?a.getAttribute(c,2):a.getAttribute(c);
            return a===null?b:a
            }
            return q.style(a,c,d)
        }
    });
var U=/\.(.*)$/,V=function(a){
    return a.replace(/[^\w\s\.\|`]/g,function(a){
        return"\\"+a
        })
    };
    
q.event={
    add:function(c,d,e,f){
        if(!(c.nodeType===3||c.nodeType===8)){
            if(c.setInterval&&c!==a&&!c.frameElement)c=a;
            var g,h;
            if(e.handler){
                g=e;
                e=g.handler
                }
                if(!e.guid)e.guid=q.guid++;
            if(h=q.data(c)){
                var i=h.events=h.events||{},j=h.handle;
                if(!j)h.handle=j=function(){
                    return typeof q!=="undefined"&&!q.event.triggered?q.event.handle.apply(j.elem,arguments):b
                    };
                    
                j.elem=c;
                d=d.split(" ");
                for(var k,l=0,m;k=d[l++];){
                    h=g?q.extend({},g):{
                        handler:e,
                        data:f
                    };
                    
                    if(k.indexOf(".")>-1){
                        m=k.split(".");
                        k=m.shift();
                        h.namespace=m.slice(0).sort().join(".")
                        }else{
                        m=[];
                        h.namespace=""
                        }
                        h.type=k;
                    h.guid=e.guid;
                    var n=i[k],o=q.event.special[k]||{};
                    
                    if(!n){
                        n=i[k]=[];
                        if(!o.setup||o.setup.call(c,f,m,j)===false)if(c.addEventListener)c.addEventListener(k,j,false);else c.attachEvent&&c.attachEvent("on"+k,j)
                            }
                            if(o.add){
                        o.add.call(c,h);
                        if(!h.handler.guid)h.handler.guid=e.guid
                            }
                            n.push(h);
                    q.event.global[k]=true
                    }
                    c=null
                }
            }
    },
global:{},
remove:function(a,b,c,d){
    if(!(a.nodeType===3||a.nodeType===8)){
        var e,f=0,g,h,i,j,k,l,m=q.data(a),n=m&&m.events;
        if(m&&n){
            if(b&&b.type){
                c=b.handler;
                b=b.type
                }
                if(!b||typeof b==="string"&&b.charAt(0)==="."){
                b=b||"";
                for(e in n)q.event.remove(a,e+b)
                    }else{
                for(b=b.split(" ");e=b[f++];){
                    j=e;
                    g=e.indexOf(".")<0;
                    h=[];
                    if(!g){
                        h=e.split(".");
                        e=h.shift();
                        i=new RegExp("(^|\\.)"+q.map(h.slice(0).sort(),V).join("\\.(?:.*\\.)?")+"(\\.|$)")
                        }
                        if(k=n[e])if(c){
                        j=q.event.special[e]||{};
                        
                        for(o=d||0;o<k.length;o++){
                            l=k[o];
                            if(c.guid===l.guid){
                                if(g||i.test(l.namespace)){
                                    d==null&&k.splice(o--,1);
                                    j.remove&&j.remove.call(a,l)
                                    }
                                    if(d!=null)break
                            }
                        }
                        if(k.length===0||d!=null&&k.length===1){
                        if(!j.teardown||j.teardown.call(a,h)===false)W(a,e,m.handle);
                        delete n[e]
                    }
                    }else for(var o=0;o<k.length;o++){
                    l=k[o];
                    if(g||i.test(l.namespace)){
                        q.event.remove(a,j,l.handler,o);
                        k.splice(o--,1)
                        }
                    }
                }
                if(q.isEmptyObject(n)){
    if(b=m.handle)b.elem=null;
    delete m.events;
    delete m.handle;
    q.isEmptyObject(m)&&q.removeData(a)
    }
}
}
}
},
trigger:function(a,c,d,e){
    var f=a.type||a;
    if(!e){
        a=typeof a==="object"?a[J]?a:q.extend(q.Event(f),a):q.Event(f);
        if(f.indexOf("!")>=0){
            a.type=f=f.slice(0,-1);
            a.exclusive=true
            }
            if(!d){
            a.stopPropagation();
            q.event.global[f]&&q.each(q.cache,function(){
                this.events&&this.events[f]&&q.event.trigger(a,c,this.handle.elem)
                })
            }
            if(!d||d.nodeType===3||d.nodeType===8)return b;
        a.result=b;
        a.target=d;
        c=q.makeArray(c);
        c.unshift(a)
        }
        a.currentTarget=d;
    (e=q.data(d,"handle"))&&e.apply(d,c);
    e=d.parentNode||d.ownerDocument;
    try{
        if(!(d&&d.nodeName&&q.noData[d.nodeName.toLowerCase()]))if(d["on"+f]&&d["on"+f].apply(d,c)===false)a.result=false
            }catch(g){}
    if(!a.isPropagationStopped()&&e)q.event.trigger(a,c,e,true);
    else if(!a.isDefaultPrevented()){
        e=a.target;
        var h,i=q.nodeName(e,"a")&&f==="click",j=q.event.special[f]||{};
        
        if((!j._default||j._default.call(d,a)===false)&&!i&&!(e&&e.nodeName&&q.noData[e.nodeName.toLowerCase()])){
            try{
                if(e[f]){
                    if(h=e["on"+f])e["on"+f]=null;
                    q.event.triggered=true;
                    e[f]()
                    }
                }catch(k){}
        if(h)e["on"+f]=h;
        q.event.triggered=false
        }
    }
},
handle:function(c){
    var d,e,f,g;
    c=arguments[0]=q.event.fix(c||a.event);
    c.currentTarget=this;
    d=c.type.indexOf(".")<0&&!c.exclusive;
    if(!d){
        e=c.type.split(".");
        c.type=e.shift();
        f=new RegExp("(^|\\.)"+e.slice(0).sort().join("\\.(?:.*\\.)?")+"(\\.|$)")
        }
        g=q.data(this,"events");
    e=g[c.type];
    if(g&&e){
        e=e.slice(0);
        g=0;
        for(var h=e.length;g<h;g++){
            var i=e[g];
            if(d||f.test(i.namespace)){
                c.handler=i.handler;
                c.data=i.data;
                c.handleObj=i;
                i=i.handler.apply(this,arguments);
                if(i!==b){
                    c.result=i;
                    if(i===false){
                        c.preventDefault();
                        c.stopPropagation()
                        }
                    }
                if(c.isImmediatePropagationStopped())break
        }
        }
    }
return c.result
},
props:"altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),
fix:function(a){
    if(a[J])return a;
    var c=a;
    a=q.Event(c);
    for(var d=this.props.length,e;d;){
        e=this.props[--d];
        a[e]=c[e]
        }
        if(!a.target)a.target=a.srcElement||t;
    if(a.target.nodeType===3)a.target=a.target.parentNode;
    if(!a.relatedTarget&&a.fromElement)a.relatedTarget=a.fromElement===a.target?a.toElement:a.fromElement;
    if(a.pageX==null&&a.clientX!=null){
        c=t.documentElement;
        d=t.body;
        a.pageX=a.clientX+(c&&c.scrollLeft||d&&d.scrollLeft||0)-(c&&c.clientLeft||d&&d.clientLeft||0);
        a.pageY=a.clientY+(c&&c.scrollTop||d&&d.scrollTop||0)-(c&&c.clientTop||d&&d.clientTop||0)
        }
        if(!a.which&&(a.charCode||a.charCode===0?a.charCode:a.keyCode))a.which=a.charCode||a.keyCode;
    if(!a.metaKey&&a.ctrlKey)a.metaKey=a.ctrlKey;
    if(!a.which&&a.button!==b)a.which=a.button&1?1:a.button&2?3:a.button&4?2:0;
    return a
    },
guid:1e8,
proxy:q.proxy,
special:{
    ready:{
        setup:q.bindReady,
        teardown:q.noop
        },
    live:{
        add:function(a){
            q.event.add(this,a.origType,q.extend({},a,{
                handler:j
            }))
            },
        remove:function(a){
            var b=true,c=a.origType.replace(U,"");
            q.each(q.data(this,"events").live||[],function(){
                if(c===this.origType.replace(U,""))return b=false
                    });
            b&&q.event.remove(this,a.origType,j)
            }
        },
beforeunload:{
    setup:function(a,b,c){
        if(this.setInterval)this.onbeforeunload=c;
        return false
        },
    teardown:function(a,b){
        if(this.onbeforeunload===b)this.onbeforeunload=null
            }
        }
}
};

var W=t.removeEventListener?function(a,b,c){
    a.removeEventListener(b,c,false)
    }:function(a,b,c){
    a.detachEvent("on"+b,c)
    };
    
q.Event=function(a){
    if(!this.preventDefault)return new q.Event(a);
    if(a&&a.type){
        this.originalEvent=a;
        this.type=a.type
        }else this.type=a;
    this.timeStamp=f();
    this[J]=true
    };
    
q.Event.prototype={
    preventDefault:function(){
        this.isDefaultPrevented=h;
        var a=this.originalEvent;
        if(a){
            a.preventDefault&&a.preventDefault();
            a.returnValue=false
            }
        },
stopPropagation:function(){
    this.isPropagationStopped=h;
    var a=this.originalEvent;
    if(a){
        a.stopPropagation&&a.stopPropagation();
        a.cancelBubble=true
        }
    },
stopImmediatePropagation:function(){
    this.isImmediatePropagationStopped=h;
    this.stopPropagation()
    },
isDefaultPrevented:g,
isPropagationStopped:g,
isImmediatePropagationStopped:g
};

var X=function(a){
    var b=a.relatedTarget;
    try{
        for(;b&&b!==this;)b=b.parentNode;
        if(b!==this){
            a.type=a.data;
            q.event.handle.apply(this,arguments)
            }
        }catch(c){}
},Y=function(a){
    a.type=a.data;
    q.event.handle.apply(this,arguments)
    };
    
q.each({
    mouseenter:"mouseover",
    mouseleave:"mouseout"
},function(a,b){
    q.event.special[a]={
        setup:function(c){
            q.event.add(this,b,c&&c.selector?Y:X,a)
            },
        teardown:function(a){
            q.event.remove(this,b,a&&a.selector?Y:X)
            }
        }
});
if(!q.support.submitBubbles)q.event.special.submit={
    setup:function(){
        if(this.nodeName.toLowerCase()!=="form"){
            q.event.add(this,"click.specialSubmit",function(a){
                var b=a.target,c=b.type;
                if((c==="submit"||c==="image")&&q(b).closest("form").length)return i("submit",this,arguments)
                    });
            q.event.add(this,"keypress.specialSubmit",function(a){
                var b=a.target,c=b.type;
                if((c==="text"||c==="password")&&q(b).closest("form").length&&a.keyCode===13)return i("submit",this,arguments)
                    })
            }else return false
            },
    teardown:function(){
        q.event.remove(this,".specialSubmit")
        }
    };

if(!q.support.changeBubbles){
    var Z=/textarea|input|select/i,$,_=function(a){
        var b=a.type,c=a.value;
        if(b==="radio"||b==="checkbox")c=a.checked;
        else if(b==="select-multiple")c=a.selectedIndex>-1?q.map(a.options,function(a){
            return a.selected
            }).join("-"):"";
        else if(a.nodeName.toLowerCase()==="select")c=a.selectedIndex;
        return c
        },ba=function(a,c){
        var d=a.target,e,f;
        if(!(!Z.test(d.nodeName)||d.readOnly)){
            e=q.data(d,"_change_data");
            f=_(d);
            if(a.type!=="focusout"||d.type!=="radio")q.data(d,"_change_data",f);
            if(!(e===b||f===e))if(e!=null||f){
                a.type="change";
                return q.event.trigger(a,c,d)
                }
            }
        };

q.event.special.change={
    filters:{
        focusout:ba,
        click:function(a){
            var b=a.target,c=b.type;
            if(c==="radio"||c==="checkbox"||b.nodeName.toLowerCase()==="select")return ba.call(this,a)
                },
        keydown:function(a){
            var b=a.target,c=b.type;
            if(a.keyCode===13&&b.nodeName.toLowerCase()!=="textarea"||a.keyCode===32&&(c==="checkbox"||c==="radio")||c==="select-multiple")return ba.call(this,a)
                },
        beforeactivate:function(a){
            a=a.target;
            q.data(a,"_change_data",_(a))
            }
        },
setup:function(){
    if(this.type==="file")return false;
    for(var a in $)q.event.add(this,a+".specialChange",$[a]);return Z.test(this.nodeName)
    },
teardown:function(){
    q.event.remove(this,".specialChange");
    return Z.test(this.nodeName)
    }
};

$=q.event.special.change.filters
}
t.addEventListener&&q.each({
    focus:"focusin",
    blur:"focusout"
},function(a,b){
    function c(a){
        a=q.event.fix(a);
        a.type=b;
        return q.event.handle.call(this,a)
        }
        q.event.special[b]={
        setup:function(){
            this.addEventListener(a,c,true)
            },
        teardown:function(){
            this.removeEventListener(a,c,true)
            }
        }
});
q.each(["bind","one"],function(a,c){
    q.fn[c]=function(a,d,e){
        if(typeof a==="object"){
            for(var f in a)this[c](f,d,a[f],e);return this
            }
            if(q.isFunction(d)){
            e=d;
            d=b
            }
            var g=c==="one"?q.proxy(e,function(a){
            q(this).unbind(a,g);
            return e.apply(this,arguments)
            }):e;
        if(a==="unload"&&c!=="one")this.one(a,d,e);
        else{
            f=0;
            for(var h=this.length;f<h;f++)q.event.add(this[f],a,g,d)
                }
                return this
        }
    });
q.fn.extend({
    unbind:function(a,b){
        if(typeof a==="object"&&!a.preventDefault)for(var c in a)this.unbind(c,a[c]);else{
            c=0;
            for(var d=this.length;c<d;c++)q.event.remove(this[c],a,b)
                }
                return this
        },
    delegate:function(a,b,c,d){
        return this.live(b,c,d,a)
        },
    undelegate:function(a,b,c){
        return arguments.length===0?this.unbind("live"):this.die(b,null,c,a)
        },
    trigger:function(a,b){
        return this.each(function(){
            q.event.trigger(a,b,this)
            })
        },
    triggerHandler:function(a,b){
        if(this[0]){
            a=q.Event(a);
            a.preventDefault();
            a.stopPropagation();
            q.event.trigger(a,b,this[0]);
            return a.result
            }
        },
toggle:function(a){
    for(var b=arguments,c=1;c<b.length;)q.proxy(a,b[c++]);
    return this.click(q.proxy(a,function(d){
        var e=(q.data(this,"lastToggle"+a.guid)||0)%c;
        q.data(this,"lastToggle"+a.guid,e+1);
        d.preventDefault();
        return b[e].apply(this,arguments)||false
        }))
    },
hover:function(a,b){
    return this.mouseenter(a).mouseleave(b||a)
    }
});
var bb={
    focus:"focusin",
    blur:"focusout",
    mouseenter:"mouseover",
    mouseleave:"mouseout"
};

q.each(["live","die"],function(a,c){
    q.fn[c]=function(a,d,e,f){
        var g,h=0,i,j,l=f||this.selector,m=f?this:q(this.context);
        if(q.isFunction(d)){
            e=d;
            d=b
            }
            for(a=(a||"").split(" ");(g=a[h++])!=null;){
            f=U.exec(g);
            i="";
            if(f){
                i=f[0];
                g=g.replace(U,"")
                }
                if(g==="hover")a.push("mouseenter"+i,"mouseleave"+i);
            else{
                j=g;
                if(g==="focus"||g==="blur"){
                    a.push(bb[g]+i);
                    g+=i
                    }else g=(bb[g]||g)+i;
                c==="live"?m.each(function(){
                    q.event.add(this,k(g,l),{
                        data:d,
                        selector:l,
                        handler:e,
                        origType:g,
                        origHandler:e,
                        preType:j
                    })
                    }):m.unbind(k(g,l),e)
                }
            }
        return this
    }
});
q.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error".split(" "),function(a,b){
    q.fn[b]=function(a){
        return a?this.bind(b,a):this.trigger(b)
        };
        
    if(q.attrFn)q.attrFn[b]=true
        });
a.attachEvent&&!a.addEventListener&&a.attachEvent("onunload",function(){
    for(var a in q.cache)if(q.cache[a].handle)try{
        q.event.remove(q.cache[a].handle.elem)
        }catch(b){}
        });
(function(){
    function d(a,b,c,d,e,f){
        e=0;
        for(var g=d.length;e<g;e++){
            var h=d[e];
            if(h){
                h=h[a];
                for(var i=false;h;){
                    if(h.sizcache===c){
                        i=d[h.sizset];
                        break
                    }
                    if(h.nodeType===1){
                        if(!f){
                            h.sizcache=c;
                            h.sizset=e
                            }
                            if(typeof b!=="string"){
                            if(h===b){
                                i=true;
                                break
                            }
                        }else if(j.filter(b,[h]).length>0){
                        i=h;
                        break
                    }
                }
                h=h[a]
                }
                d[e]=i
        }
        }
}
function c(a,b,c,d,e,f){
    e=0;
    for(var g=d.length;e<g;e++){
        var h=d[e];
        if(h){
            h=h[a];
            for(var i=false;h;){
                if(h.sizcache===c){
                    i=d[h.sizset];
                    break
                }
                if(h.nodeType===1&&!f){
                    h.sizcache=c;
                    h.sizset=e
                    }
                    if(h.nodeName.toLowerCase()===b){
                    i=h;
                    break
                }
                h=h[a]
                }
                d[e]=i
            }
        }
    }
function a(b){
    for(var c="",d,e=0;b[e];e++){
        d=b[e];
        if(d.nodeType===3||d.nodeType===4)c+=d.nodeValue;
        else if(d.nodeType!==8)c+=a(d.childNodes)
            }
            return c
    }
    var e=/((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]*['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,f=0,g=Object.prototype.toString,h=false,i=true;
[0,0].sort(function(){
    i=false;
    return 0
    });
var j=function(a,b,c,d){
    c=c||[];
    var f=b=b||t;
    if(b.nodeType!==1&&b.nodeType!==9)return[];
    if(!a||typeof a!=="string")return c;
    for(var h=[],i,m,o,p,q=true,v=s(b),w=a;(e.exec(""),i=e.exec(w))!==null;){
        w=i[3];
        h.push(i[1]);
        if(i[2]){
            p=i[3];
            break
        }
    }
    if(h.length>1&&l.exec(a))if(h.length===2&&k.relative[h[0]])m=u(h[0]+h[1],b);else for(m=k.relative[h[0]]?[b]:j(h.shift(),b);h.length;){
    a=h.shift();
    if(k.relative[a])a+=h.shift();
    m=u(a,m)
    }else{
    if(!d&&h.length>1&&b.nodeType===9&&!v&&k.match.ID.test(h[0])&&!k.match.ID.test(h[h.length-1])){
        i=j.find(h.shift(),b,v);
        b=i.expr?j.filter(i.expr,i.set)[0]:i.set[0]
        }
        if(b){
        i=d?{
            expr:h.pop(),
            set:n(d)
            }:j.find(h.pop(),h.length===1&&(h[0]==="~"||h[0]==="+")&&b.parentNode?b.parentNode:b,v);
        m=i.expr?j.filter(i.expr,i.set):i.set;
        if(h.length>0)o=n(m);else q=false;
        for(;h.length;){
            var x=h.pop();
            i=x;
            if(k.relative[x])i=h.pop();else x="";
            if(i==null)i=b;
            k.relative[x](o,i,v)
            }
        }else o=[]
    }
    o||(o=m);
o||j.error(x||a);
if(g.call(o)==="[object Array]")if(q)if(b&&b.nodeType===1)for(a=0;o[a]!=null;a++){
    if(o[a]&&(o[a]===true||o[a].nodeType===1&&r(b,o[a])))c.push(m[a])
        }else for(a=0;o[a]!=null;a++)o[a]&&o[a].nodeType===1&&c.push(m[a]);else c.push.apply(c,o);else n(o,c);
if(p){
    j(p,f,c,d);
    j.uniqueSort(c)
    }
    return c
};

j.uniqueSort=function(a){
    if(p){
        h=i;
        a.sort(p);
        if(h)for(var b=1;b<a.length;b++)a[b]===a[b-1]&&a.splice(b--,1)
            }
            return a
    };
    
j.matches=function(a,b){
    return j(a,null,null,b)
    };
    
j.find=function(a,b,c){
    var d,e;
    if(!a)return[];
    for(var f=0,g=k.order.length;f<g;f++){
        var h=k.order[f];
        if(e=k.leftMatch[h].exec(a)){
            var i=e[1];
            e.splice(1,1);
            if(i.substr(i.length-1)!=="\\"){
                e[1]=(e[1]||"").replace(/\\/g,"");
                d=k.find[h](e,b,c);
                if(d!=null){
                    a=a.replace(k.match[h],"");
                    break
                }
            }
        }
    }
d||(d=b.getElementsByTagName("*"));
return{
    set:d,
    expr:a
}
};

j.filter=function(a,c,d,e){
    for(var f=a,g=[],h=c,i,l,m=c&&c[0]&&s(c[0]);a&&c.length;){
        for(var n in k.filter)if((i=k.leftMatch[n].exec(a))!=null&&i[2]){
            var o=k.filter[n],p,q;
            q=i[1];
            l=false;
            i.splice(1,1);
            if(q.substr(q.length-1)!=="\\"){
                if(h===g)g=[];
                if(k.preFilter[n])if(i=k.preFilter[n](i,h,d,g,e,m)){
                    if(i===true)continue
                }else l=p=true;
                if(i)for(var r=0;(q=h[r])!=null;r++)if(q){
                    p=o(q,i,r,h);
                    var t=e^!!p;
                    if(d&&p!=null)if(t)l=true;else h[r]=false;
                    else if(t){
                        g.push(q);
                        l=true
                        }
                    }
                if(p!==b){
                d||(h=g);
                a=a.replace(k.match[n],"");
                if(!l)return[];
                break
            }
        }
        }
        if(a===f)if(l==null)j.error(a);else break;
f=a
}
return h
};

j.error=function(a){
    throw"Syntax error, unrecognized expression: "+a
    };
    
var k=j.selectors={
    order:["ID","NAME","TAG"],
    match:{
        ID:/#((?:[\w\u00c0-\uFFFF-]|\\.)+)/,
        CLASS:/\.((?:[\w\u00c0-\uFFFF-]|\\.)+)/,
        NAME:/\[name=['"]*((?:[\w\u00c0-\uFFFF-]|\\.)+)['"]*\]/,
        ATTR:/\[\s*((?:[\w\u00c0-\uFFFF-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/,
        TAG:/^((?:[\w\u00c0-\uFFFF\*-]|\\.)+)/,
        CHILD:/:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/,
        POS:/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/,
        PSEUDO:/:((?:[\w\u00c0-\uFFFF-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
    },
    leftMatch:{},
    attrMap:{
        "class":"className",
        "for":"htmlFor"
    },
    attrHandle:{
        href:function(a){
            return a.getAttribute("href")
            }
        },
relative:{
    "+":function(a,b){
        var c=typeof b==="string",d=c&&!/\W/.test(b);
        c=c&&!d;
        if(d)b=b.toLowerCase();
        d=0;
        for(var e=a.length,f;d<e;d++)if(f=a[d]){
            for(;(f=f.previousSibling)&&f.nodeType!==1;);
            a[d]=c||f&&f.nodeName.toLowerCase()===b?f||false:f===b
            }
            c&&j.filter(b,a,true)
        },
    ">":function(a,b){
        var c=typeof b==="string";
        if(c&&!/\W/.test(b)){
            b=b.toLowerCase();
            for(var d=0,e=a.length;d<e;d++){
                var f=a[d];
                if(f){
                    c=f.parentNode;
                    a[d]=c.nodeName.toLowerCase()===b?c:false
                    }
                }
            }else{
    d=0;
    for(e=a.length;d<e;d++)if(f=a[d])a[d]=c?f.parentNode:f.parentNode===b;c&&j.filter(b,a,true)
    }
},
"":function(a,b,e){
    var g=f++,h=d;
    if(typeof b==="string"&&!/\W/.test(b)){
        var i=b=b.toLowerCase();
        h=c
        }
        h("parentNode",b,g,a,i,e)
    },
"~":function(a,b,e){
    var g=f++,h=d;
    if(typeof b==="string"&&!/\W/.test(b)){
        var i=b=b.toLowerCase();
        h=c
        }
        h("previousSibling",b,g,a,i,e)
    }
},
find:{
    ID:function(a,b,c){
        if(typeof b.getElementById!=="undefined"&&!c)return(a=b.getElementById(a[1]))?[a]:[]
            },
    NAME:function(a,b){
        if(typeof b.getElementsByName!=="undefined"){
            var c=[];
            b=b.getElementsByName(a[1]);
            for(var d=0,e=b.length;d<e;d++)b[d].getAttribute("name")===a[1]&&c.push(b[d]);
            return c.length===0?null:c
            }
        },
TAG:function(a,b){
    return b.getElementsByTagName(a[1])
    }
},
preFilter:{
    CLASS:function(a,b,c,d,e,f){
        a=" "+a[1].replace(/\\/g,"")+" ";
        if(f)return a;
        f=0;
        for(var g;(g=b[f])!=null;f++)if(g)if(e^(g.className&&(" "+g.className+" ").replace(/[\t\n]/g," ").indexOf(a)>=0))c||d.push(g);
            else if(c)b[f]=false;return false
        },
    ID:function(a){
        return a[1].replace(/\\/g,"")
        },
    TAG:function(a){
        return a[1].toLowerCase()
        },
    CHILD:function(a){
        if(a[1]==="nth"){
            var b=/(-?)(\d*)n((?:\+|-)?\d*)/.exec(a[2]==="even"&&"2n"||a[2]==="odd"&&"2n+1"||!/\D/.test(a[2])&&"0n+"+a[2]||a[2]);
            a[2]=b[1]+(b[2]||1)-0;
            a[3]=b[3]-0
        }
        a[0]=f++;
        return a
        },
    ATTR:function(a,b,c,d,e,f){
        b=a[1].replace(/\\/g,"");
        if(!f&&k.attrMap[b])a[1]=k.attrMap[b];
        if(a[2]==="~=")a[4]=" "+a[4]+" ";
        return a
        },
    PSEUDO:function(a,b,c,d,f){
        if(a[1]==="not")if((e.exec(a[3])||"").length>1||/^\w/.test(a[3]))a[3]=j(a[3],null,null,b);
            else{
            a=j.filter(a[3],b,c,true^f);
            c||d.push.apply(d,a);
            return false
            }else if(k.match.POS.test(a[0])||k.match.CHILD.test(a[0]))return true;
        return a
        },
    POS:function(a){
        a.unshift(true);
        return a
        }
    },
filters:{
    enabled:function(a){
        return a.disabled===false&&a.type!=="hidden"
        },
    disabled:function(a){
        return a.disabled===true
        },
    checked:function(a){
        return a.checked===true
        },
    selected:function(a){
        return a.selected===true
        },
    parent:function(a){
        return!!a.firstChild
        },
    empty:function(a){
        return!a.firstChild
        },
    has:function(a,b,c){
        return!!j(c[3],a).length
        },
    header:function(a){
        return/h\d/i.test(a.nodeName)
        },
    text:function(a){
        return"text"===a.type
        },
    radio:function(a){
        return"radio"===a.type
        },
    checkbox:function(a){
        return"checkbox"===a.type
        },
    file:function(a){
        return"file"===a.type
        },
    password:function(a){
        return"password"===a.type
        },
    submit:function(a){
        return"submit"===a.type
        },
    image:function(a){
        return"image"===a.type
        },
    reset:function(a){
        return"reset"===a.type
        },
    button:function(a){
        return"button"===a.type||a.nodeName.toLowerCase()==="button"
        },
    input:function(a){
        return/input|select|textarea|button/i.test(a.nodeName)
        }
    },
setFilters:{
    first:function(a,b){
        return b===0
        },
    last:function(a,b,c,d){
        return b===d.length-1
        },
    even:function(a,b){
        return b%2===0
        },
    odd:function(a,b){
        return b%2===1
        },
    lt:function(a,b,c){
        return b<c[3]-0
        },
    gt:function(a,b,c){
        return b>c[3]-0
        },
    nth:function(a,b,c){
        return c[3]-0===b
        },
    eq:function(a,b,c){
        return c[3]-0===b
        }
    },
filter:{
    PSEUDO:function(b,c,d,e){
        var f=c[1],g=k.filters[f];
        if(g)return g(b,d,c,e);
        else if(f==="contains")return(b.textContent||b.innerText||a([b])||"").indexOf(c[3])>=0;
        else if(f==="not"){
            c=c[3];
            d=0;
            for(e=c.length;d<e;d++)if(c[d]===b)return false;return true
            }else j.error("Syntax error, unrecognized expression: "+f)
            },
    CHILD:function(a,b){
        var c=b[1],d=a;
        switch(c){
            case"only":case"first":
                for(;d=d.previousSibling;)if(d.nodeType===1)return false;if(c==="first")return true;
                d=a;
            case"last":
                for(;d=d.nextSibling;)if(d.nodeType===1)return false;return true;
            case"nth":
                c=b[2];
                var e=b[3];
                if(c===1&&e===0)return true;
                b=b[0];
                var f=a.parentNode;
                if(f&&(f.sizcache!==b||!a.nodeIndex)){
                var g=0;
                for(d=f.firstChild;d;d=d.nextSibling)if(d.nodeType===1)d.nodeIndex=++g;f.sizcache=b
                }
                a=a.nodeIndex-e;
            return c===0?a===0:a%c===0&&a/c>=0
            }
            },
ID:function(a,b){
    return a.nodeType===1&&a.getAttribute("id")===b
    },
TAG:function(a,b){
    return b==="*"&&a.nodeType===1||a.nodeName.toLowerCase()===b
    },
CLASS:function(a,b){
    return(" "+(a.className||a.getAttribute("class"))+" ").indexOf(b)>-1
    },
ATTR:function(a,b){
    var c=b[1];
    a=k.attrHandle[c]?k.attrHandle[c](a):a[c]!=null?a[c]:a.getAttribute(c);
    c=a+"";
    var d=b[2];
    b=b[4];
    return a==null?d==="!=":d==="="?c===b:d==="*="?c.indexOf(b)>=0:d==="~="?(" "+c+" ").indexOf(b)>=0:!b?c&&a!==false:d==="!="?c!==b:d==="^="?c.indexOf(b)===0:d==="$="?c.substr(c.length-b.length)===b:d==="|="?c===b||c.substr(0,b.length+1)===b+"-":false
    },
POS:function(a,b,c,d){
    var e=k.setFilters[b[2]];
    if(e)return e(a,c,b,d)
        }
    }
},l=k.match.POS;
for(var m in k.match){
    k.match[m]=new RegExp(k.match[m].source+/(?![^\[]*\])(?![^\(]*\))/.source);
    k.leftMatch[m]=new RegExp(/(^(?:.|\r|\n)*?)/.source+k.match[m].source.replace(/\\(\d+)/g,function(a,b){
        return"\\"+(b-0+1)
        }))
    }
    var n=function(a,b){
    a=Array.prototype.slice.call(a,0);
    if(b){
        b.push.apply(b,a);
        return b
        }
        return a
    };
    
try{
    Array.prototype.slice.call(t.documentElement.childNodes,0)
    }catch(o){
    n=function(a,b){
        b=b||[];
        if(g.call(a)==="[object Array]")Array.prototype.push.apply(b,a);
        else if(typeof a.length==="number")for(var c=0,d=a.length;c<d;c++)b.push(a[c]);else for(c=0;a[c];c++)b.push(a[c]);
        return b
        }
    }
var p;
if(t.documentElement.compareDocumentPosition)p=function(a,b){
    if(!a.compareDocumentPosition||!b.compareDocumentPosition){
        if(a==b)h=true;
        return a.compareDocumentPosition?-1:1
        }
        a=a.compareDocumentPosition(b)&4?-1:a===b?0:1;
    if(a===0)h=true;
    return a
    };
else if("sourceIndex"in t.documentElement)p=function(a,b){
    if(!a.sourceIndex||!b.sourceIndex){
        if(a==b)h=true;
        return a.sourceIndex?-1:1
        }
        a=a.sourceIndex-b.sourceIndex;
    if(a===0)h=true;
    return a
    };
else if(t.createRange)p=function(a,b){
    if(!a.ownerDocument||!b.ownerDocument){
        if(a==b)h=true;
        return a.ownerDocument?-1:1
        }
        var c=a.ownerDocument.createRange(),d=b.ownerDocument.createRange();
    c.setStart(a,0);
    c.setEnd(a,0);
    d.setStart(b,0);
    d.setEnd(b,0);
    a=c.compareBoundaryPoints(Range.START_TO_END,d);
    if(a===0)h=true;
    return a
    };
(function(){
    var a=t.createElement("div"),c="script"+(new Date).getTime();
    a.innerHTML="<a name='"+c+"'/>";
    var d=t.documentElement;
    d.insertBefore(a,d.firstChild);
    if(t.getElementById(c)){
        k.find.ID=function(a,c,d){
            if(typeof c.getElementById!=="undefined"&&!d)return(c=c.getElementById(a[1]))?c.id===a[1]||typeof c.getAttributeNode!=="undefined"&&c.getAttributeNode("id").nodeValue===a[1]?[c]:b:[]
                };
                
        k.filter.ID=function(a,b){
            var c=typeof a.getAttributeNode!=="undefined"&&a.getAttributeNode("id");
            return a.nodeType===1&&c&&c.nodeValue===b
            }
        }
    d.removeChild(a);
    d=a=null
    })();
(function(){
    var a=t.createElement("div");
    a.appendChild(t.createComment(""));
    if(a.getElementsByTagName("*").length>0)k.find.TAG=function(a,b){
        b=b.getElementsByTagName(a[1]);
        if(a[1]==="*"){
            a=[];
            for(var c=0;b[c];c++)b[c].nodeType===1&&a.push(b[c]);
            b=a
            }
            return b
        };
        
    a.innerHTML="<a href='#'></a>";
    if(a.firstChild&&typeof a.firstChild.getAttribute!=="undefined"&&a.firstChild.getAttribute("href")!=="#")k.attrHandle.href=function(a){
        return a.getAttribute("href",2)
        };
        
    a=null
    })();
t.querySelectorAll&&function(){
    var a=j,b=t.createElement("div");
    b.innerHTML="<p class='TEST'></p>";
    if(!(b.querySelectorAll&&b.querySelectorAll(".TEST").length===0)){
        j=function(b,c,d,e){
            c=c||t;
            if(!e&&c.nodeType===9&&!s(c))try{
                return n(c.querySelectorAll(b),d)
                }catch(f){}
                return a(b,c,d,e)
            };
            
        for(var c in a)j[c]=a[c];b=null
        }
    }();
(function(){
    var a=t.createElement("div");
    a.innerHTML="<div class='test e'></div><div class='test'></div>";
    if(!(!a.getElementsByClassName||a.getElementsByClassName("e").length===0)){
        a.lastChild.className="e";
        if(a.getElementsByClassName("e").length!==1){
            k.order.splice(1,0,"CLASS");
            k.find.CLASS=function(a,b,c){
                if(typeof b.getElementsByClassName!=="undefined"&&!c)return b.getElementsByClassName(a[1])
                    };
                    
            a=null
            }
        }
})();
var r=t.compareDocumentPosition?function(a,b){
    return!!(a.compareDocumentPosition(b)&16)
    }:function(a,b){
    return a!==b&&(a.contains?a.contains(b):true)
    },s=function(a){
    return(a=(a?a.ownerDocument||a:0).documentElement)?a.nodeName!=="HTML":false
    },u=function(a,b){
    var c=[],d="",e;
    for(b=b.nodeType?[b]:b;e=k.match.PSEUDO.exec(a);){
        d+=e[0];
        a=a.replace(k.match.PSEUDO,"")
        }
        a=k.relative[a]?a+"*":a;
    e=0;
    for(var f=b.length;e<f;e++)j(a,b[e],c);
    return j.filter(d,c)
    };
    
q.find=j;
q.expr=j.selectors;
q.expr[":"]=q.expr.filters;
q.unique=j.uniqueSort;
q.text=a;
q.isXMLDoc=s;
q.contains=r
})();
var bc=/Until$/,bd=/^(?:parents|prevUntil|prevAll)/,be=/,/;
H=Array.prototype.slice;
var bf=function(a,b,c){
    if(q.isFunction(b))return q.grep(a,function(a,d){
        return!!b.call(a,d,a)===c
        });
    else if(b.nodeType)return q.grep(a,function(a){
        return a===b===c
        });
    else if(typeof b==="string"){
        var d=q.grep(a,function(a){
            return a.nodeType===1
            });
        if(w.test(b))return q.filter(b,d,!c);else b=q.filter(b,d)
            }
            return q.grep(a,function(a){
        return q.inArray(a,b)>=0===c
        })
    };
    
q.fn.extend({
    find:function(a){
        for(var b=this.pushStack("","find",a),c=0,d=0,e=this.length;d<e;d++){
            c=b.length;
            q.find(a,this[d],b);
            if(d>0)for(var f=c;f<b.length;f++)for(var g=0;g<c;g++)if(b[g]===b[f]){
                b.splice(f--,1);
                break
            }
            }
            return b
    },
has:function(a){
    var b=q(a);
    return this.filter(function(){
        for(var a=0,c=b.length;a<c;a++)if(q.contains(this,b[a]))return true
            })
    },
not:function(a){
    return this.pushStack(bf(this,a,false),"not",a)
    },
filter:function(a){
    return this.pushStack(bf(this,a,true),"filter",a)
    },
is:function(a){
    return!!a&&q.filter(a,this).length>0
    },
closest:function(a,b){
    if(q.isArray(a)){
        var c=[],d=this[0],e,f={},g;
        if(d&&a.length){
            e=0;
            for(var h=a.length;e<h;e++){
                g=a[e];
                f[g]||(f[g]=q.expr.match.POS.test(g)?q(g,b||this.context):g)
                }
                for(;d&&d.ownerDocument&&d!==b;){
                for(g in f){
                    e=f[g];
                    if(e.jquery?e.index(d)>-1:q(d).is(e)){
                        c.push({
                            selector:g,
                            elem:d
                        });
                        delete f[g]
                    }
                }
                d=d.parentNode
            }
            }
        return c
}
var i=q.expr.match.POS.test(a)?q(a,b||this.context):null;
return this.map(function(c,d){
    for(;d&&d.ownerDocument&&d!==b;){
        if(i?i.index(d)>-1:q(d).is(a))return d;
        d=d.parentNode
        }
        return null
    })
},
index:function(a){
    if(!a||typeof a==="string")return q.inArray(this[0],a?q(a):this.parent().children());
    return q.inArray(a.jquery?a[0]:a,this)
    },
add:function(a,b){
    a=typeof a==="string"?q(a,b||this.context):q.makeArray(a);
    b=q.merge(this.get(),a);
    return this.pushStack(l(a[0])||l(b[0])?b:q.unique(b))
    },
andSelf:function(){
    return this.add(this.prevObject)
    }
});
q.each({
    parent:function(a){
        return(a=a.parentNode)&&a.nodeType!==11?a:null
        },
    parents:function(a){
        return q.dir(a,"parentNode")
        },
    parentsUntil:function(a,b,c){
        return q.dir(a,"parentNode",c)
        },
    next:function(a){
        return q.nth(a,2,"nextSibling")
        },
    prev:function(a){
        return q.nth(a,2,"previousSibling")
        },
    nextAll:function(a){
        return q.dir(a,"nextSibling")
        },
    prevAll:function(a){
        return q.dir(a,"previousSibling")
        },
    nextUntil:function(a,b,c){
        return q.dir(a,"nextSibling",c)
        },
    prevUntil:function(a,b,c){
        return q.dir(a,"previousSibling",c)
        },
    siblings:function(a){
        return q.sibling(a.parentNode.firstChild,a)
        },
    children:function(a){
        return q.sibling(a.firstChild)
        },
    contents:function(a){
        return q.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:q.makeArray(a.childNodes)
        }
    },function(a,b){
    q.fn[a]=function(c,d){
        var e=q.map(this,b,c);
        bc.test(a)||(d=c);
        if(d&&typeof d==="string")e=q.filter(d,e);
        e=this.length>1?q.unique(e):e;
        if((this.length>1||be.test(d))&&bd.test(a))e=e.reverse();
        return this.pushStack(e,a,H.call(arguments).join(","))
        }
    });
q.extend({
    filter:function(a,b,c){
        if(c)a=":not("+a+")";
        return q.find.matches(a,b)
        },
    dir:function(a,c,d){
        var e=[];
        for(a=a[c];a&&a.nodeType!==9&&(d===b||a.nodeType!==1||!q(a).is(d));){
            a.nodeType===1&&e.push(a);
            a=a[c]
            }
            return e
        },
    nth:function(a,b,c){
        b=b||1;
        for(var d=0;a;a=a[c])if(a.nodeType===1&&++d===b)break;return a
        },
    sibling:function(a,b){
        for(var c=[];a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);
        return c
        }
    });
var bg=/ jQuery\d+="(?:\d+|null)"/g,bh=/^\s+/,bi=/(<([\w:]+)[^>]*?)\/>/g,bj=/^(?:area|br|col|embed|hr|img|input|link|meta|param)$/i,bk=/<([\w:]+)/,bl=/<tbody/i,bm=/<|&#?\w+;/,bn=/<script|<object|<embed|<option|<style/i,bo=/checked\s*(?:[^=]|=\s*.checked.)/i,bp=function(a,b,c){
    return bj.test(c)?a:b+"></"+c+">"
    },bq={
    option:[1,"<select multiple='multiple'>","</select>"],
    legend:[1,"<fieldset>","</fieldset>"],
    thead:[1,"<table>","</table>"],
    tr:[2,"<table><tbody>","</tbody></table>"],
    td:[3,"<table><tbody><tr>","</tr></tbody></table>"],
    col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],
    area:[1,"<map>","</map>"],
    _default:[0,"",""]
    };
    
bq.optgroup=bq.option;
bq.tbody=bq.tfoot=bq.colgroup=bq.caption=bq.thead;
bq.th=bq.td;
if(!q.support.htmlSerialize)bq._default=[1,"div<div>","</div>"];
q.fn.extend({
    text:function(a){
        if(q.isFunction(a))return this.each(function(b){
            var c=q(this);
            c.text(a.call(this,b,c.text()))
            });
        if(typeof a!=="object"&&a!==b)return this.empty().append((this[0]&&this[0].ownerDocument||t).createTextNode(a));
        return q.text(this)
        },
    wrapAll:function(a){
        if(q.isFunction(a))return this.each(function(b){
            q(this).wrapAll(a.call(this,b))
            });
        if(this[0]){
            var b=q(a,this[0].ownerDocument).eq(0).clone(true);
            this[0].parentNode&&b.insertBefore(this[0]);
            b.map(function(){
                for(var a=this;a.firstChild&&a.firstChild.nodeType===1;)a=a.firstChild;
                return a
                }).append(this)
            }
            return this
        },
    wrapInner:function(a){
        if(q.isFunction(a))return this.each(function(b){
            q(this).wrapInner(a.call(this,b))
            });
        return this.each(function(){
            var b=q(this),c=b.contents();
            c.length?c.wrapAll(a):b.append(a)
            })
        },
    wrap:function(a){
        return this.each(function(){
            q(this).wrapAll(a)
            })
        },
    unwrap:function(){
        return this.parent().each(function(){
            q.nodeName(this,"body")||q(this).replaceWith(this.childNodes)
            }).end()
        },
    append:function(){
        return this.domManip(arguments,true,function(a){
            this.nodeType===1&&this.appendChild(a)
            })
        },
    prepend:function(){
        return this.domManip(arguments,true,function(a){
            this.nodeType===1&&this.insertBefore(a,this.firstChild)
            })
        },
    before:function(){
        if(this[0]&&this[0].parentNode)return this.domManip(arguments,false,function(a){
            this.parentNode.insertBefore(a,this)
            });
        else if(arguments.length){
            var a=q(arguments[0]);
            a.push.apply(a,this.toArray());
            return this.pushStack(a,"before",arguments)
            }
        },
after:function(){
    if(this[0]&&this[0].parentNode)return this.domManip(arguments,false,function(a){
        this.parentNode.insertBefore(a,this.nextSibling)
        });
    else if(arguments.length){
        var a=this.pushStack(this,"after",arguments);
        a.push.apply(a,q(arguments[0]).toArray());
        return a
        }
    },
remove:function(a,b){
    for(var c=0,d;(d=this[c])!=null;c++)if(!a||q.filter(a,[d]).length){
        if(!b&&d.nodeType===1){
            q.cleanData(d.getElementsByTagName("*"));
            q.cleanData([d])
            }
            d.parentNode&&d.parentNode.removeChild(d)
        }
        return this
    },
empty:function(){
    for(var a=0,b;(b=this[a])!=null;a++)for(b.nodeType===1&&q.cleanData(b.getElementsByTagName("*"));b.firstChild;)b.removeChild(b.firstChild);
    return this
    },
clone:function(a){
    var b=this.map(function(){
        if(!q.support.noCloneEvent&&!q.isXMLDoc(this)){
            var a=this.outerHTML,b=this.ownerDocument;
            if(!a){
                a=b.createElement("div");
                a.appendChild(this.cloneNode(true));
                a=a.innerHTML
                }
                return q.clean([a.replace(bg,"").replace(/=([^="'>\s]+\/)>/g,'="$1">').replace(bh,"")],b)[0]
            }else return this.cloneNode(true)
            });
    if(a===true){
        m(this,b);
        m(this.find("*"),b.find("*"))
        }
        return b
    },
html:function(a){
    if(a===b)return this[0]&&this[0].nodeType===1?this[0].innerHTML.replace(bg,""):null;
    else if(typeof a==="string"&&!bn.test(a)&&(q.support.leadingWhitespace||!bh.test(a))&&!bq[(bk.exec(a)||["",""])[1].toLowerCase()]){
        a=a.replace(bi,bp);
        try{
            for(var c=0,d=this.length;c<d;c++)if(this[c].nodeType===1){
                q.cleanData(this[c].getElementsByTagName("*"));
                this[c].innerHTML=a
                }
            }catch(e){
        this.empty().append(a)
        }
    }else q.isFunction(a)?this.each(function(b){
    var c=q(this),d=c.html();
    c.empty().append(function(){
        return a.call(this,b,d)
        })
    }):this.empty().append(a);
return this
},
replaceWith:function(a){
    if(this[0]&&this[0].parentNode){
        if(q.isFunction(a))return this.each(function(b){
            var c=q(this),d=c.html();
            c.replaceWith(a.call(this,b,d))
            });
        if(typeof a!=="string")a=q(a).detach();
        return this.each(function(){
            var b=this.nextSibling,c=this.parentNode;
            q(this).remove();
            b?q(b).before(a):q(c).append(a)
            })
        }else return this.pushStack(q(q.isFunction(a)?a():a),"replaceWith",a)
        },
detach:function(a){
    return this.remove(a,true)
    },
domManip:function(a,c,e){
    function f(a){
        return q.nodeName(a,"table")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a
        }
        var g,h,i=a[0],j=[],k;
    if(!q.support.checkClone&&arguments.length===3&&typeof i==="string"&&bo.test(i))return this.each(function(){
        q(this).domManip(a,c,e,true)
        });
    if(q.isFunction(i))return this.each(function(d){
        var f=q(this);
        a[0]=i.call(this,d,c?f.html():b);
        f.domManip(a,c,e)
        });
    if(this[0]){
        g=i&&i.parentNode;
        g=q.support.parentNode&&g&&g.nodeType===11&&g.childNodes.length===this.length?{
            fragment:g
        }:n(a,this,j);
        k=g.fragment;
        if(h=k.childNodes.length===1?k=k.firstChild:k.firstChild){
            c=c&&q.nodeName(h,"tr");
            for(var l=0,m=this.length;l<m;l++)e.call(c?f(this[l],h):this[l],l>0||g.cacheable||this.length>1?k.cloneNode(true):k)
                }
                j.length&&q.each(j,d)
        }
        return this
    }
});
q.fragments={};

q.each({
    appendTo:"append",
    prependTo:"prepend",
    insertBefore:"before",
    insertAfter:"after",
    replaceAll:"replaceWith"
},function(a,b){
    q.fn[a]=function(c){
        var d=[];
        c=q(c);
        var e=this.length===1&&this[0].parentNode;
        if(e&&e.nodeType===11&&e.childNodes.length===1&&c.length===1){
            c[b](this[0]);
            return this
            }else{
            e=0;
            for(var f=c.length;e<f;e++){
                var g=(e>0?this.clone(true):this).get();
                q.fn[b].apply(q(c[e]),g);
                d=d.concat(g)
                }
                return this.pushStack(d,a,c.selector)
            }
        }
});
q.extend({
    clean:function(a,b,c,d){
        b=b||t;
        if(typeof b.createElement==="undefined")b=b.ownerDocument||b[0]&&b[0].ownerDocument||t;
        for(var e=[],f=0,g;(g=a[f])!=null;f++){
            if(typeof g==="number")g+="";
            if(g){
                if(typeof g==="string"&&!bm.test(g))g=b.createTextNode(g);
                else if(typeof g==="string"){
                    g=g.replace(bi,bp);
                    var h=(bk.exec(g)||["",""])[1].toLowerCase(),i=bq[h]||bq._default,j=i[0],k=b.createElement("div");
                    for(k.innerHTML=i[1]+g+i[2];j--;)k=k.lastChild;
                    if(!q.support.tbody){
                        j=bl.test(g);
                        h=h==="table"&&!j?k.firstChild&&k.firstChild.childNodes:i[1]==="<table>"&&!j?k.childNodes:[];
                        for(i=h.length-1;i>=0;--i)q.nodeName(h[i],"tbody")&&!h[i].childNodes.length&&h[i].parentNode.removeChild(h[i])
                            }!q.support.leadingWhitespace&&bh.test(g)&&k.insertBefore(b.createTextNode(bh.exec(g)[0]),k.firstChild);
                    g=k.childNodes
                    }
                    if(g.nodeType)e.push(g);else e=q.merge(e,g)
                    }
                }
        if(c)for(f=0;e[f];f++)if(d&&q.nodeName(e[f],"script")&&(!e[f].type||e[f].type.toLowerCase()==="text/javascript"))d.push(e[f].parentNode?e[f].parentNode.removeChild(e[f]):e[f]);
        else{
        e[f].nodeType===1&&e.splice.apply(e,[f+1,0].concat(q.makeArray(e[f].getElementsByTagName("script"))));
        c.appendChild(e[f])
        }
        return e
    },
cleanData:function(a){
    for(var b,c,d=q.cache,e=q.event.special,f=q.support.deleteExpando,g=0,h;(h=a[g])!=null;g++)if(c=h[q.expando]){
        b=d[c];
        if(b.events)for(var i in b.events)e[i]?q.event.remove(h,i):W(h,i,b.handle);if(f)delete h[q.expando];else h.removeAttribute&&h.removeAttribute(q.expando);
        delete d[c]
    }
    }
});
var br=/z-?index|font-?weight|opacity|zoom|line-?height/i,bs=/alpha\([^)]*\)/,bt=/opacity=([^)]*)/,bu=/float/i,bv=/-([a-z])/ig,bw=/([A-Z])/g,bx=/^-?\d+(?:px)?$/i,by=/^-?\d/,bz={
    position:"absolute",
    visibility:"hidden",
    display:"block"
},bA=["Left","Right"],bB=["Top","Bottom"],bC=t.defaultView&&t.defaultView.getComputedStyle,bD=q.support.cssFloat?"cssFloat":"styleFloat",bE=function(a,b){
    return b.toUpperCase()
    };
    
q.fn.css=function(a,c){
    return e(this,a,c,true,function(a,c,d){
        if(d===b)return q.curCSS(a,c);
        if(typeof d==="number"&&!br.test(c))d+="px";
        q.style(a,c,d)
        })
    };
    
q.extend({
    style:function(a,c,d){
        if(!a||a.nodeType===3||a.nodeType===8)return b;
        if((c==="width"||c==="height")&&parseFloat(d)<0)d=b;
        var e=a.style||a,f=d!==b;
        if(!q.support.opacity&&c==="opacity"){
            if(f){
                e.zoom=1;
                c=parseInt(d,10)+""==="NaN"?"":"alpha(opacity="+d*100+")";
                a=e.filter||q.curCSS(a,"filter")||"";
                e.filter=bs.test(a)?a.replace(bs,c):c
                }
                return e.filter&&e.filter.indexOf("opacity=")>=0?parseFloat(bt.exec(e.filter)[1])/100+"":""
            }
            if(bu.test(c))c=bD;
        c=c.replace(bv,bE);
        if(f)e[c]=d;
        return e[c]
        },
    css:function(a,b,c,d){
        if(b==="width"||b==="height"){
            var e,f=b==="width"?bA:bB;
            function g(){
                e=b==="width"?a.offsetWidth:a.offsetHeight;
                d!=="border"&&q.each(f,function(){
                    d||(e-=parseFloat(q.curCSS(a,"padding"+this,true))||0);
                    if(d==="margin")e+=parseFloat(q.curCSS(a,"margin"+this,true))||0;else e-=parseFloat(q.curCSS(a,"border"+this+"Width",true))||0
                        })
                }
                a.offsetWidth!==0?g():q.swap(a,bz,g);
            return Math.max(0,Math.round(e))
            }
            return q.curCSS(a,b,c)
        },
    curCSS:function(a,b,c){
        var d,e=a.style;
        if(!q.support.opacity&&b==="opacity"&&a.currentStyle){
            d=bt.test(a.currentStyle.filter||"")?parseFloat(RegExp.$1)/100+"":"";
            return d===""?"1":d
            }
            if(bu.test(b))b=bD;
        if(!c&&e&&e[b])d=e[b];
        else if(bC){
            if(bu.test(b))b="float";
            b=b.replace(bw,"-$1").toLowerCase();
            e=a.ownerDocument.defaultView;
            if(!e)return null;
            if(a=e.getComputedStyle(a,null))d=a.getPropertyValue(b);
            if(b==="opacity"&&d==="")d="1"
                }else if(a.currentStyle){
            c=b.replace(bv,bE);
            d=a.currentStyle[b]||a.currentStyle[c];
            if(!bx.test(d)&&by.test(d)){
                b=e.left;
                var f=a.runtimeStyle.left;
                a.runtimeStyle.left=a.currentStyle.left;
                e.left=c==="fontSize"?"1em":d||0;
                d=e.pixelLeft+"px";
                e.left=b;
                a.runtimeStyle.left=f
                }
            }
        return d
    },
swap:function(a,b,c){
    var d={};
    
    for(var e in b){
        d[e]=a.style[e];
        a.style[e]=b[e]
        }
        c.call(a);
    for(e in b)a.style[e]=d[e]
        }
    });
if(q.expr&&q.expr.filters){
    q.expr.filters.hidden=function(a){
        var b=a.offsetWidth,c=a.offsetHeight,d=a.nodeName.toLowerCase()==="tr";
        return b===0&&c===0&&!d?true:b>0&&c>0&&!d?false:q.curCSS(a,"display")==="none"
        };
        
    q.expr.filters.visible=function(a){
        return!q.expr.filters.hidden(a)
        }
    }
var bF=f(),bG=/<script(.|\s)*?\/script>/gi,bH=/select|textarea/i,bI=/color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week/i,bJ=/=\?(&|$)/,bK=/\?/,bL=/(\?|&)_=.*?(&|$)/,bM=/^(\w+:)?\/\/([^\/?#]+)/,bN=/%20/g,bO=q.fn.load;
q.fn.extend({
    load:function(a,b,c){
        if(typeof a!=="string")return bO.call(this,a);
        else if(!this.length)return this;
        var d=a.indexOf(" ");
        if(d>=0){
            var e=a.slice(d,a.length);
            a=a.slice(0,d)
            }
            d="GET";
        if(b)if(q.isFunction(b)){
            c=b;
            b=null
            }else if(typeof b==="object"){
            b=q.param(b,q.ajaxSettings.traditional);
            d="POST"
            }
            var f=this;
        q.ajax({
            url:a,
            type:d,
            dataType:"html",
            data:b,
            complete:function(a,b){
                if(b==="success"||b==="notmodified")f.html(e?q("<div />").append(a.responseText.replace(bG,"")).find(e):a.responseText);
                c&&f.each(c,[a.responseText,b,a])
                }
            });
    return this
    },
serialize:function(){
    return q.param(this.serializeArray())
    },
serializeArray:function(){
    return this.map(function(){
        return this.elements?q.makeArray(this.elements):this
        }).filter(function(){
        return this.name&&!this.disabled&&(this.checked||bH.test(this.nodeName)||bI.test(this.type))
        }).map(function(a,b){
        a=q(this).val();
        return a==null?null:q.isArray(a)?q.map(a,function(a){
            return{
                name:b.name,
                value:a
            }
        }):{
        name:b.name,
        value:a
    }
    }).get()
}
});
q.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){
    q.fn[b]=function(a){
        return this.bind(b,a)
        }
    });
q.extend({
    get:function(a,b,c,d){
        if(q.isFunction(b)){
            d=d||c;
            c=b;
            b=null
            }
            return q.ajax({
            type:"GET",
            url:a,
            data:b,
            success:c,
            dataType:d
        })
        },
    getScript:function(a,b){
        return q.get(a,null,b,"script")
        },
    getJSON:function(a,b,c){
        return q.get(a,b,c,"json")
        },
    post:function(a,b,c,d){
        if(q.isFunction(b)){
            d=d||c;
            c=b;
            b={}
        }
        return q.ajax({
        type:"POST",
        url:a,
        data:b,
        success:c,
        dataType:d
    })
    },
ajaxSetup:function(a){
    q.extend(q.ajaxSettings,a)
    },
ajaxSettings:{
    url:location.href,
    global:true,
    type:"GET",
    contentType:"application/x-www-form-urlencoded",
    processData:true,
    async:true,
    xhr:a.XMLHttpRequest&&(a.location.protocol!=="file:"||!a.ActiveXObject)?function(){
        return new a.XMLHttpRequest
        }:function(){
        try{
            return new a.ActiveXObject("Microsoft.XMLHTTP")
            }catch(b){}
    },
accepts:{
    xml:"application/xml, text/xml",
    html:"text/html",
    script:"text/javascript, application/javascript",
    json:"application/json, text/javascript",
    text:"text/plain",
    _default:"*/*"
}
},
lastModified:{},
etag:{},
ajax:function(c){
    function g(a,b){
        (h.context?q(h.context):q.event).trigger(a,b)
        }
        function e(){
        h.complete&&h.complete.call(l,v,j);
        h.global&&g("ajaxComplete",[v,h]);
        h.global&&!--q.active&&q.event.trigger("ajaxStop")
        }
        function d(){
        h.success&&h.success.call(l,k,j,v);
        h.global&&g("ajaxSuccess",[v,h])
        }
        var h=q.extend(true,{},q.ajaxSettings,c),i,j,k,l=c&&c.context||h,m=h.type.toUpperCase();
    if(h.data&&h.processData&&typeof h.data!=="string")h.data=q.param(h.data,h.traditional);
    if(h.dataType==="jsonp"){
        if(m==="GET")bJ.test(h.url)||(h.url+=(bK.test(h.url)?"&":"?")+(h.jsonp||"callback")+"=?");
        else if(!h.data||!bJ.test(h.data))h.data=(h.data?h.data+"&":"")+(h.jsonp||"callback")+"=?";
        h.dataType="json"
        }
        if(h.dataType==="json"&&(h.data&&bJ.test(h.data)||bJ.test(h.url))){
        i=h.jsonpCallback||"jsonp"+bF++;
        if(h.data)h.data=(h.data+"").replace(bJ,"="+i+"$1");
        h.url=h.url.replace(bJ,"="+i+"$1");
        h.dataType="script";
        a[i]=a[i]||function(c){
            k=c;
            d();
            e();
            a[i]=b;
            try{
                delete a[i]
            }catch(f){}
            p&&p.removeChild(r)
            }
        }
    if(h.dataType==="script"&&h.cache===null)h.cache=false;
if(h.cache===false&&m==="GET"){
    var n=f(),o=h.url.replace(bL,"$1_="+n+"$2");
    h.url=o+(o===h.url?(bK.test(h.url)?"&":"?")+"_="+n:"")
    }
    if(h.data&&m==="GET")h.url+=(bK.test(h.url)?"&":"?")+h.data;
h.global&&!(q.active++)&&q.event.trigger("ajaxStart");
n=(n=bM.exec(h.url))&&(n[1]&&n[1]!==location.protocol||n[2]!==location.host);
if(h.dataType==="script"&&m==="GET"&&n){
    var p=t.getElementsByTagName("head")[0]||t.documentElement,r=t.createElement("script");
    r.src=h.url;
    if(h.scriptCharset)r.charset=h.scriptCharset;
    if(!i){
        var s=false;
        r.onload=r.onreadystatechange=function(){
            if(!s&&(!this.readyState||this.readyState==="loaded"||this.readyState==="complete")){
                s=true;
                d();
                e();
                r.onload=r.onreadystatechange=null;
                p&&r.parentNode&&p.removeChild(r)
                }
            }
    }
p.insertBefore(r,p.firstChild);
return b
}
var u=false,v=h.xhr();
if(v){
    h.username?v.open(m,h.url,h.async,h.username,h.password):v.open(m,h.url,h.async);
    try{
        if(h.data||c&&c.contentType)v.setRequestHeader("Content-Type",h.contentType);
        if(h.ifModified){
            q.lastModified[h.url]&&v.setRequestHeader("If-Modified-Since",q.lastModified[h.url]);
            q.etag[h.url]&&v.setRequestHeader("If-None-Match",q.etag[h.url])
            }
            n||v.setRequestHeader("X-Requested-With","XMLHttpRequest");
        v.setRequestHeader("Accept",h.dataType&&h.accepts[h.dataType]?h.accepts[h.dataType]+", */*":h.accepts._default)
        }catch(w){}
    if(h.beforeSend&&h.beforeSend.call(l,v,h)===false){
        h.global&&!--q.active&&q.event.trigger("ajaxStop");
        v.abort();
        return false
        }
        h.global&&g("ajaxSend",[v,h]);
    var x=v.onreadystatechange=function(a){
        if(!v||v.readyState===0||a==="abort"){
            u||e();
            u=true;
            if(v)v.onreadystatechange=q.noop
                }else if(!u&&v&&(v.readyState===4||a==="timeout")){
            u=true;
            v.onreadystatechange=q.noop;
            j=a==="timeout"?"timeout":!q.httpSuccess(v)?"error":h.ifModified&&q.httpNotModified(v,h.url)?"notmodified":"success";
            var b;
            if(j==="success")try{
                k=q.httpData(v,h.dataType,h)
                }catch(c){
                j="parsererror";
                b=c
                }
                if(j==="success"||j==="notmodified")i||d();else q.handleError(h,v,j,b);
            e();
            a==="timeout"&&v.abort();
            if(h.async)v=null
                }
            };
    
try{
    var y=v.abort;
    v.abort=function(){
        v&&y.call(v);
        x("abort")
        }
    }catch(z){}
h.async&&h.timeout>0&&setTimeout(function(){
    v&&!u&&x("timeout")
    },h.timeout);
try{
    v.send(m==="POST"||m==="PUT"||m==="DELETE"?h.data:null)
    }catch(A){
    q.handleError(h,v,null,A);
    e()
    }
    h.async||x();
return v
}
},
handleError:function(a,b,c,d){
    if(a.error)a.error.call(a.context||a,b,c,d);
    if(a.global)(a.context?q(a.context):q.event).trigger("ajaxError",[b,a,d])
        },
active:0,
httpSuccess:function(a){
    try{
        return!a.status&&location.protocol==="file:"||a.status>=200&&a.status<300||a.status===304||a.status===1223||a.status===0
        }catch(b){}
    return false
    },
httpNotModified:function(a,b){
    var c=a.getResponseHeader("Last-Modified"),d=a.getResponseHeader("Etag");
    if(c)q.lastModified[b]=c;
    if(d)q.etag[b]=d;
    return a.status===304||a.status===0
    },
httpData:function(a,b,c){
    var d=a.getResponseHeader("content-type")||"",e=b==="xml"||!b&&d.indexOf("xml")>=0;
    a=e?a.responseXML:a.responseText;
    e&&a.documentElement.nodeName==="parsererror"&&q.error("parsererror");
    if(c&&c.dataFilter)a=c.dataFilter(a,b);
    if(typeof a==="string")if(b==="json"||!b&&d.indexOf("json")>=0)a=q.parseJSON(a);
        else if(b==="script"||!b&&d.indexOf("javascript")>=0)q.globalEval(a);
    return a
    },
param:function(a,c){
    function e(a,b){
        b=q.isFunction(b)?b():b;
        f[f.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)
        }
        function d(a,b){
        if(q.isArray(b))q.each(b,function(b,f){
            c||/\[\]$/.test(a)?e(a,f):d(a+"["+(typeof f==="object"||q.isArray(f)?b:"")+"]",f)
            });else!c&&b!=null&&typeof b==="object"?q.each(b,function(b,c){
            d(a+"["+b+"]",c)
            }):e(a,b)
            }
            var f=[];
    if(c===b)c=q.ajaxSettings.traditional;
    if(q.isArray(a)||a.jquery)q.each(a,function(){
        e(this.name,this.value)
        });else for(var g in a)d(g,a[g]);return f.join("&").replace(bN,"+")
    }
});
var bP={},bQ=/toggle|show|hide/,bR=/^([+-]=)?([\d+-.]+)(.*)$/,bS,bT=[["height","marginTop","marginBottom","paddingTop","paddingBottom"],["width","marginLeft","marginRight","paddingLeft","paddingRight"],["opacity"]];
q.fn.extend({
    show:function(a,b){
        if(a||a===0)return this.animate(o("show",3),a,b);
        else{
            a=0;
            for(b=this.length;a<b;a++){
                var c=q.data(this[a],"olddisplay");
                this[a].style.display=c||"";
                if(q.css(this[a],"display")==="none"){
                    c=this[a].nodeName;
                    var d;
                    if(bP[c])d=bP[c];
                    else{
                        var e=q("<"+c+" />").appendTo("body");
                        d=e.css("display");
                        if(d==="none")d="block";
                        e.remove();
                        bP[c]=d
                        }
                        q.data(this[a],"olddisplay",d)
                    }
                }
            a=0;
        for(b=this.length;a<b;a++)this[a].style.display=q.data(this[a],"olddisplay")||"";
        return this
        }
    },
hide:function(a,b){
    if(a||a===0)return this.animate(o("hide",3),a,b);
    else{
        a=0;
        for(b=this.length;a<b;a++){
            var c=q.data(this[a],"olddisplay");
            !c&&c!=="none"&&q.data(this[a],"olddisplay",q.css(this[a],"display"))
            }
            a=0;
        for(b=this.length;a<b;a++)this[a].style.display="none";
        return this
        }
    },
_toggle:q.fn.toggle,
toggle:function(a,b){
    var c=typeof a==="boolean";
    if(q.isFunction(a)&&q.isFunction(b))this._toggle.apply(this,arguments);else a==null||c?this.each(function(){
        var b=c?a:q(this).is(":hidden");
        q(this)[b?"show":"hide"]()
        }):this.animate(o("toggle",3),a,b);
    return this
    },
fadeTo:function(a,b,c){
    return this.filter(":hidden").css("opacity",0).show().end().animate({
        opacity:b
    },a,c)
    },
animate:function(a,b,c,d){
    var e=q.speed(b,c,d);
    if(q.isEmptyObject(a))return this.each(e.complete);
    return this[e.queue===false?"each":"queue"](function(){
        var b=q.extend({},e),c,d=this.nodeType===1&&q(this).is(":hidden"),f=this;
        for(c in a){
            var g=c.replace(bv,bE);
            if(c!==g){
                a[g]=a[c];
                delete a[c];
                c=g
                }
                if(a[c]==="hide"&&d||a[c]==="show"&&!d)return b.complete.call(this);
            if((c==="height"||c==="width")&&this.style){
                b.display=q.css(this,"display");
                b.overflow=this.style.overflow
                }
                if(q.isArray(a[c])){
                (b.specialEasing=b.specialEasing||{})[c]=a[c][1];
                a[c]=a[c][0]
                }
            }
        if(b.overflow!=null)this.style.overflow="hidden";
        b.curAnim=q.extend({},a);
        q.each(a,function(c,e){
        var g=new q.fx(f,b,c);
        if(bQ.test(e))g[e==="toggle"?d?"show":"hide":e](a);
        else{
            var h=bR.exec(e),i=g.cur(true)||0;
            if(h){
                e=parseFloat(h[2]);
                var j=h[3]||"px";
                if(j!=="px"){
                    f.style[c]=(e||1)+j;
                    i=(e||1)/g.cur(true)*i;
                    f.style[c]=i+j
                    }
                    if(h[1])e=(h[1]==="-="?-1:1)*e+i;
                g.custom(i,e,j)
                }else g.custom(i,e,"")
                }
            });
    return true
    })
},
stop:function(a,b){
    var c=q.timers;
    a&&this.queue([]);
    this.each(function(){
        for(var a=c.length-1;a>=0;a--)if(c[a].elem===this){
            b&&c[a](true);
            c.splice(a,1)
            }
        });
b||this.dequeue();
return this
}
});
q.each({
    slideDown:o("show",1),
    slideUp:o("hide",1),
    slideToggle:o("toggle",1),
    fadeIn:{
        opacity:"show"
    },
    fadeOut:{
        opacity:"hide"
    }
},function(a,b){
    q.fn[a]=function(a,c){
        return this.animate(b,a,c)
        }
    });
q.extend({
    speed:function(a,b,c){
        var d=a&&typeof a==="object"?a:{
            complete:c||!c&&b||q.isFunction(a)&&a,
            duration:a,
            easing:c&&b||b&&!q.isFunction(b)&&b
            };
            
        d.duration=q.fx.off?0:typeof d.duration==="number"?d.duration:q.fx.speeds[d.duration]||q.fx.speeds._default;
        d.old=d.complete;
        d.complete=function(){
            d.queue!==false&&q(this).dequeue();
            q.isFunction(d.old)&&d.old.call(this)
            };
            
        return d
        },
    easing:{
        linear:function(a,b,c,d){
            return c+d*a
            },
        swing:function(a,b,c,d){
            return(-Math.cos(a*Math.PI)/2+.5)*d+c
            }
        },
timers:[],
fx:function(a,b,c){
    this.options=b;
    this.elem=a;
    this.prop=c;
    if(!b.orig)b.orig={}
    }
});
q.fx.prototype={
    update:function(){
        this.options.step&&this.options.step.call(this.elem,this.now,this);
        (q.fx.step[this.prop]||q.fx.step._default)(this);
        if((this.prop==="height"||this.prop==="width")&&this.elem.style)this.elem.style.display="block"
            },
    cur:function(a){
        if(this.elem[this.prop]!=null&&(!this.elem.style||this.elem.style[this.prop]==null))return this.elem[this.prop];
        return(a=parseFloat(q.css(this.elem,this.prop,a)))&&a>-1e4?a:parseFloat(q.curCSS(this.elem,this.prop))||0
        },
    custom:function(a,b,c){
        function d(a){
            return e.step(a)
            }
            this.startTime=f();
        this.start=a;
        this.end=b;
        this.unit=c||this.unit||"px";
        this.now=this.start;
        this.pos=this.state=0;
        var e=this;
        d.elem=this.elem;
        if(d()&&q.timers.push(d)&&!bS)bS=setInterval(q.fx.tick,13)
            },
    show:function(){
        this.options.orig[this.prop]=q.style(this.elem,this.prop);
        this.options.show=true;
        this.custom(this.prop==="width"||this.prop==="height"?1:0,this.cur());
        q(this.elem).show()
        },
    hide:function(){
        this.options.orig[this.prop]=q.style(this.elem,this.prop);
        this.options.hide=true;
        this.custom(this.cur(),0)
        },
    step:function(a){
        var b=f(),c=true;
        if(a||b>=this.options.duration+this.startTime){
            this.now=this.end;
            this.pos=this.state=1;
            this.update();
            this.options.curAnim[this.prop]=true;
            for(var d in this.options.curAnim)if(this.options.curAnim[d]!==true)c=false;if(c){
                if(this.options.display!=null){
                    this.elem.style.overflow=this.options.overflow;
                    a=q.data(this.elem,"olddisplay");
                    this.elem.style.display=a?a:this.options.display;
                    if(q.css(this.elem,"display")==="none")this.elem.style.display="block"
                        }
                        this.options.hide&&q(this.elem).hide();
                if(this.options.hide||this.options.show)for(var e in this.options.curAnim)q.style(this.elem,e,this.options.orig[e]);this.options.complete.call(this.elem)
                }
                return false
            }else{
            e=b-this.startTime;
            this.state=e/this.options.duration;
            a=this.options.easing||(q.easing.swing?"swing":"linear");
            this.pos=q.easing[this.options.specialEasing&&this.options.specialEasing[this.prop]||a](this.state,e,0,1,this.options.duration);
            this.now=this.start+(this.end-this.start)*this.pos;
            this.update()
            }
            return true
        }
    };

q.extend(q.fx,{
    tick:function(){
        for(var a=q.timers,b=0;b<a.length;b++)a[b]()||a.splice(b--,1);
        a.length||q.fx.stop()
        },
    stop:function(){
        clearInterval(bS);
        bS=null
        },
    speeds:{
        slow:600,
        fast:200,
        _default:400
    },
    step:{
        opacity:function(a){
            q.style(a.elem,"opacity",a.now)
            },
        _default:function(a){
            if(a.elem.style&&a.elem.style[a.prop]!=null)a.elem.style[a.prop]=(a.prop==="width"||a.prop==="height"?Math.max(0,a.now):a.now)+a.unit;else a.elem[a.prop]=a.now
                }
            }
});
if(q.expr&&q.expr.filters)q.expr.filters.animated=function(a){
    return q.grep(q.timers,function(b){
        return a===b.elem
        }).length
    };
    
q.fn.offset="getBoundingClientRect"in t.documentElement?function(a){
    var b=this[0];
    if(a)return this.each(function(b){
        q.offset.setOffset(this,a,b)
        });
    if(!b||!b.ownerDocument)return null;
    if(b===b.ownerDocument.body)return q.offset.bodyOffset(b);
    var c=b.getBoundingClientRect(),d=b.ownerDocument;
    b=d.body;
    d=d.documentElement;
    return{
        top:0,
        left:c.left+(self.pageXOffset||q.support.boxModel&&d.scrollLeft||b.scrollLeft)-(d.clientLeft||b.clientLeft||0)
        }
    }:function(a){
    var b=this[0];
    if(a)return this.each(function(b){
        q.offset.setOffset(this,a,b)
        });
    if(!b||!b.ownerDocument)return null;
    if(b===b.ownerDocument.body)return q.offset.bodyOffset(b);
    q.offset.initialize();
    var c=b.offsetParent,d=b,e=b.ownerDocument,f,g=e.documentElement,h=e.body;
    d=(e=e.defaultView)?e.getComputedStyle(b,null):b.currentStyle;
    for(var i=b.offsetTop,j=b.offsetLeft;(b=b.parentNode)&&b!==h&&b!==g;){
        if(q.offset.supportsFixedPosition&&d.position==="fixed")break;
        f=e?e.getComputedStyle(b,null):b.currentStyle;
        i-=b.scrollTop;
        j-=b.scrollLeft;
        if(b===c){
            i+=b.offsetTop;
            j+=b.offsetLeft;
            if(q.offset.doesNotAddBorder&&!(q.offset.doesAddBorderForTableAndCells&&/^t(able|d|h)$/i.test(b.nodeName))){
                i+=parseFloat(f.borderTopWidth)||0;
                j+=parseFloat(f.borderLeftWidth)||0
                }
                d=c;
            c=b.offsetParent
            }
            if(q.offset.subtractsBorderForOverflowNotVisible&&f.overflow!=="visible"){
            i+=parseFloat(f.borderTopWidth)||0;
            j+=parseFloat(f.borderLeftWidth)||0
            }
            d=f
        }
        if(d.position==="relative"||d.position==="static"){
        i+=h.offsetTop;
        j+=h.offsetLeft
        }
        if(q.offset.supportsFixedPosition&&d.position==="fixed"){
        i+=Math.max(g.scrollTop,h.scrollTop);
        j+=Math.max(g.scrollLeft,h.scrollLeft)
        }
        return{
        top:i,
        left:j
    }
};

q.offset={
    initialize:function(){
        var a=t.body,b=t.createElement("div"),c,d,e,f=parseFloat(q.curCSS(a,"marginTop",true))||0;
        q.extend(b.style,{
            position:"absolute",
            top:0,
            left:0,
            margin:0,
            border:0,
            width:"1px",
            height:"1px",
            visibility:"hidden"
        });
        b.innerHTML="<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";
        a.insertBefore(b,a.firstChild);
        c=b.firstChild;
        d=c.firstChild;
        e=c.nextSibling.firstChild.firstChild;
        this.doesNotAddBorder=d.offsetTop!==5;
        this.doesAddBorderForTableAndCells=e.offsetTop===5;
        d.style.position="fixed";
        d.style.top="0px";
        this.supportsFixedPosition=d.offsetTop===20||d.offsetTop===15;
        d.style.position=d.style.top="";
        c.style.overflow="hidden";
        c.style.position="relative";
        this.subtractsBorderForOverflowNotVisible=d.offsetTop===-5;
        this.doesNotIncludeMarginInBodyOffset=a.offsetTop!==f;
        a.removeChild(b);
        q.offset.initialize=q.noop
        },
    bodyOffset:function(a){
        var b=a.offsetTop,c=a.offsetLeft;
        q.offset.initialize();
        if(q.offset.doesNotIncludeMarginInBodyOffset){
            b+=parseFloat(q.curCSS(a,"marginTop",true))||0;
            c+=parseFloat(q.curCSS(a,"marginLeft",true))||0
            }
            return{
            top:b,
            left:c
        }
    },
setOffset:function(a,b,c){
    if(/static/.test(q.curCSS(a,"position")))a.style.position="relative";
    var d=q(a),e=d.offset(),f=parseInt(q.curCSS(a,"top",true),10)||0,g=parseInt(q.curCSS(a,"left",true),10)||0;
    if(q.isFunction(b))b=b.call(a,c,e);
    c={
        top:b.top-e.top+f,
        left:b.left-e.left+g
        };
        
    "using"in b?b.using.call(a,c):d.css(c)
    }
};

q.fn.extend({
    position:function(){
        if(!this[0])return null;
        var a=this[0],b=this.offsetParent(),c=this.offset(),d=/^body|html$/i.test(b[0].nodeName)?{
            top:0,
            left:0
        }:b.offset();
        c.top-=parseFloat(q.curCSS(a,"marginTop",true))||0;
        c.left-=parseFloat(q.curCSS(a,"marginLeft",true))||0;
        d.top+=parseFloat(q.curCSS(b[0],"borderTopWidth",true))||0;
        d.left+=parseFloat(q.curCSS(b[0],"borderLeftWidth",true))||0;
        return{
            top:c.top-d.top,
            left:c.left-d.left
            }
        },
offsetParent:function(){
    return this.map(function(){
        for(var a=this.offsetParent||t.body;a&&!/^body|html$/i.test(a.nodeName)&&q.css(a,"position")==="static";)a=a.offsetParent;
        return a
        })
    }
});
q.each(["Left","Top"],function(a,c){
    var d="scroll"+c;
    q.fn[d]=function(c){
        var e=this[0],f;
        if(!e)return null;
        if(c!==b)return this.each(function(){
            if(f=p(this))f.scrollTo(!a?c:q(f).scrollLeft(),a?c:q(f).scrollTop());else this[d]=c
                });else return(f=p(e))?"pageXOffset"in f?f[a?"pageYOffset":"pageXOffset"]:q.support.boxModel&&f.document.documentElement[d]||f.document.body[d]:e[d]
            }
        });
q.each(["Height","Width"],function(a,c){
    var d=c.toLowerCase();
    q.fn["inner"+c]=function(){
        return this[0]?q.css(this[0],d,false,"padding"):null
        };
        
    q.fn["outer"+c]=function(a){
        return this[0]?q.css(this[0],d,false,a?"margin":"border"):null
        };
        
    q.fn[d]=function(a){
        var e=this[0];
        if(!e)return a==null?null:this;
        if(q.isFunction(a))return this.each(function(b){
            var c=q(this);
            c[d](a.call(this,b,c[d]()))
            });
        return"scrollTo"in e&&e.document?e.document.compatMode==="CSS1Compat"&&e.document.documentElement["client"+c]||e.document.body["client"+c]:e.nodeType===9?Math.max(e.documentElement["client"+c],e.body["scroll"+c],e.documentElement["scroll"+c],e.body["offset"+c],e.documentElement["offset"+c]):a===b?q.css(e,d):this.css(d,typeof a==="string"?a:a+"px")
        }
    });
a.jQuery=a.$=q
})(window);
var fadeSlideShow_descpanel={
    controls:[["x.png",7,7],["restore.png",10,11],["loading.gif",54,55]],
    fontStyle:"normal 11px Verdana",
    slidespeed:200
};

jQuery.noConflict(true);
fadeSlideShow.prototype={
    navigate:function(a){
        var b=this.setting;
        clearTimeout(b.playtimer);
        if(b.displaymode.type=="auto"){
            b.displaymode.type="manual";
            b.displaymode.wraparound=true
            }
            if(!isNaN(parseInt(a))){
            this.showslide(parseInt(a))
            }else if(/(prev)|(next)/i.test(a)){
            this.showslide(a.toLowerCase())
            }
        },
showslide:function(a){
    var b=this;
    var c=b.setting;
    if(c.displaymode.type=="auto"&&c.ismouseover&&c.currentstep<=c.totalsteps){
        c.playtimer=setTimeout(function(){
            b.showslide("next")
            },c.displaymode.pause);
        return
    }
    var d=c.imagearray.length;
    var e=a=="next"?c.curimage<d-1?c.curimage+1:0:a=="prev"?c.curimage>0?c.curimage-1:d-1:Math.min(a,d-1);
    var f=c.$gallerylayers.eq(c.bglayer).find("img").hide().eq(e).show();
    var g=[f.width(),f.height()];
    f.css({
        marginLeft:g[0]>0&&g[0]<c.dimensions[0]?c.dimensions[0]/2-g[0]/2:0
        });
    f.css({
        marginTop:g[1]>0&&g[1]<c.dimensions[1]?0:0
        });
    if(c.descreveal=="peekaboo"&&c.longestdesc!=""){
        clearTimeout(c.hidedesctimer);
        b.showhidedescpanel("hide",0)
        }
        c.$gallerylayers.eq(c.bglayer).css({
        zIndex:1e3,
        opacity:0
    }).stop().css({
        opacity:0
    }).animate({
        opacity:1
    },c.fadeduration,function(){
        clearTimeout(c.playtimer);
        try{
            c.onslide.call(b,c.$gallerylayers.eq(c.fglayer).get(0),c.curimage)
            }catch(a){
            alert('Fade In Slideshow error: An error has occured somwhere in your code attached to the "onslide" event: '+a)
            }
            if(c.descreveal=="peekaboo"&&c.longestdesc!=""){
            b.showhidedescpanel("show");
            c.hidedesctimer=setTimeout(function(){
                b.showhidedescpanel("hide")
                },c.displaymode.pause-fadeSlideShow_descpanel.slidespeed)
            }
            c.currentstep+=1;
        if(c.displaymode.type=="auto"){
            if(c.currentstep<=c.totalsteps||c.displaymode.cycles==0)c.playtimer=setTimeout(function(){
                b.showslide("next")
                },c.displaymode.pause)
            }
            });
c.$gallerylayers.eq(c.fglayer).css({
    zIndex:999
});
c.fglayer=c.bglayer;
c.bglayer=c.bglayer==0?1:0;
c.curimage=e;
if(c.$descpanel){
    c.$descpanel.css({
        visibility:c.imagearray[e][3]?"visible":"hidden"
        });
    if(c.imagearray[e][3])c.$descinner.empty().html(c.closebutton+c.imagearray[e][3])
        }
        if(c.displaymode.type=="manual"&&!c.displaymode.wraparound){
    this.paginatecontrol()
    }
    if(c.$status)c.$status.html(c.curimage+1+"/"+d)
    },
showhidedescpanel:function(a,b){
    var c=this.setting;
    var d=a=="show"?c.dimensions[1]-c.panelheight:this.setting.dimensions[1];
    c.$descpanel.stop().animate({
        top:d
    },typeof b!="undefined"?b:fadeSlideShow_descpanel.slidespeed,function(){
        if(c.descreveal=="always"&&a=="hide")c.$restorebutton.css({
            visibility:"visible"
        })
        })
    },
paginateinit:function(a){
    var b=this;
    var c=this.setting;
    if(c.togglerid){
        c.$togglerdiv=a("#"+c.togglerid);
        c.$prev=c.$togglerdiv.find(".prev").data("action","prev");
        c.$next=c.$togglerdiv.find(".next").data("action","next");
        c.$prev.add(c.$next).click(function(c){
            var d=a(this);
            b.navigate(d.data("action"));
            c.preventDefault()
            });
        c.$status=c.$togglerdiv.find(".status")
        }
    },
paginatecontrol:function(){
    var a=this.setting;
    a.$prev.css({
        opacity:a.curimage==0?.4:1
        }).data("action",a.curimage==0?"none":"prev");
    a.$next.css({
        opacity:a.curimage==a.imagearray.length-1?.4:1
        }).data("action",a.curimage==a.imagearray.length-1?"none":"next");
    if(document.documentMode==8){
        a.$prev.find("img:eq(0)").css({
            opacity:a.curimage==0?.4:1
            });
        a.$next.find("img:eq(0)").css({
            opacity:a.curimage==a.imagearray.length-1?.4:1
            })
        }
    }
};

fadeSlideShow.routines={
    getSlideHTML:function(a){
        var b=a[1]?'<a href="'+a[1]+'" target="'+a[2]+'">\n':"";
        b+='<img src="'+a[0]+'" style="  " />\n';
        b+=a[1]?"</a>\n":"";
        return b
        },
    getFullHTML:function(a){
        var b="";
        for(var c=0;c<a.length;c++)b+=this.getSlideHTML(a[c]);
        return b
        },
    adddescpanel:function(a,b){
        b.$descpanel=a('<div class="fadeslidedescdiv"></div>').css({
            position:"absolute",
            visibility:"hidden",
            width:"100%",
            left:0,
            top:0,
            font:fadeSlideShow_descpanel.fontStyle,
            zIndex:"1001"
        }).appendTo(b.$wrapperdiv);
        a('<div class="descpanelbg"></div><div class="descpanelfg"></div>').css({
            position:"absolute",
            left:0,
            top:0,
            width:b.$descpanel.width()-8,
            padding:"4px"
        }).eq(0).css({
            background:"transparent",
            opacity:.7
        }).end().eq(1).css({
            color:"white"
        }).html(b.closebutton+b.longestdesc).end().appendTo(b.$descpanel);
        b.$descinner=b.$descpanel.find("div.descpanelfg");
        b.panelheight=b.$descinner.outerHeight();
        b.$descpanel.css({
            height:b.panelheight
            }).find("div").css({
            height:"100%"
        });
        if(b.descreveal=="always"){
            b.$restorebutton=a('<img class="restore" title="Restore Description" src="'+fadeSlideShow_descpanel.controls[1][0]+'" style="position:absolute;visibility:hidden;right:0;bottom:0;z-index:1002;width:'+fadeSlideShow_descpanel.controls[1][1]+"px;height:"+fadeSlideShow_descpanel.controls[1][2]+'px;cursor:pointer;cursor:hand" />').appendTo(b.$wrapperdiv)
            }
        },
getCookie:function(a){
    var b=new RegExp(a+"=[^;]+","i");
    if(document.cookie.match(b))return document.cookie.match(b)[0].split("=")[1];
    return null
    },
setCookie:function(a,b){
    document.cookie=a+"="+b+";path=/"
    }
}