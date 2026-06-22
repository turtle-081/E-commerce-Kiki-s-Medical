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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_social_links"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_social_links"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_social_links"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_social_links"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_social_links"]');

                        /* Styling
                        ---------------*/

                            var icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                icon_color_hover             = edit_element.find('input[name="icon_color_hover"]').val(),
                                icon_background_color        = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_background_color_hover  = edit_element.find('input[name="icon_background_color_hover"]').val(),
                                icon_border_color            = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_border_color_hover      = edit_element.find('input[name="icon_border_color_hover"]').val(),
                                icon_border_width            = edit_element.find('input[name="icon_border_width"]').val(),
                                styling_original             = edit_element.find('select[name="styling_original"] option:selected').val(),
                                shadow                       = edit_element.find('input[name="shadow"]');

                            if (styling_original != "true") {

                                CSS += '#et-social-links-'+ID+' svg {';
                                    if (icon_color.length) {
                                        CSS += 'fill:'+icon_color+';';
                                    }
                                CSS += '}';

                                CSS += '#et-social-links-'+ID+' a {';

                                    if (icon_background_color.length) {
                                        CSS += 'background-color:'+icon_background_color+';';
                                    } else {
                                        CSS += 'background-color:transparent;';
                                    }

                                    if (icon_border_width.length) {

                                        if (!icon_border_color.length) {
                                            icon_border_color = icon_color;
                                        }

                                        CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color+';margin-right:4px !important;';
                                    }

                                    if ((icon_border_width.length && icon_border_color.length) || icon_background_color.length) {
                                        CSS += 'margin-right:8px !important;';
                                    } else {
                                        CSS += 'margin-right:0;';
                                    }

                                CSS += '}';

                                if (icon_color_hover.length) {
                                    CSS += '#et-social-links-'+ID+' a:hover svg {';
                                        CSS += 'fill:'+icon_color_hover+';';
                                    CSS += '}';
                                }

                                CSS += '#et-social-links-'+ID+' a:hover {';

                                    if (icon_background_color_hover.length) {
                                        CSS += 'background-color:'+icon_background_color_hover+';';
                                    } else {
                                        CSS += 'background-color:transparent;';
                                    }

                                    if (icon_border_width.length) {

                                        if (!icon_border_color_hover.length) {
                                            icon_border_color_hover = icon_color_hover;
                                        }

                                        CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color_hover+';';
                                    } else {
                                        CSS += 'box-shadow:none;';
                                    }

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