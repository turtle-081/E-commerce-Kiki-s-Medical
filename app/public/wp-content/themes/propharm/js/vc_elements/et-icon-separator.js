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
                return dataObj[key] === "et_icon_separator";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_icon_separator"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_separator"]'),
                    animate      = edit_element.find('select[name="animate"] option:selected').val(),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_separator"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_separator"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_separator"]');

                        var height = edit_element.find('input[name="height"]').val(),
                            width  = edit_element.find('input[name="width"]').val(),
                            top    = edit_element.find('input[name="top"]').val(),
                            bottom = edit_element.find('input[name="bottom"]').val(),
                            color_icon = edit_element.find('input[name="color_icon"]').val(),
                            color_sep  = edit_element.find('input[name="color_sep"]').val();

                        CSS += '.et-icon-separator-'+ID+' {';
                            if (top.length) {CSS += 'margin-top:'+top+'px;';}
                            if (bottom.length) {CSS += 'margin-bottom:'+bottom+'px;';}
                        CSS += '}';

                        CSS += '.et-icon-separator-'+ID+' .line {';
                            if (color_sep.length) {CSS += 'background-color:'+color_sep+';';}
                            if (width.length) {CSS += 'width:'+width+'px;';}
                            if (height.length) {CSS += 'height:'+height+'px;';}
                        CSS += '}';

                        if (color_icon.length) {
                            CSS += '.et-icon-separator-'+ID+' .icon, .et-icon-separator-'+ID+' .icon * {';
                                CSS += 'fill:'+color_icon+';';
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