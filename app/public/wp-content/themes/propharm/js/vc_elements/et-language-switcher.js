(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function toggleBack(element,toggle,scrollElement){

        var $this  = jQuery(element),
            isOpen = false;

        toggle.on('click',function(){

            toggle.toggleClass('active');

            if (isOpen==false) {

                if (typeof scrollElement != "undefined" && scrollElement != null) {
                    // Custom scroll bar
                    setTimeout(function(){
                        var scroll = element.querySelector(scrollElement);
                        if (typeof scroll != "undefined" && scroll != null) {
                            SimpleScrollbar.initEl(scroll);
                        }
                    },200);
                }

                isOpen=true;

            } else {
                isOpen=false;
            }
        });
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

    function iframeSCRIPT(element){
        $(element).each(function(){

            var element      = this,
                $this        = $(element),
                toggle       = $this.find('.language-toggle');

            toggleBack(element,toggle,'.language-switcher-content');
            
        });
        
    }

    function hbeAlign(element,doc){
        var CSS = '';

        if (element.hasClass('hbe-right')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:right;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-left')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:left;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-center') || element.hasClass('hbe-none')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:none;display:inline-block;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
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
                return dataObj[key] === "et_language_switcher";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_language_switcher"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_language_switcher"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_language_switcher"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_language_switcher"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_language_switcher"]');

                        /* Styling
                        ---------------*/

                            var align                        = edit_element.find('select[name="align"] option:selected').val(),
                                close_icon_name              = edit_element.find('ul.admin-icon-list.icon-close span.active').data('name'),
                                icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                submenu_color                  = edit_element.find('input[name="submenu_color"]').val(),
                                submenu_color_hover            = edit_element.find('input[name="submenu_color_hover"]').val(),
                                submenu_background_color       = edit_element.find('input[name="submenu_background_color"]').val(),
                                submenu_background_color_hover = edit_element.find('input[name="submenu_background_color_hover"]').val(),
                                submenu_width                  = edit_element.find('input[name="submenu_width"]').val();

                            if (icon_color.length) {
                                CSS += '#language-switcher-'+ID+' .language-toggle svg {';
                                    CSS += 'fill:'+icon_color+';';
                                CSS += '}';
                                CSS += '#language-switcher-'+ID+' .language-toggle {';
                                    CSS += 'color:'+icon_color+';';
                                CSS += '}';
                            }

                            if (submenu_background_color.length) {
                                CSS += '#language-box-'+ID+' {';
                                    CSS += 'background:'+submenu_background_color+';';
                                CSS += '}';
                            }

                            if (submenu_width.length) {

                                CSS += '#language-box-'+ID+' {';
                                    CSS += 'width:'+submenu_width+'px;';
                                CSS += '}';

                                CSS += '.box-align-center #language-box-'+ID+' {';
                                    CSS += 'margin-left:-'+(submenu_width/2)+'px;';
                                CSS += '}';
                            }

                            if (submenu_color.length) {
                                CSS += '#language-box-'+ID+' ul li a {';
                                    CSS += 'color:'+submenu_color+';';
                                CSS += '}';

                                CSS += '#language-box-'+ID+' svg.close {';
                                    CSS += 'fill:'+submenu_color+';';
                                CSS += '}';
                            }

                            CSS += '#language-box-'+ID+' ul li a:hover {';
                                if (submenu_color_hover.length) {CSS += 'color:'+submenu_color_hover+';';}
                                if (submenu_background_color_hover.length) {
                                    CSS += 'background-color:'+submenu_background_color_hover+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }
                            CSS += '}';

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

                            CSS += '#language-switcher-'+ID+' {';
                                CSS += 'margin:'+margin_value+';';
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

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){

                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .language-switcher');

                        if (typeof(element) != 'undefined' && element != null) {
                            hbeAlign(element,doc);
                            iframeSCRIPT(element);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);
