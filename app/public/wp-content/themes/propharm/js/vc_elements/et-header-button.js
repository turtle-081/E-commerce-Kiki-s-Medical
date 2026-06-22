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

    function iframeSCRIPT(element,doc){
        $(element).each(function(){

            var $this  = $(this);

            // HBE
            var attr = $this.parent().parent().attr('data-tag');
            var hasAttribute = (typeof attr !== 'undefined' && attr !== false) ? true : false;

            if ($this.parent().hasClass('hbe-right') && hasAttribute) {$this.parent().parent().addClass('hbe-right');}
            if ($this.parent().hasClass('hbe-left') && hasAttribute) {$this.parent().parent().addClass('hbe-left');}
            if ($this.parent().hasClass('hbe-center') && hasAttribute) {$this.parent().parent().addClass('hbe-center');}

            if ($this.hasClass('wpb_animate_when_almost_visible')) {
                $this
                .addClass('wpb_start_animation')
                .addClass('animated');
            }

            var effect = $this.data('effect');

            var tl = new gsap.timeline({paused: true});

            switch (effect) {
                case 'fill':

                    var hover       = $this.find('span.hover'),
                        icon        = $this.find('.icon svg'),
                        color       = $this.data('color'),
                        color_hover = $this.data('color-hover');

                    tl.to(hover,0.6, {
                      x:0,
                      ease:"expo.out"
                    },'+=0.2');

                    tl.to($this,0.1, {
                      css:{color:color_hover}
                    },'-=0.6');

                    tl.to(icon,0.1, {
                      css:{fill:color_hover}
                    },'-=0.1');

                    tl.add("in");

                    tl.to(hover,0.6, {
                      x:'100%',
                      ease:"expo.out"
                    },'+=0.2');

                    tl.to(hover,0, {
                      x:'-100%',immediateRender:false
                    });

                    tl.to($this,0.1, {
                      css:{color:color}
                   },'-=0.6');

                   tl.to(icon,0.1, {
                     css:{fill:color}
                   },'-=0.1');

                   $this.hover(
                        function(){
                            tl.progress(0);
                            tl.tweenTo("in");
                        },
                        function(){
                            tl.play();
                        }
                    );

                break;

                case 'scale':

                    var back = $this.find('.button-back .regular');

                    $this.on('mouseover',function(){
                        gsap.to(back,0.8, {
                            scale:1.05,
                            ease:"elastic.out"
                        });
                    });

                    $this.on('mouseout',function(){
                        gsap.to(back,0.8, {
                            scale:1,
                            ease:"expo.out"
                        });
                    });

                break;

                case 'move':

                    $this.on('mousemove',function(e){

                        var sxPos =  e.pageX - ($this.width()/2  + $this.offset().left);
                        var syPos =  e.pageY - ($this.height()/2 + $this.offset().top);

                        gsap.to( $this, 0.4, { 
                            x: Math.round(0.1 * sxPos), 
                            y: Math.round(0.5 * syPos), 
                        });

                    });

                    $this.on('mouseleave',function(){
                        gsap.to( $this, 0.4, { 
                            x: 0, 
                            y: 0, 
                        });
                    });

                break;
               
            }

            if ($this.hasClass('click-smooth') && $this.hasClass('modal-false')) {
                $this.on('click',function(){
                    gsap.to(doc, {
                        duration: 1, 
                        scrollTo: {y:$this.attr('href')},
                        ease:Power3.easeOut 
                    });
                    return false;
                });
            }

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
                return dataObj[key] === "et_header_button";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_header_button"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_button"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_button"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_button"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_button"]');

                        /* Styling
                        ---------------*/

                            var align                     = edit_element.find('select[name="align"] option:selected').val(),
                                width                     = edit_element.find('input[name="width"]').val(),
                                height                    = edit_element.find('input[name="height"]').val(),
                                offset                    = edit_element.find('input[name="submenuoffset"]').val(),
                                font_weight               = edit_element.find('select[name="font_weight"] option:selected').val(),
                                font_family               = edit_element.find('select[name="font_family"] option:selected').val(),
                                button_font_size          = edit_element.find('input[name="button_font_size"]').val(),
                                icon_font_size            = edit_element.find('input[name="icon_font_size"]').val(),
                                icon_margin               = edit_element.find('input[name="icon_margin"]').val(),
                                icon2_font_size           = edit_element.find('input[name="icon2_font_size"]').val(),
                                icon2_margin              = edit_element.find('input[name="icon2_margin"]').val(),
                                button_letter_spacing     = edit_element.find('input[name="button_letter_spacing"]').val(),
                                button_line_height        = edit_element.find('input[name="button_line_height"]').val(),
                                button_text_transform     = edit_element.find('select[name="button_text_transform"] option:selected').val(),
                                button_style              = edit_element.find('select[name="button_style"] option:selected').val(),
                                button_type               = edit_element.find('select[name="button_type"] option:selected').val(),
                                button_size               = edit_element.find('select[name="button_size"] option:selected').val(),
                                button_size_custom        = edit_element.find('select[name="button_size_custom"] option:selected').val(),
                                button_color              = edit_element.find('input[name="button_color"]').val(),
                                button_back_color         = edit_element.find('input[name="button_back_color"]').val(),
                                button_border_color       = edit_element.find('input[name="button_border_color"]').val(),
                                button_color_hover        = edit_element.find('input[name="button_color_hover"]').val(),
                                button_back_color_hover   = edit_element.find('input[name="button_back_color_hover"]').val(),
                                button_border_color_hover = edit_element.find('input[name="button_border_color_hover"]').val(),
                                animate_hover             = edit_element.find('select[name="animate_hover"] option:selected').val(),
                                animate_hover_outline     = edit_element.find('select[name="animate_hover_outline"] option:selected').val();

                                if (button_style == 'outline') {
                                    button_back_color       = button_border_color;
                                    button_back_color_hover = button_border_color_hover;
                                    animate_hover           = animate_hover_outline;
                                }

                                if (button_size_custom == "false") {
                                    if (button_size == "small") {
                                        width = '';
                                        height= '40';
                                    } else
                                    if (button_size == "medium") {
                                        width = '';
                                        height= '48';
                                    } else
                                    if (button_size == "large") {
                                        width = '';
                                        height= '56';
                                    }
                                }

                            CSS += '#et-header-button-'+ID+' dir-child* a {';

                                if (width.length) {CSS += 'width:'+width+'px;';}
                                if (height.length) {CSS += 'height:'+height+'px;padding-top:0;padding-bottom:0;';}

                                if (button_type == 'round') {
                                    CSS += 'border-radius:'+height+'px;';
                                }

                                if (button_font_size.length) {
                                    CSS += 'font-size:'+button_font_size+'px !important;';
                                }
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

                                if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}
                                if (button_letter_spacing.length) {
                                    CSS += 'letter-spacing:'+button_letter_spacing+'px;';
                                }
                                if (button_line_height.length) {
                                    CSS += 'line-height:'+button_line_height+'px !important;';
                                }
                                if (button_text_transform.length) {
                                    CSS += 'text-transform:'+button_text_transform+';';
                                }
                                if (button_color.length) {
                                    CSS += 'color:'+button_color+';';
                                }

                            CSS += '}';

                            if (icon_margin.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* a.icon-position-left dir-child* .icon {margin-right:'+icon_margin+'px;}';
                                CSS += '#et-header-button-'+ID+' dir-child* a.icon-position-right dir-child* .icon {margin-left:'+icon_margin+'px;}';
                            }

                            if (icon_font_size.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* a dir-child* .icon {';
                                    CSS += 'width:'+icon_font_size+'px !important;';
                                    CSS += 'line-height:'+icon_font_size+'px;';
                                CSS += '}';
                            }

                            if (icon2_margin.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* a.icon2-position-left dir-child* .icon2 {margin-right:'+icon2_margin+'px;margin-left:0}';
                                CSS += '#et-header-button-'+ID+' dir-child* a.icon2-position-right dir-child* .icon2 {margin-left:'+icon2_margin+'px;margin-right:0}';
                            }

                            if (icon2_font_size.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* a dir-child* .icon2 {';
                                    CSS += 'width:'+icon2_font_size+'px !important;';
                                CSS += '}';
                            }

                            if (button_color.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* a dir-child* .icon svg, #et-header-button-'+ID+' dir-child* a dir-child* .icon svg * {';
                                    CSS += 'fill:'+button_color+';';
                                CSS += '}';
                            }   

                            if (button_color_hover.length) {
                                if (animate_hover != "fill") {
                                    CSS += '#et-header-button-'+ID+' dir-child* a:hover {';
                                        CSS += 'color:'+button_color_hover+';';
                                    CSS += '}';

                                    CSS += '#et-header-button-'+ID+' dir-child* a:hover dir-child* .icon svg, #et-header-button-'+ID+' dir-child* a:hover dir-child* .icon svg * {';
                                        CSS += 'fill:'+button_color_hover+';';
                                    CSS += '}';
                                }
                            }

                            if (button_back_color.length) {

                                if (button_style == 'outline') {
                                    CSS += '#et-header-button-'+ID+' dir-child* a dir-child* .button-back .regular {';
                                        CSS += 'border-color:'+button_back_color+';';
                                    CSS += '}';
                                } else {
                                    CSS += '#et-header-button-'+ID+' dir-child* a dir-child* .button-back .regular {';
                                        CSS += 'background:'+button_back_color+';';
                                    CSS += '}';
                                }
                            }

                            if (button_back_color_hover.length) {
                                if (animate_hover == "fill") {
                                    CSS += '#et-header-button-'+ID+' dir-child* a  dir-child* .button-back .hover {';
                                        CSS += 'background:'+button_back_color_hover+';';
                                    CSS += '}';
                                } else {

                                    if (button_style == 'outline') {
                                        CSS += '#et-header-button-'+ID+' dir-child* a:hover dir-child* .button-back .regular {';
                                            CSS += 'border-color:'+button_back_color_hover+';';
                                        CSS += '}';
                                    } else {
                                        CSS += '#et-header-button-'+ID+' dir-child* a:hover dir-child* .button-back .regular {';
                                            CSS += 'background:'+button_back_color_hover+';';
                                        CSS += '}';
                                    }

                                }
                            }

                            if (offset.length) {
                                CSS += '#et-header-button-'+ID+' dir-child* .megamenu {';
                                    CSS += 'top:'+offset+';';
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

                            CSS += '#et-header-button-'+ID+' {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-header-button a');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                            element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-header-button')
                            hbeAlign(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);
