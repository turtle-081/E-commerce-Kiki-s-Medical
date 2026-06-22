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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_sidebar_menu"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_sidebar_menu"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    padding_box  = edit_element.find(".padding-box"),
                    padding       = edit_element.find('input[name="padding"]'),
                    padding_val   = padding.val(),
                    padding_array = [];

                if(typeof(padding_val) != "undefined" && padding_val.length){

                    var padding_array = padding_val.split(",");

                    padding_box.find("input[name=\"padding-top\"]").attr('value',padding_array[0]);
                    padding_box.find("input[name=\"padding-right\"]").attr('value',padding_array[1]);
                    padding_box.find("input[name=\"padding-bottom\"]").attr('value',padding_array[2]);
                    padding_box.find("input[name=\"padding-left\"]").attr('value',padding_array[3]);

                }

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_sidebar_menu"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_sidebar_menu"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_sidebar_menu"]');

                        var font_weight      = edit_element.find('select[name="font_weight"] option:selected').val(),
                            font_family      = edit_element.find('select[name="font_family"] option:selected').val(),
                            line_height      = edit_element.find('input[name="line_height"]').val(),
                            font_size        = edit_element.find('input[name="font_size"]').val(),
                            letter_spacing   = edit_element.find('input[name="letter_spacing"]').val(),
                            text_transform   = edit_element.find('select[name="text_transform"] option:selected').val(),
                            menu_color       = edit_element.find('input[name="menu_color"]').val(),
                            menu_color_hover = edit_element.find('input[name="menu_color_hover"]').val();

                        CSS += '#sidebar-menu-'+ID+' .menu-item dir-child* .mi-link {';

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
                            CSS += '#sidebar-menu-'+ID+' .menu-item dir-child* .mi-link .arrow svg {';
                                CSS += 'fill:'+menu_color+';';
                            CSS += '}';
                        }

                        if (menu_color_hover.length) {
                            CSS += '#sidebar-menu-'+ID+' .menu-item:hover dir-child* .mi-link {color:'+menu_color_hover+';}';
                            CSS += '#sidebar-menu-'+ID+' .menu-item:hover dir-child* .mi-link .arrow svg {';
                                CSS += 'fill:'+menu_color_hover+';';
                            CSS += '}';
                        }

                        /* Submenu
                        ---------------*/

                            var suboffset           = edit_element.find('input[name="suboffset"]').val(),
                                subfont_weight      = edit_element.find('select[name="subfont_weight"] option:selected').val(),
                                subfont_family      = edit_element.find('select[name="subfont_family"] option:selected').val(),
                                subfont_size        = edit_element.find('input[name="subfont_size"]').val(),
                                subline_height      = edit_element.find('input[name="subline_height"]').val(),
                                subletter_spacing   = edit_element.find('input[name="subletter_spacing"]').val(),
                                subtext_transform   = edit_element.find('select[name="subtext_transform"] option:selected').val();

                            if (suboffset.length) {
                                CSS += '#sidebar-menu-'+ID+' dir-child* .menu-item:not(.mm-true) > .sub-menu {';
                                    CSS += 'top:'+suboffset+';';
                                CSS += '}';
                            }

                            CSS += '#sidebar-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item .mi-link {';

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

                        /* Margin
                        ---------------*/

                            var padding_left   = edit_element.find(".padding-box input[name=\"padding-left\"]").val(),
                                padding_top    = edit_element.find(".padding-box input[name=\"padding-top\"]").val(),
                                padding_right  = edit_element.find(".padding-box input[name=\"padding-right\"]").val(),
                                padding_bottom = edit_element.find(".padding-box input[name=\"padding-bottom\"]").val();

                            padding_top = (padding_top.length) ? padding_top : '0';
                            padding_right = (padding_right.length) ? padding_right : '0';
                            padding_bottom = (padding_bottom.length) ? padding_bottom : '0';
                            padding_left = (padding_left.length) ? padding_left : '0';

                            var padding_output = padding_top+','+padding_right+','+padding_bottom+','+padding_left,
                                padding_value  = padding_top+'px '+padding_right+'px '+padding_bottom+'px '+padding_left+'px';

                            padding.val(padding_output);

                            CSS += '#sidebar-menu-container-'+ID+' {';
                                CSS += 'padding:'+padding_value+';';
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
