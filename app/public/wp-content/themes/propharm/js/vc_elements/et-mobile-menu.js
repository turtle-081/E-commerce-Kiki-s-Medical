(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    function isInArray(value, array) {return array.indexOf(value) > -1;}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
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

    var font_weight_array = [];

    for (var i = 1; i <= 9; i++) {
        font_weight_array.push(i+'00italic');
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

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_mobile_menu"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_menu"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    margin_box  = edit_element.find(".margin-box"),
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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_menu"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_menu"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_menu"]');

                        var font_weight      = edit_element.find('select[name="font_weight"] option:selected').val(),
                            font_family      = edit_element.find('select[name="font_family"] option:selected').val(),
                            line_height      = edit_element.find('input[name="line_height"]').val(),
                            font_size        = edit_element.find('input[name="font_size"]').val(),
                            letter_spacing   = edit_element.find('input[name="letter_spacing"]').val(),
                            text_transform   = edit_element.find('select[name="text_transform"] option:selected').val(),
                            menu_color       = edit_element.find('input[name="menu_color"]').val(),
                            menu_color_hover = edit_element.find('input[name="menu_color_hover"]').val(),
                            separator_color  = edit_element.find('input[name="separator_color"]').val();

                        CSS += '#mobile-menu-'+ID+' .menu-item dir-child* a {';

                            if (menu_color.length) {CSS += 'color:'+menu_color+';';}
                            if (font_size.length) {CSS += 'font-size:'+font_size+'px;';}
                            if (line_height.length) {CSS += 'line-height:'+line_height+'px;';}

                            if (font_weight.length && font_weight != "italic" && font_weight != "regular") {

                                if (isInArray(font_weight,font_weight_array)) {
                                    font_weight = font_weight.substring(0, 3);
                                    CSS += 'font-style:italic;';
                                }

                                CSS += 'font-weight:'+font_weight+';';
                            }

                            if (letter_spacing.length) {CSS += 'letter-spacing:'+letter_spacing+'px;';}
                            if (text_transform.length) {CSS += 'text-transform:'+text_transform+';';}
                            if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}

                        CSS += '}';

                        if (menu_color.length) {
                            CSS += '#mobile-menu-'+ID+' .menu-item dir-child* a .arrow svg {';
                                CSS += 'fill:'+menu_color+';';
                            CSS += '}';
                            CSS += '#mobile-menu-'+ID+' .menu-item dir-child* a .menu-icon {';
                                CSS += 'background:'+menu_color+';';
                            CSS += '}';
                        }

                        if (menu_color_hover.length) {
                            CSS += '#mobile-menu-'+ID+' .menu-item.isolate dir-child* a {color:'+menu_color_hover+';}';
                            CSS += '#mobile-menu-'+ID+' .menu-item.isolate dir-child* a .arrow svg {';
                                CSS += 'fill:'+menu_color_hover+';';
                            CSS += '}';
                            CSS += '#mobile-menu-'+ID+' .menu-item.isolate dir-child* a .menu-icon {';
                                CSS += 'background:'+menu_color_hover+';';
                            CSS += '}';
                        }

                        if (separator_color.length) {
                            CSS += '#mobile-menu-'+ID+' ul {border-color:'+separator_color+';}';
                        }

                        /* Submenu
                        ---------------*/

                            var subfont_weight           = edit_element.find('select[name="subfont_weight"] option:selected').val(),
                                subfont_family           = edit_element.find('select[name="subfont_family"] option:selected').val(),
                                subfont_size             = edit_element.find('input[name="subfont_size"]').val(),
                                subline_height           = edit_element.find('input[name="subline_height"]').val(),
                                subletter_spacing        = edit_element.find('input[name="subletter_spacing"]').val(),
                                subtext_transform        = edit_element.find('select[name="subtext_transform"] option:selected').val(),
                                submenu_background_color = edit_element.find('input[name="submenu_background_color"]').val(),
                                submenu_color            = edit_element.find('input[name="submenu_color"]').val(),
                                submenu_color_hover      = edit_element.find('input[name="submenu_color_hover"]').val();

                            if (submenu_background_color.length) {
                                CSS += '#mobile-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu {';
                                        CSS += 'margin:8px 16px 0 16px;';
                                        CSS += 'padding: 16px 8px;';
                                        CSS += 'border-radius: 10px;';
                                        CSS += 'background: '+submenu_background_color+';';
                                CSS += '}';
                            }

                            CSS += '#mobile-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item a {';

                                if (submenu_color.length) {CSS += 'color:'+submenu_color+';';}
                                if (subfont_size.length) {CSS += 'font-size:'+subfont_size+'px;';}
                                if (subline_height.length) {CSS += 'line-height:'+subline_height+'px;';}

                                if (subfont_weight.length && subfont_weight != "italic" && subfont_weight != "regular") {

                                    if (isInArray(subfont_weight,font_weight_array)) {
                                        subfont_weight = subfont_weight.substring(0, 3);
                                        CSS += 'font-style:italic;';
                                    }

                                    CSS += 'font-weight:'+subfont_weight+';';
                                }

                                if (subletter_spacing.length) {CSS += 'letter-spacing:'+subletter_spacing+'px;';}
                                if (subtext_transform.length) {CSS += 'text-transform:'+subtext_transform+';';}
                                if (subfont_family.length && subfont_family != "Theme default") {CSS += 'font-family:\''+subfont_family+'\';';}

                            CSS += '}';

                            if (submenu_color_hover.length) {
                                CSS += '#mobile-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item:hover dir-child* a {';
                                    CSS += 'color:'+submenu_color_hover;
                                CSS += '}';
                            }

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

                            CSS += '#mobile-menu-container-'+ID+' {';
                                CSS += 'margin:'+margin_value+';';
                            CSS += '}';

                        element_id.val(ID);

                        if (CSS) {
                            element_css.text(CSS);
                            iframeCSS(CSS);
                            CSS = '';
                        }

                    }

                });

                return;
            }

    });

})(jQuery);
