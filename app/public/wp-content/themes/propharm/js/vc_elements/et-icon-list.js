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

    function iframeSCRIPT(element){
        $(element).each(function(){

            var $this = $(this),
                delay = '+='+(0.2 + parseInt($this.data('delay'))/1000);

            var tl = new gsap.timeline({paused: true});

            tl.to($this.find('li'),{
                duration: 0.8,
                x:0,
                opacity:1,
                stagger: 0.1,
                ease:"expo.out"
            },delay);

            tl.progress(0);
            tl.play();

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
                return dataObj[key] === "et_icon_list";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_icon_list"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_list"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_list"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_list"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_list"]');

                        var icon_color            = edit_element.find('input[name="icon_color"]').val(),
                            icon_background_color = edit_element.find('input[name="icon_background_color"]').val(),
                            icon_border_color     = edit_element.find('input[name="icon_border_color"]').val(),
                            icon_border_radius    = edit_element.find('input[name="icon_border_radius"]').val(),
                            icon_border_width     = edit_element.find('input[name="icon_border_width"]').val(),
                            icon_size             = edit_element.find('select[name="icon_size"] option:selected').val(),
                            shadow                = edit_element.find('input[name="shadow"]');

                        CSS += '#et-icon-list-'+ID+' .et-icon {';
                            if (icon_background_color.length) {
                                CSS += 'background:'+icon_background_color+';';

                                if (!icon_border_width.length && (shadow.length && shadow.is(":checked"))) {
                                    CSS += 'box-shadow:0px 0px 16px 0px rgba(0, 0, 0, 0.08);';
                                }

                            } else {
                                CSS += 'background:transparent;';
                            }
                            if (icon_border_width.length) {
                                if (!icon_border_color.length) {
                                    icon_border_color = icon_color;
                                }
                                if (shadow.length && shadow.is(":checked")) {
                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_color+', 0px 0px 16px 0px rgba(0, 0, 0, 0.08);';
                                } else {
                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_color+';';
                                }
                            }

                        CSS += '}';
                        
                        if (icon_color.length) {
                            CSS += '#et-icon-list-'+ID+' .et-icon svg, #et-icon-list-'+ID+' .et-icon svg * {';
                                CSS += 'fill:'+icon_color+';';
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

                            CSS += '#et-icon-list-'+ID+' {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-icon-list.animate-true');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);