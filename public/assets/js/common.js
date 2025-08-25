$(document).ready(function () {
    $.ajaxSetup({ cache: false });
    
    // active left menu
    if (location.pathname == "/admin/dashboard") {
        $("._dash").addClass('active');
    } else {
       var _link_path = $('nav a[href^="' + location + '"]');
        if(_link_path.length == 0) {
            $('a[href="'+location.pathname+'"]').parent('li').addClass('active');
        } else {
            _link_path.addClass('active');
            if (_link_path.parents().hasClass("collapse")) {
                _link_path.parents('.inner_menu').find(".dropdown-toggle").click();
                
            }
        }
    }


    $('.list-unstyled  li a').click(function (e) {
        var $this = $(this);
        // if the current path is like this link, make it active
        if ($this.attr('href').indexOf(current) !== -1) {
            $this.css("color", "#f65365")
        }
    });

    $('body').on('click', '.hii', function () {
        toastr.error('errors messages');
        appAlert.error("500: Internal Server Error.");
    });
    $('body').on('click', '[data-act=ajax-modal]', function () {

        var data = { ajaxModal: 1 },
            url = $(this).attr('data-action-url'),
            isLargeModal = $(this).attr('data-modal-lg'),
            isFullscreenModal = $(this).attr('data-modal-fullscreen'),
            title = $(this).attr('data-title');

        if (!url) {
            console.log('Ajax Modal: Set data-action-url!');
            return false;
        }
        if (title) {
            $("#ajaxModalTitle").html(title);
        } else {
            $("#ajaxModalTitle").html($("#ajaxModalTitle").attr('data-title'));
        }

        if ($(this).attr("data-post-hide-header")) {
            $("#ajaxModal .modal-header").addClass("hide");
            $("#ajaxModal .modal-footer").addClass("hide");
        } else {
            $("#ajaxModal .modal-header").removeClass("hide");
            $("#ajaxModal .modal-footer").removeClass("hide");
        }

        $("#ajaxModalContent").html($("#ajaxModalOriginalContent").html());
        $("#ajaxModalContent").find(".original-modal-body").removeClass("original-modal-body").addClass("modal-body");
        $("#ajaxModal").modal('show');
        $("#ajaxModal").find(".modal-dialog").removeClass("custom-modal-lg");
        $("#ajaxModal").find(".modal-dialog").removeClass("modal-full");


        
        $(this).each(function () {
            $.each(this.attributes, function () {
                if (this.specified && this.name.match("^data-post-")) {
                    var dataName = this.name.replace("data-post-", "");
                    data[dataName] = this.value;
                }
            });
        });
        ajaxModalXhr = $.ajax({
            url: url,
            data: data,
            cache: false,
            type: 'POST',
            success: function (response) {
                // console.log("isLargeModal => " , isLargeModal); 
                // console.log("response => " , response); 
                $("#ajaxModal").find(".modal-dialog").removeClass("mini-modal");
                if (isLargeModal === "1") {
                    $("#ajaxModal").find(".modal-dialog").addClass("custom-modal-lg");
                } else if (isFullscreenModal === "1") {
                    $("#ajaxModal").find(".modal-dialog").addClass("modal-full");
                } else {
                    $("#ajaxModal").find(".modal-dialog").addClass("modal-full");
                }
                $("#ajaxModalContent").html(response);

                //setSummernoteToAll(true);
                setModalScrollbar();

                //feather.replace();
            },
            statusCode: {
                404: function () {
                    $("#ajaxModalContent").find('.modal-body').html("");
                    appAlert.error("404: Page not found.", { container: '.modal-body', animate: false });
                }
            },
            error: function () {
                $("#ajaxModalContent").find('.modal-body').html("");
                appAlert.error("500: Internal Server Error.", { container: '.modal-body', animate: false });
            }
        });
        return false;
    });

    //----- Start Inner modal box
    $('body').on('click', '[data-act=ajax-modal-inner]', function () {

        var data = { ajaxModal: 1 },
            url = $(this).attr('data-action-url'),
            isLargeModal = $(this).attr('data-modal-lg'),
            isXLModal = $(this).attr('data-modal-xl'),
            isFullscreenModal = $(this).attr('data-modal-fullscreen'),
            title = $(this).attr('data-title');

        if (!url) {
            console.log('Ajax Modal: Set data-action-url!');
            return false;
        }
        if (title) {
            $("#ajaxModalTitle_inner").html(title);
        } else {
            $("#ajaxModalTitle_inner").html($("#ajaxModalTitle_inner").attr('data-title'));
        }

        if ($(this).attr("data-post-hide-header")) {
            $("#ajaxModal_inner .modal-header").addClass("hide");
            $("#ajaxModal_inner .modal-footer").addClass("hide");
        } else {
            $("#ajaxModal_inner .modal-header").removeClass("hide");
            $("#ajaxModal_inner .modal-footer").removeClass("hide");
        }

        $("#ajaxModalContent_inner").html($("#ajaxModalOriginalContent_inner").html());
        $("#ajaxModalContent_inner").find(".original-modal-body").removeClass("original-modal-body").addClass("modal-body");
        $("#ajaxModal_inner").modal('show');
        $("#ajaxModal_inner").find(".modal-dialog").removeClass("custom-modal-lg");
        $("#ajaxModal_inner").find(".modal-dialog").removeClass("modal-full");


        
        $(this).each(function () {
            $.each(this.attributes, function () {
                if (this.specified && this.name.match("^data-post-")) {
                    var dataName = this.name.replace("data-post-", "");
                    data[dataName] = this.value;
                }
            });
        });
        ajaxModalXhr = $.ajax({
            url: url,
            data: data,
            cache: false,
            type: 'POST',
            success: function (response) {
                // console.log("isLargeModal => " , isLargeModal); 
                // console.log("response => " , response); 
                $("#ajaxModal_inner").find(".modal-dialog").removeClass("mini-modal");
                if (isLargeModal === "1") {
                    $("#ajaxModal_inner").find(".modal-dialog").addClass("custom-modal-lg");
                } else if (isXLModal === "1") {
                    $("#ajaxModal_inner").find(".modal-dialog").addClass("modal-xl");
                } else if (isFullscreenModal === "1") {
                    $("#ajaxModal_inner").find(".modal-dialog").addClass("modal-full");
                } else {
                    $("#ajaxModal_inner").find(".modal-dialog").addClass("modal-xl");
                }
                $("#ajaxModalContent_inner").html(response);

                //setSummernoteToAll(true);
                setModalScrollbar();

                //feather.replace();
            },
            statusCode: {
                404: function () {
                    $("#ajaxModalContent_inner").find('.modal-body').html("");
                    appAlert.error("404: Page not found.", { container: '.modal-body', animate: false });
                }
            },
            error: function () {
                $("#ajaxModalContent_inner").find('.modal-body').html("");
                appAlert.error("500: Internal Server Error.", { container: '.modal-body', animate: false });
            }
        });
        return false;
    });
    //----- End Inner modal box


    //apply scrollbar on modal
    setModalScrollbar = function () {
        var $scroll = $("<th> Sr. No.</th>Content").find(".modal-body"),
            height = $scroll.height(),
            maxHeight = $(window).height() - 200;

        //if (isMobile()) {
        //    //show full screen in mobile devices
        //    maxHeight = $(window).height() - 123;
        //}

        if (height > maxHeight) {
            height = maxHeight;
            initScrollbar($scroll, { setHeight: height });
        }
    };

});



