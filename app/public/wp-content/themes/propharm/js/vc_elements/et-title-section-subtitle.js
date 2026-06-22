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

    function tseAlign(align,tag){
        var iframe = $('#vc_inline-frame');
        if (typeof(iframe) != 'undefined' && iframe != null){
            iframe.ready(function() {
                iframe.contents().find(".tse").each(function(){
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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_title_section_subtitle"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_title_section_subtitle"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    etp_subtitle = edit_element.find('input[name="etp_subtitle"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_title_section_subtitle"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_title_section_subtitle"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_title_section_subtitle"]');

                        var align            = edit_element.find('select[name="align"] option:selected').val(),
                            font_weight      = edit_element.find('select[name="font_weight"] option:selected').val(),
                            font_family      = edit_element.find('select[name="font_family"] option:selected').val(),
                            text_color       = edit_element.find('input[name="text_color"]').val(),
                            background_color = edit_element.find('input[name="background_color"]').val(),
                            font_size        = edit_element.find('input[name="font_size"]').val(),
                            letter_spacing   = edit_element.find('input[name="letter_spacing"]').val(),
                            line_height      = edit_element.find('input[name="line_height"]').val(),
                            text_transform   = edit_element.find('select[name="text_transform"] option:selected').val();

                        CSS += '#title-section-subtitle-'+ID+' {';

                            if (background_color.length) {
                                CSS += 'background-color:'+background_color+';padding:4px 16px 8px 16px;';
                            } else {
                                CSS += 'background-color:transparent;padding:0;';
                            }
                            if (text_color.length) {CSS += 'color:'+text_color+';';}
                            if (font_size.length) {CSS += 'font-size:'+font_size+'px;';}

                            if (font_weight.length && font_weight != "italic" && font_weight != "regular") {

                                if (isInArray(font_weight,font_weight_array)) {
                                    font_weight = font_weight.substring(0, 3);
                                    CSS += 'font-style:italic;';
                                }

                                CSS += 'font-weight:'+font_weight+';';
                            }

                            if (letter_spacing.length) {CSS += 'letter-spacing:'+letter_spacing+'px;';}
                            if (line_height.length) {CSS += 'line-height:'+line_height+'px;';}
                            if (text_transform.length) {CSS += 'text-transform:'+text_transform+';';}
                            if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}

                        CSS += '}';

                        element_id.val(ID);
                        etp_subtitle.val('etp-subtitle-replace-this');

                        if (CSS) {
                            element_css.text(CSS);
                            iframeCSS(CSS);
                            CSS = '';
                        }

                        tseAlign(align,dataObj['tag']);
                        
                    }
                    
                });

                return;
            }

    });

})(jQuery);