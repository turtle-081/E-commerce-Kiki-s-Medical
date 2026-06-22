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
                return dataObj[key] === "et_product_search";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_product_search"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_product_search"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_product_search"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_product_search"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_product_search"]');

                        /* Styling
                        ---------------*/

                            var align                          = edit_element.find('select[name="align"] option:selected').val(),
                                button_text_color              = edit_element.find('input[name="button_text_color"]').val(),
                                button_text_color_hover        = edit_element.find('input[name="button_text_color_hover"]').val(),
                                button_color                   = edit_element.find('input[name="button_color"]').val(),
                                button_color_hover             = edit_element.find('input[name="button_color_hover"]').val(),
                                search_width                   = edit_element.find('input[name="search_width"]').val(),
                                search_color                   = edit_element.find('input[name="search_color"]').val(),
                                search_background_color        = edit_element.find('input[name="search_background_color"]').val(),
                                search_border_color            = edit_element.find('input[name="search_border_color"]').val(),
                                search_border_color_focus      = edit_element.find('input[name="search_border_color_focus"]').val();

                            CSS += '#header-product-search-'+ID+' .et-button + .input-after:after {';
                                if (button_text_color.length) {CSS += 'background:'+button_text_color+';';}
                            CSS += '}';

                            CSS += '#header-product-search-'+ID+' .et-button:hover + .input-after:after {';
                                if (button_text_color_hover.length) {CSS += 'background:'+button_text_color_hover+' !important;';}
                            CSS += '}';

                            CSS += '#header-product-search-'+ID+' .et-button + .input-after {';
                                if (button_color.length) {CSS += 'background-color:'+button_color+';';}
                            CSS += '}';

                            CSS += '#header-product-search-'+ID+' .et-button:hover + .input-after {';
                                if (button_color_hover.length) {CSS += 'background-color:'+button_color_hover+' !important;';}
                            CSS += '}';

                            CSS += '#header-product-search-'+ID+' {';
                                if (search_width.length) {
                                    CSS += 'width:'+search_width+'px;';
                                }
                                
                            CSS += '}';

                            CSS += '#header-product-search-'+ID+' .product-search {';
                                if (search_border_color.length) {
                                    CSS += 'border-color:'+search_border_color+';';
                                } else {
                                    CSS += 'border:none;';
                                }
                                if (search_background_color.length) {
                                    CSS += 'background-color:'+search_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                            CSS += '}';

                            if (search_border_color.length) {
                                CSS += '#header-product-search-'+ID+' .product-search .search-wrapper:before {';
                                    CSS += 'background-color:'+search_border_color+';';
                                CSS += '}';
                            }

                            if (search_border_color_focus.length) {
                                CSS += '#header-product-search-'+ID+' .product-search.focus {';
                                    CSS += 'border-color:'+search_border_color_focus+';';
                                CSS += '}';
                            }

                            CSS += '#header-product-search-'+ID+' .search, #header-product-search-'+ID+' select {';
                                if (search_color.length) {CSS += 'color:'+search_color+';';}
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

                            CSS += '#header-product-search-'+ID+' {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-product-search');
                        if (typeof(element) != 'undefined' && element != null) {
                            hbeAlign(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);