// appAlert
(function (define) {
    define(['jquery'], function ($) {
        return (function () {

            var appAlert = {
                info: info,
                success: success,
                warning: warning,
                error: error,
                options: {
                    container: "body", // append alert on the selector
                    duration: 5000, // don't close automatically,
                    showProgressBar: true, // duration must be set
                    clearAll: true, //clear all previous alerts
                    animate: true //show animation
                }
            };

            return appAlert;

            function info(message, options) {
                this._settings = _prepear_settings(options);
                this._settings.alertType = "info";
                _show(message);
                return "#" + this._settings.alertId;
            }

            function success(message, options) {
                this._settings = _prepear_settings(options);
                this._settings.alertType = "success";
                _show(message);
                return "#" + this._settings.alertId;
            }

            function warning(message, options) {
                this._settings = _prepear_settings(options);
                this._settings.alertType = "warning";
                _show(message);
                return "#" + this._settings.alertId;
            }

            function error(message, options) {
                this._settings = _prepear_settings(options);
                this._settings.alertType = "error";
                _show(message);
                return "#" + this._settings.alertId;
            }

            function _template(message) {
                var className = "info";
                if (this._settings.alertType === "error") {
                    className = "danger";
                } else if (this._settings.alertType === "success") {
                    className = "success";
                } else if (this._settings.alertType === "warning") {
                    className = "warning";
                }

                if (this._settings.animate) {
                    className += " animate";
                }

                return '<div id="' + this._settings.alertId + '" class="app-alert alert alert-' + className + ' alert-dismissible " role="alert">' +
                    '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '<div class="app-alert-message">' + message + '</div>' +
                    '<div class="progress">' +
                    '<div class="progress-bar bg-' + className + ' hide" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%">' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }

            function _prepear_settings(options) {
                if (!options)
                    var options = {};
                options.alertId = "app-alert-" + _randomId();
                return this._settings = $.extend({}, appAlert.options, options);
            }

            function _randomId() {
                var id = "";
                var keys = "abcdefghijklmnopqrstuvwxyz0123456789";
                for (var i = 0; i < 5; i++)
                    id += keys.charAt(Math.floor(Math.random() * keys.length));
                return id;
            }

            function _clear() {
                if (this._settings.clearAll) {
                    $("[role='alert']").remove();
                }
            }

            function _show(message) {
                _clear();
                var container = $(this._settings.container);
                if (container.length) {
                    if (this._settings.animate) {
                        //show animation
                        setTimeout(function () {
                            $(".app-alert").animate({
                                opacity: 1,
                                right: "40px"
                            }, 500, function () {
                                $(".app-alert").animate({
                                    right: "15px"
                                }, 300);
                            });
                        }, 20);
                    }
                    $(this._settings.container).prepend(_template(message));
                    _progressBarHandler();
                } else {
                    console.log("appAlert: container must be an html selector!");
                }
            }

            function _progressBarHandler() {
                if (this._settings.duration && this._settings.showProgressBar) {
                    var alertId = "#" + this._settings.alertId;
                    var $progressBar = $(alertId).find('.progress-bar');

                    $progressBar.removeClass('hide').width(0);
                    var css = "width " + this._settings.duration + "ms ease";
                    $progressBar.css({
                        WebkitTransition: css,
                        MozTransition: css,
                        MsTransition: css,
                        OTransition: css,
                        transition: css
                    });

                    setTimeout(function () {
                        if ($(alertId).length > 0) {
                            $(alertId).remove();
                        }
                    }, this._settings.duration);
                }
            }
        })();
    });
}(function (d, f) {
    window['appAlert'] = f(window['jQuery']);
}));

