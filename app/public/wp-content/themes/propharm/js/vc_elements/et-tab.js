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
                return dataObj[key] === "et_tab";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_tab"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_tab"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_tab"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_tab"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_tab"]');

                        var color                   = edit_element.find('input[name="color"]').val(),
                            color_active            = edit_element.find('input[name="color_active"]').val(),
                            background_color        = edit_element.find('input[name="background_color"]').val(),
                            background_color_active = edit_element.find('input[name="background_color_active"]').val();

                        CSS += '#et-tab-'+ID+' .tab {';
                            if (background_color.length) {
                                CSS += 'background-color:'+background_color+';';
                            }
                            if (color.length) {
                                CSS += 'color:'+color+';';
                            }
                        CSS += '}';

                        CSS += '#et-tab-'+ID+' .tab.active {';
                            if (color_active.length) {
                                CSS += 'color:'+color_active+';';
                                CSS += 'background-color:'+background_color_active+';';
                            }
                        CSS += '}';

                        if (color.length) {
                            CSS += '#et-tab-'+ID+' .tab svg, #et-tab-'+ID+' .tab svg * {';
                                CSS += 'fill:'+color+' !important;';
                            CSS += '}';
                        }

                        if (color_active.length) {
                            CSS += '#et-tab-'+ID+' .tab.active svg, #et-tab-'+ID+' .tab.active svg * {';
                                CSS += 'fill:'+color_active+' !important;';
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