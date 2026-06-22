(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function toggleBack(element,toggle,scrollElement){

        var $this  = jQuery(element),
            isOpen = false;

        toggle.on('click',function(){

            toggle.toggleClass('active');

            if (isOpen==false) {
                // Custom scroll bar
                setTimeout(function(){
                    var scroll = element.querySelector(scrollElement);
                    if (typeof scroll != "undefined" && scroll != null) {
                        SimpleScrollbar.initEl(scroll);
                    }
                },200);
                isOpen=true;
            } else {
                isOpen=false;
            }
        });
    }

    function iframeCSS(CSS){
        var iframe = $('#vc_inline-frame');
        if (typeof(iframe) != 'undefined' && iframe != null){
            iframe.ready(function() {
                CSS = CSS.replaceAll("dir-child*",">");
                iframe.contents().find("#dynamic-styles-inline-css").append(CSS);
            });
        }
    }

    function hbeAlign(element,doc){
        var CSS = '';

        element = $(element);

        if (element.hasClass('hbe-right')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:right;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-left')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:left;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-center') || element.hasClass('hbe-none')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:none;display:inline-block;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
    }

    function iframeSCRIPT(element){
        $(element).each(function(){

            var element = this,
                toggle  = $(element).find('.login-toggle');

            toggleBack(element,toggle,'.widget_reglog');
            
        });

    }

    $( document ).ajaxComplete(function( event, xhr, settings ) {

        if (settings['type'] != 'POST') {return;}

        /* Prepare settings
        /*-------------*/

            var data = decodeURIComponent(settings['data']);
                data = data.split("&");

                var dataObj = [{}];

                for (var i = 0; i < data.length; i++) {
                    var property = data[i].split("=");
                    var key      = (property[0]);
                    var value    = (property[1]);
                    dataObj[key] = value;
                }

            var elementExists = Object.keys(dataObj).some(function(key) {
                return dataObj[key] === "et_login_toggle";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_login_toggle"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_login_toggle"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    margin_box   = edit_element.find(".margin-box"),
                    margin       = edit_element.find('input[name="margin"]'),
                    margin_val   = margin.val(),
                    margin_array = [];

                if(typeof(margin_val) != "undefined" && margin_val.length){

                    var margin_array = margin_val.split(",");

                    margin_box.find("input[name=\"margin-top\"]").attr('value',margin_array[0]);
                    margin_box.find("input[name=\"margin-right\"]").attr('value',margin_array[1]);
                    margin_box.find("input[name=\"margin-bottom\"]").attr('value',margin_array[2]);
                    margin_box.find("input[name=\"margin-left\"]").attr('value',margin_array[3]);

                }

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_login_toggle"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_login_toggle"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_login_toggle"]');

                        /* Styling
                        ---------------*/

                            var align                               = edit_element.find('select[name="align"] option:selected').val(),
                                icon_color                          = edit_element.find('input[name="icon_color"]').val(),
                                icon_background_color               = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_border_color                   = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_border_width                   = edit_element.find('input[name="icon_border_width"]').val(),
                                login_color                         = edit_element.find('input[name="login_color"]').val(),
                                login_background_color              = edit_element.find('input[name="login_background_color"]').val(),
                                login_border_color                  = edit_element.find('input[name="login_border_color"]').val(),
                                login_button_color                  = edit_element.find('input[name="login_button_color"]').val(),
                                login_button_color_hover            = edit_element.find('input[name="login_button_color_hover"]').val(),
                                login_button_background_color       = edit_element.find('input[name="login_button_background_color"]').val(),
                                login_button_background_color_hover = edit_element.find('input[name="login_button_background_color_hover"]').val(),
                                login_button_border_width           = edit_element.find('input[name="login_button_border_width"]').val(),
                                login_button_border_color           = edit_element.find('input[name="login_button_border_color"]').val(),
                                login_button_border_color_hover     = edit_element.find('input[name="login_button_border_color_hover"]').val();

                            if (icon_color.length) {
                                CSS += '#login-toggle-'+ID+'  dir-child* svg {';
                                    CSS += 'fill:'+icon_color+';';
                                CSS += '}';
                            }

                            CSS += '#login-toggle-'+ID+' {';

                                if (icon_color.length) {CSS += 'color:'+icon_color+';';}
                                if (icon_background_color.length) {
                                    CSS += 'background-color:'+icon_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }

                                if (icon_border_width.length) {

                                    if (!icon_border_color.length) {
                                        icon_border_color = icon_color;
                                    }

                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }

                            CSS += '}';

                            if (login_color.length) {
                                CSS += '#login-box-'+ID+' {';
                                    CSS += 'color:'+login_color+';';
                                CSS += '}';

                                CSS += '#login-box-'+ID+' svg.close {';
                                    CSS += 'fill:'+login_color+';';
                                CSS += '}';
                            }

                            if (login_background_color.length) {
                                CSS += '#login-box-'+ID+' {';
                                    CSS += 'background:'+login_background_color+';';
                                CSS += '}';
                            }

                            if (login_color.length) {
                                CSS += '#header-login-'+ID+' .widget_reglog a {';
                                    CSS += 'color:'+login_color+';';
                                CSS += '}';
                            }

                            CSS += '#header-login-'+ID+' .widget_reglog .input {';
                                if (login_color.length) {CSS += 'color:'+login_color+';';}
                                if (login_background_color.length) {
                                    CSS += 'background-color:'+login_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                                if (login_border_color.length) {
                                    CSS += 'border-color:'+login_border_color+';';
                                }
                            CSS += '}';

                            CSS += '#header-login-'+ID+' .widget_reglog .button {';
                                if (login_button_color.length) {CSS += 'color:'+login_button_color+';';}
                                if (login_button_background_color.length) {
                                    CSS += 'background-color:'+login_button_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                                if (login_button_border_width.length) {

                                    if (!login_button_border_color.length) {
                                        login_button_border_color = login_color;
                                    }

                                    CSS += 'box-shadow:inset 0 0 0 '+login_button_border_width+'px '+login_button_border_color+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }
                            CSS += '}';

                            CSS += '#header-login-'+ID+' .widget_reglog .button:hover {';
                                if (login_button_color_hover.length) {CSS += 'color:'+login_button_color_hover+' !important;';}
                                if (login_button_background_color_hover.length) {
                                    CSS += 'background-color:'+login_button_background_color_hover+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                                if (login_button_border_width.length) {

                                    if (!login_button_border_color_hover.length) {
                                        login_button_border_color_hover = login_color;
                                    }

                                    CSS += 'box-shadow:inset 0 0 0 '+login_button_border_width+'px '+login_button_border_color_hover+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }
                            CSS += '}';

                        /* Margin
                        ---------------*/

                            var margin_left   = edit_element.find(".margin-box input[name=\"margin-left\"]").val(),
                                margin_top    = edit_element.find(".margin-box input[name=\"margin-top\"]").val(),
                                margin_right  = edit_element.find(".margin-box input[name=\"margin-right\"]").val(),
                                margin_bottom = edit_element.find(".margin-box input[name=\"margin-bottom\"]").val();

                            margin_top = (margin_top.length) ? margin_top : '0';
                            margin_right = (margin_right.length) ? margin_right : '0';
                            margin_bottom = (margin_bottom.length) ? margin_bottom : '0';
                            margin_left = (margin_left.length) ? margin_left : '0';

                            var margin_output = margin_top+','+margin_right+','+margin_bottom+','+margin_left,
                                margin_value  = margin_top+'px '+margin_right+'px '+margin_bottom+'px '+margin_left+'px';

                            margin.val(margin_output);

                            CSS += '#header-login-'+ID+' {';
                                CSS += 'margin:'+margin_value+';';
                            CSS += '}';

                        element_id.val(ID);

                        if (CSS) {
                            element_css.text(CSS);
                            iframeCSS(CSS);
                            CSS = '';
                        }

                        hbeAlign(align,dataObj['tag']);

                    }

                });

                return;
            }

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){

                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-login');

                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                            hbeAlign(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);
