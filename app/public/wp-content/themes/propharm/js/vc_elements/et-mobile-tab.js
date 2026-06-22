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
                return dataObj[key] === "et_mobile_tab";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_mobile_tab"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_tab"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_tab"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_tab"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mobile_tab"]');

                        var color                   = edit_element.find('input[name="color"]').val(),
                            color_active            = edit_element.find('input[name="color_active"]').val(),
                            background_color        = edit_element.find('input[name="background_color"]').val(),
                            bubble_color            = edit_element.find('input[name="bubble_color"]').val(),
                            bubble_back_color       = edit_element.find('input[name="bubble_back_color"]').val();

                        CSS += '#et-mobile-tab-'+ID+' .cart-info, #et-mobile-tab-'+ID+' .wishlist-contents, #et-mobile-tab-'+ID+' .compare-contents {';
                            if (bubble_back_color.length) {
                                CSS += 'background:'+bubble_back_color+';';
                            }
                            if (bubble_color.length) {
                                CSS += 'color:'+bubble_color+';';
                            }
                        CSS += '}';

                        CSS += '#et-mobile-tab-'+ID+' .mob-tabset, #et-mobile-tab-'+ID+' .mob-tabset-toggle {';
                            if (background_color.length) {
                                CSS += 'background:'+background_color+';';
                            }
                            if (color.length) {
                                CSS += 'color:'+color+';';
                            }
                        CSS += '}';

                        if (color.length) {
                            CSS += '#et-mobile-tab-'+ID+' .tab svg, #et-mobile-tab-'+ID+' .mob-tabset-toggle svg {';
                                CSS += 'fill:'+color+';';
                            CSS += '}';
                            CSS += '#et-mobile-tab-'+ID+' .tab:after {';
                                CSS += 'background:'+color+';';
                            CSS += '}';
                        }

                        CSS += '#et-mobile-tab-'+ID+' .tab:hover {';
                            if (color_active.length) {
                                CSS += 'color:'+color_active+';';
                            }
                        CSS += '}';

                        if (color_active.length) {
                            CSS += '#et-mobile-tab-'+ID+' .tab:hover svg, #et-mobile-tab-'+ID+' .tab:hover svg *, #et-mobile-tab-'+ID+' .mob-tabset-toggle:hover svg, #et-mobile-tab-'+ID+' .mob-tabset-toggle:hover svg * {';
                                CSS += 'fill:'+color_active+';';
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