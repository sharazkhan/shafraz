
// Copyright Zikula Foundation 2010 - license GNU/LGPLv3 (or at your option, any later version).
Zikula.define("Users");
Zikula.Users.NewUser = {
    validatorHandlers: $H(),
    init: function() {
        Zikula.Users.NewUser.setup();
        var d = $(Zikula.Users.NewUser.fieldId.submit);
        d.disabled = true;
        d.addClassName("z-hide");
        var e = Form.getElements(Zikula.Users.NewUser.formId);
        for (var c = 0, a = e.length; c < a; c++) {
            if ((e[c].id != Zikula.Users.NewUser.fieldId.checkUser) && (e[c].id != Zikula.Users.NewUser.fieldId.submit)) {
                e[c].observe("click", Zikula.Users.NewUser.lastCheckExpired);
                e[c].observe("keypress", Zikula.Users.NewUser.lastCheckExpired)
            }
        }
        $(Zikula.Users.NewUser.fieldId.userName).observe("blur", function() {
            var f = $(Zikula.Users.NewUser.fieldId.userName);
            f.value = f.value.toLowerCase()
        });
        $(Zikula.Users.NewUser.fieldId.email).observe("blur", function() {
            var f = $(Zikula.Users.NewUser.fieldId.email);
            f.value = f.value.toLowerCase()
        });
        var b = $(Zikula.Users.NewUser.fieldId.checkUser);
        b.removeClassName("z-hide");
        b.observe("click", Zikula.Users.NewUser.callGetRegistrationErrors)
    },
    lastCheckExpired: function() {
        var b = $(Zikula.Users.NewUser.fieldId.submit);
        var a = $(Zikula.Users.NewUser.fieldId.checkUser);
        var c = $(Zikula.Users.NewUser.fieldId.checkMessage);
        var d = $(Zikula.Users.NewUser.fieldId.validMessage);
        if (c.hasClassName("z-hide")) {
            c.removeClassName("z-hide")
        }
        if (!d.hasClassName("z-hide")) {
            d.addClassName("z-hide")
        }
        if (!b.hasClassName("z-hide")) {
            b.addClassName("z-hide")
        }
        b.disabled = true;
        if (a.hasClassName("z-hide")) {
            a.removeClassName("z-hide")
        }
        a.disabled = false
    },
    showAjaxInProgress: function() {
        var c = $(Zikula.Users.NewUser.fieldId.submit);
        var b = $(Zikula.Users.NewUser.fieldId.checkUser);
        var a = $(Zikula.Users.NewUser.formId + "_ajax_indicator");
        var d = $(Zikula.Users.NewUser.fieldId.checkMessage);
        var e = $(Zikula.Users.NewUser.fieldId.validMessage);
        if (a.hasClassName("z-hide")) {
            a.removeClassName("z-hide")
        }
        if (d.hasClassName("z-hide")) {
            d.removeClassName("z-hide")
        }
        if (!d.hasClassName("z-invisible")) {
            d.addClassName("z-invisible")
        }
        if (!e.hasClassName("z-hide")) {
            e.addClassName("z-hide")
        }
        if (!c.hasClassName("z-hide")) {
            c.addClassName("z-hide")
        }
        c.disabled = true;
        if (!b.hasClassName("z-hide")) {
            b.addClassName("z-hide")
        }
        b.disabled = true
    },
    showAjaxComplete: function(a) {
        var d = $(Zikula.Users.NewUser.fieldId.submit);
        var c = $(Zikula.Users.NewUser.fieldId.checkUser);
        var b = $(Zikula.Users.NewUser.formId + "_ajax_indicator");
        var e = $(Zikula.Users.NewUser.fieldId.checkMessage);
        var f = $(Zikula.Users.NewUser.fieldId.validMessage);
        if (!b.hasClassName("z-hide")) {
            b.addClassName("z-hide")
        }
        if (a) {
            if (e.hasClassName("z-invisible")) {
                e.removeClassName("z-invisible")
            }
            if (e.hasClassName("z-hide")) {
                e.removeClassName("z-hide")
            }
            if (!f.hasClassName("z-hide")) {
                f.addClassName("z-hide")
            }
            if (!d.hasClassName("z-hide")) {
                d.addClassName("z-hide")
            }
            d.disabled = true;
            if (c.hasClassName("z-hide")) {
                c.removeClassName("z-hide")
            }
            c.disabled = false
        } else {
            if (e.hasClassName("z-invisible")) {
                e.removeClassName("z-invisible")
            }
            if (!e.hasClassName("z-hide")) {
                e.addClassName("z-hide")
            }
            if (f.hasClassName("z-hide")) {
                f.removeClassName("z-hide")
            }
            if (d.hasClassName("z-hide")) {
                d.removeClassName("z-hide")
            }
            d.disabled = false;
            if (!c.hasClassName("z-hide")) {
                c.addClassName("z-hide")
            }
            c.disabled = true
        }
    },
    addValidatorHandler: function(b, a) {
        Zikula.Users.NewUser.validatorHandlers.set(b, a)
    },
    callGetRegistrationErrors: function() {
        Zikula.Users.NewUser.showAjaxInProgress();
        var a = $(Zikula.Users.NewUser.formId).serialize(true);
        new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=ZSELEX&type=ajaxuser&func=getRegistrationErrors", {
            parameters: a,
            onComplete: Zikula.Users.NewUser.getRegistrationErrorsResponse
        })
    },
    getRegistrationErrorsResponse: function(c) {
        if (!c.isSuccess()) {
            Zikula.Users.NewUser.showAjaxComplete(true);
            Zikula.showajaxerror(c.getMessage());
            return
        }
        var e = c.getData();
        if (!e) {
            Zikula.Users.NewUser.showAjaxComplete(true);
            return
        }
        $$("div#z-maincontent>div.z-errormsg").each(function(g) {
            if (!g.hasClassName("z-hide")) {
                g.addClassName("z-hide")
            }
        });
        $(Zikula.Users.NewUser.formId).getElements().each(function(h, g) {
            h.removeClassName("z-form-error")
        });
        $$("#" + Zikula.Users.NewUser.formId + " .z-errormsg").each(function(h, g) {
            h.update();
            if (!h.hasClassName("z-hide")) {
                h.addClassName("z-hide")
            }
        });
        if (e.errorMessagesCount > 0) {
            var f = $A(e.errorMessages);
            var d = $(Zikula.Users.NewUser.formId + "_errormsgs");
            d.update();
            f.each(function(h, g) {
                if (g > 0) {
                    d.insert("<hr />")
                }
                d.insert(h)
            });
            d.removeClassName("z-hide")
        }
        if (e.errorFieldsCount > 0) {
            var b = $H(e.errorFields);
            b.each(function(h) {
                var g = $(Zikula.Users.NewUser.formId + "_" + h.key);
                if (g) {
                    g.addClassName("z-form-error")
                }
                g = $(Zikula.Users.NewUser.formId + "_" + h.key + "_error");
                g.update(h.value);
                g.removeClassName("z-hide")
            })
        }
        if (e.validatorErrorsCount > 0) {
            var a = $H(e.validatorErrors);
            a.each(function(h) {
                var g = Zikula.Users.NewUser.validatorHandlers.get(h.key);
                if (g) {
                    var i = $H(h.value);
                    g(i.get("errorFieldsCount"), $H(i.get("errorFields")))
                }
            })
        }
        if ((e.errorMessagesCount > 0) || (e.errorFieldsCount > 0) || (e.validatorErrorsCount > 0)) {
            Zikula.Users.NewUser.showAjaxComplete(true);
            Zikula.Users.NewUser.lastCheckExpired();
            location.hash = Zikula.Users.NewUser.formId + "_errormsgs"
        } else {
            Zikula.Users.NewUser.showAjaxComplete(false)
        }
    }
};
document.observe("dom:loaded", Zikula.Users.NewUser.init);