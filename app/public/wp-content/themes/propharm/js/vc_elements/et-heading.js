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

    function iframeSCRIPT(element,doc){
        $(element).each(function(){

            var $this = $(this),
                delay = '+='+(0.2 + parseInt($this.data('delay'))/1000),
                text  = $this.find('.text');

            var tl = new gsap.timeline({paused: true});

            if ($this.hasClass('curtain')) {

                var curtain = $this.find('.curtain');

                tl.to(curtain,0.8, {
                  scaleX:1,
                  transformOrigin:'left top',
                  ease:"power3.out"
                },delay);

                tl.to(curtain,0.8, {
                  scaleX:0,
                  transformOrigin:'right top',
                  ease:"power3.out"
                });

                tl.to(text,0.2, {
                  opacity:1,
                },'-=0.8');
            } else

            if ($this.hasClass('letter')) {
                var letterText = new SplitText($this.find('.text'),{type:"chars"});

                gsap.set($this,{perspective:500});

                tl.from(letterText.chars,{
                    duration: 0.4,
                },delay);

                tl.from(letterText.chars,{
                    duration: 0.8,
                    opacity:0,
                    scale:3,
                    x:50,
                    y:50,
                    force3D:true,
                    stagger: 0.01,
                    ease:"expo.out"
                },'-=0.6');

            } else

            if ($this.hasClass('words')) {

                var wordsText = new SplitText($this.find('.text'),{type:"words"});
                
                gsap.set($this,{perspective:500});

                tl.from(wordsText.words,{
                    duration: 0.4,
                },delay);

                tl.from(wordsText.words,{
                    duration: 0.8,
                    opacity:0,
                    scaleY:2,
                    transformOrigin:'left top',
                    y:24,
                    force3D:true,
                    stagger: 0.04,
                    ease:"expo.out"
                },'-=0.6');

            } else

            if ($this.hasClass('rows')) {
                
                var rowsText = new SplitText($this.find('.text'),{type:"lines"});
                
                gsap.set($this,{perspective:1000});

                tl.from(rowsText.lines,{
                    duration: 0.4,
                },delay);

                tl.from(rowsText.lines,{
                    duration: 1.2,
                    opacity:0,
                    rotationX:8,
                    rotationY:-50,
                    rotationZ:8,
                    y:50,
                    x:-50,
                    z:50,
                    transformOrigin:'left top',
                    force3D:true,
                    stagger: 0.08,
                    ease:"expo.out"
                },'-=0.5');

            }

            tl.progress(0);
            tl.play();
        
        });
    }

    var font_weight_array = [];

    for (var i = 1; i <= 9; i++) {
        font_weight_array.push(i+'00italic');
    }

    /* Ajax complete
    /*-------------*/

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
                        return dataObj[key] === "et_heading";
                    });

                /* Edit element
                /*-------------*/

                    if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_heading"){

                        var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_heading"]');

                        var element_css  = edit_element.find('textarea[name="element_css"]'),
                            element_id   = edit_element.find('input[name="element_id"]'),
                            margin_box   = edit_element.find(".margin-box"),
                            margin       = edit_element.find('input[name="margin"]'),
                            margin_val   = margin.val(),
                            margin_array = [],
                            padding_box   = edit_element.find(".padding-box"),
                            padding       = edit_element.find('input[name="padding"]'),
                            padding_val   = padding.val(),
                            padding_array = [];

                        // Set defaults
                        if(typeof(margin_val) != "undefined" && margin_val.length){

                            var margin_array = margin_val.split(",");

                            margin_box.find("input[name=\"margin-top\"]").attr('value',margin_array[0]);
                            margin_box.find("input[name=\"margin-right\"]").attr('value',margin_array[1]);
                            margin_box.find("input[name=\"margin-bottom\"]").attr('value',margin_array[2]);
                            margin_box.find("input[name=\"margin-left\"]").attr('value',margin_array[3]);

                        }

                        if(typeof(padding_val) != "undefined" && padding_val.length){

                            var padding_array = padding_val.split(",");

                            padding_box.find("input[name=\"padding-top\"]").attr('value',padding_array[0]);
                            padding_box.find("input[name=\"padding-right\"]").attr('value',padding_array[1]);
                            padding_box.find("input[name=\"padding-bottom\"]").attr('value',padding_array[2]);
                            padding_box.find("input[name=\"padding-left\"]").attr('value',padding_array[3]);

                        }

                        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_heading"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                            if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_heading"]').length) {

                                var ID  = uniqueID();
                                var CSS = '';

                                edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_heading"]');

                                /* Styling
                                ---------------*/

                                    var font_weight      = edit_element.find('select[name="font_weight"] option:selected').val(),
                                        font_family      = edit_element.find('select[name="font_family"] option:selected').val(),
                                        text_color       = edit_element.find('input[name="text_color"]').val(),
                                        background_color = edit_element.find('input[name="background_color"]').val(),
                                        font_size        = edit_element.find('input[name="font_size"]').val(),
                                        letter_spacing   = edit_element.find('input[name="letter_spacing"]').val(),
                                        line_height      = edit_element.find('input[name="line_height"]').val(),
                                        text_transform   = edit_element.find('select[name="text_transform"] option:selected').val(),
                                        element_color    = edit_element.find('input[name="element_color"]').val(),
                                        animate          = edit_element.find('input[name="animate"]:checked').val(),
                                        animation_type   = edit_element.find('select[name="animation_type"] option:selected').val(),
                                        icon_font_size   = edit_element.find('input[name="icon_font_size"]').val(),
                                        icon_margin      = edit_element.find('input[name="icon_margin"]').val();


                                    if(typeof(animate) == "undefined" || !animate.length){
                                        animation_type = "none";
                                    } else {
                                        if (animation_type == 'curtain'){
                                            CSS += '#et-heading-'+ID+' .curtain {';
                                                CSS += 'background-color:'+element_color+';';
                                            CSS += '}';
                                        }
                                    }

                                    if (background_color.length) {
                                        CSS += '#et-heading-'+ID+' .text-wrapper {';
                                            CSS += 'background-color:'+background_color+';';
                                        CSS += '}';
                                    } else {
                                        CSS += '#et-heading-'+ID+' .text-wrapper {';
                                            CSS += 'background-color:transparent;padding:0;';
                                        CSS += '}';
                                    }

                                    CSS += '#et-heading-'+ID+' {';
                                        
                                        if (text_color.length) {CSS += 'color:'+text_color+';';}
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
                                        if (text_transform.length) {CSS += 'text-transform:'+text_transform+';';}
                                        if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}

                                    CSS += '}';


                                    if (line_height.length) {
                                        CSS += '#et-heading-'+ID+', #et-heading-'+ID+' .text-wrapper {';
                                            CSS += 'line-height:'+line_height+'px;';
                                        CSS += '}';
                                    }

                                    if (text_color.length) {
                                        CSS += '#et-heading-'+ID+' a {';
                                            CSS += 'color:'+text_color+';';
                                        CSS += '}';
                                        CSS += '#et-heading-'+ID+' .text-wrapper:after {';
                                            CSS += 'background-color:'+text_color+';';
                                        CSS += '}';
                                    }

                                    if (icon_margin.length) {
                                        CSS += '#et-heading-'+ID+'.icon-position-left .icon {margin-right:'+icon_margin+'px;}';
                                        CSS += '#et-heading-'+ID+'.icon-position-right .icon {margin-left:'+icon_margin+'px;}';
                                    }
                                    if (icon_font_size.length) {
                                        CSS += '#et-heading-'+ID+' .icon {';
                                            CSS += 'width:'+icon_font_size+'px !important;';
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

                                    CSS += '#et-heading-'+ID+' {';
                                        CSS += 'margin:'+margin_value+';';
                                    CSS += '}';

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

                                    CSS += '#et-heading-'+ID+' .text-wrapper {';
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
                                var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-heading');
                                if (typeof(element) != 'undefined' && element != null) {
                                    iframeSCRIPT(element,doc);
                                }
                            });
                        }
                        return;
                    }

                    

        });

})(jQuery);