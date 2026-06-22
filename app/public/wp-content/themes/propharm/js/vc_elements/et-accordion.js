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

            var elementExists = Object.keys(dataObj).some(function(key) {
                return dataObj[key] === "et_accordion";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_accordion"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_accordion"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_accordion"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_accordion"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_accordion"]');

                        var color                   = edit_element.find('input[name="color"]').val(),
                            color_active            = edit_element.find('input[name="color_active"]').val(),
                            border_color            = edit_element.find('input[name="border_color"]').val(),
                            border_color_active     = edit_element.find('input[name="border_color_active"]').val(),
                            background_color        = edit_element.find('input[name="background_color"]').val(),
                            background_color_active = edit_element.find('input[name="background_color_active"]').val();

                        CSS += '#et-accordion-'+ID+' .toggle-content-group {';
                            if (background_color.length) {
                                CSS += 'background-color:'+background_color+';';
                            }
                            if (border_color.length) {
                                CSS += 'border-color:'+border_color+';';
                            }
                        CSS += '}';

                        if (color.length) {
                            CSS += '#et-accordion-'+ID+' .toggle-title {';
                                CSS += 'color:'+color+';';
                            CSS += '}';
                            CSS += '#et-accordion-'+ID+' .toggle-title svg, #et-accordion-'+ID+' .toggle-title svg * {';
                                CSS += 'fill:'+color+' !important;';
                            CSS += '}';
                        }

                        if (color_active.length) {
                            CSS += '#et-accordion-'+ID+' .toggle-title.active {';
                                CSS += 'color:'+color_active+';';
                            CSS += '}';
                            CSS += '#et-accordion-'+ID+' .toggle-title.active svg, #et-accordion-'+ID+' .toggle-title.active svg * {';
                                CSS += 'fill:'+color_active+' !important;';
                            CSS += '}';
                        }

                        CSS += '#et-accordion-'+ID+' .toggle-content-group.active {';
                            if (background_color_active.length) {
                                CSS += 'background-color:'+background_color_active+';';
                            }
                            if (border_color_active.length) {
                                CSS += 'border-color:'+border_color_active+';';
                            }
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