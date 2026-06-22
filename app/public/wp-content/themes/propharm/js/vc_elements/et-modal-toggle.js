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

    function hbeAlign(align,tag){
        var iframe = $('#vc_inline-frame');
        if (typeof(iframe) != 'undefined' && iframe != null){
            iframe.ready(function() {
                iframe.contents().find(".hbe").each(function(){
                    var $this = $(this);
                    var attr = $this.parent().attr('data-tag');
                    var hasAttribute = (typeof attr !== typeof undefined && attr !== false && attr == tag) ? true : false;
                    var CSS = '';

                    if (hasAttribute) {

                        if (align == "right") {
                            CSS = '.vc_element[data-model-id="'+$this.parent().attr('data-model-id')+'"] {float:right;}';
                            iframe.contents().find("#dynamic-styles-inline-css").append(CSS);
                            return;
                        }
                        if (align == "left") {
                            CSS = '.vc_element[data-model-id="'+$this.parent().attr('data-model-id')+'"] {float:left;}';
                            iframe.contents().find("#dynamic-styles-inline-css").append(CSS);
                            return;
                        }
                        if (align == "center" || align == "none") {
                            CSS = '.vc_element[data-model-id="'+$this.parent().attr('data-model-id')+'"] {float:none;}';
                            iframe.contents().find("#dynamic-styles-inline-css").append(CSS);
                            return;
                        }

                    }
                });
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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_modal_toggle"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_modal_toggle"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_modal_toggle"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_modal_toggle"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_modal_toggle"]');

                        /* Styling
                        ---------------*/

                            var align                        = edit_element.find('select[name="align"] option:selected').val(),
                                icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                icon_color_hover             = edit_element.find('input[name="icon_color_hover"]').val(),
                                icon_background_color        = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_background_color_hover  = edit_element.find('input[name="icon_background_color_hover"]').val();

                            if (icon_background_color.length) {
                                CSS += '#modal-toggle-'+ID+' .back {';
                                    CSS += 'fill:'+icon_background_color+';';
                                CSS += '}';
                                CSS += '#modal-toggle-'+ID+' svg {';
                                    CSS += '-webkit-filter: drop-shadow(0px 0px 16px rgba(0,0,0,0.08));';
                                    CSS += 'filter: drop-shadow(0px 0px 16px rgba(0,0,0,0.08));';
                                CSS += '}';
                            }

                            if (icon_color.length) {
                                CSS += '#modal-toggle-'+ID+' .line, #modal-toggle-'+ID+' .close {';
                                    CSS += 'fill:'+icon_color+';';
                                CSS += '}';
                            }

                            if (icon_background_color_hover.length) {
                                CSS += '#modal-toggle-'+ID+':hover .back, #modal-toggle-'+ID+'.active .back {';
                                    CSS += 'fill:'+icon_background_color_hover+';';
                                CSS += '}';
                            }

                            if (icon_color_hover.length) {
                                CSS += '#modal-toggle-'+ID+':hover .line, #modal-toggle-'+ID+':hover .close, #modal-toggle-'+ID+'.active .line, #modal-toggle-'+ID+'.active .close {';
                                    CSS += 'fill:'+icon_color_hover+';';
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

                            CSS += '#modal-container-toggle-'+ID+' {';
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

    });

})(jQuery);
