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
                return dataObj[key] === "et_megamenu_tab_item";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_megamenu_tab_item"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab_item"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    padding_box  = edit_element.find(".padding-box"),
                    padding       = edit_element.find('input[name="padding"]'),
                    padding_val   = padding.val(),
                    padding_array = [];

                if(typeof(padding_val) != "undefined" && padding_val.length){

                    var padding_array = padding_val.split(",");

                    padding_box.find("input[name=\"padding-top\"]").attr('value',padding_array[0]);
                    padding_box.find("input[name=\"padding-right\"]").attr('value',padding_array[1]);
                    padding_box.find("input[name=\"padding-bottom\"]").attr('value',padding_array[2]);
                    padding_box.find("input[name=\"padding-left\"]").attr('value',padding_array[3]);

                } 

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab_item"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab_item"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab_item"]');

                        /* Padding
                        ---------------*/

                            var padding_left   = edit_element.find(".padding-box input[name=\"padding-left\"]").val(),
                                padding_top    = edit_element.find(".padding-box input[name=\"padding-top\"]").val(),
                                padding_right  = edit_element.find(".padding-box input[name=\"padding-right\"]").val(),
                                padding_bottom = edit_element.find(".padding-box input[name=\"padding-bottom\"]").val();

                            padding_top = (padding_top.length) ? padding_top : '0';
                            padding_right = (padding_right.length) ? padding_right : '0';
                            padding_bottom = (padding_bottom.length) ? padding_bottom : '0';
                            padding_left = (padding_left.length) ? padding_left : '0';

                            var padding_output = padding_top+','+padding_right+','+padding_bottom+','+padding_left,
                                padding_value  = padding_top+'px '+padding_right+'px '+padding_bottom+'px '+padding_left+'px';

                            padding.val(padding_output);

                            CSS += '#tab-item-'+ID+' {';
                                CSS += 'padding:'+padding_value+' !important;';
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