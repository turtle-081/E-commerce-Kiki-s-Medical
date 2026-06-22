(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    function isInArray(value, array) {return array.indexOf(value) > -1;}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    var font_weight_array = [];

    for (var i = 1; i <= 9; i++) {
        font_weight_array.push(i+'00italic');
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

    function iframeSCRIPT(element,doc){
        $(element).each(function(){

            var $this  = $(this),
                effect = $this.data('effect');

            $this.addClass('active');

            $this.removeAttr('style');
            $this.css({'opacity':'1','transform':'translateY(0)'});

            if (effect == "scale") {

                var back = $this.find('.et-icon .icon-back');

                $this.on('mouseover',function(){
                    gsap.to(back,0.8, {
                        scale:1.2,
                        ease:"elastic.out"
                    });
                });

                $this.on('mouseout',function(){
                    gsap.to(back,0.8, {
                        scale:1,
                        ease:"expo.out"
                    });
                });

            } else if(effect == "transform"){

                $this.on('mouseover',function(){
                    gsap.to($this,0.4, {
                        y:-24,
                        ease:"expo.out"
                    });
                });

                $this.on('mouseout',function(){
                    gsap.to($this,0.4, {
                        y:0,
                        ease:"expo.out"
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
                return dataObj[key] === "et_icon_box";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_icon_box"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_box"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    padding_box   = edit_element.find(".padding-box"),
                    padding       = edit_element.find('input[name="padding"]'),
                    padding_val   = padding.val(),
                    padding_array = [];

                var table              = edit_element.find(".column-responsive-padding"),
                    media_query        = table.find(".media-query"),
                    crp                = edit_element.find("input[name=\"crp\"]"),
                    resp_padding_array = [];    

                // Set defaults

                    var crp_val = crp.val();

                    if(typeof(crp_val) != "undefined" && crp_val.length){
                        var crp_array = crp_val.split(",");

                        media_query.each(function(index){
                            var $this = jQuery(this);
                            var defaults = crp_array[index].split(":");

                            if(defaults[0] == $this.data("query")){
                                $this.find("td.left option[value=\""+defaults[1]+"\"]").attr("selected","selected").siblings().removeAttr("selected");
                                $this.find("td.right option[value=\""+defaults[2]+"\"]").attr("selected","selected").siblings().removeAttr("selected");
                            }
                        });

                    }

                    if(typeof(padding_val) != "undefined" && padding_val.length){

                        var padding_array = padding_val.split(",");

                        padding_box.find("input[name=\"padding-top\"]").attr('value',padding_array[0]);
                        padding_box.find("input[name=\"padding-right\"]").attr('value',padding_array[1]);
                        padding_box.find("input[name=\"padding-bottom\"]").attr('value',padding_array[2]);
                        padding_box.find("input[name=\"padding-left\"]").attr('value',padding_array[3]);

                    }

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_box"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_box"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_icon_box"]');

                        /* Styling
                        ---------------*/

                            var icon_color              = edit_element.find('input[name="icon_color"]').val(),
                                icon_back_color         = edit_element.find('input[name="icon_back_color"]').val(),
                                icon_border_color       = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_color_hover        = edit_element.find('input[name="icon_color_hover"]').val(),
                                icon_back_color_hover   = edit_element.find('input[name="icon_back_color_hover"]').val(),
                                icon_border_color_hover = edit_element.find('input[name="icon_border_color_hover"]').val(),
                                icon_border_width       = edit_element.find('input[name="icon_border_width"]').val(),
                                title_color             = edit_element.find('input[name="title_color"]').val(),
                                text_color              = edit_element.find('input[name="text_color"]').val(),
                                box_color               = edit_element.find('input[name="box_color"]').val(),
                                title_color_hover       = edit_element.find('input[name="title_color_hover"]').val(),
                                text_color_hover        = edit_element.find('input[name="text_color_hover"]').val(),
                                box_color_hover         = edit_element.find('input[name="box_color_hover"]').val(),
                                box_border_color        = edit_element.find('input[name="box_border_color"]').val(),
                                box_border_color_hover  = edit_element.find('input[name="box_border_color_hover"]').val(),
                                box_border_width        = edit_element.find('input[name="box_border_width"]').val(),
                                shadow                  = edit_element.find('input[name="shadow"]');

                            var font_weight      = edit_element.find('select[name="font_weight"] option:selected').val(),
                                font_size        = edit_element.find('input[name="font_size"]').val(),
                                letter_spacing   = edit_element.find('input[name="letter_spacing"]').val(),
                                line_height      = edit_element.find('input[name="line_height"]').val();

                            if (shadow.length && shadow.is(":checked")) {
                                CSS += '#et-icon-box-'+ID+' {';
                                    CSS += 'box-shadow:0 2px 20px 0 rgba(0,0,0,0.05);';
                                CSS += '}';
                            }

                            CSS += '#et-icon-box-'+ID+' {';
                                if (box_color.length) {
                                    CSS += 'background-color:'+box_color+';';
                                }
                                if (box_border_color.length && box_border_width.length) {
                                    if (shadow.length && shadow.is(":checked")) {
                                        CSS += 'box-shadow:inset 0 0 0 '+box_border_width.length+'px '+box_border_color+', 0 2px 20px 0 rgba(0,0,0,0.05);';
                                    } else {
                                        CSS += 'box-shadow:inset 0 0 0 '+box_border_width.length+'px '+box_border_color+';';
                                    }
                                }
                            CSS += '}';

                            CSS += '#et-icon-box-'+ID+':hover {';
                                if (box_color_hover.length) {
                                    CSS += 'background-color:'+box_color_hover+';';
                                }
                                if (box_border_color_hover.length && box_border_width.length) {
                                    if (shadow.length && shadow.is(":checked")) {
                                        CSS += 'box-shadow:inset 0 0 0 '+box_border_width.length+'px '+box_border_color_hover+', 0 2px 20px 0 rgba(0,0,0,0.05);';
                                    } else {
                                        CSS += 'box-shadow:inset 0 0 0 '+box_border_width.length+'px '+box_border_color_hover+';';
                                    }
                                }
                            CSS += '}';

                            CSS += '#et-icon-box-'+ID+' .et-icon-box-title {';
                                if (title_color.length) {
                                    CSS += 'color:'+title_color+';';
                                }
                                if (font_size.length) {CSS += 'font-size:'+font_size+'px;';}

                                if (font_weight.length && font_weight != "italic") {

                                    if (isInArray(font_weight,font_weight_array)) {
                                        font_weight = font_weight.substring(0, 3);
                                        CSS += 'font-style:italic;';
                                    }

                                    if (font_weight == "regular") {
                                        font_weight = "400";
                                    }

                                    CSS += 'font-weight:'+font_weight+';';
                                }

                                if (letter_spacing.length) {CSS += 'letter-spacing:'+letter_spacing+'px;';}
                                if (line_height.length) {CSS += 'line-height:'+line_height+'px;';}
                            CSS += '}';

                            if (title_color_hover.length) {
                                CSS += '#et-icon-box-'+ID+':hover .et-icon-box-title {';
                                    CSS += 'color:'+title_color_hover+';';
                                CSS += '}';
                            }

                            if (text_color.length) {
                                CSS += '#et-icon-box-'+ID+' .et-icon-box-content {';
                                    CSS += 'color:'+text_color+';';
                                CSS += '}';
                            }

                            if (text_color_hover.length) {
                                CSS += '#et-icon-box-'+ID+':hover .et-icon-box-content {';
                                    CSS += 'color:'+text_color_hover+';';
                                CSS += '}';
                            }

                            if (icon_color.length) {
                                CSS += '#et-icon-box-'+ID+' .et-icon svg * {';
                                    CSS += 'fill:'+icon_color+' !important;';
                                CSS += '}';
                            }
                            
                            CSS += '#et-icon-box-'+ID+' .et-icon .icon-back {';
                                if (icon_back_color.length) {
                                    CSS += 'background-color:'+icon_back_color+';';
                                }
                                if (icon_border_width.length && icon_border_color.length) {
                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color+';';
                                }
                            CSS += '}';

                            if (icon_color_hover.length) {
                                CSS += '#et-icon-box-'+ID+':hover .et-icon svg * {';
                                    CSS += 'fill:'+icon_color_hover+' !important;';
                                CSS += '}';
                            }

                            CSS += '#et-icon-box-'+ID+':hover .et-icon .icon-back {';
                                if (icon_back_color_hover.length) {
                                    CSS += 'background-color:'+icon_back_color_hover+';';
                                }
                                if (icon_border_color_hover.length && icon_border_width.length) {
                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color_hover+';';
                                }
                            CSS += '}';

                        /* Responsive padding
                        ---------------*/

                            if(crp.length){

                                for(var i=0;i<media_query.length;i++){
                                    var query = jQuery(media_query[i]).data("query");
                                    var left = jQuery(media_query[i]).find("td.left option:selected").val();
                                    var right = jQuery(media_query[i]).find("td.right option:selected").val();
                                    resp_padding_array.push(query+":"+left+":"+right);
                                }

                                var padding_string = resp_padding_array.join();
                                crp.val(padding_string);
                                resp_padding_array= [];

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

                            CSS += '#et-icon-box-'+ID+' {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-icon-box');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);