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

    function iframeSCRIPT(element){
        $(element).each(function(){

            var $this  = $(this);

            if ($this.hasClass('click-true')) {

                var iconBack      = $this.find('.icon-back path'),
                    morphPath     = iconBack.data('hover'),
                    morphOriginal = iconBack.attr('d'),
                    shapeIndex    = 8;

                $this.on('mousedown touchstart',function(){
                    gsap.to(iconBack,0.6, {
                      morphSVG:{shape: morphPath, shapeIndex: shapeIndex},
                      ease:'elastic.out'
                    });
                });

                $this.on('mouseup mouseleave touchend',function(){
                    gsap.to(iconBack,0.6, {
                        morphSVG:{shape: morphOriginal, shapeIndex: shapeIndex},
                        ease:'elastic.out'
                    });
                });

            }

        });
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
                return dataObj[key] === "et_header_icon";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_header_icon"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_icon"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_icon"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_icon"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_icon"]');

                        /* Styling
                        ---------------*/

                            var align                        = edit_element.find('select[name="align"] option:selected').val(),
                                icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                icon_color_hover             = edit_element.find('input[name="icon_color_hover"]').val(),
                                icon_background_color        = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_background_color_hover  = edit_element.find('input[name="icon_background_color_hover"]').val(),
                                icon_border_color            = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_border_color_hover      = edit_element.find('input[name="icon_border_color_hover"]').val(),
                                icon_border_width            = edit_element.find('input[name="icon_border_width"]').val(),
                                icon_size_type               = edit_element.find('select[name="size"] option:selected').val(),
                                icon_size                    = edit_element.find('input[name="icon_size"]').val(),
                                icon_box_size                = edit_element.find('input[name="icon_box_size"]').val();

                            if (icon_size_type != "custom") {
                                icon_size     = '';
                                icon_box_size = '';
                            }

                            if (icon_box_size.length) {
                                CSS += '#header-icon-'+ID+' .hicon {';
                                    CSS += 'width:'+icon_box_size+'px;height:'+icon_box_size+'px;line-height:'+icon_box_size+'px;';
                                CSS += '}';
                            }

                            CSS += '#header-icon-'+ID+' .hicon .icon-back {';
                                if (icon_background_color.length) {
                                    CSS += 'fill:'+icon_background_color+';';
                                } else {
                                    CSS += 'fill:transparent;';
                                }
                                if (icon_border_width.length) {
                                    if (!icon_border_color.length) {
                                        icon_border_color = icon_color;
                                    }
                                    CSS += 'stroke-width:'+icon_border_width+'px;';
                                    CSS += 'stroke:'+icon_border_color+';';
                                }
                            CSS += '}';


                            CSS += '#header-icon-'+ID+' .hicon svg:not(.icon-back) {';
                                if (icon_color.length) {
                                    CSS += 'fill:'+icon_color+';';
                                }
                                if (icon_size.length) {
                                    CSS += 'width:'+icon_size+'px;';
                                }
                            CSS += '}';

                            if (icon_background_color_hover.length) {
                                CSS += '#header-icon-'+ID+' .hicon:hover .icon-back {';
                                    CSS += 'fill:'+icon_background_color_hover+';';
                                    if (icon_border_width.length) {

                                        if (!icon_border_color_hover.length) {
                                            icon_border_color_hover = icon_color_hover;
                                        }
                                        CSS += 'stroke-width:'+icon_border_width+'px;';
                                        CSS += 'stroke:'+icon_border_color_hover+';';
                                    }
                                CSS += '}';
                            }

                            if (icon_color_hover.length) {
                                CSS += '#header-icon-'+ID+' .hicon:hover svg:not(.icon-back) {';
                                    CSS += 'fill:'+icon_color_hover+';';
                                CSS += '}';
                            }

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

                            CSS += '#header-icon-'+ID+' .hicon {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-icon');
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
