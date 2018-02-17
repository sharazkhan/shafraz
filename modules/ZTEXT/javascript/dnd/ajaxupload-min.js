/**
 * Real Ajax Uploader 3.4.1
 * http://www.albanx.com/ajaxuploader
 *
 * Copyright 2010-2015, Alban Xhaferllari
 * XScripts
 * Lastupdate : 13-03-2015
 * Libraries and functions Integrated :
 * jQuery 1.7+
 * jQuery Deferred
 * Web Wokers
 * HTML5 FileReader
 * XMLHTTREQUEST
 * John Resig Class definition
 * Google CryptoJS for MD5
 */

(function (c, k) {
    function g(c) {
        return r[c] || c
    }
    function h() {
        var c = Array.prototype.slice.call(arguments, 0), a = new Date, a = (10 > a.getHours() ? "0" : "") + a.getHours() + ":" + (10 > a.getMinutes() ? "0" : "") + a.getMinutes() + ":" + (10 > a.getSeconds() ? "0" : "") + a.getSeconds();
        c.unshift(a);
        console.log.apply(console, c)
    }
    var l = !1, w = /xyz/.test(function () {
        xyz
    }) ? /\b_super\b/ : /.*/, t = function () {
    };
    t.extend = function a(b) {
        function e() {
            !l && this.init && this.init.apply(this, arguments)
        }
        var d = this.prototype;
        l = !0;
        var c = new this;
        l = !1;
        for (var f in b)
            c[f] =
                    "function" == typeof b[f] && "function" == typeof d[f] && w.test(b[f]) ? function (a, b) {
                return function () {
                    var e = this._super;
                    this._super = d[a];
                    var c = b.apply(this, arguments);
                    this._super = e;
                    return c
                }
            }(f, b[f]) : b[f];
        e.prototype = c;
        e.prototype.constructor = e;
        e.extend = a;
        return e
    };
    var u = {en_EN: {"Add files": "Add files", "Start upload": "Start upload", "Remove all": "Remove all", Close: "Close", "Select Files": "Select Files", Preview: "Preview", "Remove file": "Remove file", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload aborted",
            "Upload all files": "Upload all files", "Select Files or Drag&Drop Files": "Select Files or Drag&Drop Files", "File uploaded 100%": "File uploaded 100%", "Max files number reached": "Max files number reached", "Extension not allowed": "Extension not allowed", "File size now allowed": "File size now allowed"}, it_IT: {"Add files": "Aggiungi file", "Start upload": "Inizia caricamento", "Remove all": "Rimuvi tutti", Close: "Chiudi", "Select Files": "Seleziona", Preview: "Anteprima", "Remove file": "Rimuovi file", Bytes: "Bytes",
            KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Interroto", "Upload all files": "Carica tutto", "Select Files or Drag&Drop Files": "Seleziona o Trascina qui i file", "File uploaded 100%": "File caricato 100%", "Max files number reached": "Max files number reached", "Extension not allowed": "Estensione file non permessa", "File size now allowed": "Dimensione file non permessa"}, sq_AL: {"Add files": "Shto file", "Start upload": "Fillo karikimin", "Remove all": "Hiqi te gjith\u00eb", Close: "Mbyll", "Select Files": "Zgjith filet",
            Preview: "Miniatur\u00eb", "Remove file": "Hiqe file-in", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Karikimi u nd\u00ebrpre", "Upload all files": "Kariko t\u00eb gjith\u00eb", "Select Files or Drag&Drop Files": "Zgjith ose Zvarrit dokumentat k\u00ebtu", "File uploaded 100%": "File u karikua 100%", "Max files number reached": "Maksimumi i fileve u arrit", "Extension not allowed": "Prapashtesa nuk lejohet", "File size now allowed": "Madh\u00ebsia e filit nuk lejohet"}, nl_NL: {"Add files": "Bestanden toevoegen",
            "Start upload": "Start uploaden", "Remove all": "Verwijder alles", Close: "Sluiten", "Select Files": "Selecteer bestanden", Preview: "Voorbeeld", "Remove file": "Verwijder bestand", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload afgebroken", "Upload all files": "Upload alle bestanden", "Select Files or Drag&Drop Files": "Selecteer bestanden of  Drag&Drop bestanden", "File uploaded 100%": "Bestand ge\u00fcpload 100%"}, de_DE: {"Add files": "Dateien hinzuf\u00fcgen", "Start upload": "Hochladen", "Remove all": "Alle entfernen",
            Close: "Schliessen", "Select Files": "Dateien w\u00e4hlen", Preview: "Vorschau", "Remove file": "Datei entfernen", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload abgebrochen", "Upload all files": "Alle hochgeladen", "Select Files or Drag&Drop Files": "W\u00e4hlen Sie Dateien oder f\u00fcgen Sie sie mit Drag & Drop hinzu.", "File uploaded 100%": "Upload 100%"}, fr_FR: {"Add files": "Ajouter", "Start upload": "Envoyer", "Remove all": "Tout supprimer", Close: "Fermer", "Select Files": "Parcourir", Preview: "Visualiser",
            "Remove file": "Supprimer fichier", Bytes: "Bytes", KB: "Ko", MB: "Mo", GB: "Go", "Upload aborted": "Envoi annul\u00e9", "Upload all files": "Tout envoyer", "Select Files or Drag&Drop Files": "Parcourir ou Glisser/D\u00e9poser", "File uploaded 100%": "Fichier envoy\u00e9 100%"}}, r = {}, x = t.extend({init: function (a, b, e, d, c) {
            this.file = a;
            this.status = 0;
            this.name = b;
            this.size = e;
            this.info = this.xhr = null;
            this.ext = d;
            this.pos = c.files.length;
            this.AU = c;
            this.settings = c.settings;
            this.exifData = null;
            this.md5 = "";
            this.ready = this.disabled =
                    !1;
            this.temp_bytes = this.loading_bytes = this.currentByte = 0;
            this.afterInit()
        }, afterInit: function () {
            this.renderHtml();
            this.bindEvents();
            this.settings.hideUploadButton && this.uploadButton.hide();
            if (this.AU.hasHtml4) {
                var a = this.AU.getParams(this.name, 0, !1), b = c('<form action="' + this.settings.url + '" method="post" target="ax-main-frame" encType="multipart/form-data" />').hide().appendTo(this.li);
                b.append(this.file);
                b.append('<input type="hidden" value="' + this.name + '" name="ax-file-name" />');
                for (var e = 0; e <
                        a.length; e++) {
                    var d = a[e].split("=");
                    b.append('<input type="hidden" value="' + d[1] + '" name="' + d[0] + '" />')
                }
                this.xhr = b
            }
            this.postSelectFile().always(function () {
                this.doPreview()
            })
        }, renderHtml: function () {
            var a = this.settings, b = this.formatSize(this.size);
            this.li = c("<li />").appendTo(this.AU.fileList).attr("title", name);
            a.bootstrap && (this.li = c("<a />").appendTo(this.li));
            this.prevContainer = c('<a class="ax-prev-container" />').appendTo(this.li);
            this.prevImage = c('<img class="ax-preview" src="" alt="' + g("Preview") +
                    '" />').appendTo(this.prevContainer);
            this.details = c('<div class="ax-details" />').appendTo(this.li);
            this.nameContainer = c('<div class="ax-file-name">' + this.name + "</div>").appendTo(this.details);
            this.sizeContainer = c('<div class="ax-file-size">' + b + "</div>").appendTo(this.details);
            this.progressCont = c('<div class="ax-progress-data" />').appendTo(this.li);
            this.progressInfo = c('<div class="ax-progress" />').appendTo(this.progressCont);
            this.progressBar = c('<div class="ax-progress-bar" />').appendTo(this.progressInfo);
            this.progressPer = c('<div class="ax-progress-info">0%</div>').appendTo(this.progressInfo);
            this.progressStat = c('<div class="ax-progress-stat" />').appendTo(this.progressCont);
            this.buttons = c('<div class="ax-toolbar" />').appendTo(this.li);
            this.uploadButton = c('<a title="' + g("Start upload") + '" class="ax-upload ax-button" />').appendTo(this.buttons).append('<span class="ax-upload-icon ax-icon"></span>');
            this.removeButton = c('<a title="Remove file" class="ax-remove ax-button" />').appendTo(this.buttons).append('<span class="ax-clear-icon ax-icon"></span>');
            a.bootstrap && (this.li.addClass("media thumbnail label-info"), this.prevContainer.addClass("pull-left"), this.prevImage.addClass("img-rounded media-object"), this.details.addClass("label label-info").css({"border-bottom-left-radius": 0}), this.progressInfo.addClass("progress progress-striped active").css({"margin-bottom": 0}), this.progressBar.addClass("bar"), this.buttons.css({"border-top-left-radius": 0, "border-top-right-radius": 0}), this.uploadButton.addClass("btn btn-success btn-small").find("span").addClass("icon-play"),
                    this.removeButton.addClass("btn btn-danger btn-small").find("span").addClass("icon-minus-sign"))
        }, disableUpload: function (a) {
            this.disabled = !0;
            this.buttons.css("opacity", .5);
            this.progressPer.html(a)
        }, enableUpload: function (a) {
            this.disabled = !1;
            this.buttons.css("opacity", 1);
            this.progressPer.html(a)
        }, workProgress: function (a, b) {
            this.progressBar.css("width", b + "%");
            this.progressPer.html(a + " " + b + "%")
        }, bindEvents: function () {
            this.uploadButton.on("click", this, function (a) {
                a.data.AU.settings.enable && !a.data.disabled &&
                        (2 != a.data.status ? a.data.startUpload() : a.data.stopUpload())
            });
            this.removeButton.bind("click", this, function (a) {
                a.data.AU.settings.enable && !a.data.disabled && a.data.AU.removeFile(a.data.pos)
            });
            this.settings.editFilename && this.nameContainer.bind("dblclick", this, function (a) {
                if (a.data.AU.settings.enable && !a.data.disabled) {
                    a.stopPropagation();
                    var b = a.data.ext;
                    a = a.data.name.replace("." + b, "");
                    c(this).html('<input type="text" value="' + a + '" />.' + b)
                }
            }).bind("blur focusout", this, function (a) {
                a.stopPropagation();
                var b = c(this).children("input").val();
                "undefined" != typeof b && (b = b.replace(/[|&;$%@"<>()+,]/g, "") + "." + a.data.ext, c(this).html(b), a.data.name = b, a.data.AU.hasAjaxUpload || a.data.xhr.children('input[name="ax-file-name"]').val(b))
            });
            this.createUploadDeferred()
        }, createUploadDeferred: function () {
            this.defUpload = new c.Deferred;
            this.defUpload.done(function (a, b, e, d) {
                this.name = a;
                this.status = parseInt(e);
                this.info = d;
                this.nameContainer.html(a);
                this.li.attr("title", a);
                this.onProgress(100, 0);
                this.progressPer.html(g("File uploaded"));
                this.AU.hasAjaxUpload || this.AU.hasFlash || (b = this.formatSize(this.size), this.sizeContainer.html(b));
                this.AU.fileUploaded(this, a)
            }).fail(function (a, b) {
                this.progressPer.html(b);
                this.info = a;
                "stoped" == a && (this.status = 4);
                "error" == a && (this.status = -1, this.settings.error.call(this, a, this.name), this.settings.removeOnError && this.AU.removeFile(this.pos))
            }).progress(function (a, b, e) {
                "progress" == a ? (this.progressBar.css("width", b + "%"), this.progressPer.html(b + "%"), this.loading_bytes = e, this.AU.onOverwallProgress(e)) :
                        "start" == a && "function" == typeof this.settings.onStart && this.settings.onStart.call(this, this.name)
            }).always(function () {
                this.AU.slots++;
                this.currentByte = 0;
                this.uploadButton.removeClass("ax-abort");
                this.progressBar.css("width", "0%");
                this.stat_interval !== k && (clearInterval(this.stat_interval), this.stat_interval = k);
                this.createUploadDeferred()
            })
        }, readImageFile: function () {
            h("readImageFile: start");
            if (this.settings.previews && this.AU.hasAjaxUpload && this.file.type.match(/image.*/) && ("jpeg" == this.ext || "jpg" ==
                    this.ext || "gif" == this.ext || "png" == this.ext) && "undefined" !== typeof window.FileReader) {
                var a = this, b = new FileReader;
                b.onprogress = function (b) {
                    b = Math.round(100 * b.loaded / b.total);
                    a.readFileDef.notifyWith(a, [b])
                };
                b.onerror = function (b) {
                    a.readFileDef.notifyWith(a, [0]);
                    a.readFileDef.rejectWith(a, [g("FileReader API error")])
                };
                b.onload = function (b) {
                    a.readFileDef.notifyWith(a, [90]);
                    h("readImageFile: file readed, creating image");
                    var d = new Image;
                    d.onload = function () {
                        h("readImageFile: image created");
                        a.readFileDef.notifyWith(a,
                                [100]);
                        a.readFileDef.resolveWith(a, [d])
                    };
                    d.src = b.target.result
                };
                b.readAsDataURL(a.file)
            } else
                h("readImageFile: it is not a web image"), this.readFileDef.rejectWith(this, [g("Image preview not supported")])
        }, doPreview: function () {
            h("doPreview: start, readFileDef");
            this.readFileDef = new c.Deferred;
            this.readFileDef.done(function (a) {
                h("doPreview: file readed");
                var b = this, e = c(window).width(), d = c(window).height() - 100, d = Math.min(e / a.width, d / a.height), e = 1 > d ? a.width * d : a.width, d = 1 > d ? a.height * d : a.height, q = c(window).scrollTop() -
                        20 + (c(window).height() - d) / 2, f = (c(window).width() - e) / 2;
                a = b.canvasScale(a, e, d);
                a = b.fixOrientation(a);
                b.prevImage.attr("src", a.toDataURL("image/jpeg", .75));
                b.prevImage.data("lightbox", {top: q, left: f, w: e, height: d});
                b.prevContainer.css("background", "none");
                b.prevImage.css("cursor", "pointer").click(function () {
                    var a = c(this).data("lightbox"), d = c("#ax-box").css({top: a.top, height: a.h, width: a.w, left: a.left});
                    d.children("img").attr({width: a.w, height: a.h, src: this.src});
                    c("#ax-box-fn").find("span").html(b.name +
                            " (" + b.formatSize(b.size) + ")");
                    d.fadeIn(500);
                    c("#ax-box-shadow").css("height", c(document).height()).show();
                    c("#ax-box-shadow").css("z-index", 1E4);
                    c("#ax-box").css("z-index", 10001)
                });
                h("doPreview: done");
                b.onPreview()
            }).fail(function () {
                h("doPreview: cannot preview this file: ", this.ext);
                this.prevContainer.addClass("ax-filetype-" + this.ext).children("img:first").remove()
            }).progress(function (a) {
                this.workProgress(g("Rendering..."), a)
            }).always(function () {
                this.workProgress(g(Zikula.__('Ready', 'module_ztext_js')), 0);
                this.enableUpload(g(Zikula.__('Ready', 'module_ztext_js')));
                this.status = 4
            });
            this.disableUpload(g("Rendering preview..."));
            this.readImageFile()
        }, getHalfScaleCanvas: function (a) {
            var b = document.createElement("canvas");
            b.width = a.width / 2;
            b.height = a.height / 2;
            b.getContext("2d").drawImage(a, 0, 0, b.width, b.height);
            return b
        }, canvasScale: function (a, b, e) {
            var d = document.createElement("canvas");
            d.width = a.width;
            d.height = a.height;
            for (d.getContext("2d").drawImage(a, 0, 0, d.width, d.height); d.width >= 2 * b && d.height >= 2 * e; )
                d = this.getHalfScaleCanvas(d);
            return d
        }, fixOrientation: function (a) {
            if (this.exifData &&
                    this.exifData.Orientation) {
                var b = a.width, e = a.height, d = document.createElement("canvas"), c = this.exifData.Orientation;
                h("fixOrientation:", c);
                switch (c) {
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        d.width = e;
                        d.height = b;
                        break;
                    default:
                        d.width = b, d.height = e
                }
                var f = d.getContext("2d");
                switch (c) {
                    case 2:
                        f.translate(b, 0);
                        f.scale(-1, 1);
                        break;
                    case 3:
                        f.translate(b, e);
                        f.rotate(Math.PI);
                        break;
                    case 4:
                        f.translate(0, e);
                        f.scale(1, -1);
                        break;
                    case 5:
                        f.rotate(.5 * Math.PI);
                        f.scale(1, -1);
                        break;
                    case 6:
                        f.rotate(.5 * Math.PI);
                        f.translate(0, -e);
                        break;
                    case 7:
                        f.rotate(.5 * Math.PI);
                        f.translate(b, -e);
                        f.scale(-1, 1);
                        break;
                    case 8:
                        f.rotate(-.5 * Math.PI), f.translate(-b, 0)
                }
                f.drawImage(a, 0, 0);
                return d
            }
            h("fixOrientation: no exif");
            return a
        }, onPreview: function () {
            this.settings.onPreview.call(this)
        }, askUser: function (a, b) {
            this.askDiv && this.askDiv.remove();
            this.askDiv = c('<div class="ax-ask-div"></div>').appendTo(this.li);
            var e = c('<div class="ax-ask-inner"><div class="ax-ask-quest">' + b + "</div> </div>").appendTo(this.askDiv), d = c('<a title="' + g("Yes") + '" class="ax-button ax-ask-yes"><span class="ax-upload-icon ax-icon"></span> <span>' +
                    g("Yes") + "</span></a>").appendTo(e), e = c('<a title="' + g("No") + '" class="ax-button ax-ask-no"><span class="ax-clear-icon ax-icon"></span> <span>' + g("No") + "</span></a>").appendTo(e);
            this.settings.bootstrap && (this.askDiv.addClass("alert"), d.addClass("btn btn-success btn-small").find(".ax-icon").addClass("icon-ok"), e.addClass("btn btn-danger btn-small").find(".ax-icon").addClass("icon-remove"));
            d.on("click", this, function (b) {
                a.resolve(b.data)
            });
            e.on("click", this, function (b) {
                a.reject(b.data)
            });
            var q = this;
            a.always(function () {
                q.askDiv.remove();
                q.askDiv = null
            })
        }, checkFileExists: function () {
            h("Checking file exists");
            var a = new c.Deferred, b = this;
            if (this.settings.checkFileExists) {
                var e = this.AU.getParams(this.name, this.size, !1);
                e.push("ax-check-file=1");
                c.post(this.settings.url, e.join("&")).done(function (d) {
                    "yes" == d ? b.askUser(a, g("File exits on server. Override?")) : a.resolve(b)
                })
            } else
                a.resolve(b);
            return a.promise()
        }, postSelectFile: function () {
            var a = new c.Deferred;
            a.resolveWith(this, []);
            return a.promise()
        },
        preProcessFile: function () {
            this.preDef = new c.Deferred;
            this.preDef.resolveWith(this, []);
            return this.preDef.promise()
        }, startUpload: function () {
            h("Start uploadx");
            if (this.disabled)
                return!1;
            h("Start upload");
            var a = this.settings.beforeUpload.call(this, this.name, this.file);
            if (a)
                this.status = 3, this.checkFileExists().done(function (a) {
                    a.defUpload.notifyWith(a, ["start", g("Upload started")]);
                    a.progressBar.css("width", "0%");
                    a.progressPer.html("0%");
                    a.uploadButton.addClass("ax-abort");
                    a.status = 2;
                    a.preProcessFile().always(function () {
                        h("Pre process resolved");
                        a.AU.hasAjaxUpload ? (h("Start ajax upload"), a.uploadAjax()) : a.AU.hasFlash ? (h("Start standard upload"), a.AU.uploading || (a.AU.uploading = !0, a.AU.flashObj.uploadFile(a.pos))) : a.uploadStandard();
                        a.settings.bandwidthUpdateInterval && (a.stat_interval = setInterval(function () {
                            a.progressStat.html((1E3 / a.settings.bandwidthUpdateInterval * (a.loading_bytes - a.temp_bytes) / 1024).toFixed(0) + " KB/s");
                            a.temp_bytes = a.loading_bytes
                        }, a.settings.bandwidthUpdateInterval))
                    })
                }).fail(function () {
                    this.defUpload.rejectWith(this, ["user_abort",
                        g("User stop")])
                });
            else
                this.onError("File validation failed");
            return a
        }, uploadAjax: function () {
            var a = this.settings, b = this.file, e = this.currentByte, d = this.name, c = this.size, f = a.chunkSize, g = f + e, h = 0 >= c - g, k = b;
            this.xhr = new XMLHttpRequest;
            0 == f ? (k = b, h = !0) : k = this.AU.fileSlice(b, e, g);
            null === k && (k = b, h = !0);
            "function" == typeof a.onBeforeChunkUpload && a.onBeforeChunkUpload.call(this, this.xhr, b, d, c, k);
            var m = this;
            this.xhr.upload.addEventListener("abort", function (a) {
                m.AU.slots++
            }, !1);
            this.xhr.upload.addEventListener("progress",
                    function (a) {
                        if (a.lengthComputable) {
                            var b = Math.round(100 * (a.loaded + e) / c);
                            m.onProgress(b, a.loaded + e)
                        }
                    }, !1);
            this.xhr.upload.addEventListener("error", function (a) {
                m.onError(this.responseText)
            }, !1);
            this.xhr.onreadystatechange = function () {
                if (4 == this.readyState && 200 == this.status) {
                    a.onAfterChunkUpload.call(m, m.xhr, b, d, c, k);
                    try {
                        var f = JSON.parse(this.responseText);
                        0 == e && (m.name = f.name, m.nameContainer.html(f.name));
                        if (-1 == parseInt(f.status))
                            throw f.info;
                        if (h)
                            m.onFinishUpload(f.name, f.size, f.status, f.info);
                        else
                            m.currentByte =
                                    g, m.uploadAjax()
                    } catch (l) {
                        m.onError(l)
                    }
                }
            };
            f = this.AU.getParams(d, c, !this.AU.useFormData);
            f.push("ax-start-byte=" + e);
            f.push("ax-last-chunk=" + h);
            f.push("ax-file-md5=" + this.md5);
            if (this.AU.useFormData) {
                var p = new FormData;
                p.append("ax_file_input", k);
                for (var l = 0; l < f.length; l++) {
                    var n = f[l].split("=");
                    p.append(n[0], n[1])
                }
                this.xhr.open("POST", a.url, a.async);
                this.xhr.send(p)
            } else
                p = -1 == a.url.indexOf("?") ? "?" : "&", this.xhr.open("POST", a.url + p + f.join("&"), a.async), this.xhr.setRequestHeader("Cache-Control", "no-cache"),
                        this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"), this.xhr.setRequestHeader("Content-Type", "application/octet-stream"), this.xhr.send(k)
        }, uploadStandard: function () {
            this.progressBar.css("width", "50%");
            this.progressPer.html("50%");
            c("#ax-main-frame").unbind("load").bind("load", this, function (a) {
                var b = null;
                this.contentDocument ? b = this.contentDocument : this.contentWindow && (b = this.contentWindow.document);
                try {
                    var e = c.parseJSON(b.body.innerHTML);
                    a.data.onProgress(100, 0);
                    a.data.onFinishUpload(e.name,
                            e.size, e.status, e.info)
                } catch (d) {
                    a.data.onError(b.body.innerHTML)
                }
            });
            this.xhr.submit()
        }, stopUpload: function () {
            if (this.AU.hasAjaxUpload)
                null !== this.xhr && (this.xhr.abort(), this.xhr = null);
            else if (this.AU.hasFlash)
                this.AU.flashObj.stopUpload(this.pos);
            else {
                var a = document.getElementById("ax-main-frame");
                try {
                    a.contentWindow.document.execCommand("Stop")
                } catch (b) {
                    a.contentWindow.stop()
                }
            }
            this.defUpload.rejectWith(this, ["stoped", g("Upload aborted")])
        }, onError: function (a) {
            this.defUpload.rejectWith(this, ["error",
                a])
        }, onFinishUpload: function (a, b, e, d) {
            this.defUpload.resolveWith(this, [a, b, e, d])
        }, onProgress: function (a, b) {
            this.defUpload.notifyWith(this, ["progress", a, b])
        }, formatSize: function (a) {
            var b = this.settings.precision;
            "undefined" == typeof b && (b = 2);
            for (var e = [g("Bytes"), g("KB"), g("MB"), g("GB")], d = 0; 1024 <= a && d < e.length - 1; )
                a /= 1024, d++;
            var c = Math.round(a), b = Math.pow(10, b);
            a = Math.round(a * b % b);
            return c + "." + a + " " + e[d]
        }}), v = function (a, b) {
        this.useFormData = this.hasAjaxUpload = this.hasFlash = !1;
        this.hasHtml4 = !0;
        this.settings =
                this.preCheckSettings(b);
        this._init(a);
        this.checkUploadSupport();
        this.container = a;
        this.files = [];
        this.slots = this.settings.maxConnections;
        this._onUploading = this._onNoFiles = this._onError = this._onEnd = this._onStart = null;
        this.uploadQueue = [];
        this.flashObj = null;
        this.globalStatus = 0;
        this.uploading = !1;
        this.total_bytes = 0;
        this.checkInterval = !1;
        this.renderHtml();
        "function" == typeof this.settings.onInit && this.settings.onInit.call(this);
        this.bindEvents()
    };
    v.prototype = {preCheckSettings: function (a) {
            a = c.extend(!0, {},
                    {allowExt: [], allowDelete: !1, autoStart: !1, async: !0, bandwidthUpdateInterval: 500, bootstrap: !1, checkFileExists: !1, chunkSize: 1048576, data: "", dropColor: "red", dropClass: "ax-drop", dropArea: "self", enable: !0, editFilename: !1, exifRead: !1, exifWorker: "js/exif-reader.js", flash: "uploader.swf", hideUploadButton: !0, language: "auto", maxFiles: 9999, maxConnections: 3, maxFileSize: "10M", md5Calculate: !1, md5WorkerPath: "js/file-md5.js", md5CalculateOn: "upload", overrideFile: !1, thumbHeight: 0, thumbWidth: 0, thumbPostfix: "_thumb", thumbPath: "",
                        thumbFormat: "", url: "upload.php", uploadDir: !1, removeOnSuccess: !1, removeOnError: !1, remotePath: "uploads/", resizeImage: {maxWidth: 0, maxHeight: 0, quality: .5, scaleMethod: k, format: k, removeExif: !1}, previews: !0, onStart: function (a) {
                        }, onPreview: function () {
                        }, onInit: function (a) {
                        }, onSelect: function (a) {
                        }, beforeUpload: function (a, e) {
                            return!0
                        }, beforeUploadAll: function (a) {
                            return!0
                        }, onProgress: function (a) {
                        }, success: function (a) {
                        }, finish: function (a, e) {
                        }, error: function (a, e) {
                        }, validateFile: function (a, e, d) {
                        }, onBeforeChunkUpload: function (a,
                                e, d, c, f) {
                        }, onAfterChunkUpload: function (a, e, d, c, f) {
                        }, onMd5Calculate: function (a) {
                        }, onExifRead: function (a) {
                        }, fileInfo: function (a) {
                            var e = "", d;
                            for (d in a)
                                a.hasOwnProperty(d) && (e = "object" == typeof a[d] ? e + (d + " : [" + a[d].length + " values]<br>") : e + (d + " : " + a[d] + "<br>"));
                            alert(e)
                        }}, a);
            a.allowDelete = a.allowDelete || !1;
            a.checkFileExists = a.checkFileExists || !1;
            a.allowExt = c.map(a.allowExt, function (a, e) {
                return a.toLowerCase()
            });
            -1 !== navigator.userAgent.indexOf(" OS 7_") && (a.maxFiles = 1);
            "auto" == a.language && (a.language =
                    (window.navigator.userLanguage || window.navigator.language).replace("-", "_"));
            return a
        }, _init: function (a) {
            var b = this.settings;
            r = u[b.language] || u.en_EN;
            a.addClass("ax-uploader").data("author", "http://www.albanx.com/");
            0 == c("#ax-main-frame").length && c('<iframe name="ax-main-frame" id="ax-main-frame" />').hide().appendTo("body");
            0 == c("#ax-box").length && c('<div id="ax-box"><div id="ax-box-fn"><span></span></div><img /><a id="ax-box-close" title="' + g("Close") + '"></a></div>').appendTo("body");
            0 == c("#ax-box-shadow").length &&
                    c('<div id="ax-box-shadow"/>').appendTo("body");
            c("#ax-box-close, #ax-box-shadow").click(function (a) {
                a.preventDefault();
                c("#ax-box").fadeOut(500);
                c("#ax-box-shadow").hide()
            });
            b.bootstrap && c("#ax-box-close").addClass("btn btn-danger").html('<span class="ax-clear-icon ax-icon icon-remove-sign"></span>');
            for (b = "AX_" + Math.floor(100001 * Math.random()); 0 < c("#" + b).length; )
                b = "AX_" + Math.floor(100001 * Math.random());
            a.attr("id", a.attr("id") ? a.attr("id") : b)
        }, checkUploadSupport: function () {
            var a = document.createElement("input");
            a.type = "file";
            this.hasAjaxUpload = "multiple"in a && "undefined" != typeof File && "undefined" != typeof (new XMLHttpRequest).upload;
            this.hasFlash = !1;
            /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor) && /Version\/5\./.test(navigator.userAgent) && /Win/.test(navigator.platform) && (this.hasAjaxUpload = !1);
            if (!this.hasAjaxUpload) {
                try {
                    new ActiveXObject("ShockwaveFlash.ShockwaveFlash") && (this.hasFlash = !0)
                } catch (b) {
                    navigator.mimeTypes["application/x-shockwave-flash"] != k && (this.hasFlash = !0)
                }
                this.settings.maxConnections =
                0
            }
            this.hasHtml4 = !this.hasFlash && !this.hasAjaxUpload;
            this.useFormData = window.FormData !== k;
            a = navigator.userAgent.match(/Firefox\/(\d+)?/);
            null !== a && 6 >= (null === a || a[1] === k || isNaN(a[1]) ? 7 : parseFloat(a[1])) && (this.useFormData = !1);
            null !== navigator.userAgent.match(/Opera\/(\d+)?/) && (a = navigator.userAgent.match(/Version\/(\d+)?/), 12.1 > (a[1] === k || isNaN(a[1]) ? 0 : parseFloat(a[1])) && (this.useFormData = !1))
        }, renderHtml: function () {
            var a = this.settings;
            this.mainWrapper = c('<div class="ax-main-container" />').append('<h5 class="ax-main-title">' +
                    g("Select Files") + "</h5>").appendTo(this.container);
            this.max_size = a.maxFileSize;
            var b = a.maxFileSize.slice(-1);
            if (isNaN(b))
                switch (this.max_size = this.max_size.replace(b, ""), b) {
                    case "P":
                        this.max_size *= 1024;
                    case "T":
                        this.max_size *= 1024;
                    case "G":
                        this.max_size *= 1024;
                    case "M":
                        this.max_size *= 1024;
                    case "K":
                        this.max_size *= 1024
                }
            var e = "ax-browse-c ax-button", b = "ax-upload-all ax-button", d = "ax-clear ax-button", h = "ax-plus-icon ax-icon", f = "ax-upload-icon ax-icon", k = "ax-clear-icon ax-icon";
            a.bootstrap && (e += " btn btn-primary",
                    b += " btn btn-success", d += " btn btn-danger", h += " icon-plus-sign", f += " icon-play", k += " icon-remove-sign");
            this.browse_c = c('<a class="' + e + '" title="' + g(Zikula.__('Add Files', 'module_ztext_js')) + '" />').append('<span class="' + h + '"></span> <span class="ax-text">' + g(Zikula.__('Add Files', 'module_ztext_js')) + "</span>").appendTo(this.mainWrapper);
            this.browseFiles = c('<input type="file" class="ax-browse" name="ax_file_input" />').prop("multiple", this.hasAjaxUpload && 1 != this.settings.maxFiles).appendTo(this.browse_c);
            a.uploadDir && this.browseFiles.prop({directory: "directory",
                webkitdirectory: "webkitdirectory", mozdirectory: "mozdirectory"});
            this.hasFlash && (this.browse_c.children(".ax-browse").remove(), e = this.container.attr("id") + "_flash", a.flash = a.flash + "?test=" + Math.random(10, 1E5), h = '\x3c!--[if !IE]> --\x3e<object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' + e + '" type="application/x-shockwave-flash" data="' + a.flash + '" width="150" height="100">\x3c!-- <![endif]--\x3e\x3c!--[if IE]><object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' +
                    e + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="150" height="100"><param name="movie" value="' + a.flash + '" />\x3c!--\x3e\x3c!--dgx--\x3e<param name="flashvars" value="instance_id=' + this.container.attr("id") + '"><param name="allowScriptAccess" value="always" /><param value="transparent" name="wmode"></object>\x3c!-- <![endif]--\x3e', this.browse_c.append('<div style="position:absolute;overflow:hidden;width:150px;height:100px;left:0px;top:0px;z-index:0;">' +
                    h + "</div>"), this.flashObj = document.getElementById(e));
            this.uploadFiles = c('<a class="' + b + '" title="' + g("Upload all files") + '" />').append('<span class="' + f + '"></span> <span class="ax-text">' + g("Start upload") + "</span>").appendTo(this.mainWrapper);
            this.removeFiles = c('<a class="' + d + '" title="' + g("Remove all") + '" />').append('<span class="' + k + '"></span> <span class="ax-text">' + g("Remove all") + "</span>").appendTo(this.mainWrapper);
            this.fileList = c('<ul class="ax-file-list" />').appendTo(this.mainWrapper);
            a.bootstrap && this.fileList.addClass("media-list")
        }, bindEvents: function () {
            var a = this.settings;
            this.browseFiles.bind("change", this, function (a) {
                a = a.data;
                a.settings.enable && !a.hasFlash && (a.addFiles(a.hasAjaxUpload ? this.files : Array(this)), a.hasAjaxUpload ? this.value = "" : c(this).clone(!0).val("").appendTo(a.browse_c))
            });
            this.uploadFiles.bind("click", this, function (a) {
                a.data.settings.enable && a.data.enqueueAll();
                return!1
            });
            this.removeFiles.bind("click", this, function (a) {
                a.data.settings.enable && a.data.clearQueue();
                return!1
            });
            if (this.hasAjaxUpload) {
                var b = "self" != a.dropArea && 0 < c(a.dropArea).length ? c(a.dropArea) : this.container, e = this;
                "self" == a.dropArea && this.mainWrapper.find(".ax-main-title").html(g("Select Files or Drag&Drop Files"));
                b.on("dranenter", function (a) {
                    a.stopPropagation();
                    a.preventDefault()
                }).on("dragover", function (b) {
                    b.stopPropagation();
                    b.preventDefault();
                    a.enable && (a.dropClass && c(this).addClass(a.dropClass), a.dropColor && c(this).css("background-color", a.dropColor))
                }).on("dragleave", function (b) {
                    b.stopPropagation();
                    b.preventDefault();
                    a.enable && (a.dropClass && c(this).removeClass(a.dropClass), a.dropColor && c(this).css("background-color", ""))
                }).on("drop", function (b) {
                    a.enable && (b.stopPropagation(), b.preventDefault(), e.addFiles(b.originalEvent.dataTransfer.files), a.dropClass && c(this).removeClass(a.dropClass), a.dropColor && c(this).css("background-color", ""))
                });
                c(document).unbind(".ax").bind("keyup.ax", function (a) {
                    27 == a.keyCode && c("#ax-box-shadow, #ax-box").fadeOut(500)
                })
            }
            this.enable(this.settings.enable)
        }, fileUploaded: function (a,
                b) {
            this.settings.success.call(a, b);
            for (var e = !0, c = 0; c < this.files.length; c++)
                1 != this.files[c].status && -1 != this.files[c].status && (e = !1);
            e && this.finish();
            this.settings.removeOnSuccess && this.removeFile(a.pos)
        }, finish: function () {
            this.globalStatus = 1;
            for (var a = [], b = 0; b < this.files.length; b++)
                a.push(this.files[b].name);
            "function" == typeof this.settings.finish && this.settings.finish.call(this, a, this.files);
            "function" == typeof this._onEnd && this._onEnd.call(this)
        }, addFiles: function (a) {
            for (var b = [], e = 0; e < a.length; e++) {
                var c,
                        g, f;
                this.hasAjaxUpload || this.hasFlash ? (g = a[e].name, f = a[e].size) : (g = a[e].value.replace(/^.*\\/, ""), f = 0);
                c = g.split(".").pop().toLowerCase();
                var k = this.checkFile(g, f);
                0 == k.length ? (h("Create file object"), c = new x(a[e], g, f, c, this), h(" file object created"), this.files.push(c), b.push(c)) : this.settings.error.call(this, k, g)
            }
            this.settings.onSelect.call(this, b);
            this.settings.autoStart && this.enqueueAll()
        }, checkFile: function (a, b) {
            var e = a.split(".").pop().toLowerCase(), d = !!(this.files.length < this.settings.maxFiles),
                    h = !!(0 <= c.inArray(e, this.settings.allowExt) || 0 == this.settings.allowExt.length), f = !!(b <= this.max_size), k = "function" === typeof this.settings.validateFile ? this.settings.validateFile.call(this, a, e, b) : "", l = [];
            d || l.push({message: g("Max files number reached"), error: "MAX_FILES", param: d});
            h || l.push({message: g("Extension not allowed"), error: "ALLOW_EXTENSION", param: e});
            f || l.push({message: g("File size now allowed"), error: "FILE_SIZE", param: b});
            k && l.push({message: k, error: "USER_ERROR", param: ""});
            return l
        }, enqueueFile: function (a) {
            this.uploadQueue.push(a);
            this.processQueue()
        }, enqueueAll: function () {
            var a = this.settings.beforeUploadAll.call(this, this.files), b = this.getPendingFiles();
            "function" == typeof this._onNoFiles && 0 == b.length && this._onNoFiles.call(this);
            if (!1 !== a) {
                "function" == typeof this._onStart && 0 < b.length && this._onStart.call(this, b);
                for (a = 0; a < b.length; a++)
                    this.uploadQueue.push(b[a]);
                this.processQueue()
            } else
                "function" == typeof this._onError && this._onError.call(this), h("beforeUploadAll return false")
        }, startUpload: function (a, b, c, d) {
            this._onStart = a;
            this._onEnd = b;
            this._onNoFiles = c;
            this._onUploading = d;
            this.isUploading() && "function" == typeof this._onUploading && this._onUploading.call(this);
            this.enqueueAll()
        }, processQueue: function () {
            var a = this;
            this.checkInterval || (this.checkInterval = setInterval(function () {
                if (0 == a.uploadQueue.length)
                    clearInterval(a.checkInterval), a.checkInterval = null;
                else {
                    a.globalStatus = 2;
                    for (var b = 0; b < a.uploadQueue.length; b++)
                        0 < a.slots && 4 == a.uploadQueue[b].status && (a.uploadQueue[b].startUpload(), a.uploadQueue.splice(b, 1), a.slots--)
                }
            },
                    200))
        }, getPendingFiles: function () {
            for (var a = [], b = this.files.length, c = 0; c < b; c++)
                4 != this.files[c].status && 0 != this.files[c].status && 3 != this.files[c].status || a.push(this.files[c]);
            return a
        }, isUploading: function () {
            return 2 == this.globalStatus
        }, hasFinish: function () {
            return 0 == this.getPendingFiles().length && 1 == this.globalStatus
        }, isIdle: function () {
            return 0 == this.globalStatus
        }, isIdleWithFiles: function () {
            return 0 == this.globalStatus && this.hasFiles()
        }, hasFiles: function () {
            return 0 != this.files.length
        }, onOverwallProgress: function (a) {
            this.total_bytes =
                    +a;
            "function" == typeof this.settings.onProgress && this.settings.onProgress.call(this, this.total_bytes)
        }, clearQueue: function () {
            for (; 0 < this.files.length; )
                this.removeFile(0)
        }, getParams: function (a, b, c) {
            var d = this.settings, g = "function" == typeof d.remotePath ? d.remotePath() : d.remotePath, f = [];
            f.push("ax-file-path=" + (c ? encodeURIComponent(g) : g));
            f.push("ax-allow-ext=" + (c ? encodeURIComponent(d.allowExt.join("|")) : d.allowExt.join("|")));
            f.push("ax-file-name=" + (c ? encodeURIComponent(a) : a));
            f.push("ax-max-file-size=" +
                    d.maxFileSize);
            f.push("ax-file-size=" + b);
            f.push("ax-thumbPostfix=" + (c ? encodeURIComponent(d.thumbPostfix) : d.thumbPostfix));
            f.push("ax-thumbPath=" + (c ? encodeURIComponent(d.thumbPath) : d.thumbPath));
            f.push("ax-thumbFormat=" + (c ? encodeURIComponent(d.thumbFormat) : d.thumbFormat));
            f.push("ax-thumbHeight=" + d.thumbHeight);
            f.push("ax-thumbWidth=" + d.thumbWidth);
            f.push("ax-random=" + 10001 * Math.random());
            (this.settings.checkFileExists || this.settings.overrideFile) && f.push("ax-override=1");
            a = "function" == typeof d.data ?
                    d.data.call(this) : d.data;
            if ("object" == typeof a)
                for (var h in a)
                    f.push(h + "=" + (c ? encodeURIComponent(a[h]) : a[h]));
            else if ("string" == typeof a && "" != a)
                for (c = a.split("&"), h = 0; h < c.length; h++)
                    f.push(c[h]);
            return f
        }, removeFile: function (a) {
            var b = this.files[a];
            b.stopUpload();
            b.li.remove();
            b.file = null;
            this.files.splice(a, 1);
            this.hasFlash && this.flashObj.removeFile(a);
            for (a = 0; a < this.files.length; a++)
                this.files[a].pos = a
        }, stopUpload: function () {
            for (var a = 0; a < this.files.lenght; a++)
                this.files[a].stopUpload()
        }, options: function (a,
                b) {
            if (b !== k && null !== b)
                this.settings[a] = b, "enable" == a && this.enable(b);
            else
                return this.settings[a]
        }, enable: function (a) {
            (this.settings.enable = a) ? this.container.removeClass("ax-disabled").find("input").attr("disabled", !1) : this.container.addClass("ax-disabled").find("input").attr("disabled", !0)
        }, fileSlice: function (a, b, c) {
            var d;
            if (window.File.prototype.slice)
                try {
                    return a.slice(), a.slice(b, c)
                } catch (g) {
                    return a.slice(b, c - b)
                }
            else if (d = window.File.prototype.webkitSlice || window.File.prototype.mozSlice)
                return d.call(a,
                        b, c);
            return null
        }};
    var n = {init: function (a) {
            return this.each(function () {
                var b = c(this), e = b.data("AU");
                e !== k && null !== e ? h("Uploader already attached on current div") : (b.html(""), e = new v(b, a), b.data({AU: e, au: e}))
            })
        }, clear: function () {
            return this.each(function () {
                c(this).data("AU").clearQueue()
            })
        }, start: function () {
            return this.each(function () {
                return c(this).data("AU").enqueueAll()
            })
        }, addFlash: function (a) {
            c(this).data("AU").addFiles(a)
        }, progressFlash: function (a, b, e) {
            c(this).data("AU").files[b].onProgress(a,
                    e)
        }, onFinishFlash: function (a, b) {
            var e = c(this).data("AU");
            e.uploading = !1;
            try {
                var d = jQuery.parseJSON(a);
                if (-1 == parseInt(d.status))
                    throw d.info;
                e.files[b].onFinishUpload(d.name, d.size, d.status, d.info)
            } catch (g) {
                e.files[b].onError(g)
            }
        }, getUrl: function (a, b) {
            return c(this).data("AU").settings.url
        }, getParams: function (a, b) {
            return c(this).data("AU").getParams(a, b, !0).join("&")
        }, getAllowedExt: function (a) {
            var b = c(this).data("AU").settings.allowExt;
            return!0 === a ? b : b.join("|")
        }, getMaxFileNum: function (a) {
            return c(this).data("AU").settings.maxFiles
        },
        checkFile: function (a, b) {
            return"" == c(this).data("AU").checkFile(a, b)
        }, checkEnable: function () {
            return c(this).data("AU").settings.enable
        }, getFiles: function () {
            return c(this).data("AU").files
        }, enable: function () {
            return this.each(function () {
                c(this).data("AU").enable(!0)
            })
        }, disable: function () {
            return this.each(function () {
                c(this).data("AU").enable(!1)
            })
        }, destroy: function () {
            return this.each(function () {
                var a = c(this);
                a.data("AU").clearQueue();
                a.removeData("AU").html("")
            })
        }, option: function (a, b) {
            return this.each(function () {
                return c(this).data("AU").options(a,
                        b)
            })
        }, debug: function (a) {
        }};
    c.fn.ajaxupload = function (a, b) {
        if (n[a])
            return n[a].apply(this, Array.prototype.slice.call(arguments, 1));
        if ("object" !== typeof a && a)
            c.error("Method " + a + " does not exist on jQuery.RealAjaxUploader");
        else
            return n.init.apply(this, arguments)
    }
})(jQuery);
(function (d, l) {
    function g(d) {
        return B[d] || d
    }
    function h() {
        var d = Array.prototype.slice.call(arguments, 0), a = new Date, a = (10 > a.getHours() ? "0" : "") + a.getHours() + ":" + (10 > a.getMinutes() ? "0" : "") + a.getMinutes() + ":" + (10 > a.getSeconds() ? "0" : "") + a.getSeconds();
        d.unshift(a);
        console.log.apply(console, d)
    }
    var z = !1, F = /xyz/.test(function () {
        xyz
    }) ? /\b_super\b/ : /.*/, C = function () {
    };
    C.extend = function a(b) {
        function c() {
            !z && this.init && this.init.apply(this, arguments)
        }
        var e = this.prototype;
        z = !0;
        var d = new this;
        z = !1;
        for (var f in b)
            d[f] =
                    "function" == typeof b[f] && "function" == typeof e[f] && F.test(b[f]) ? function (a, b) {
                return function () {
                    var c = this._super;
                    this._super = e[a];
                    var d = b.apply(this, arguments);
                    this._super = c;
                    return d
                }
            }(f, b[f]) : b[f];
        c.prototype = d;
        c.prototype.constructor = c;
        c.extend = a;
        return c
    };
    var D = {en_EN: {"Add files": "Add files", "Start upload": "Start upload", "Remove all": "Remove all", Close: "Close", "Select Files": "Select Files", Preview: "Preview", "Remove file": "Remove file", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload aborted",
            "Upload all files": "Upload all files", "Select Files or Drag&Drop Files": "Select Files or Drag&Drop Files", "File uploaded 100%": "File uploaded 100%", "Max files number reached": "Max files number reached", "Extension not allowed": "Extension not allowed", "File size now allowed": "File size now allowed"}, it_IT: {"Add files": "Aggiungi file", "Start upload": "Inizia caricamento", "Remove all": "Rimuvi tutti", Close: "Chiudi", "Select Files": "Seleziona", Preview: "Anteprima", "Remove file": "Rimuovi file", Bytes: "Bytes",
            KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Interroto", "Upload all files": "Carica tutto", "Select Files or Drag&Drop Files": "Seleziona o Trascina qui i file", "File uploaded 100%": "File caricato 100%", "Max files number reached": "Max files number reached", "Extension not allowed": "Estensione file non permessa", "File size now allowed": "Dimensione file non permessa"}, sq_AL: {"Add files": "Shto file", "Start upload": "Fillo karikimin", "Remove all": "Hiqi te gjith\u00eb", Close: "Mbyll", "Select Files": "Zgjith filet",
            Preview: "Miniatur\u00eb", "Remove file": "Hiqe file-in", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Karikimi u nd\u00ebrpre", "Upload all files": "Kariko t\u00eb gjith\u00eb", "Select Files or Drag&Drop Files": "Zgjith ose Zvarrit dokumentat k\u00ebtu", "File uploaded 100%": "File u karikua 100%", "Max files number reached": "Maksimumi i fileve u arrit", "Extension not allowed": "Prapashtesa nuk lejohet", "File size now allowed": "Madh\u00ebsia e filit nuk lejohet"}, nl_NL: {"Add files": "Bestanden toevoegen",
            "Start upload": "Start uploaden", "Remove all": "Verwijder alles", Close: "Sluiten", "Select Files": "Selecteer bestanden", Preview: "Voorbeeld", "Remove file": "Verwijder bestand", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload afgebroken", "Upload all files": "Upload alle bestanden", "Select Files or Drag&Drop Files": "Selecteer bestanden of  Drag&Drop bestanden", "File uploaded 100%": "Bestand ge\u00fcpload 100%"}, de_DE: {"Add files": "Dateien hinzuf\u00fcgen", "Start upload": "Hochladen", "Remove all": "Alle entfernen",
            Close: "Schliessen", "Select Files": "Dateien w\u00e4hlen", Preview: "Vorschau", "Remove file": "Datei entfernen", Bytes: "Bytes", KB: "KB", MB: "MB", GB: "GB", "Upload aborted": "Upload abgebrochen", "Upload all files": "Alle hochgeladen", "Select Files or Drag&Drop Files": "W\u00e4hlen Sie Dateien oder f\u00fcgen Sie sie mit Drag & Drop hinzu.", "File uploaded 100%": "Upload 100%"}, fr_FR: {"Add files": "Ajouter", "Start upload": "Envoyer", "Remove all": "Tout supprimer", Close: "Fermer", "Select Files": "Parcourir", Preview: "Visualiser",
            "Remove file": "Supprimer fichier", Bytes: "Bytes", KB: "Ko", MB: "Mo", GB: "Go", "Upload aborted": "Envoi annul\u00e9", "Upload all files": "Tout envoyer", "Select Files or Drag&Drop Files": "Parcourir ou Glisser/D\u00e9poser", "File uploaded 100%": "Fichier envoy\u00e9 100%"}}, B = {}, p = C.extend({init: function (a, b, c, e, d) {
            this.file = a;
            this.status = 0;
            this.name = b;
            this.size = c;
            this.info = this.xhr = null;
            this.ext = e;
            this.pos = d.files.length;
            this.AU = d;
            this.settings = d.settings;
            this.exifData = null;
            this.md5 = "";
            this.ready = this.disabled =
                    !1;
            this.temp_bytes = this.loading_bytes = this.currentByte = 0;
            this.afterInit()
        }, afterInit: function () {
            this.renderHtml();
            this.bindEvents();
            this.settings.hideUploadButton && this.uploadButton.hide();
            if (this.AU.hasHtml4) {
                var a = this.AU.getParams(this.name, 0, !1), b = d('<form action="' + this.settings.url + '" method="post" target="ax-main-frame" encType="multipart/form-data" />').hide().appendTo(this.li);
                b.append(this.file);
                b.append('<input type="hidden" value="' + this.name + '" name="ax-file-name" />');
                for (var c = 0; c <
                        a.length; c++) {
                    var e = a[c].split("=");
                    b.append('<input type="hidden" value="' + e[1] + '" name="' + e[0] + '" />')
                }
                this.xhr = b
            }
            this.postSelectFile().always(function () {
                this.doPreview()
            })
        }, renderHtml: function () {
            var a = this.settings, b = this.formatSize(this.size);
            this.li = d("<li />").appendTo(this.AU.fileList).attr("title", name);
            a.bootstrap && (this.li = d("<a />").appendTo(this.li));
            this.prevContainer = d('<a class="ax-prev-container" />').appendTo(this.li);
            this.prevImage = d('<img class="ax-preview" src="" alt="' + g("Preview") +
                    '" />').appendTo(this.prevContainer);
            this.details = d('<div class="ax-details" />').appendTo(this.li);
            this.nameContainer = d('<div class="ax-file-name">' + this.name + "</div>").appendTo(this.details);
            this.sizeContainer = d('<div class="ax-file-size">' + b + "</div>").appendTo(this.details);
            this.progressCont = d('<div class="ax-progress-data" />').appendTo(this.li);
            this.progressInfo = d('<div class="ax-progress" />').appendTo(this.progressCont);
            this.progressBar = d('<div class="ax-progress-bar" />').appendTo(this.progressInfo);
            this.progressPer = d('<div class="ax-progress-info">0%</div>').appendTo(this.progressInfo);
            this.progressStat = d('<div class="ax-progress-stat" />').appendTo(this.progressCont);
            this.buttons = d('<div class="ax-toolbar" />').appendTo(this.li);
            this.uploadButton = d('<a title="' + g("Start upload") + '" class="ax-upload ax-button" />').appendTo(this.buttons).append('<span class="ax-upload-icon ax-icon"></span>');
            this.removeButton = d('<a title="Remove file" class="ax-remove ax-button" />').appendTo(this.buttons).append('<span class="ax-clear-icon ax-icon"></span>');
            a.bootstrap && (this.li.addClass("media thumbnail label-info"), this.prevContainer.addClass("pull-left"), this.prevImage.addClass("img-rounded media-object"), this.details.addClass("label label-info").css({"border-bottom-left-radius": 0}), this.progressInfo.addClass("progress progress-striped active").css({"margin-bottom": 0}), this.progressBar.addClass("bar"), this.buttons.css({"border-top-left-radius": 0, "border-top-right-radius": 0}), this.uploadButton.addClass("btn btn-success btn-small").find("span").addClass("icon-play"),
                    this.removeButton.addClass("btn btn-danger btn-small").find("span").addClass("icon-minus-sign"))
        }, disableUpload: function (a) {
            this.disabled = !0;
            this.buttons.css("opacity", .5);
            this.progressPer.html(a)
        }, enableUpload: function (a) {
            this.disabled = !1;
            this.buttons.css("opacity", 1);
            this.progressPer.html(a)
        }, workProgress: function (a, b) {
            this.progressBar.css("width", b + "%");
            this.progressPer.html(a + " " + b + "%")
        }, bindEvents: function () {
            this.uploadButton.on("click", this, function (a) {
                a.data.AU.settings.enable && !a.data.disabled &&
                        (2 != a.data.status ? a.data.startUpload() : a.data.stopUpload())
            });
            this.removeButton.bind("click", this, function (a) {
                a.data.AU.settings.enable && !a.data.disabled && a.data.AU.removeFile(a.data.pos)
            });
            this.settings.editFilename && this.nameContainer.bind("dblclick", this, function (a) {
                if (a.data.AU.settings.enable && !a.data.disabled) {
                    a.stopPropagation();
                    var b = a.data.ext;
                    a = a.data.name.replace("." + b, "");
                    d(this).html('<input type="text" value="' + a + '" />.' + b)
                }
            }).bind("blur focusout", this, function (a) {
                a.stopPropagation();
                var b = d(this).children("input").val();
                "undefined" != typeof b && (b = b.replace(/[|&;$%@"<>()+,]/g, "") + "." + a.data.ext, d(this).html(b), a.data.name = b, a.data.AU.hasAjaxUpload || a.data.xhr.children('input[name="ax-file-name"]').val(b))
            });
            this.createUploadDeferred()
        }, createUploadDeferred: function () {
            this.defUpload = new d.Deferred;
            this.defUpload.done(function (a, b, c, e) {
                this.name = a;
                this.status = parseInt(c);
                this.info = e;
                this.nameContainer.html(a);
                this.li.attr("title", a);
                this.onProgress(100, 0);
                this.progressPer.html(g("File uploaded"));
                this.AU.hasAjaxUpload || this.AU.hasFlash || (b = this.formatSize(this.size), this.sizeContainer.html(b));
                this.AU.fileUploaded(this, a)
            }).fail(function (a, b) {
                this.progressPer.html(b);
                this.info = a;
                "stoped" == a && (this.status = 4);
                "error" == a && (this.status = -1, this.settings.error.call(this, a, this.name), this.settings.removeOnError && this.AU.removeFile(this.pos))
            }).progress(function (a, b, c) {
                "progress" == a ? (this.progressBar.css("width", b + "%"), this.progressPer.html(b + "%"), this.loading_bytes = c, this.AU.onOverwallProgress(c)) :
                        "start" == a && "function" == typeof this.settings.onStart && this.settings.onStart.call(this, this.name)
            }).always(function () {
                this.AU.slots++;
                this.currentByte = 0;
                this.uploadButton.removeClass("ax-abort");
                this.progressBar.css("width", "0%");
                this.stat_interval !== l && (clearInterval(this.stat_interval), this.stat_interval = l);
                this.createUploadDeferred()
            })
        }, readImageFile: function () {
            h("readImageFile: start");
            if (this.settings.previews && this.AU.hasAjaxUpload && this.file.type.match(/image.*/) && ("jpeg" == this.ext || "jpg" ==
                    this.ext || "gif" == this.ext || "png" == this.ext) && "undefined" !== typeof window.FileReader) {
                var a = this, b = new FileReader;
                b.onprogress = function (b) {
                    b = Math.round(100 * b.loaded / b.total);
                    a.readFileDef.notifyWith(a, [b])
                };
                b.onerror = function (b) {
                    a.readFileDef.notifyWith(a, [0]);
                    a.readFileDef.rejectWith(a, [g("FileReader API error")])
                };
                b.onload = function (b) {
                    a.readFileDef.notifyWith(a, [90]);
                    h("readImageFile: file readed, creating image");
                    var e = new Image;
                    e.onload = function () {
                        h("readImageFile: image created");
                        a.readFileDef.notifyWith(a,
                                [100]);
                        a.readFileDef.resolveWith(a, [e])
                    };
                    e.src = b.target.result
                };
                b.readAsDataURL(a.file)
            } else
                h("readImageFile: it is not a web image"), this.readFileDef.rejectWith(this, [g("Image preview not supported")])
        }, doPreview: function () {
            h("doPreview: start, readFileDef");
            this.readFileDef = new d.Deferred;
            this.readFileDef.done(function (a) {
                h("doPreview: file readed");
                var b = this, c = d(window).width(), e = d(window).height() - 100, e = Math.min(c / a.width, e / a.height), c = 1 > e ? a.width * e : a.width, e = 1 > e ? a.height * e : a.height, y = d(window).scrollTop() -
                        20 + (d(window).height() - e) / 2, f = (d(window).width() - c) / 2;
                a = b.canvasScale(a, c, e);
                a = b.fixOrientation(a);
                b.prevImage.attr("src", a.toDataURL("image/jpeg", .75));
                b.prevImage.data("lightbox", {top: y, left: f, w: c, height: e});
                b.prevContainer.css("background", "none");
                b.prevImage.css("cursor", "pointer").click(function () {
                    var a = d(this).data("lightbox"), c = d("#ax-box").css({top: a.top, height: a.h, width: a.w, left: a.left});
                    c.children("img").attr({width: a.w, height: a.h, src: this.src});
                    d("#ax-box-fn").find("span").html(b.name +
                            " (" + b.formatSize(b.size) + ")");
                    c.fadeIn(500);
                    d("#ax-box-shadow").css("height", d(document).height()).show();
                    d("#ax-box-shadow").css("z-index", 1E4);
                    d("#ax-box").css("z-index", 10001)
                });
                h("doPreview: done");
                b.onPreview()
            }).fail(function () {
                h("doPreview: cannot preview this file: ", this.ext);
                this.prevContainer.addClass("ax-filetype-" + this.ext).children("img:first").remove()
            }).progress(function (a) {
                this.workProgress(g("Rendering..."), a)
            }).always(function () {
                this.workProgress(g("Ready"), 0);
                this.enableUpload(g("Ready"));
                this.status = 4
            });
            this.disableUpload(g("Rendering preview..."));
            this.readImageFile()
        }, getHalfScaleCanvas: function (a) {
            var b = document.createElement("canvas");
            b.width = a.width / 2;
            b.height = a.height / 2;
            b.getContext("2d").drawImage(a, 0, 0, b.width, b.height);
            return b
        }, canvasScale: function (a, b, c) {
            var e = document.createElement("canvas");
            e.width = a.width;
            e.height = a.height;
            for (e.getContext("2d").drawImage(a, 0, 0, e.width, e.height); e.width >= 2 * b && e.height >= 2 * c; )
                e = this.getHalfScaleCanvas(e);
            return e
        }, fixOrientation: function (a) {
            if (this.exifData &&
                    this.exifData.Orientation) {
                var b = a.width, c = a.height, e = document.createElement("canvas"), d = this.exifData.Orientation;
                h("fixOrientation:", d);
                switch (d) {
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        e.width = c;
                        e.height = b;
                        break;
                    default:
                        e.width = b, e.height = c
                }
                var f = e.getContext("2d");
                switch (d) {
                    case 2:
                        f.translate(b, 0);
                        f.scale(-1, 1);
                        break;
                    case 3:
                        f.translate(b, c);
                        f.rotate(Math.PI);
                        break;
                    case 4:
                        f.translate(0, c);
                        f.scale(1, -1);
                        break;
                    case 5:
                        f.rotate(.5 * Math.PI);
                        f.scale(1, -1);
                        break;
                    case 6:
                        f.rotate(.5 * Math.PI);
                        f.translate(0, -c);
                        break;
                    case 7:
                        f.rotate(.5 * Math.PI);
                        f.translate(b, -c);
                        f.scale(-1, 1);
                        break;
                    case 8:
                        f.rotate(-.5 * Math.PI), f.translate(-b, 0)
                }
                f.drawImage(a, 0, 0);
                return e
            }
            h("fixOrientation: no exif");
            return a
        }, onPreview: function () {
            this.settings.onPreview.call(this)
        }, askUser: function (a, b) {
            this.askDiv && this.askDiv.remove();
            this.askDiv = d('<div class="ax-ask-div"></div>').appendTo(this.li);
            var c = d('<div class="ax-ask-inner"><div class="ax-ask-quest">' + b + "</div> </div>").appendTo(this.askDiv), e = d('<a title="' + g("Yes") + '" class="ax-button ax-ask-yes"><span class="ax-upload-icon ax-icon"></span> <span>' +
                    g("Yes") + "</span></a>").appendTo(c), c = d('<a title="' + g("No") + '" class="ax-button ax-ask-no"><span class="ax-clear-icon ax-icon"></span> <span>' + g("No") + "</span></a>").appendTo(c);
            this.settings.bootstrap && (this.askDiv.addClass("alert"), e.addClass("btn btn-success btn-small").find(".ax-icon").addClass("icon-ok"), c.addClass("btn btn-danger btn-small").find(".ax-icon").addClass("icon-remove"));
            e.on("click", this, function (b) {
                a.resolve(b.data)
            });
            c.on("click", this, function (b) {
                a.reject(b.data)
            });
            var y = this;
            a.always(function () {
                y.askDiv.remove();
                y.askDiv = null
            })
        }, checkFileExists: function () {
            h("Checking file exists");
            var a = new d.Deferred, b = this;
            if (this.settings.checkFileExists) {
                var c = this.AU.getParams(this.name, this.size, !1);
                c.push("ax-check-file=1");
                d.post(this.settings.url, c.join("&")).done(function (c) {
                    "yes" == c ? b.askUser(a, g("File exits on server. Override?")) : a.resolve(b)
                })
            } else
                a.resolve(b);
            return a.promise()
        }, postSelectFile: function () {
            var a = new d.Deferred;
            a.resolveWith(this, []);
            return a.promise()
        },
        preProcessFile: function () {
            this.preDef = new d.Deferred;
            this.preDef.resolveWith(this, []);
            return this.preDef.promise()
        }, startUpload: function () {
            h("Start uploadx");
            if (this.disabled)
                return!1;
            h("Start upload");
            var a = this.settings.beforeUpload.call(this, this.name, this.file);
            if (a)
                this.status = 3, this.checkFileExists().done(function (a) {
                    a.defUpload.notifyWith(a, ["start", g("Upload started")]);
                    a.progressBar.css("width", "0%");
                    a.progressPer.html("0%");
                    a.uploadButton.addClass("ax-abort");
                    a.status = 2;
                    a.preProcessFile().always(function () {
                        h("Pre process resolved");
                        a.AU.hasAjaxUpload ? (h("Start ajax upload"), a.uploadAjax()) : a.AU.hasFlash ? (h("Start standard upload"), a.AU.uploading || (a.AU.uploading = !0, a.AU.flashObj.uploadFile(a.pos))) : a.uploadStandard();
                        a.settings.bandwidthUpdateInterval && (a.stat_interval = setInterval(function () {
                            a.progressStat.html((1E3 / a.settings.bandwidthUpdateInterval * (a.loading_bytes - a.temp_bytes) / 1024).toFixed(0) + " KB/s");
                            a.temp_bytes = a.loading_bytes
                        }, a.settings.bandwidthUpdateInterval))
                    })
                }).fail(function () {
                    this.defUpload.rejectWith(this, ["user_abort",
                        g("User stop")])
                });
            else
                this.onError("File validation failed");
            return a
        }, uploadAjax: function () {
            var a = this.settings, b = this.file, c = this.currentByte, e = this.name, d = this.size, f = a.chunkSize, g = f + c, h = 0 >= d - g, r = b;
            this.xhr = new XMLHttpRequest;
            0 == f ? (r = b, h = !0) : r = this.AU.fileSlice(b, c, g);
            null === r && (r = b, h = !0);
            "function" == typeof a.onBeforeChunkUpload && a.onBeforeChunkUpload.call(this, this.xhr, b, e, d, r);
            var k = this;
            this.xhr.upload.addEventListener("abort", function (a) {
                k.AU.slots++
            }, !1);
            this.xhr.upload.addEventListener("progress",
                    function (a) {
                        if (a.lengthComputable) {
                            var b = Math.round(100 * (a.loaded + c) / d);
                            k.onProgress(b, a.loaded + c)
                        }
                    }, !1);
            this.xhr.upload.addEventListener("error", function (a) {
                k.onError(this.responseText)
            }, !1);
            this.xhr.onreadystatechange = function () {
                if (4 == this.readyState && 200 == this.status) {
                    a.onAfterChunkUpload.call(k, k.xhr, b, e, d, r);
                    try {
                        var f = JSON.parse(this.responseText);
                        0 == c && (k.name = f.name, k.nameContainer.html(f.name));
                        if (-1 == parseInt(f.status))
                            throw f.info;
                        if (h)
                            k.onFinishUpload(f.name, f.size, f.status, f.info);
                        else
                            k.currentByte =
                                    g, k.uploadAjax()
                    } catch (n) {
                        k.onError(n)
                    }
                }
            };
            f = this.AU.getParams(e, d, !this.AU.useFormData);
            f.push("ax-start-byte=" + c);
            f.push("ax-last-chunk=" + h);
            f.push("ax-file-md5=" + this.md5);
            if (this.AU.useFormData) {
                var n = new FormData;
                n.append("ax_file_input", r);
                for (var q = 0; q < f.length; q++) {
                    var l = f[q].split("=");
                    n.append(l[0], l[1])
                }
                this.xhr.open("POST", a.url, a.async);
                this.xhr.send(n)
            } else
                n = -1 == a.url.indexOf("?") ? "?" : "&", this.xhr.open("POST", a.url + n + f.join("&"), a.async), this.xhr.setRequestHeader("Cache-Control", "no-cache"),
                        this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"), this.xhr.setRequestHeader("Content-Type", "application/octet-stream"), this.xhr.send(r)
        }, uploadStandard: function () {
            this.progressBar.css("width", "50%");
            this.progressPer.html("50%");
            d("#ax-main-frame").unbind("load").bind("load", this, function (a) {
                var b = null;
                this.contentDocument ? b = this.contentDocument : this.contentWindow && (b = this.contentWindow.document);
                try {
                    var c = d.parseJSON(b.body.innerHTML);
                    a.data.onProgress(100, 0);
                    a.data.onFinishUpload(c.name,
                            c.size, c.status, c.info)
                } catch (e) {
                    a.data.onError(b.body.innerHTML)
                }
            });
            this.xhr.submit()
        }, stopUpload: function () {
            if (this.AU.hasAjaxUpload)
                null !== this.xhr && (this.xhr.abort(), this.xhr = null);
            else if (this.AU.hasFlash)
                this.AU.flashObj.stopUpload(this.pos);
            else {
                var a = document.getElementById("ax-main-frame");
                try {
                    a.contentWindow.document.execCommand("Stop")
                } catch (b) {
                    a.contentWindow.stop()
                }
            }
            this.defUpload.rejectWith(this, ["stoped", g("Upload aborted")])
        }, onError: function (a) {
            this.defUpload.rejectWith(this, ["error",
                a])
        }, onFinishUpload: function (a, b, c, e) {
            this.defUpload.resolveWith(this, [a, b, c, e])
        }, onProgress: function (a, b) {
            this.defUpload.notifyWith(this, ["progress", a, b])
        }, formatSize: function (a) {
            var b = this.settings.precision;
            "undefined" == typeof b && (b = 2);
            for (var c = [g("Bytes"), g("KB"), g("MB"), g("GB")], e = 0; 1024 <= a && e < c.length - 1; )
                a /= 1024, e++;
            var d = Math.round(a), b = Math.pow(10, b);
            a = Math.round(a * b % b);
            return d + "." + a + " " + c[e]
        }}), E = function (a, b) {
        this.useFormData = this.hasAjaxUpload = this.hasFlash = !1;
        this.hasHtml4 = !0;
        this.settings =
                this.preCheckSettings(b);
        this._init(a);
        this.checkUploadSupport();
        this.container = a;
        this.files = [];
        this.slots = this.settings.maxConnections;
        this._onUploading = this._onNoFiles = this._onError = this._onEnd = this._onStart = null;
        this.uploadQueue = [];
        this.flashObj = null;
        this.globalStatus = 0;
        this.uploading = !1;
        this.total_bytes = 0;
        this.checkInterval = !1;
        this.renderHtml();
        "function" == typeof this.settings.onInit && this.settings.onInit.call(this);
        this.bindEvents()
    };
    E.prototype = {preCheckSettings: function (a) {
            a = d.extend(!0, {},
                    {allowExt: [], allowDelete: !1, autoStart: !1, async: !0, bandwidthUpdateInterval: 500, bootstrap: !1, checkFileExists: !1, chunkSize: 1048576, data: "", dropColor: "red", dropClass: "ax-drop", dropArea: "self", enable: !0, editFilename: !1, exifRead: !1, exifWorker: "js/exif-reader.js", flash: "uploader.swf", hideUploadButton: !0, language: "auto", maxFiles: 9999, maxConnections: 3, maxFileSize: "10M", md5Calculate: !1, md5WorkerPath: "js/file-md5.js", md5CalculateOn: "upload", overrideFile: !1, thumbHeight: 0, thumbWidth: 0, thumbPostfix: "_thumb", thumbPath: "",
                        thumbFormat: "", url: "upload.php", uploadDir: !1, removeOnSuccess: !1, removeOnError: !1, remotePath: "uploads/", resizeImage: {maxWidth: 0, maxHeight: 0, quality: .5, scaleMethod: l, format: l, removeExif: !1}, previews: !0, onStart: function (a) {
                        }, onPreview: function () {
                        }, onInit: function (a) {
                        }, onSelect: function (a) {
                        }, beforeUpload: function (a, c) {
                            return!0
                        }, beforeUploadAll: function (a) {
                            return!0
                        }, onProgress: function (a) {
                        }, success: function (a) {
                        }, finish: function (a, c) {
                        }, error: function (a, c) {
                        }, validateFile: function (a, c, e) {
                        }, onBeforeChunkUpload: function (a,
                                c, e, d, f) {
                        }, onAfterChunkUpload: function (a, c, e, d, f) {
                        }, onMd5Calculate: function (a) {
                        }, onExifRead: function (a) {
                        }, fileInfo: function (a) {
                            var c = "", e;
                            for (e in a)
                                a.hasOwnProperty(e) && (c = "object" == typeof a[e] ? c + (e + " : [" + a[e].length + " values]<br>") : c + (e + " : " + a[e] + "<br>"));
                            alert(c)
                        }}, a);
            a.allowDelete = a.allowDelete || !1;
            a.checkFileExists = a.checkFileExists || !1;
            a.allowExt = d.map(a.allowExt, function (a, c) {
                return a.toLowerCase()
            });
            -1 !== navigator.userAgent.indexOf(" OS 7_") && (a.maxFiles = 1);
            "auto" == a.language && (a.language =
                    (window.navigator.userLanguage || window.navigator.language).replace("-", "_"));
            return a
        }, _init: function (a) {
            var b = this.settings;
            B = D[b.language] || D.en_EN;
            a.addClass("ax-uploader").data("author", "http://www.albanx.com/");
            0 == d("#ax-main-frame").length && d('<iframe name="ax-main-frame" id="ax-main-frame" />').hide().appendTo("body");
            0 == d("#ax-box").length && d('<div id="ax-box"><div id="ax-box-fn"><span></span></div><img /><a id="ax-box-close" title="' + g("Close") + '"></a></div>').appendTo("body");
            0 == d("#ax-box-shadow").length &&
                    d('<div id="ax-box-shadow"/>').appendTo("body");
            d("#ax-box-close, #ax-box-shadow").click(function (a) {
                a.preventDefault();
                d("#ax-box").fadeOut(500);
                d("#ax-box-shadow").hide()
            });
            b.bootstrap && d("#ax-box-close").addClass("btn btn-danger").html('<span class="ax-clear-icon ax-icon icon-remove-sign"></span>');
            for (b = "AX_" + Math.floor(100001 * Math.random()); 0 < d("#" + b).length; )
                b = "AX_" + Math.floor(100001 * Math.random());
            a.attr("id", a.attr("id") ? a.attr("id") : b)
        }, checkUploadSupport: function () {
            var a = document.createElement("input");
            a.type = "file";
            this.hasAjaxUpload = "multiple"in a && "undefined" != typeof File && "undefined" != typeof (new XMLHttpRequest).upload;
            this.hasFlash = !1;
            /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor) && /Version\/5\./.test(navigator.userAgent) && /Win/.test(navigator.platform) && (this.hasAjaxUpload = !1);
            if (!this.hasAjaxUpload) {
                try {
                    new ActiveXObject("ShockwaveFlash.ShockwaveFlash") && (this.hasFlash = !0)
                } catch (b) {
                    navigator.mimeTypes["application/x-shockwave-flash"] != l && (this.hasFlash = !0)
                }
                this.settings.maxConnections =
                0
            }
            this.hasHtml4 = !this.hasFlash && !this.hasAjaxUpload;
            this.useFormData = window.FormData !== l;
            a = navigator.userAgent.match(/Firefox\/(\d+)?/);
            null !== a && 6 >= (null === a || a[1] === l || isNaN(a[1]) ? 7 : parseFloat(a[1])) && (this.useFormData = !1);
            null !== navigator.userAgent.match(/Opera\/(\d+)?/) && (a = navigator.userAgent.match(/Version\/(\d+)?/), 12.1 > (a[1] === l || isNaN(a[1]) ? 0 : parseFloat(a[1])) && (this.useFormData = !1))
        }, renderHtml: function () {
            var a = this.settings;
            this.mainWrapper = d('<div class="ax-main-container" />').append('<h5 class="ax-main-title">' +
                    g("Select Files") + "</h5>").appendTo(this.container);
            this.max_size = a.maxFileSize;
            var b = a.maxFileSize.slice(-1);
            if (isNaN(b))
                switch (this.max_size = this.max_size.replace(b, ""), b) {
                    case "P":
                        this.max_size *= 1024;
                    case "T":
                        this.max_size *= 1024;
                    case "G":
                        this.max_size *= 1024;
                    case "M":
                        this.max_size *= 1024;
                    case "K":
                        this.max_size *= 1024
                }
            var c = "ax-browse-c ax-button", b = "ax-upload-all ax-button", e = "ax-clear ax-button", h = "ax-plus-icon ax-icon", f = "ax-upload-icon ax-icon", u = "ax-clear-icon ax-icon";
            a.bootstrap && (c += " btn btn-primary",
                    b += " btn btn-success", e += " btn btn-danger", h += " icon-plus-sign", f += " icon-play", u += " icon-remove-sign");
            this.browse_c = d('<a class="' + c + '" title="' + g(Zikula.__('Add Files', 'module_ztext_js')) + '" />').append('<span class="' + h + '"></span> <span class="ax-text">' + g(Zikula.__('Add Files', 'module_ztext_js')) + "</span>").appendTo(this.mainWrapper);
            this.browseFiles = d('<input type="file" class="ax-browse" name="ax_file_input" />').prop("multiple", this.hasAjaxUpload && 1 != this.settings.maxFiles).appendTo(this.browse_c);
            a.uploadDir && this.browseFiles.prop({directory: "directory",
                webkitdirectory: "webkitdirectory", mozdirectory: "mozdirectory"});
            this.hasFlash && (this.browse_c.children(".ax-browse").remove(), c = this.container.attr("id") + "_flash", a.flash = a.flash + "?test=" + Math.random(10, 1E5), h = '\x3c!--[if !IE]> --\x3e<object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' + c + '" type="application/x-shockwave-flash" data="' + a.flash + '" width="150" height="100">\x3c!-- <![endif]--\x3e\x3c!--[if IE]><object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' +
                    c + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="150" height="100"><param name="movie" value="' + a.flash + '" />\x3c!--\x3e\x3c!--dgx--\x3e<param name="flashvars" value="instance_id=' + this.container.attr("id") + '"><param name="allowScriptAccess" value="always" /><param value="transparent" name="wmode"></object>\x3c!-- <![endif]--\x3e', this.browse_c.append('<div style="position:absolute;overflow:hidden;width:150px;height:100px;left:0px;top:0px;z-index:0;">' +
                    h + "</div>"), this.flashObj = document.getElementById(c));
            this.uploadFiles = d('<a class="' + b + '" title="' + g("Upload all files") + '" />').append('<span class="' + f + '"></span> <span class="ax-text">' + g("Start upload") + "</span>").appendTo(this.mainWrapper);
            this.removeFiles = d('<a class="' + e + '" title="' + g("Remove all") + '" />').append('<span class="' + u + '"></span> <span class="ax-text">' + g("Remove all") + "</span>").appendTo(this.mainWrapper);
            this.fileList = d('<ul class="ax-file-list" />').appendTo(this.mainWrapper);
            a.bootstrap && this.fileList.addClass("media-list")
        }, bindEvents: function () {
            var a = this.settings;
            this.browseFiles.bind("change", this, function (a) {
                a = a.data;
                a.settings.enable && !a.hasFlash && (a.addFiles(a.hasAjaxUpload ? this.files : Array(this)), a.hasAjaxUpload ? this.value = "" : d(this).clone(!0).val("").appendTo(a.browse_c))
            });
            this.uploadFiles.bind("click", this, function (a) {
                a.data.settings.enable && a.data.enqueueAll();
                return!1
            });
            this.removeFiles.bind("click", this, function (a) {
                a.data.settings.enable && a.data.clearQueue();
                return!1
            });
            if (this.hasAjaxUpload) {
                var b = "self" != a.dropArea && 0 < d(a.dropArea).length ? d(a.dropArea) : this.container, c = this;
                "self" == a.dropArea && this.mainWrapper.find(".ax-main-title").html(g("Select Files or Drag&Drop Files"));
                b.on("dranenter", function (a) {
                    a.stopPropagation();
                    a.preventDefault()
                }).on("dragover", function (b) {
                    b.stopPropagation();
                    b.preventDefault();
                    a.enable && (a.dropClass && d(this).addClass(a.dropClass), a.dropColor && d(this).css("background-color", a.dropColor))
                }).on("dragleave", function (b) {
                    b.stopPropagation();
                    b.preventDefault();
                    a.enable && (a.dropClass && d(this).removeClass(a.dropClass), a.dropColor && d(this).css("background-color", ""))
                }).on("drop", function (b) {
                    a.enable && (b.stopPropagation(), b.preventDefault(), c.addFiles(b.originalEvent.dataTransfer.files), a.dropClass && d(this).removeClass(a.dropClass), a.dropColor && d(this).css("background-color", ""))
                });
                d(document).unbind(".ax").bind("keyup.ax", function (a) {
                    27 == a.keyCode && d("#ax-box-shadow, #ax-box").fadeOut(500)
                })
            }
            this.enable(this.settings.enable)
        }, fileUploaded: function (a,
                b) {
            this.settings.success.call(a, b);
            for (var c = !0, e = 0; e < this.files.length; e++)
                1 != this.files[e].status && -1 != this.files[e].status && (c = !1);
            c && this.finish();
            this.settings.removeOnSuccess && this.removeFile(a.pos)
        }, finish: function () {
            this.globalStatus = 1;
            for (var a = [], b = 0; b < this.files.length; b++)
                a.push(this.files[b].name);
            "function" == typeof this.settings.finish && this.settings.finish.call(this, a, this.files);
            "function" == typeof this._onEnd && this._onEnd.call(this)
        }, addFiles: function (a) {
            for (var b = [], c = 0; c < a.length; c++) {
                var e,
                        d, f;
                this.hasAjaxUpload || this.hasFlash ? (d = a[c].name, f = a[c].size) : (d = a[c].value.replace(/^.*\\/, ""), f = 0);
                e = d.split(".").pop().toLowerCase();
                var g = this.checkFile(d, f);
                0 == g.length ? (h("Create file object"), e = new p(a[c], d, f, e, this), h(" file object created"), this.files.push(e), b.push(e)) : this.settings.error.call(this, g, d)
            }
            this.settings.onSelect.call(this, b);
            this.settings.autoStart && this.enqueueAll()
        }, checkFile: function (a, b) {
            var c = a.split(".").pop().toLowerCase(), e = !!(this.files.length < this.settings.maxFiles),
                    h = !!(0 <= d.inArray(c, this.settings.allowExt) || 0 == this.settings.allowExt.length), f = !!(b <= this.max_size), u = "function" === typeof this.settings.validateFile ? this.settings.validateFile.call(this, a, c, b) : "", m = [];
            e || m.push({message: g("Max files number reached"), error: "MAX_FILES", param: e});
            h || m.push({message: g("Extension not allowed"), error: "ALLOW_EXTENSION", param: c});
            f || m.push({message: g("File size now allowed"), error: "FILE_SIZE", param: b});
            u && m.push({message: u, error: "USER_ERROR", param: ""});
            return m
        }, enqueueFile: function (a) {
            this.uploadQueue.push(a);
            this.processQueue()
        }, enqueueAll: function () {
            var a = this.settings.beforeUploadAll.call(this, this.files), b = this.getPendingFiles();
            "function" == typeof this._onNoFiles && 0 == b.length && this._onNoFiles.call(this);
            if (!1 !== a) {
                "function" == typeof this._onStart && 0 < b.length && this._onStart.call(this, b);
                for (a = 0; a < b.length; a++)
                    this.uploadQueue.push(b[a]);
                this.processQueue()
            } else
                "function" == typeof this._onError && this._onError.call(this), h("beforeUploadAll return false")
        }, startUpload: function (a, b, c, e) {
            this._onStart = a;
            this._onEnd = b;
            this._onNoFiles = c;
            this._onUploading = e;
            this.isUploading() && "function" == typeof this._onUploading && this._onUploading.call(this);
            this.enqueueAll()
        }, processQueue: function () {
            var a = this;
            this.checkInterval || (this.checkInterval = setInterval(function () {
                if (0 == a.uploadQueue.length)
                    clearInterval(a.checkInterval), a.checkInterval = null;
                else {
                    a.globalStatus = 2;
                    for (var b = 0; b < a.uploadQueue.length; b++)
                        0 < a.slots && 4 == a.uploadQueue[b].status && (a.uploadQueue[b].startUpload(), a.uploadQueue.splice(b, 1), a.slots--)
                }
            },
                    200))
        }, getPendingFiles: function () {
            for (var a = [], b = this.files.length, c = 0; c < b; c++)
                4 != this.files[c].status && 0 != this.files[c].status && 3 != this.files[c].status || a.push(this.files[c]);
            return a
        }, isUploading: function () {
            return 2 == this.globalStatus
        }, hasFinish: function () {
            return 0 == this.getPendingFiles().length && 1 == this.globalStatus
        }, isIdle: function () {
            return 0 == this.globalStatus
        }, isIdleWithFiles: function () {
            return 0 == this.globalStatus && this.hasFiles()
        }, hasFiles: function () {
            return 0 != this.files.length
        }, onOverwallProgress: function (a) {
            this.total_bytes =
                    +a;
            "function" == typeof this.settings.onProgress && this.settings.onProgress.call(this, this.total_bytes)
        }, clearQueue: function () {
            for (; 0 < this.files.length; )
                this.removeFile(0)
        }, getParams: function (a, b, c) {
            var e = this.settings, d = "function" == typeof e.remotePath ? e.remotePath() : e.remotePath, f = [];
            f.push("ax-file-path=" + (c ? encodeURIComponent(d) : d));
            f.push("ax-allow-ext=" + (c ? encodeURIComponent(e.allowExt.join("|")) : e.allowExt.join("|")));
            f.push("ax-file-name=" + (c ? encodeURIComponent(a) : a));
            f.push("ax-max-file-size=" +
                    e.maxFileSize);
            f.push("ax-file-size=" + b);
            f.push("ax-thumbPostfix=" + (c ? encodeURIComponent(e.thumbPostfix) : e.thumbPostfix));
            f.push("ax-thumbPath=" + (c ? encodeURIComponent(e.thumbPath) : e.thumbPath));
            f.push("ax-thumbFormat=" + (c ? encodeURIComponent(e.thumbFormat) : e.thumbFormat));
            f.push("ax-thumbHeight=" + e.thumbHeight);
            f.push("ax-thumbWidth=" + e.thumbWidth);
            f.push("ax-random=" + 10001 * Math.random());
            (this.settings.checkFileExists || this.settings.overrideFile) && f.push("ax-override=1");
            a = "function" == typeof e.data ?
                    e.data.call(this) : e.data;
            if ("object" == typeof a)
                for (var g in a)
                    f.push(g + "=" + (c ? encodeURIComponent(a[g]) : a[g]));
            else if ("string" == typeof a && "" != a)
                for (c = a.split("&"), g = 0; g < c.length; g++)
                    f.push(c[g]);
            return f
        }, removeFile: function (a) {
            var b = this.files[a];
            b.stopUpload();
            b.li.remove();
            b.file = null;
            this.files.splice(a, 1);
            this.hasFlash && this.flashObj.removeFile(a);
            for (a = 0; a < this.files.length; a++)
                this.files[a].pos = a
        }, stopUpload: function () {
            for (var a = 0; a < this.files.lenght; a++)
                this.files[a].stopUpload()
        }, options: function (a,
                b) {
            if (b !== l && null !== b)
                this.settings[a] = b, "enable" == a && this.enable(b);
            else
                return this.settings[a]
        }, enable: function (a) {
            (this.settings.enable = a) ? this.container.removeClass("ax-disabled").find("input").attr("disabled", !1) : this.container.addClass("ax-disabled").find("input").attr("disabled", !0)
        }, fileSlice: function (a, b, c) {
            var d;
            if (window.File.prototype.slice)
                try {
                    return a.slice(), a.slice(b, c)
                } catch (g) {
                    return a.slice(b, c - b)
                }
            else if (d = window.File.prototype.webkitSlice || window.File.prototype.mozSlice)
                return d.call(a,
                        b, c);
            return null
        }};
    var A = {init: function (a) {
            return this.each(function () {
                var b = d(this), c = b.data("AU");
                c !== l && null !== c ? h("Uploader already attached on current div") : (b.html(""), c = new E(b, a), b.data({AU: c, au: c}))
            })
        }, clear: function () {
            return this.each(function () {
                d(this).data("AU").clearQueue()
            })
        }, start: function () {
            return this.each(function () {
                return d(this).data("AU").enqueueAll()
            })
        }, addFlash: function (a) {
            d(this).data("AU").addFiles(a)
        }, progressFlash: function (a, b, c) {
            d(this).data("AU").files[b].onProgress(a,
                    c)
        }, onFinishFlash: function (a, b) {
            var c = d(this).data("AU");
            c.uploading = !1;
            try {
                var e = jQuery.parseJSON(a);
                if (-1 == parseInt(e.status))
                    throw e.info;
                c.files[b].onFinishUpload(e.name, e.size, e.status, e.info)
            } catch (g) {
                c.files[b].onError(g)
            }
        }, getUrl: function (a, b) {
            return d(this).data("AU").settings.url
        }, getParams: function (a, b) {
            return d(this).data("AU").getParams(a, b, !0).join("&")
        }, getAllowedExt: function (a) {
            var b = d(this).data("AU").settings.allowExt;
            return!0 === a ? b : b.join("|")
        }, getMaxFileNum: function (a) {
            return d(this).data("AU").settings.maxFiles
        },
        checkFile: function (a, b) {
            return"" == d(this).data("AU").checkFile(a, b)
        }, checkEnable: function () {
            return d(this).data("AU").settings.enable
        }, getFiles: function () {
            return d(this).data("AU").files
        }, enable: function () {
            return this.each(function () {
                d(this).data("AU").enable(!0)
            })
        }, disable: function () {
            return this.each(function () {
                d(this).data("AU").enable(!1)
            })
        }, destroy: function () {
            return this.each(function () {
                var a = d(this);
                a.data("AU").clearQueue();
                a.removeData("AU").html("")
            })
        }, option: function (a, b) {
            return this.each(function () {
                return d(this).data("AU").options(a,
                        b)
            })
        }, debug: function (a) {
        }};
    d.fn.ajaxupload = function (a, b) {
        if (A[a])
            return A[a].apply(this, Array.prototype.slice.call(arguments, 1));
        if ("object" !== typeof a && a)
            d.error("Method " + a + " does not exist on jQuery.RealAjaxUploader");
        else
            return A.init.apply(this, arguments)
    };
    var p = p.extend({renderHtml: function () {
            this._super();
            this.settings.allowDelete && (this.deleteButt = d('<a title="Delete the file from server" class="ax-delete ax-button ax-disabled" />').appendTo(this.buttons).append('<span class="ax-delete-icon ax-icon"></span>'),
                    this.settings.bootstrap && this.deleteButt.addClass("btn btn-warning btn-small").find("span").addClass("icon-remove"))
        }, bindEvents: function () {
            this._super();
            this.settings.allowDelete && this.deleteButt.bind("click", this, function (a) {
                var b = a.data;
                a = new d.Deferred;
                a.done(function () {
                    b.deleteFile()
                });
                1 != b.status || d(this).hasClass("ax-disabled") || b.askUser(a, g("Delete uploaded file?"))
            })
        }, deleteFile: function () {
            if (this.settings.allowDelete) {
                var a = this.AU.getParams(this.name, this.size, !1);
                a.push("ax-delete-file=1");
                d.post(this.settings.url, a.join("&"));
                this.status = 4;
                this.deleteButt.addClass("ax-disabled")
            }
        }, onFinishUpload: function (a, b, c, d) {
            this._super(a, b, c, d);
            1 == this.status && this.settings.allowDelete && this.deleteButt.removeClass("ax-disabled")
        }}), p = p.extend({renderHtml: function () {
            this._super();
            this.AU.hasAjaxUpload && "jpg" == this.ext.toLowerCase() && this.settings.exifRead && (this.infoButton = d('<a title="File Exif data" class="ax-info ax-button" />').appendTo(this.buttons).append('<span class="ax-info-icon ax-icon"></span>'),
                    this.settings.bootstrap && this.infoButton.addClass("btn btn-primary btn-small").find("span").addClass("icon-info-sign"))
        }, bindEvents: function () {
            this._super();
            this.AU.hasAjaxUpload && "jpg" == this.ext.toLowerCase() && this.settings.exifRead && this.infoButton.bind("click", this, function (a) {
                a.data.settings.fileInfo.call(a.data, a.data.exifData)
            })
        }, postSelectFile: function () {
            if (this.settings.exifRead) {
                var a = new d.Deferred;
                this.readExif(a);
                a.done(function (a) {
                    "function" == typeof this.settings.onExifRead && this.settings.onExifRead.call(this,
                            a)
                });
                return a.promise()
            }
            return this._super()
        }, readExif: function (a) {
            this.settings.exifRead && (h("exif: start"), this.settings.exifWorker ? this.readWorkerMode(a) : this.readPluginMode(a))
        }, readWorkerMode: function (a) {
            var b = this, c = new Worker(this.settings.exifWorker);
            c.onmessage = function (c) {
                h("exif: done woker");
                b.exifData = c.data.exif;
                a.resolveWith(b, [c.data.exif])
            };
            c.onerror = function (c) {
                h(c);
                b.readPluginMode(a)
            };
            c.postMessage({blob: this.file})
        }, readPluginMode: function (a) {
            if (this.AU.hasAjaxUpload && d.fileExif &&
                    "jpg" == this.ext.toLowerCase()) {
                var b = this;
                d.fileExif(this.file, function (c) {
                    h("exif: done plugin");
                    b.exifData = c;
                    a.resolveWith(b, [c])
                })
            } else
                a.rejectWith(this, [g("Exif not activated")])
        }}), p = p.extend({afterInit: function () {
            this._super();
            this.AU.settings.md5WorkerPath && this.AU.settings.md5Calculate && "select" == this.AU.settings.md5CalculateOn && (this.createMd5Def(), this.calculateMd5())
        }, preProcessFile: function () {
            h("MD5 Preprocess");
            var a = this._super();
            return this.AU.settings.md5WorkerPath && this.AU.settings.md5Calculate &&
                    "upload" == this.AU.settings.md5CalculateOn ? (a.always(function () {
                        h("MD5 parent def done");
                        this.calculateMd5()
                    }), this.createMd5Def().promise()) : a
        }, createMd5Def: function () {
            h("MD5 Deferred");
            this.md5 = null;
            this.defMd5 = new d.Deferred;
            this.defMd5.done(function (a) {
                this.md5 = a;
                h("MD5 done: ", a);
                this.AU.settings.onMd5Calculate.call(this, a)
            }).fail(function (a) {
                h(a)
            }).progress(function (a) {
                this.workProgress(g("MD5 Calc ..."), a)
            }).always(function () {
                this.workProgress(g(Zikula.__('Ready', 'module_ztext_js')), 0);
                this.enableUpload("0%")
            });
            return this.defMd5
        },
        calculateMd5: function () {
            var a = this;
            if (this.settings.md5WorkerPath && this.settings.md5Calculate) {
                h("MD5 Start");
                this.disableUpload(g("Calculating md5..."));
                var b = new Worker(this.settings.md5WorkerPath);
                b.onmessage = function (b) {
                    b = b.data;
                    "progress" == b.status ? a.defMd5.notifyWith(a, [b.progress]) : "end" == b.status && (a.defMd5.resolveWith(a, [b.result]), this.terminate())
                };
                b.onerror = function (b) {
                    a.defMd5.rejectWith(a, [b.message]);
                    this.terminate()
                };
                b.postMessage({file: this.file})
            } else
                a.defMd5.rejectWith(a, [g("Md5 not activated")])
        }}),
            p = p.extend({preProcessFile: function () {
                    h("ImageScale Preprocess");
                    var a = this._super(), b = new d.Deferred;
                    a.always(function () {
                        this.readFileDef.done(function (a) {
                            h("File readed");
                            this.scaleBeforeUpload(a, b)
                        }).fail(function () {
                            b.resolveWith(this, [])
                        })
                    });
                    return b.promise()
                }, scaleBeforeUpload: function (a, b) {
                    var c = this.settings.resizeImage;
                    this.defResize = new d.Deferred;
                    this.defResize.done(function (b, d) {
                        c.removeExif || "undefined" === typeof ExifRestorer || (b = ExifRestorer.restore(a.src, b));
                        if (b) {
                            for (var e = atob(b.split(",")[1]),
                                    f = [], g = 0; g < e.length; g++)
                                f.push(e.charCodeAt(g));
                            this.file = new Blob([new Uint8Array(f)], {type: d})
                        }
                    }).progress(function (a) {
                        this.workProgress(g("Scaling ..."), a)
                    }).fail(function () {
                    }).always(function () {
                        this.enableUpload("0%");
                        b.resolveWith(this, [])
                    });
                    if (isNaN(c.maxWidth) || isNaN(c.maxHeight) || 0 >= c.maxWidth && 0 >= c.maxHeight)
                        this.defResize.rejectWith(this, []);
                    else {
                        this.disableUpload(g("Resizing image..."));
                        var e = Math.min((c.maxWidth || a.width) / a.width, (c.maxHeight || a.height) / a.height, 1), h = a.width * e, e = a.height *
                                e, f = "png" == this.ext ? "image/png" : "image/jpeg";
                        c.format && (f = "png" == c.format ? "image/png" : "image/jpeg", this.name = this.name.replace("." + this.ext, "." + c.format), this.ext = c.format);
                        "function" == typeof c.scaleMethod ? c.scaleMethod.call(this, a, h, e) : this.scaleCanvasWithAlgorithm(a, h, e, f, c.quality)
                    }
                }, scaleCanvasWithAlgorithm: function (a, b, c, d, g) {
                    a = this.canvasScale(a, b, c);
                    if (a.width > b || a.height > c) {
                        var f = document.createElement("canvas");
                        b /= a.width;
                        f.width = a.width * b;
                        f.height = a.height * b;
                        c = a.getContext("2d").getImageData(0,
                                0, a.width, a.height);
                        a = f.getContext("2d").createImageData(f.width, f.height);
                        var h = this.bilinearInterpolation(), m = this;
                        h.onmessage = function (a) {
                            a = a.data;
                            "end" == a.status ? (f.getContext("2d").putImageData(a.destImgData, 0, 0), m.defResize.resolveWith(m, [f.toDataURL(d, g), d]), this.terminate()) : "progress" == a.status && m.defResize.notifyWith(m, [a.progress])
                        };
                        h.onerror = function (a) {
                            m.defResize.rejectWith(m, [a])
                        };
                        h.postMessage({srcImgData: c, destImgData: a, scale: b})
                    } else
                        this.defResize.resolveWith(this, [a.toDataURL(d,
                                    g), d])
                }, bilinearInterpolation: function () {
                    return new G(function (a) {
                        function b(a, b, c, d, e, f) {
                            var g = 1 - e, h = 1 - f;
                            return a * g * h + b * e * h + c * g * f + d * e * f
                        }
                        var c = a.srcImgData, d = a.destImgData;
                        a = a.scale;
                        var g, f, h, m, l, k, n, q, p, t, w, x, v;
                        for (g = 0; g < d.height; ++g)
                            for (h = g / a, m = Math.floor(h), l = Math.ceil(h) > c.height - 1?c.height - 1:Math.ceil(h), f = 0; f < d.width; ++f)
                                k = f / a, n = Math.floor(k), q = Math.ceil(k) > c.width - 1 ? c.width - 1 : Math.ceil(k), p = 4 * (f + d.width * g), t = 4 * (n + c.width * m), w = 4 * (q + c.width * m), x = 4 * (n + c.width * l), q = 4 * (q + c.width * l), k -= n, n = h - m, v =
                                        b(c.data[t], c.data[w], c.data[x], c.data[q], k, n), d.data[p] = v, v = b(c.data[t + 1], c.data[w + 1], c.data[x + 1], c.data[q + 1], k, n), d.data[p + 1] = v, v = b(c.data[t + 2], c.data[w + 2], c.data[x + 2], c.data[q + 2], k, n), d.data[p + 2] = v, t = b(c.data[t + 3], c.data[w + 3], c.data[x + 3], c.data[q + 3], k, n), d.data[p + 3] = t;
                        postMessage({status: "end", destImgData: d, progress: 0})
                    })
                }}), G = function (a) {
        a = URL.createObjectURL(new Blob(["(", function (a) {
                self.addEventListener("message", function (c) {
                    a(c.data)
                }, !1)
            }.toString(), ")(", a.toString(), ")"], {type: "application/javascript"}));
        return new Worker(a)
    }
})(jQuery);