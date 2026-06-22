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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_header_mobile_container"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_mobile_container"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_mobile_container"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_mobile_container"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_mobile_container"]');

                        /* Styling
                        ---------------*/

                            var text_color                  = edit_element.find('input[name="text_color"]').val(),
                                background_color            = edit_element.find('input[name="background_color"]').val(),
                                tab_color                   = edit_element.find('input[name="tab_color"]').val(),
                                tab_color_active            = edit_element.find('input[name="tab_color_active"]').val(),
                                tab_background_color        = edit_element.find('input[name="tab_background_color"]').val(),
                                tab_background_color_active = edit_element.find('input[name="tab_background_color_active"]').val();

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

                            CSS += '#mobile-container-'+ID+' {';
                                if (text_color.length) {
                                    CSS += 'color:'+text_color+';';
                                }
                                if (background_color.length) {
                                    CSS += 'background-color:'+background_color+';';
                                }
                                CSS += 'padding:'+margin_value+';';
                            CSS += '}';

                            if (tab_color.length) {
                                CSS += '#mobile-container-'+ID+' .mobile-container-tab {';
                                    CSS += 'color:'+tab_color+';';
                                    if (tab_background_color.length) {
                                        CSS += 'background-color:'+tab_background_color+';';
                                    }
                                CSS += '}';

                                CSS += '#mobile-container-'+ID+' .mobile-container-tab svg {';
                                    CSS += 'fill:'+tab_color+';';
                                CSS += '}';

                                CSS += '#mobile-container-'+ID+' .mobile-container-tab:after {';
                                    CSS += 'background-color:'+tab_color+';';
                                CSS += '}';
                            }

                            CSS += '#mobile-container-'+ID+' .mobile-container-tab.active {';
                                if (tab_color_active.length) {
                                    CSS += 'color:'+tab_color_active+';';
                                }
                                if (tab_background_color_active.length) {
                                    CSS += 'background-color:'+tab_background_color_active+';';
                                }
                            CSS += '}';

                            if (tab_color_active.length) {
                                CSS += '#mobile-container-'+ID+' .mobile-container-tab.active svg {';
                                    CSS += 'fill:'+tab_color_active+';';
                                CSS += '}';
                            }

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