/**
 * submit any form using this common method
 * @param string form_id is the id of form (example : form_id="#formid")
 * @param object validation_rules, for validation 
 * @param bool datatable_reload, reload datatable after form submit, set true to reload
 * @param bool datataVar, reload datatable after form submit, set true to reload
 * @author Sunil Joshi
 */
function formSubmitCommon(form_id, validation_rules, datatableVar, datatable_reload) {
    $(form_id).validate({
        rules: validation_rules,
        errorClass: "help-block error",
        highlight: function (e) {
            $(e).closest(".form-group.row").addClass("has-error");
        },
        unhighlight: function (e) {
            $(e).closest(".form-group.row").removeClass("has-error");
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function (data) {
                    result = $.parseJSON(data);

                    $('#ajaxModal').modal('toggle');
                    if (result.status == 200) {

                        if (result.message) {
                            toastr.success(result.message);
                        }
                        if (datatable_reload) {
                            var info = datatableVar.page.info();
                            datatableVar.ajax.reload(null, false);
                            //  designationDatatable.page(info.page+1);
                        }
                        if (result.url) {
                            window.location.href = result.url;
                        }

                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    toastr.error(
                        "We are facing some issue. Refresh the page and try again. If issue still persist, contact tech support."
                    );
                },
            });
        }
    });
}

