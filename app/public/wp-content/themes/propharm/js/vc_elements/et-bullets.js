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

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_bullets"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_bullets"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_bullets"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_bullets"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_bullets"]');

                        /* Styling
                        ---------------*/

                            var color       = edit_element.find('input[name="color"]').val(),
                                back_color  = edit_element.find('input[name="back_color"]').val(),
                                color_hover = edit_element.find('input[name="color_hover"]').val();

                            if (back_color.length) {
                                CSS += '#bullets-menu-container-'+ID+' {';
                                    CSS += 'background-color:'+back_color+';';
                                CSS += '}';
                            }

                            if (color.length) {
                                CSS += '#bullets-menu-'+ID+' a {';
                                    CSS += 'color:'+color+';';
                                CSS += '}';
                            }

                            if (color_hover.length) {
                                CSS += '#bullets-menu-'+ID+' a:hover, #bullets-menu-'+ID+' li a.active {';
                                    CSS += 'color:'+color_hover+';';
                                CSS += '}';
                                CSS += '#bullets-menu-'+ID+' li a .effect {';
                                    CSS += 'background-color:'+color_hover+';';
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