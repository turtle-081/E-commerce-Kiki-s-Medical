(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

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

    function hbeAlign(element,doc){
        var CSS = '';

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

            var elementExists = Object.keys(dataObj).some(function(key) {
                return dataObj[key] === "et_search_form";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_search_form"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_form"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_form"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_form"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_form"]');

                        /* Styling
                        ---------------*/

                            var align                        = edit_element.find('select[name="align"] option:selected').val(),
                                icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                icon_color_hover             = edit_element.find('input[name="icon_color_hover"]').val(),
                                icon_background_color        = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_background_color_hover  = edit_element.find('input[name="icon_background_color_hover"]').val(),
                                icon_border_color            = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_border_color_hover      = edit_element.find('input[name="icon_border_color_hover"]').val(),
                                search_width                 = edit_element.find('input[name="search_width"]').val(),
                                search_color                 = edit_element.find('input[name="search_color"]').val(),
                                search_background_color      = edit_element.find('input[name="search_background_color"]').val(),
                                search_border_color          = edit_element.find('input[name="search_border_color"]').val();

                            CSS += '#search-icon-'+ID+' svg {';

                                if (icon_color.length) {CSS += 'fill:'+icon_color+';';}

                            CSS += '}';

                            CSS += '#search-icon-'+ID+' {';

                                if (icon_color.length) {CSS += 'fill:'+icon_color+';';}

                                if (icon_background_color.length) {
                                    CSS += 'background-color:'+icon_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }

                                if (icon_border_color.length) {
                                    CSS += 'box-shadow:inset 0 0 0 1px '+icon_border_color+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }

                            CSS += '}';

                            CSS += 'input:hover + #search-icon-'+ID+' svg {';

                                if (icon_color_hover.length) {CSS += 'fill:'+icon_color_hover+';';}

                            CSS += '}';

                            CSS += 'input:hover + #search-icon-'+ID+' {';

                                if (icon_background_color_hover.length) {
                                    CSS += 'background-color:'+icon_background_color_hover+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }

                                if (icon_border_color_hover.length) {
                                    CSS += 'box-shadow:inset 0 0 0 1px '+icon_border_color_hover+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }

                            CSS += '}';

                            if (search_width.length) {
                                CSS += '#search-form-'+ID+' {';
                                    CSS += 'width:'+search_width+'px;';
                                CSS += '}';
                            }

                            CSS += '#search-form-'+ID+' input#s {';
                                if (search_width.length) {CSS += 'width:'+search_width+'px;';}
                                if (search_color.length) {CSS += 'color:'+search_color+';';}
                                if (search_background_color.length) {
                                    CSS += 'background-color:'+search_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                                if (search_border_color.length) {
                                    CSS += 'border-color:'+search_border_color+';';
                                } else {
                                    CSS += 'border:none;';
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

                            CSS += '#header-search-form-'+ID+' {';
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

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){

                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-search-form');
                        if (typeof(element) != 'undefined' && element != null) {
                            hbeAlign(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);