function mandatory(form_id) {
    var i = 0;
    $(".mandatory").each(function () {
        $("._custom_req").remove();
    });
    $(form_id).find(".mandatory").each(function () {
        var field = $(form_id).find(this).val();
        if (field == "" || field == undefined || field.trim() == "") {
            $(form_id).find(this).after("<span class='text-danger _custom_req'>This field is required</span>");
            i = 1;
            console.log(field);
        }
    });
    // console.log(i);
    if (i) {
        return false;
    } else {
        return true;
    }
}

$(document).on("keyup", ".mandatory", function () {
    $(this).next("._custom_req").remove();
});

$(document).on("change", ".mandatory", function () {
    $(this).next("._custom_req").remove();
});

$(function () {
    var current = location.pathname;
    $('.list-unstyled li a').click(function () {
        var masterhref = $(this).attr("href");
        if (masterhref == "#master") {
            //$(this).collapse()
        }

    });
    $('.active li a').each(function () {
        var $this = $(this).parent().parent().attr("id");
        if ($this == "master") {
            $("#master").collapse();
        }

    });

    //$('select[multiple]').multiselect({
    //    columns: 1,
    //    search: true
    //});

});


//================================================
$(document).ready(function () {

    $.fn.appForm = function (options) {
        var defaults = {
            ajaxSubmit: true,
            isModal: true,
            closeModalOnSuccess: true,
            dataType: "json",
            showLoader: true,
            onModalClose: function () { },
            onSuccess: function () { },
            onError: function () {
                return true;
            },
            onSubmit: function () { },
            onAjaxSuccess: function () { },
            beforeAjaxSubmit: function (data, self, options) { }
        };

        var settings = $.extend({}, defaults, options);
        
        this.each(function () {
            
            if (settings.ajaxSubmit) {
                

                validateForm($(this), function (form) {
                    settings.onSubmit();

                    if (settings.isModal) {
                        maskModal($("#ajaxModalContent").find(".modal-body"));
                    } else {
                        $(form).find('[type="submit"]').attr('disabled', 'disabled');
                    }


                    //set empty value to all textarea, if they are empty
                    //if (AppHelper.settings.enableRichTextEditor === "1") {
                    //    $("textarea").each(function () {
                    //        var $instance = $(this);
                    //        if ($instance.attr("data-rich-text-editor")) {
                    //            if ($instance.val() === '<p><br></p>' || $instance.val() === "") {
                    //                $instance.val('');
                    //            } else {
                    //                $instance.val($instance.summernote('code'));
                    //            }
                    //        }
                    //    });
                    //}

                    $(form).ajaxSubmit({

                        dataType: settings.dataType,
                        beforeSubmit: function (data, self, options) {

                            //Modified \assets\js\jquery-validation\jquery.form.js #1178.
                            //Added data  a.push({name: n, value: v, type: el.type, required: el.required, data: $(el).data()});

                            //to set the convertDateFormat with the input fields, we used the setDatePicker function.
                            //it is the easiest way to regognize the date fields.

                            $.each(data, function (index, obj) {
                                if (obj.data && obj.data.convertDateFormat && obj.value) {
                                    data[index]["value"] = convertDateToYMD(obj.value);
                                }
                            });

                            if (!settings.isModal && settings.showLoader) {
                                appLoader.show({ container: form, css: "top:2%; right:46%;" });
                            }


                            settings.beforeAjaxSubmit(data, self, options);
                        },
                        success: function (result) {
                            settings.onAjaxSuccess(result);
                            
                            // _closeModal = 1;
                            // if(result.is_close_modal != undefined && result.is_close_modal == 0)
                            //     _closeModal = 0;
                            
                            if (result.success) {
                                settings.onSuccess(result);
                                if(result.message) {
                                    appAlert.success(result.message);
                                }
                                if (settings.isModal && settings.closeModalOnSuccess) {
                                // if(_closeModal == 1) {
                                    closeAjaxModal(true);
                                }

                                //remove summernote from all existing summernote field
                                if (!settings.isModal) {
                                    $(form).find("textarea").each(function () {
                                        if ($(this).attr("data-rich-text-editor") && !$(this).attr("data-keep-rich-text-editor-after-submit")) {
                                            $(this).summernote('destroy');
                                        }
                                    });
                                }

                                appLoader.hide();
                            } else {

                                if (settings.onError(result)) {
                                    if (settings.isModal) {
                                        unmaskModal();
                                        if (result.message) {
                                            // appAlert.error(result.message, { container: '.modal-body' }); // animate: false
                                            appAlert.error(result.message);
                                        }
                                    } else if (result.message) {
                                        appAlert.error(result.message);
                                    }
                                }
                            }

                            $(form).find('[type="submit"]').removeAttr('disabled');
                        }
                    });
                });
            } else {
                validateForm($(this));
            }
        });

    };


    function validateForm(form, customSubmit) {
        //add custom method
        $.validator.addMethod("greaterThanOrEqual",
            function (value, element, params) {
                var paramsVal = params;
                if (params && (params.indexOf("#") === 0 || params.indexOf(".") === 0)) {
                    paramsVal = $(params).val();
                }

                if (typeof $(element).attr("data-rule-required") === 'undefined' && !value) {
                    return true;
                }

                if (!/Invalid|NaN/.test(new Date(convertDateToYMD(value)))) {
                    return !paramsVal || (new Date(convertDateToYMD(value)) >= new Date(convertDateToYMD(paramsVal)));
                }
                return isNaN(value) && isNaN(paramsVal) ||
                    (Number(value) >= Number(paramsVal));
            }, 'Must be greater than {0}.');

        //add custom method
        $.validator.addMethod("greaterThan",
            function (value, element, params) {
                var paramsVal = params;
                if (params && (params.indexOf("#") === 0 || params.indexOf(".") === 0)) {
                    paramsVal = $(params).val();
                }
                if (!/Invalid|NaN/.test(new Number(value))) {
                    return new Number((value)) > new Number((paramsVal));
                }
                return isNaN(value) && isNaN(paramsVal) ||
                    (Number(value) > Number(paramsVal));
            }, 'Must be greater than.');

        //add custom method
        $.validator.addMethod("mustBeSameYear",
            function (value, element, params) {
                var paramsVal = params;
                if (params && (params.indexOf("#") === 0 || params.indexOf(".") === 0)) {
                    paramsVal = $(params).val();
                }
                if (!/Invalid|NaN/.test(new Date(convertDateToYMD(value)))) {
                    var dateA = new Date(convertDateToYMD(value)),
                        dateB = new Date(convertDateToYMD(paramsVal));
                    return (dateA && dateB && dateA.getFullYear() === dateB.getFullYear());
                }
            }, 'The year must be same for both dates.');

        $.validator.addMethod("email",
            function (value, element, params) {
                if (value) {
                    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if (re.test(value)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }, 'Please enter a valid email address.');

        $.validator.addMethod("mobile",
            function (value, element, params) {
                if (value) {
                    var filter = /^\d*(?:\.\d{1,2})?$/;
                    if (filter.test(value)) {
                        if (value.length == 10) {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            }, 'Please enter a valid mobile number.');

        $.validator.addMethod("url",
            function (value, element, params) {
                if (value) {
                    var filter = /^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/gm;
                    if (filter.test(value)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }, 'Please enter a valid url.');

        $(form).validate({
            submitHandler: function (form) {
                if (customSubmit) {
                    customSubmit(form);
                } else {
                    return true;
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            ignore: ":hidden:not(.validate-hidden)",
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
        //handeling the hidden field validation like select2
        $(".validate-hidden").click(function () {
            $(this).closest('.form-group').removeClass('has-error').find(".help-block").hide();
        });
    }



    //show loadig mask on modal before form submission;
    function maskModal($maskTarget) {
        var padding = $maskTarget.height() - 80;
        if (padding > 0) {
            padding = Math.floor(padding / 2);
        }

        $maskTarget.after("<div class='modal-mask'><div class='circle-loader'></div></div>");
        //check scrollbar
        var height = $maskTarget.outerHeight();
        $('.modal-mask').css({ "width": $maskTarget.width() + 22 + "px", "height": height + "px", "padding-top": padding + "px" });
        $maskTarget.closest('.modal-dialog').find('[type="submit"]').attr('disabled', 'disabled');
        $maskTarget.addClass("hide");
    }

    //remove loadig mask from modal
    function unmaskModal() {
        var $maskTarget = $(".modal-body").removeClass("hide");
        $maskTarget.closest('.modal-dialog').find('[type="submit"]').removeAttr('disabled');
        $maskTarget.removeClass("hide");
        $(".modal-mask").remove();
    }

    //colse ajax modal and show success check mark
    function closeAjaxModal(success) {
        if (success) {
            $(".modal-mask").html("<div class='circle-done'><i data-feather='check' stroke-width='5'></i></div>");
            setTimeout(function () {
                $(".modal-mask").find('.circle-done').addClass('ok');
            }, 30);
        }
        setTimeout(function () {
            $(".modal-mask").remove();
            // $("#ajaxModal").modal('toggle');
            $('#ajaxModal').modal('hide');
            //settings.onModalClose();
        }, 1000);
    }



});



(function (define) {
    define(['jquery'], function ($) {
        return (function () {
            var appLoader = {
                show: show,
                hide: hide,
                options: {
                    container: 'body',
                    zIndex: "auto",
                    css: "",
                }
            };

            return appLoader;

            function show(options) {
                var $template = $("#app-loader");
                this._settings = _prepear_settings(options);
                if (!$template.length) {
                    var $container = $(this._settings.container);
                    if ($container.length) {
                        $container.append('<div id="app-loader" class="app-loader" style="z-index:' + this._settings.zIndex + ';' + this._settings.css + '"><div class="loading"></div></div>');
                    } else {
                        console.log("appLoader: container must be an html selector!");
                    }

                }
            }

            function hide() {
                var $template = $("#app-loader");
                if ($template.length) {
                    $template.remove();
                }
            }

            function _prepear_settings(options) {
                if (!options)
                    var options = {};
                return this._settings = $.extend({}, appLoader.options, options);
            }
        })();
    });
}(function (d, f) {
    window['appLoader'] = f(window['jQuery']);
}));



$(document).ready(function () {
    $(document).on("keypress keyup blur", ".numberOnly", function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(".emailOnly").on("blur", function () {
        $(this).next("span").remove();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test($(this).val())) {
            $('<span class="help-block error">Please enter a valid email address</span>').insertAfter(this);
        }
    });

    $(document).on("keypress keyup blur", ".decimal", function (e) {
        var character = String.fromCharCode(e.keyCode);
        var newValue = this.value + character;
        if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
            e.preventDefault();
            return false;
        }
    });

    function hasDecimalPlace(value, x) {
        var pointIndex = value.indexOf('.');
        return pointIndex >= 0 && pointIndex < value.length - x;
    }
});



// data table row
function initDataTable(_table_id, _ajax_url, _filters, _search) {
    subAdminDatatable = $("#" + _table_id).DataTable({
        "columnDefs": [
            { "orderable": false, "targets": 'no-sort' } // Disable sorting on columns with class 'no-sort'
        ],
        processing: true,
        serverSide: true,
        scrollX: 400,
        deferRender: true,
        scroller: true,
        ajax: {
            url: _ajax_url,
            type: 'POST',
            data: function (d) {
                _filters.forEach(function (item) {
                    if ($('#' + item.id).val()) {
                        d[item.field] = $('#' + item.id).val();
                    }
                });
            },
            columns: _search
        },
    });

}