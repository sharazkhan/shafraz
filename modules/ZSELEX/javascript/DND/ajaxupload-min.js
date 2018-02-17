jQuery.noConflict();
(function(c) {
    function g(a) {
        return q ? q[t[a]] || a : a
    }
    var t = {
        "Add files": 0,
        "Start upload": 1,
        "Remove all": 2,
        Close: 3,
        "Select Files": 4,
        Preview: 5,
        "Remove file": 6,
        Bytes: 7,
        KB: 8,
        MB: 9,
        GB: 10,
        "Upload aborted": 11,
        "Upload all files": 12,
        "Select Files or Drag&Drop Files": 13,
        "File uploaded 100%": 14,
        "Max files number reached": 15,
        "Extension not allowed": 16,
        "File size now allowed": 17
    }, u = {
        it_IT: "Aggiungi file;Inizia caricamento;Rimuvi tutti;Chiudi;Seleziona;Anteprima;Rimuovi file;Bytes;KB;MB;GB;Interroto;Carica tutto;Seleziona o Trascina qui i file;File caricato 100%;Numero massimo di file superato;Estensione file non permessa;Dimensione file non permessa".split(";"),
        sq_AL: "Shto file;Fillo karikimin;Hiqi te gjith\u00eb;Mbyll;Zgjith filet;Miniatur\u00eb;Hiqe file-in;Bytes;KB;MB;GB;Karikimi u nd\u00ebrpre;Kariko t\u00eb gjith\u00eb;Zgjith ose Zvarrit dokumentat k\u00ebtu;File u karikua 100%".split(";"),
        nl_NL: "Bestanden toevoegen;Start uploaden;Verwijder alles;Sluiten;Selecteer bestanden;Voorbeeld;Verwijder bestand;Bytes;KB;MB;GB;Upload afgebroken;Upload alle bestanden;Selecteer bestanden of  Drag&Drop bestanden;Bestand ge\u00fcpload 100%".split(";"),
        de_DE: "Dateien hinzuf\u00fcgen;Hochladen;Alle entfernen;Schliessen;Dateien w\u00e4hlen;Vorschau;Datei entfernen;Bytes;KB;MB;GB;Upload abgebrochen;Alle hochgeladen;W\u00e4hlen Sie Dateien oder f\u00fcgen Sie sie mit Drag & Drop hinzu.;Upload 100%".split(";"),
        fr_FR: "Ajouter;Envoyer;Tout supprimer;Fermer;Parcourir;Visualiser;Supprimer fichier;Bytes;Ko;Mo;Go;Envoi annul\u00e9;Tout envoyer;Parcourir ou Glisser/D\u00e9poser;Fichier envoy\u00e9 100%".split(";")
    }, q = {}, f = function(a, b, d, e, k) {
        this.file = a;
        this.status = this.currentByte = 0;
        this.name = b;
        this.size = d;
        this.info = this.xhr = null;
        this.ext = e;
        this.pos = k.files.length;
        this.AU = k;
        e = k.settings;
        d = this.sizeFormat();
        this.li = c("<li />").appendTo(this.AU.fileList).attr("title", b);
        e.bootstrap && (this.li = c("<a />").appendTo(this.li));
        this.prevContainer = c('<a class="ax-prev-container" />').appendTo(this.li);
        this.prevImage = c('<img class="ax-preview" src="" alt="' + g("Preview") + '" />').appendTo(this.prevContainer);
        this.details = c('<div class="ax-details" />').appendTo(this.li);
        this.nameContainer = c('<div class="ax-file-name">' + b + "</div>").appendTo(this.details);
        this.sizeContainer = c('<div class="ax-file-size">' + d + "</div>").appendTo(this.details);
        this.progressInfo = c('<div class="ax-progress" />').appendTo(this.li);
        this.progressBar = c('<div class="ax-progress-bar" />').appendTo(this.progressInfo);
        this.progressPer = c('<div class="ax-progress-info">0%</div>').appendTo(this.progressInfo);
        this.buttons = c('<div class="ax-toolbar" />').appendTo(this.li);
        this.uploadButton = c('<a title="' + g("Start upload") + '" class="ax-upload ax-button" />').appendTo(this.buttons).append('<span class="ax-upload-icon ax-icon"></span>');
        this.removeButton = c('<a title="Remove file" class="ax-remove ax-button" />').appendTo(this.buttons).append('<span class="ax-clear-icon ax-icon"></span>');
        e.bootstrap && (this.li.addClass("media thumbnail"),
                this.prevContainer.addClass("pull-left"), this.prevImage.addClass("img-rounded media-object"), this.details.addClass("label label-info").css({
            "border-bottom-left-radius": 0
        }), this.progressInfo.addClass("progress progress-striped active").css({
            "margin-bottom": 0
        }), this.progressBar.addClass("bar"), this.buttons.addClass("label label-info").css({
            "border-top-left-radius": 0,
            "border-top-right-radius": 0
        }), this.uploadButton.addClass("btn btn-success btn-small").find("span").addClass("icon-play"), this.removeButton.addClass("btn btn-danger btn-small").find("span").addClass("icon-minus-sign"));
        if (k.hasHtml4) {
            d = this.AU.getParams(b, 0, !1);
            k = c('<form action="' + e.url + '" method="post" target="ax-main-frame" encType="multipart/form-data" />').hide().appendTo(this.li);
            k.append(a);
            k.append('<input type="hidden" value="' + b + '" name="ax-file-name" />');
            for (a = 0; a < d.length; a++)
                b = d[a].split("="), k.append('<input type="hidden" value="' + b[1] + '" name="' + b[0] + '" />');
            this.xhr = k
        }
        this.bindEvents();
        this.doPreview();
        e.hideUploadForm && (null !== this.AU.form && void 0 !== this.AU.form) && this.uploadButton.hide()
    };

    f.prototype.sizeFormat =
            function() {
                var a = this.size;
                "undefined" == typeof precision && (precision = 2);
                for (var b = [g("Bytes"), g("KB"), g("MB"), g("GB")], d = 0; 1024 <= a && d < b.length - 1; )
                    a /= 1024, d++;
                var e = Math.round(a), c = Math.pow(10, precision), a = Math.round(a * c % c);
                return e + "." + a + " " + b[d]
            };

    f.prototype.bindEvents = function() {
        this.uploadButton.bind("click", this, function(a) {
            a.data.AU.settings.enable && (2 != a.data.status ? a.data.startUpload(!1) : a.data.stopUpload())
        });
        this.removeButton.bind("click", this, function(a) {
            a.data.AU.settings.enable && a.data.AU.removeFile(a.data.pos)
        });
        this.AU.settings.editFilename && this.nameContainer.bind("dblclick", this, function(a) {
            if (a.data.AU.settings.enable) {
                a.stopPropagation();
                var b = a.data.ext;
                a = a.data.name.replace("." + b, "");
                c(this).html('<input type="text" value="' + a + '" />.' + b)
            }
        }).bind("blur focusout", this, function(a) {
            a.stopPropagation();
            var b = c(this).children("input").val();
            "undefined" != typeof b && (b = b.replace(/[|&;$%@"<>()+,]/g, "") + "." + a.data.ext, c(this).html(b), a.data.name = b, a.data.AU.hasAjaxUpload || a.data.xhr.children('input[name="ax-file-name"]').val(b))
        })
    };
    f.prototype.doPreview = function() {
        if (this.AU.settings.previews && this.AU.hasAjaxUpload && this.file.type.match(/image.*/) && ("jpg" == this.ext || "gif" == this.ext || "png" == this.ext) && "undefined" !== typeof FileReader) {
            var a = this.name, b = this;
            this.prevContainer.css("background", "none");
            var d = this.prevImage, e = new FileReader;
            e.onload = function(e) {
                d.css("cursor", "pointer").attr("src", e.target.result).click(function() {
                    var d = new Image;
                    d.onload = function() {
                        var d = Math.min(c(window).width() / this.width, (c(window).height() -
                                100) / this.height), h = 1 > d ? this.width * d : this.width, d = 1 > d ? this.height * d : this.height, g = c(window).scrollTop() - 20 + (c(window).height() - d) / 2, f = (c(window).width() - h) / 2, g = c("#ax-box").css({
                            top: g,
                            height: d,
                            width: h,
                            left: f
                        });
                        g.children("img").attr({
                            width: h,
                            height: d,
                            src: e.target.result
                        });
                        c("#ax-box-fn").find("span").html(a + " (" + b.sizeFormat() + ")");
                        g.fadeIn(500);
                        c("#ax-box-shadow").css("height", c(document).height()).show()
                    };

                    d.src = e.target.result;
                    c("#ax-box-shadow").css("z-index", 1E4);
                    c("#ax-box").css("z-index", 10001)
                })
            };
            e.readAsDataURL(this.file)
        } else
            this.prevContainer.addClass("ax-filetype-" + this.ext).children("img:first").remove()
    };

    f.prototype.startUpload = function(a) {
        this.AU.upload_all = a;
        var b = this.AU.settings.beforeUpload.call(this, this.name, this.file);
        b ? (this.progressBar.css("width", "0%"), this.progressPer.html("0%"), this.uploadButton.addClass("ax-abort"), this.status = 2, this.AU.hasAjaxUpload ? this.uploadAjax() : this.AU.hasFlash ? this.AU.uploading || (this.AU.uploading = !0, this.AU.flashObj.uploadFile(this.pos)) : this.uploadStandard(a)) :
                (this.status = -1, this.onError(Zikula.__('File validation failed', 'module_zselex_js')));
        return b
    };

    f.prototype.uploadAjax = function() {
        var a = this.AU.settings, b = this.file, d = this.currentByte, e = this.name, c = this.size, h = a.chunkSize, l = h + d, g = 0 >= c - l, m = b, f = 0 != h ? l / h : 1;
        this.xhr = new XMLHttpRequest;
        0 == d && this.AU.slots++;
        0 == h ? (m = b, g = !0) : b.mozSlice ? m = b.mozSlice(d, l) : b.webkitSlice ? m = b.webkitSlice(d, l) : b.slice ? m = b.slice(d, l) : (m = b, g = !0);
        var p = this;
        this.xhr.upload.addEventListener("abort", function(a) {
            p.AU.slots--
        }, !1);
        this.xhr.upload.addEventListener("progress",
                function(a) {
                    a.lengthComputable && (a = Math.round(100 * (a.loaded + f * h - h) / c), p.onProgress(a))
                }, !1);
        this.xhr.upload.addEventListener("error", function(a) {
            p.onError(this.responseText)
        }, !1);
        this.xhr.onreadystatechange = function() {
            if (4 == this.readyState && 200 == this.status)
                try {
                    var a = JSON.parse(this.responseText);
                    0 == d && (p.name = a.name, p.nameContainer.html(a.name));
                    if (-1 == parseInt(a.status))
                        throw a.info;
                    g ? (p.AU.slots--, p.onFinish(a.name, a.size, a.status, a.info)) : (p.currentByte = l, p.uploadAjax())
                } catch (b) {
                    p.AU.slots--,
                            p.onError(b)
                }
        };

        var b = void 0 !== window.FormData, n = navigator.userAgent.match(/Firefox\/(\d+)?/);
        if (null !== n && 6 >= (null !== n && void 0 !== n[1] && !isNaN(n[1]) ? parseFloat(n[1]) : 7))
            b = !1;
        if (null !== navigator.userAgent.match(/Opera\/(\d+)?/) && (n = navigator.userAgent.match(/Version\/(\d+)?/), 12.1 > (void 0 !== n[1] && !isNaN(n[1]) ? parseFloat(n[1]) : 0)))
            b = !1;
        e = this.AU.getParams(e, c, !b);
        e.push("ax-start-byte=" + d);
        e.push("ax-last-chunk=" + g);
        if (b) {
            b = new FormData;
            b.append("ax_file_input", m);
            for (m = 0; m < e.length; m++)
                n = e[m].split("="),
                        b.append(n[0], n[1]);
            this.xhr.open("POST", a.url, a.async);
            this.xhr.send(b)
        } else
            b = -1 == a.url.indexOf("?") ? "?" : "&", this.xhr.open("POST", a.url + b + e.join("&"), a.async), this.xhr.setRequestHeader("Cache-Control", "no-cache"), this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"), this.xhr.setRequestHeader("Content-Type", "application/octet-stream"), this.xhr.send(m)
    };

    f.prototype.uploadStandard = function(a) {
        this.progressBar.css("width", "50%");
        this.progressPer.html("50%");
        c("#ax-main-frame").unbind("load").bind("load",
                this, function(b) {
            var d = null;
            this.contentDocument ? d = this.contentDocument : this.contentWindow && (d = this.contentWindow.document);
            try {
                var e = c.parseJSON(d.body.innerHTML);
                b.data.onProgress(100);
                b.data.onFinish(e.name, e.size, e.status, e.info)
            } catch (k) {
                b.data.onError(d.body.innerHTML)
            }
            void 0 !== a && void 0 !== b.data.AU.files[b.data.pos + 1] && b.data.AU.files[b.data.pos + 1].startUpload(a)
        });
        this.xhr.submit()
    };

    f.prototype.stopUpload = function() {
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
        this.uploadButton.removeClass("ax-abort");
        this.status = this.currentByte = 0;
        this.progressBar.css("width", 0);
        this.progressPer.html(g(Zikula.__('Upload aborted', 'module_zselex_js')))
    };

    f.prototype.onError = function(a) {
        this.currentByte = 0;
        this.status = -1;
        this.info = a;
        this.progressPer.html(a);
        this.progressBar.css("width", "0%");
        this.uploadButton.removeClass("ax-abort");
        this.AU.settings.error.call(this, a, this.name);
        this.AU.settings.removeOnError && this.AU.removeFile(this.pos)
    };

    f.prototype.onFinish = function(a, b, d, e) {
        this.name = a;
        this.status = parseInt(d);
        this.info = e;
        !this.AU.hasAjaxUpload && !this.AU.hasFlash && (this.size = b, b = this.sizeFormat(), this.sizeContainer.html(b));
        this.currentByte = 0;
        this.nameContainer.html(a);
        this.li.attr("title", a);
        this.onProgress(100);
        this.uploadButton.removeClass("ax-abort");
        this.progressBar.width(0);
        this.progressPer.html(g(Zikula.__('File uploaded 100%', 'module_zselex_js')));
        this.AU.settings.success.call(this, a);
        a = !0;
        for (b = 0; b < this.AU.files.length; b++)
            1 != this.AU.files[b].status && -1 != this.AU.files[b].status && (a = !1);
        a && this.AU.finish();
        this.AU.settings.removeOnSuccess && this.AU.removeFile(this.pos)
    };

    f.prototype.onProgress = function(a) {
        this.progressBar.css("width", a + "%");
        this.progressPer.html(a + "%")
    };

    var s = function(a, b) {
        var d = document.createElement("input");
        d.type = "file";
        this.hasAjaxUpload = "multiple"in d && "undefined" != typeof File && "undefined" != typeof(new XMLHttpRequest).upload;
        this.hasFlash = !1;
        /Safari/.test(navigator.userAgent) && (/Apple Computer/.test(navigator.vendor) && /Version\/5\./.test(navigator.userAgent) && /Win/.test(navigator.platform)) && (this.hasAjaxUpload = !1);
        if (!this.hasAjaxUpload)
            try {
                new ActiveXObject("ShockwaveFlash.ShockwaveFlash") && (this.hasFlash = !0)
            } catch (e) {
                void 0 != navigator.mimeTypes["application/x-shockwave-flash"] && (this.hasFlash = !0)
            }
        this.hasHtml4 = !this.hasFlash && !this.hasAjaxUpload;
        this.$this = a;
        this.files = [];
        this.slots = 0;
        this.settings = b;
        this.fieldSet =
                c("<fieldset class='aj-frame'/>").append('<legend class="ax-legend">' + g(Zikula.__('Select Files', 'module_zselex_js')) + "</legend>").appendTo(a);
        this.flashObj = this.form_submit_event = this.form = null;
        this.uploading = this.upload_all = !1;
        this.max_size = b.maxFileSize;
        d = b.maxFileSize.slice(-1);
        if (isNaN(d))
            switch (this.max_size = this.max_size.replace(d, ""), d) {
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
        var k = "ax-browse-c ax-button", d = "ax-upload-all ax-button",
                h = "ax-clear ax-button", l = "ax-plus-icon ax-icon", f = "ax-upload-icon ax-icon", m = "ax-clear-icon ax-icon";
        b.bootstrap && (k += " btn btn-primary", d += " btn btn-success", h += " btn btn-danger", l += " icon-plus-sign", f += " icon-play", m += " icon-remove-sign");
        this.browse_c = c('<a class="' + k + '" title="' + g(Zikula.__('Add files', 'module_zselex_js')) + '" />').append('<span class="' + l + '"></span> <span>' + g(Zikula.__('Add files', 'module_zselex_js')) + "</span>").appendTo(this.fieldSet);
        this.browseFiles = c('<input type="file" class="ax-browse" name="ax_file_input" />').attr("multiple", this.hasAjaxUpload).appendTo(this.browse_c);
        b.uploadDir && this.browseFiles.attr({
            directory: "directory",
            webkitdirectory: "webkitdirectory",
            mozdirectory: "mozdirectory"
        });
        this.hasFlash && (this.browse_c.children(".ax-browse").remove(), k = a.attr("id") + "_flash", l = '\x3c!--[if !IE]> --\x3e<object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' + k + '" type="application/x-shockwave-flash" data="' + b.flash + '" width="150" height="100">\x3c!-- <![endif]--\x3e\x3c!--[if IE]><object style="position:absolute;width:150px;height:100px;left:0px;top:0px;z-index:1000;" id="' +
                k + '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="150" height="100"><param name="movie" value="' + b.flash + '" />\x3c!--\x3e\x3c!--dgx--\x3e<param name="flashvars" value="instance_id=' + a.attr("id") + '"><param name="allowScriptAccess" value="always" /><param value="transparent" name="wmode"></object>\x3c!-- <![endif]--\x3e', this.browse_c.append('<div style="position:absolute;overflow:hidden;width:150px;height:100px;left:0px;top:0px;z-index:0;">' +
                l + "</div>"), this.flashObj = document.getElementById(k));
        this.uploadFiles = c('<a class="' + d + '" title="' + g(Zikula.__('Upload all files', 'module_zselex_js')) + '" />').append('<span class="' + f + '"></span> <span>' + g(Zikula.__('Start upload', 'module_zselex_js')) + "</span>").appendTo(this.fieldSet);
        this.removeFiles = c('<a class="' + h + '" title="' + g(Zikula.__('Remove all', 'module_zselex_js')) + '" />').append('<span class="' + m + '"></span> <span>' + g(Zikula.__('Remove all', 'module_zselex_js')) + "</span>").appendTo(this.fieldSet);
        this.fileList = c('<ul class="ax-file-list" />').appendTo(this.fieldSet);
        b.bootstrap && this.fileList.addClass("media-list");
        b.onInit.call(this, this);
        this.bindEvents()
    };

    s.prototype = {
        bindEvents: function() {
            var a = this.settings;
            this.browseFiles.bind("change", this, function(a) {
                a = a.data;
                a.settings.enable && !a.hasFlash && (a.addFiles(a.hasAjaxUpload ? this.files : Array(this)), a.hasAjaxUpload ? this.value = "" : c(this).clone(!0).val("").appendTo(a.browse_c))
            });
            this.uploadFiles.bind("click", this, function(a) {
                a.data.settings.enable && a.data.uploadAll();
                return!1
            });
            this.removeFiles.bind("click", this, function(a) {
                a.data.settings.enable && a.data.clearQueue();
                return!1
            });
            0 < c(a.form).length ? this.form = c(a.form) : "parent" == a.form && (this.form = this.$this.parents("form:first"));
            if (null !== this.form && void 0 !== this.form) {
                a.hideUploadForm && this.uploadFiles.hide();
                var b = this.form.data("events");
                null !== b && void 0 !== b && (null !== b.submit && void 0 !== b.submit) && (this.form_submit_event = b.submit);
                this.form.unbind("submit");
                this.form.bind("submit.ax", this, function(a) {
                    if (0 < a.data.files.length)
                        return a.data.uploadAll(), !1
                })
            }
            if (this.hasAjaxUpload) {
                var b = "self" == a.dropArea ? this.$this[0] :
                        c(a.dropArea)[0], d = this;
                "self" == a.dropArea && this.fieldSet.find(".ax-legend").html(g(Zikula.__('Select Files or Drag&Drop Files', 'module_zselex_js')));
                b.addEventListener("dragenter", function(a) {
                    a.stopPropagation();
                    a.preventDefault()
                }, !1);
                b.addEventListener("dragover", function(b) {
                    b.stopPropagation();
                    b.preventDefault();
                    d.settings.enable && (a.dropClass ? c(this).addClass(a.dropClass) : this.style.backgroundColor = a.dropColor)
                }, !1);
                b.addEventListener("dragleave", function(b) {
                    b.stopPropagation();
                    b.preventDefault();
                    d.settings.enable && (a.dropClass ?
                            c(this).removeClass(a.dropClass) : this.style.backgroundColor = "")
                }, !1);
                b.addEventListener("drop", function(b) {
                    d.settings.enable && (b.stopPropagation(), b.preventDefault(), d.addFiles(b.dataTransfer.files), this.style.backgroundColor = "", a.autoStart && d.uploadAll())
                }, !1);
                c(document).unbind(".ax").bind("keyup.ax", function(a) {
                    27 == a.keyCode && c("#ax-box-shadow, #ax-box").fadeOut(500)
                })
            }
            this.enable(this.settings.enable)
        },
        finish: function() {
            for (var a = [], b = 0; b < this.files.length; b++)
                a.push(this.files[b].name);
            this.settings.finish.call(this,
                    a, this.files);
            this.settings.beforeSubmit.call(this, a, this.files, function() {
                if (null !== this.form && void 0 !== this.form) {
                    for (var b = "function" == typeof this.settings.remotePath ? this.settings.remotePath() : this.settings.remotePath, e = 0; e < a.length; e++)
                        this.form.append('<input name="ax-uploaded-files[]" type="hidden" value="' + (b + a[e]) + '" />');
                    this.form.unbind("submit.ax");
                    null !== this.form_submit_event && void 0 !== this.form_submit_event && this.form.bind("submit", this.form_submit_event);
                    b = this.form.find('[type="submit"]');
                    0 < b.length ? b.trigger("click") : this.form.submit()
                }
            })
        },
        addFiles: function(a) {
            for (var b = [], d = 0; d < a.length; d++) {
                var e, c, h;
                this.hasAjaxUpload || this.hasFlash ? (c = a[d].name, h = a[d].size) : (c = a[d].value.replace(/^.*\\/, ""), h = 0);
                e = c.split(".").pop().toLowerCase();
                var g = this.checkFile(c, h);
                "" == g ? (e = new f(a[d], c, h, e, this), this.files.push(e), b.push(e)) : this.settings.error.call(this, g, c)
            }
            this.settings.onSelect.call(this, b);
            this.settings.autoStart && this.uploadAll()
        },
        checkFile: function(a, b) {
            var d = a.split(".").pop().toLowerCase(),
                    e = !!(this.files.length < this.settings.maxFiles), k = !!(0 <= c.inArray(d, this.settings.allowExt) || 0 == this.settings.allowExt.length), h = !!(b <= this.max_size), l = this.settings.validateFile.call(this, a, d, b), f = "";
            e || (f = f + g(Zikula.__('Max files number reached', 'module_zselex_js')) /*+ ":" + e*/ + "\n");
            k || (f = f + g(Zikula.__('Extension not allowed', 'module_zselex_js')) + ":" + d + "\n");
            h || (f = f + g(Zikula.__('File size now allowed', 'module_zselex_js')) + ":" + b + "\n");
            "" != l && (void 0 !== l && null !== l) && (f += l);
            return f
        },
        uploadAll: function() {
            if (!1 !== this.settings.beforeUploadAll.call(this, this.files)) {
                for (var a = !1, b = !1, d = 0; d < this.files.length; d++)
                    0 ==
                            this.files[d].status && (a = !0, b || (b = this.files[d]));
                if (a)
                    if (this.hasAjaxUpload) {
                        var c = this;
                        setTimeout(function() {
                            for (var a = !0, b = 0; b < c.files.length; b++)
                                0 == c.files[b].status && (a = !1, c.slots <= c.settings.maxConnections && c.files[b].startUpload(!1));
                            a || c.uploadAll()
                        }, 300)
                    } else
                        b && b.startUpload(!0)
            }
        },
        clearQueue: function() {
            for (; 0 < this.files.length; )
                this.removeFile(0)
        },
        getParams: function(a, b, d) {
            var c = this.settings, g = "function" == typeof c.remotePath ? c.remotePath() : c.remotePath, h = [];
            h.push("ax-file-path=" + (d ? encodeURIComponent(g) :
                    g));
            h.push("ax-allow-ext=" + (d ? encodeURIComponent(c.allowExt.join("|")) : c.allowExt.join("|")));
            h.push("ax-file-name=" + (d ? encodeURIComponent(a) : a));
            h.push("ax-max-file-size=" + c.maxFileSize);
            h.push("ax-file-size=" + b);
            h.push("ax-thumbPostfix=" + (d ? encodeURIComponent(c.thumbPostfix) : c.thumbPostfix));
            h.push("ax-thumbPath=" + (d ? encodeURIComponent(c.thumbPath) : c.thumbPath));
            h.push("ax-thumbFormat=" + (d ? encodeURIComponent(c.thumbFormat) : c.thumbFormat));
            h.push("ax-thumbHeight=" + c.thumbHeight);
            h.push("ax-thumbWidth=" +
                    c.thumbWidth);
            a = "function" == typeof c.data ? c.data() : c.data;
            if ("object" == typeof a)
                for (var f in a)
                    h.push(f + "=" + (d ? encodeURIComponent(a[f]) : a[f]));
            else if ("string" == typeof a && "" != a) {
                d = a.split("&");
                for (f = 0; f < d.length; f++)
                    h.push(d[f])
            }
            return h
        },
        removeFile: function(a) {
            var b = this.files[a];
            b.stopUpload();
            b.li.remove();
            b.file = null;
            this.files.splice(a, 1);
            this.hasFlash && this.flashObj.removeFile(a);
            for (a = 0; a < this.files.length; a++)
                this.files[a].pos = a
        },
        stopUpload: function() {
            for (var a = 0; a < this.files.lenght; a++)
                this.files[a].stopUpload()
        },
        options: function(a, b) {
            if (void 0 !== b && null !== b)
                this.settings[a] = b, "enable" == a && this.enable(b);
            else
                return this.settings[a]
        },
        enable: function(a) {
            (this.settings.enable = a) ? this.$this.removeClass("ax-disabled").find("input").attr("disabled", !1) : this.$this.addClass("ax-disabled").find("input").attr("disabled", !0)
        }
    };

    var v = {
        remotePath: "uploads/",
        url: "upload.php",
        flash: "uploader.swf",
        data: "",
        async: !0,
        maxFiles: 9999,
        allowExt: [],
        success: function(a) {
        },
        finish: function(a, b) {
        },
        error: function(a, b) {
        },
        enable: !0,
        chunkSize: 1048576,
        maxConnections: 3,
        dropColor: "red",
        dropClass: "ax-drop",
        dropArea: "self",
        autoStart: !1,
        thumbHeight: 0,
        thumbWidth: 0,
        thumbPostfix: "_thumb",
        thumbPath: "",
        thumbFormat: "",
        maxFileSize: "10M",
        form: null,
        hideUploadForm: !0,
        beforeSubmit: function(a, b, c) {
            c.call(this)
        },
        editFilename: !1,
        beforeUpload: function(a, b) {
            return!0
        },
        beforeUploadAll: function(a) {
            return!0
        },
        onSelect: function(a) {
        },
        onInit: function(a) {
        },
        language: "auto",
        uploadDir: !1,
        removeOnSuccess: !1,
        removeOnError: !1,
        bootstrap: !1,
        previews: !0,
        validateFile: function(a, b, c) {
        }
    },
    r = {
        init: function(a) {
            return this.each(function() {
                var b = c.extend({}, v, a), d = c(this).html(""), e = d.data("AU");
                if (!(void 0 !== e && null !== e)) {
                    "auto" == b.language && (b.language = (window.navigator.userLanguage || window.navigator.language).replace("-", "_"));
                    q = u[b.language];
                    d.addClass("ax-uploader").data("author", "http://www.albanx.com/");
                    0 == c("#ax-main-frame").length && c('<iframe name="ax-main-frame" id="ax-main-frame" />').hide().appendTo("body");
                    0 == c("#ax-box").length && c('<div id="ax-box"><div id="ax-box-fn"><span></span></div><img /><a id="ax-box-close" title="' +
                            g("Close") + '"></a></div>').appendTo("body");
                    0 == c("#ax-box-shadow").length && c('<div id="ax-box-shadow"/>').appendTo("body");
                    c("#ax-box-close, #ax-box-shadow").click(function(a) {
                        a.preventDefault();
                        c("#ax-box").fadeOut(500);
                        c("#ax-box-shadow").hide()
                    });
                    b.bootstrap && c("#ax-box-close").addClass("btn btn-danger").html('<span class="ax-clear-icon ax-icon icon-remove-sign"></span>');
                    for (e = "AX_" + Math.floor(100001 * Math.random()); 0 < c("#" + e).length; )
                        e = "AX_" + Math.floor(100001 * Math.random());
                    this.id = this.id ? this.id :
                            e;
                    b.allowExt = c.map(b.allowExt, function(a, b) {
                        return a.toLowerCase()
                    });
                    d.data("AU", new s(d, b))
                }
            })
        },
        clear: function() {
            return this.each(function() {
                c(this).data("AU").clearQueue()
            })
        },
        start: function() {
            return this.each(function() {
                c(this).data("AU").uploadAll()
            })
        },
        addFlash: function(a) {
            c(this).data("AU").addFiles(a)
        },
        progressFlash: function(a, b) {
            c(this).data("AU").files[b].onProgress(a)
        },
        onFinishFlash: function(a, b) {
            var d = c(this).data("AU");
            d.uploading = !1;
            try {
                var e = jQuery.parseJSON(a);
                if (-1 == parseInt(e.status))
                    throw e.info;
                d.files[b].onFinish(e.name, e.size, e.status, e.info)
            } catch (f) {
                d.files[b].onError(f)
            }
            if (d.upload_all)
                for (e = !0; e; )
                    b++, void 0 !== d.files[b] && 0 == d.files[b].status ? (e = !1, d.files[b].startUpload(d.upload_all)) : e = void 0 !== d.files[b] && 0 != d.files[b].status ? !0 : !1
        },
        getUrl: function(a, b) {
            return c(this).data("AU").settings.url
        },
        getParams: function(a, b) {
            return c(this).data("AU").getParams(a, b, !0).join("&")
        },
        getAllowedExt: function(a) {
            var b = c(this).data("AU").settings.allowExt;
            return!0 === a ? b : b.join("|")
        },
        getMaxFileNum: function(a) {
            return c(this).data("AU").settings.maxFiles
        },
        checkFile: function(a, b) {
            return"" == c(this).data("AU").checkFile(a, b)
        },
        checkEnable: function() {
            return c(this).data("AU").settings.enable
        },
        getFiles: function() {
            return c(this).data("AU").files
        },
        enable: function() {
            return this.each(function() {
                c(this).data("AU").enable(!0)
            })
        },
        disable: function() {
            return this.each(function() {
                c(this).data("AU").enable(!1)
            })
        },
        destroy: function() {
            return this.each(function() {
                var a = c(this);
                a.data("AU").clearQueue();
                a.removeData("AU").html("")
            })
        },
        option: function(a, b) {
            return this.each(function() {
                return c(this).data("AU").options(a,
                        b)
            })
        },
        debug: function(a) {
        }
    };

    c.fn.ajaxupload = function(a, b) {
        if (r[a])
            return r[a].apply(this, Array.prototype.slice.call(arguments, 1));
        if ("object" === typeof a || !a)
            return r.init.apply(this, arguments);
        c.error("Method " + a + " does not exist on jQuery.AjaxUploader")
    }
})(jQuery);
