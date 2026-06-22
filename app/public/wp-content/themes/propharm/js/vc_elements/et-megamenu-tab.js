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

    function iframeSCRIPT(element,doc){

        $(element).each(function(){

            var $this  = $(this);
            var width  = $this.parents('.container').first().width();
            var gap    = (width - 1200);
            var offset = $this.offset();
            var correction = Math.round(($(doc).outerWidth()/2) - offset.left,0);

            $this.find('.megamenu[data-width="100"]').each(function(){
                if (this) {
                    var megamenu = $(this);

                    if (megamenu.data('stretch') == "stretch") {
                        gap = 0;
                    }

                    CSS = '#'+megamenu.attr('id')+'{';
                        CSS += 'width:'+width+'px !important;';
                        CSS += 'max-width:'+width+'px !important;';
                        CSS += 'padding-left:-'+Math.round((width/2)+gap)+'px !important;';
                        CSS += 'right:auto !important;';
                        CSS += 'left:'+correction+'px !important;';
                    CSS += '}';
                    $(doc).find("#dynamic-styles-inline-css").append(CSS);
                }
            });

        });
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

            var elementExists = Object.keys(dataObj).some(function(key) {
                return dataObj[key] === "et_megamenu_tab";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_megamenu_tab"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_megamenu_tab"]');

                        /* Top level
                        ---------------*/

                            var font_weight         = edit_element.find('select[name="font_weight"] option:selected').val(),
                                font_family         = edit_element.find('select[name="font_family"] option:selected').val(),
                                menu_color          = edit_element.find('input[name="menu_color"]').val(),
                                menu_color_hover    = edit_element.find('input[name="menu_color_hover"]').val(),
                                font_size           = edit_element.find('input[name="font_size"]').val(),
                                letter_spacing      = edit_element.find('input[name="letter_spacing"]').val(),
                                text_transform      = edit_element.find('select[name="text_transform"] option:selected').val(),
                                tabset_color        = edit_element.find('input[name="tabset_color"]').val(),
                                tab_content_color   = edit_element.find('input[name="tab_content_color"]').val();

                            if (tabset_color.length) {
                                CSS += '#megamenu-tab-'+ID+' .tabset {background-color:'+tabset_color+';}';
                            }

                            if (tab_content_color.length) {
                                CSS += '#megamenu-tab-'+ID+' .tabs-container {background-color:'+tab_content_color+';}';
                            }

                            CSS += '#megamenu-tab-'+ID+' .tab-item {';

                                if (menu_color.length) {CSS += 'color:'+menu_color+';';}
                                if (font_size.length) {CSS += 'font-size:'+font_size+'px;';}

                                if (font_weight.length && font_weight != "italic" && font_weight != "regular") {

                                    if (isInArray(font_weight,font_weight_array)) {
                                        font_weight = font_weight.substring(0, 3);
                                        CSS += 'font-style:italic;';
                                    }

                                    CSS += 'font-weight:'+font_weight+';';
                                }

                                if (letter_spacing.length) {CSS += 'letter-spacing:'+letter_spacing+'px;';}
                                if (text_transform.length) {CSS += 'text-transform:'+text_transform+';';}
                                if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}

                            CSS += '}';

                            if (menu_color.length) {
                                CSS += '#megamenu-tab-'+ID+' .tab-item dir-child* .arrow, #megamenu-tab-'+ID+' .tab-item dir-child* .icon svg {';
                                    CSS += 'fill:'+menu_color+';';
                                CSS += '}';
                            }

                            if (menu_color_hover.length) {
                                CSS += '#megamenu-tab-'+ID+' .tab-item.active {color:'+menu_color_hover+';}';

                                CSS += '#megamenu-tab-'+ID+' .tab-item.active dir-child* .arrow, #megamenu-tab-'+ID+' .tab-item.active dir-child* .icon svg {';
                                    CSS += 'fill:'+menu_color_hover+';';
                                CSS += '}';
                            }

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

                            CSS += '#megamenu-tab-'+ID+' .tab-content {';
                                CSS += 'padding:'+padding_value+';';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .megamenu-tab-container');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);