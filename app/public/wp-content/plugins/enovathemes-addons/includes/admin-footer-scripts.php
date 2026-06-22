<?php

    add_action('admin_print_footer_scripts', 'propharm_enovathemes_print_admin_footer_scripts');
    function propharm_enovathemes_print_admin_footer_scripts() {

        $current_screen = get_current_screen();

        if( $current_screen->base === "post" ) {
            $google_fonts = enovathemes_addons_google_fonts();

            ob_start(); ?>
            <script>

                function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

                function iframeCSS(CSS){
                    var iframe = jQuery('#vc_inline-frame');
                    if (typeof(iframe) != 'undefined' && iframe != null){
                        iframe.ready(function() {
                            CSS = CSS.replaceAll("dir-child*",">");
                            iframe.contents().find("#dynamic-styles-inline-css").append(CSS);
                        });
                    }
                }

                function iframeFONT(FONT){
                    var iframe = jQuery('#vc_inline-frame');
                    if (typeof(iframe) != 'undefined' && iframe != null){
                        iframe.ready(function() {

                            if (FONT != '') {

                                var FONT_array = FONT.split(":");


                                if (FONT_array[0] != "Theme default") {

                                    var font_name   = FONT_array[0];
                                    var font_weight = FONT_array[1];
                                    var font_subset = FONT_array[2];

                                    font_name   = font_name.replace(" ", "+");
                                    font_weight = font_weight.replace("italic", "i");
                                    font_weight = font_weight.replace("regular", "400");
                                    font_subset = font_subset.replace("latin", "");

                                    var FONT_link = 'https://fonts.googleapis.com/css?family='+font_name+':'+font_weight;

                                    if (font_subset != '') {
                                        FONT_link += 'amp;subset='+font_subset;
                                    }

                                    iframe.contents().find("head").append('<link href="'+FONT_link+'" rel="stylesheet">');

                                }

                            }


                        });
                    }
                }

                function set_font_defaults(element_font,font_weight,font_subsets){

                    var element_font_val = element_font.val();

                    if(typeof(element_font_val) != "undefined" && element_font_val.length){

                        var element_font_array = element_font_val.split(":");
                        var selected_font_weight = element_font_array[1];
                        var selected_font_subsets = element_font_array[2];

                        if(typeof(selected_font_weight) != "undefined" && selected_font_weight.length){
                            jQuery("select[name=\""+font_weight+"\"] > option[value=\""+selected_font_weight+"\"]").attr("selected","selected").siblings().removeAttr("selected");
                        }

                        if(typeof(selected_font_subsets) != "undefined" && selected_font_subsets.length){
                            jQuery("select[name=\""+font_subsets+"\"] > option[value=\""+selected_font_subsets+"\"]").attr("selected","selected").siblings().removeAttr("selected");
                        }

                    } else {
                        jQuery("select[name=\""+font_weight+"\"] > option[value=\"400\"]").attr("selected","selected").siblings().removeAttr("selected");
                        jQuery("select[name=\""+font_subsets+"\"] > option[value=\"latin\"]").attr("selected","selected").siblings().removeAttr("selected");
                    }

                }

                function manage_font(edit_element,google_fonts,selected,font_subsets,font_weight){

                    var font_subsets = edit_element.find("select[name=\""+font_subsets+"\"]").empty();
                    var font_weight  = edit_element.find("select[name=\""+font_weight+"\"]").empty();

                    var index    = 0;
                    var exists = Object.keys(google_fonts).some(function(i) {
                        if(google_fonts[i]["family"] === selected){
                            index = i;
                            return true;
                        } else {
                            return false;
                        }
                    });


                    var font_weight_array = (exists) ? google_fonts[index]["variants"] : ["100","200","300","400","500","600","700","800","900"];
                    var font_subsets_array = (exists) ? google_fonts[index]["subsets"] : ["latin"];

                    for (var i=0; i < font_weight_array.length; i++) {
                        font_weight.append("<option value=\""+font_weight_array[i]+"\" class=\""+font_weight_array[i]+"\">"+font_weight_array[i]+"</option>");
                    }

                    for (var i=0; i < font_subsets_array.length; i++) {
                        font_subsets.append("<option value=\""+font_subsets_array[i]+"\" class=\""+font_subsets_array[i]+"\">"+font_subsets_array[i]+"</option>");
                    }

                }

                function backgroundScroll(el,speed,direction){
                    var size = (direction == "horizontal") ? el.data('img-width') : el.data('img-height');
                    if (direction == "horizontal") {
                        el.animate({'background-position-x' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-x','0');backgroundScroll(el, speed,direction);}});
                    } else if (direction == "vertical") {
                        el.animate({'background-position-y' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-y','0');backgroundScroll(el, speed,direction);}});
                    };
                }

                function iframeSCRIPT(element,doc){
                    jQuery(element).each(function(){

                        var $this  = jQuery(this);

                        if ($this.hasClass('wpb_animate_when_almost_visible')) {
                            $this
                            .addClass('wpb_start_animation')
                            .addClass('animated');
                        }

                        $this.find('.wpb_animate_when_almost_visible').each(function(){
                            jQuery(this)
                            .addClass('wpb_start_animation')
                            .addClass('animated');
                        });

                        if ($this.find('.video-container').length) {
                            $this.find('.video-container').trigger('play');
                        }

                        if ($this.hasClass('vc-parallax')) {

                            var plx = $this.find('.parallax-container');

                            if ($this.hasClass('vc-video-parallax')) {
                                plx = $this.find('.video-container');
                            }

                            var duration = parseInt($this.data('parallax-duration')),
                                ratio    = (typeof(duration) != 'undefined' && duration != null && duration != 0) ? 0.1 : 1;

                                duration = duration/100;

                            doc.scroll(function() {
                                var yPos   = Math.round((doc.scrollTop()-plx.offset().top) / $this.data('parallax-speed'));
                                yPos = ratio*yPos;
                                gsap.to(plx,{
                                    duration:duration,
                                    delay:0,
                                    y:yPos,
                                });
                            });

                            return;

                        }

                        if ($this.hasClass('vc-fixed-bg')) {

                            var fx = $this.find('.fixed-container'),
                                $secHeight = $this.outerHeight(),
                                $secWidth  = $this.outerWidth(),
                                fxHeight   = ($secHeight > doc.height()) ? $secHeight : doc.height();

                            fx.css({'height':fxHeight*1.2+'px'});

                            doc.resize(function(){
                                fx.css({'height':fxHeight+100+'px'});
                            });

                            return;
                        }

                        if ($this.hasClass('vc-animated-bg')) {

                            var animatedBg    = $this.find('.animated-container'),
                                animatedDir   = $this.data('animatedbg-dir'),
                                animatedSpeed = $this.data('animatedbg-speed');

                            if (animatedDir == 'horizontal') {
                                backgroundScroll(animatedBg, animatedSpeed, 'horizontal');
                            } else if (animatedDir == 'vertical') {
                                backgroundScroll(animatedBg, animatedSpeed, 'vertical');
                            };

                            return;
                        }


                        /* Curtain
                        /*-------------*/

                            var curtain   = $this.find('.vc-curtain');

                            if (curtain.length) {

                                var row       = $this,
                                    content   = (row.find('.container').length) ? row.find('.container') : row.find('.wpb_wrapper'),
                                    animation = curtain.data('curtain'),
                                    delay     = 0.2;

                                var tl = new gsap.timeline({paused: true});

                                if (
                                    animation == "curtain-left" || 
                                    animation == "curtain-right" || 
                                    animation == "curtain-top" || 
                                    animation == "curtain-bottom"
                                ) {

                                    switch(animation){
                                        case "curtain-left":

                                            tl.to(curtain,0.8, {
                                              scaleX:1,
                                              transformOrigin:'left top',
                                              ease:"power3.out"
                                            },delay);

                                        break;

                                        case "curtain-right":

                                            tl.to(curtain,0.8, {
                                              scaleX:1,
                                              transformOrigin:'right top',
                                              ease:"power3.out"
                                            },delay);
                                        
                                        break;

                                        case "curtain-top":

                                            tl.to(curtain,0.8, {
                                              scaleY:1,
                                              transformOrigin:'left top',
                                              ease:"power3.out"
                                            },delay);
                                        
                                        break;

                                        case "curtain-bottom":

                                            tl.to(curtain,0.8, {
                                              scaleY:1,
                                              transformOrigin:'left bottom',
                                              ease:"power3.out"
                                            },delay);

                                        break;
                                    }

                                    tl.from(content,0.2, {
                                      opacity:0,
                                    },'-=0.5');
                                }

                                tl.progress(0);
                                tl.play();

                            }

                        /* Header builder
                        /*-------------*/

                            $this.find(".hbe").each(function(){
                                var $hbe = jQuery(this);
                                var attr = $hbe.parent().attr('data-tag');
                                var hasAttribute = (typeof attr !== 'undefined' && attr !== false) ? true : false;
                                if ($hbe.hasClass('hbe-right') && hasAttribute) {$hbe.parent().addClass('hbe-right');}
                                if ($hbe.hasClass('hbe-left') && hasAttribute) {$hbe.parent().addClass('hbe-left');}
                                if ($hbe.hasClass('hbe-center') && hasAttribute) {$hbe.parent().addClass('hbe-center');}
                            });

                        /* Title section
                        /*-------------*/

                            $this.find(".tse").each(function(){
                                var $tse = jQuery(this);
                                var attr = $tse.parent().attr('data-tag');
                                var hasAttribute = (typeof attr !== 'undefined' && attr !== false) ? true : false;
                                if ($tse.hasClass('tse-right') && hasAttribute) {$tse.parent().addClass('tse-right');}
                                if ($tse.hasClass('tse-left') && hasAttribute) {$tse.parent().addClass('tse-left');}
                                if ($tse.hasClass('tse-center') && hasAttribute) {$tse.parent().addClass('tse-center');}
                            });

                    });
                }

                /* Font elements
                /*-------------*/

                    (function($){

                        "use strict";

                        var google_fonts = <?php echo json_encode($google_fonts); ?>;
                        var iframe       = jQuery('#vc_inline-frame');

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

                            if (settings['type'] != 'POST') {return;}

                            /* Prepare settings
                            /*-------------*/

                                var data = settings['data'];

                                data = data.split("&");

                                var dataObj = [{}];

                                for (var i = 0; i < data.length; i++) {
                                    var property = data[i].split("=");
                                    var key      = (property[0]);
                                    var value    = (property[1]);
                                    dataObj[key] = value;
                                }

                            /* et_header_menu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_header_menu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_header_menu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_header_menu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_header_menu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;
                                }

                            /* et_megamenu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_megamenu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;

                                }

                            /* et_megamenu_tab
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_megamenu_tab"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu_tab\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu_tab\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_megamenu_tab\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_mobile_menu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_mobile_menu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_mobile_menu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_mobile_menu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_mobile_menu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;

                                }

                            /* et_modal_menu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_modal_menu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_modal_menu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_modal_menu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_modal_menu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;

                                }

                            /* et_sidebar_menu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_sidebar_menu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_sidebar_menu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_sidebar_menu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_sidebar_menu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;

                                }

                            /* et_title_section_title
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_title_section_title"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_title\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_title\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_title\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_title_section_subtitle
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_title_section_subtitle"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_subtitle\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_subtitle\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_title_section_subtitle\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_heading
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_heading"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_heading\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_heading\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_heading\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_highlight_heading
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_highlight_heading"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_highlight_heading\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_highlight_heading\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_highlight_heading\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_typeit
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_typeit"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_typeit\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_typeit\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_typeit\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* et_menu
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_menu"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_menu\"]");

                                    var FONT    = "";
                                    var SUBFONT = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    var subelement_font = jQuery("#vc_ui-panel-edit-element input[name=\"subelement_font\"]");
                                    var subfont_family  = edit_element.find("select[name=\"subfont_family\"]");
                                    var subselected     = subfont_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    $.when(manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight")).done(function(){
                                        set_font_defaults(subelement_font,"subfont_weight","subfont_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    subfont_family.on("change",function(){
                                        subselected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,subselected,"subfont_subsets","subfont_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_menu\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_menu\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            var subfont_family_selected  = edit_element.find("select[name=\"subfont_family\"] option:selected").val();
                                            var subfont_subsets_selected = edit_element.find("select[name=\"subfont_subsets\"] option:selected").val();
                                            var subfont_weight_selected  = edit_element.find("select[name=\"subfont_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                            if (subfont_family_selected.length && subfont_subsets_selected.length && subfont_weight_selected.length) {
                                                SUBFONT = subfont_family_selected+":"+subfont_weight_selected+":"+subfont_subsets_selected;

                                                if(SUBFONT.length){
                                                    subelement_font.val(SUBFONT);
                                                    iframeFONT(SUBFONT);
                                                }
                                            }


                                        }

                                    });

                                    return;
                                }

                            /* et_button
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_button"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_button\"]");

                                    var FONT    = "";

                                    var element_font = jQuery("#vc_ui-panel-edit-element input[name=\"element_font\"]");
                                    var font_family  = edit_element.find("select[name=\"font_family\"]");
                                    var selected     = font_family.find("option:selected").text();

                                    $.when(manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight")).done(function(){
                                        set_font_defaults(element_font,"font_weight","font_subsets");
                                    });

                                    font_family.on("change",function(){
                                        selected = jQuery(this).find("option:selected").text();
                                        manage_font(edit_element,google_fonts,selected,"font_subsets","font_weight");
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_button\"]");

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"et_button\"]").length){

                                            var font_family_selected  = edit_element.find("select[name=\"font_family\"] option:selected").val();
                                            var font_subsets_selected = edit_element.find("select[name=\"font_subsets\"] option:selected").val();
                                            var font_weight_selected  = edit_element.find("select[name=\"font_weight\"] option:selected").val();

                                            if (font_family_selected.length && font_subsets_selected.length && font_weight_selected.length) {
                                                FONT = font_family_selected+":"+font_weight_selected+":"+font_subsets_selected;

                                                if(FONT.length){
                                                    element_font.val(FONT);
                                                    iframeFONT(FONT);
                                                }
                                            }

                                        }

                                    });

                                    return;

                                }
                        });

                    })(jQuery);

                /* vc_row
                /*-------------*/

                    (function($){

                        "use strict";

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_row";
                                });

                            /* Edit element
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "vc_row"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row\"]");

                                    var element_css  = edit_element.find("textarea[name=\"element_css\"]");
                                    var element_id  = edit_element.find("input[name=\"element_id\"]");

                                    var parallax    = edit_element.find("input[name=\"parallax\"]").addClass("checkbox-change");
                                    var video_bg    = edit_element.find("input[name=\"video_bg\"]").addClass("checkbox-change");
                                    var fixed_bg    = edit_element.find("input[name=\"fixed_bg\"]").addClass("checkbox-change");
                                    var animated_bg = edit_element.find("input[name=\"animated_bg\"]").addClass("checkbox-change");

                                    parallax.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    video_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    fixed_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    animated_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");

                                    jQuery(".checkbox-change").on("change", function() {
                                        if(jQuery(this).is(":checked")){
                                            jQuery(".checkbox-change").not(this).prop("checked", false);
                                            jQuery("#vc_edit-form-tab-2 .vc_shortcode-param").not(".et-checkbox-group").addClass("vc_dependent-hidden");
                                            jQuery("#vc_edit-form-tab-2 [data-vc-shortcode-param-name^=\""+jQuery(this).attr("name")+"\"]").removeClass("vc_dependent-hidden");
                                        }
                                    });

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row\"] .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row\"]").length){

                                            var ID  = uniqueID();
                                            var CSS = "";

                                            edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row\"]");

                                            /* Header builder
                                            ---------------*/

                                                var row_height = edit_element.find("input[name=\"row_height\"]").val();
                                                var row_height_sticky = edit_element.find("input[name=\"row_height_sticky\"]").val();
                                                var row_background_sticky = edit_element.find("input[name=\"row_background_sticky\"]").val();
                                                var z_index = edit_element.find("input[name=\"z_index\"]").val();

                                                if(z_index.length){
                                                    CSS += ".header .vc-row-"+ID+" {z-index:"+z_index+";}";
                                                }

                                                if(row_height.length){
                                                    CSS += ".header .vc-row-"+ID+" {height:"+row_height+"px;}";
                                                    CSS += ".header .vc-row-"+ID+" .hbe {line-height:"+row_height+"px;height:"+row_height+"px;}";
                                                }

                                                if(row_height_sticky.length){
                                                    CSS += ".header.sticky-true.active .vc-row-"+ID+" {height:"+row_height_sticky+"px;}";
                                                    CSS += ".header.sticky-true.active .vc-row-"+ID+" .hbe {line-height:"+row_height_sticky+"px;height:"+row_height_sticky+"px;}";
                                                }

                                                if(row_background_sticky.length){
                                                    CSS += ".header.sticky-true.active .vc-row-"+ID+" {background:"+row_background_sticky+" !important;background-color:"+row_background_sticky+" !important;}";
                                                }

                                            /* Parallax
                                            ---------------*/

                                                var parallax = edit_element.find("input[name=\"parallax\"]");

                                                if(parallax.is(":checked")){

                                                    var parallax_image = edit_element.find("input[name=\"parallax_image\"]").val();

                                                    if(parallax_image.length){
                                                        parallax_image = edit_element.find("img[rel=\""+parallax_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-row-"+ID+" .parallax-container {background-image:url("+parallax_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"parallax_image\"]").val('');
                                                }

                                            /* Fixed
                                            ---------------*/

                                                var fixed_bg = edit_element.find("input[name=\"fixed_bg\"]");

                                                if(fixed_bg.is(":checked")){

                                                    var fixed_bg_image = edit_element.find("input[name=\"fixed_bg_image\"]").val();

                                                    if(fixed_bg_image.length){
                                                        fixed_bg_image = edit_element.find("img[rel=\""+fixed_bg_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-row-"+ID+" .fixed-container {background-image:url("+fixed_bg_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"fixed_bg_image\"]").val('');
                                                }

                                            /* Video
                                            ---------------*/

                                                var video_bg = edit_element.find("input[name=\"video_bg\"]");

                                                if(video_bg.is(":checked")){

                                                    var video_bg_overlay = edit_element.find("input[name=\"video_bg_overlay\"]").val();
                                                    var video_bg_placeholder = edit_element.find("input[name=\"video_bg_placeholder\"]").val();

                                                    if(video_bg_overlay.length){
                                                        video_bg_overlay = edit_element.find("img[rel=\""+video_bg_overlay+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-row-"+ID+" .video-container-overlay {background-image:url("+video_bg_overlay+");}";
                                                    }

                                                    if(video_bg_placeholder.length){
                                                        video_bg_placeholder = edit_element.find("img[rel=\""+video_bg_placeholder+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-row-"+ID+" .video-container-placeholder {background-image:url("+video_bg_placeholder+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"video_bg_overlay\"]").val('');
                                                    edit_element.find("input[name=\"video_bg_placeholder\"]").val('');
                                                }

                                            /* Animated
                                            ---------------*/

                                                var animated_bg = edit_element.find("input[name=\"animated_bg\"]");

                                                if(animated_bg.is(":checked")){

                                                    var animated_bg_image = edit_element.find("input[name=\"animated_bg_image\"]").val();

                                                    if(animated_bg_image.length){
                                                        animated_bg_image = edit_element.find("img[rel=\""+animated_bg_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-row-"+ID+" .animated-container {background-image:url("+animated_bg_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"animated_bg_image\"]").val('');
                                                }

                                            /* Responsive background
                                            ---------------*/

                                                // var mobile_image = edit_element.find("input[name=\"mobile_image\"]").val();

                                                // if(mobile_image.length){
                                                //     mobile_image = edit_element.find("img[rel=\""+mobile_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                //     CSS += "@media only screen and (max-width: 767px){.vc-row-"+ID+" {background-image:url("+mobile_image+") !important;}";
                                                // }

                                            /* Curtain
                                            ---------------*/

                                                var curtain_color = edit_element.find("input[name=\"curtain_color\"]").val();

                                                if (curtain_color.length) {
                                                    CSS += ".vc-row-"+ID+" .vc-curtain {background-color:"+curtain_color+";}";
                                                }

                                            /* Overlays
                                            ---------------*/

                                                var top_gradient               = edit_element.find("input[name=\"top_gradient\"]"),
                                                    bottom_gradient            = edit_element.find("input[name=\"bottom_gradient\"]"),
                                                    gradient_color             = edit_element.find("input[name=\"gradient_color\"]").val(),
                                                    curtain_gradient           = edit_element.find("input[name=\"curtain_gradient\"]"),
                                                    curtain_gradient_position  = edit_element.find("select[name=\"curtain_gradient_position\"] option:selected").val(),
                                                    curtain_gradient_color     = edit_element.find("input[name=\"curtain_gradient_color\"]").val();

                                                if(top_gradient.is(":checked")){

                                                    if(gradient_color.length){
                                                        CSS += ".vc-row-"+ID+" .top-gradient {background:linear-gradient(to bottom, "+gradient_color+" 0%,rgba(255,255,255,0) 100%);}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                }

                                                if(bottom_gradient.is(":checked")){

                                                    if(gradient_color.length){
                                                        CSS += ".vc-row-"+ID+" .bottom-gradient {background:linear-gradient(to top, "+gradient_color+" 0%,rgba(255,255,255,0) 100%);}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                }

                                                if(curtain_gradient.is(":checked")){

                                                    if(curtain_gradient_color.length){
                                                        if (curtain_gradient_position == "left") {
                                                            CSS += ".vc-row-"+ID+" .curtain-gradient {background:linear-gradient(-55deg, transparent 6%, "+curtain_gradient_color+" 50%);}";
                                                        } else {
                                                            CSS += ".vc-row-"+ID+" .curtain-gradient {background:linear-gradient(55deg, transparent 6%, "+curtain_gradient_color+" 50%);}";
                                                        }
                                                    }

                                                } else {
                                                    CSS += "";
                                                }

                                            element_id.val(ID);

                                            if (CSS) {
                                                element_css.text(CSS);
                                                iframeCSS(CSS);
                                                CSS = "";
                                            }


                                        }

                                    });

                                    return;

                                }

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .vc_row');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                iframeSCRIPT(element,doc);
                                            }
                                        });
                                    }
                                    return;
                                }

                        });

                    })(jQuery);

                /* vc_row_inner
                ---------------*/

                    (function($){

                        "use strict";

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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

                            /* Edit element
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "vc_row_inner"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row_inner\"]");
                                    var element_css  = edit_element.find("textarea[name=\"element_css\"]");
                                    var element_id  = edit_element.find("input[name=\"element_id\"]");

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row_inner\"] .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row_inner\"]").length){

                                            var ID  = uniqueID();
                                            var CSS = "";

                                            edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_row_inner\"]");

                                            /* Header builder
                                            ---------------*/

                                                var row_height = edit_element.find("input[name=\"row_height\"]").val();
                                                var row_height_sticky = edit_element.find("input[name=\"row_height_sticky\"]").val();
                                                var z_index = edit_element.find("input[name=\"z_index\"]").val();

                                                if(z_index.length){
                                                    CSS += ".header .vc-row-"+ID+" {z-index:"+z_index+";}";
                                                }

                                                if(row_height.length){
                                                    CSS += ".header .vc-row-"+ID+" {height:"+row_height+"px;}";
                                                    CSS += ".header .vc-row-"+ID+" .hbe {line-height:"+row_height+"px;height:"+row_height+"px;}";
                                                }

                                                if(row_height_sticky.length){
                                                    CSS += ".header.sticky-true.active .vc-row-"+ID+" {height:"+row_height_sticky+"px;}";
                                                    CSS += ".header.sticky-true.active .vc-row-"+ID+" .hbe {line-height:"+row_height_sticky+"px;}";
                                                }

                                            element_id.val(ID);

                                            element_css.text(CSS);

                                            if (CSS) {
                                                element_css.text(CSS);
                                                iframeCSS(CSS);
                                                CSS = "";
                                            }

                                        }

                                    });

                                    return;

                                }

                        });

                    })(jQuery);

                /* vc_column
                ---------------*/

                    (function($){

                        "use strict";

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_column";
                                });

                            /* Edit element
                            ---------------*/

                                if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "vc_column"){

                                    var edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_column\"]");
                                    var element_css  = edit_element.find('textarea[name="element_css"]');
                                    var element_id   = edit_element.find('input[name="element_id"]');
                                    var table        = edit_element.find(".column-responsive-padding");
                                    var media_query  = table.find(".media-query");
                                    var crp          = edit_element.find("input[name=\"crp\"]");
                                    var padding_array= [];

                                    var parallax    = edit_element.find("input[name=\"parallax\"]").addClass("checkbox-change");
                                    var video_bg    = edit_element.find("input[name=\"video_bg\"]").addClass("checkbox-change");
                                    var fixed_bg    = edit_element.find("input[name=\"fixed_bg\"]").addClass("checkbox-change");
                                    var animated_bg = edit_element.find("input[name=\"animated_bg\"]").addClass("checkbox-change");

                                    parallax.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    video_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    fixed_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");
                                    animated_bg.parents(".vc_shortcode-param").addClass("et-checkbox-group");

                                    jQuery(".checkbox-change").on("change", function() {
                                        if(jQuery(this).is(":checked")){
                                            jQuery(".checkbox-change").not(this).prop("checked", false);
                                            jQuery("#vc_edit-form-tab-3 .vc_shortcode-param").not(".et-checkbox-group").addClass("vc_dependent-hidden");
                                            jQuery("#vc_edit-form-tab-3 [data-vc-shortcode-param-name^=\""+jQuery(this).attr("name")+"\"]").removeClass("vc_dependent-hidden");

                                        }
                                    });

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

                                    // Save values
                                    jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_column\"] .vc_ui-button-action[data-vc-ui-element=\"button-save\"]").on("click",function(){

                                        if(jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_column\"]").length){

                                            var CSS = '';
                                            var ID  = uniqueID();

                                            edit_element = jQuery("#vc_ui-panel-edit-element[data-vc-shortcode=\"vc_column\"]");

                                            /* Parallax
                                            ---------------*/

                                                parallax = edit_element.find("input[name=\"parallax\"]");

                                                if(parallax.is(":checked")){

                                                    var parallax_image = edit_element.find("input[name=\"parallax_image\"]").val();

                                                    if(parallax_image.length){
                                                        parallax_image = edit_element.find("img[rel=\""+parallax_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-column-"+ID+" .parallax-container {background-image:url("+parallax_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"parallax_image\"]").val('');
                                                }

                                            /* Fixed
                                            ---------------*/

                                                fixed_bg = edit_element.find("input[name=\"fixed_bg\"]");

                                                if(fixed_bg.is(":checked")){

                                                    var fixed_bg_image = edit_element.find("input[name=\"fixed_bg_image\"]").val();

                                                    if(fixed_bg_image.length){
                                                        fixed_bg_image = edit_element.find("img[rel=\""+fixed_bg_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-column-"+ID+" .fixed-container {background-image:url("+fixed_bg_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"fixed_bg_image\"]").val('');
                                                }

                                            /* Video
                                            ---------------*/

                                                video_bg = edit_element.find("input[name=\"video_bg\"]");

                                                if(video_bg.is(":checked")){

                                                    var video_bg_overlay = edit_element.find("input[name=\"video_bg_overlay\"]").val();
                                                    var video_bg_placeholder = edit_element.find("input[name=\"video_bg_placeholder\"]").val();

                                                    if(video_bg_overlay.length){
                                                        video_bg_overlay = edit_element.find("img[rel=\""+video_bg_overlay+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-column-"+ID+" .video-container-overlay {background-image:url("+video_bg_overlay+");}";
                                                    }

                                                    if(video_bg_placeholder.length){
                                                        video_bg_placeholder = edit_element.find("img[rel=\""+video_bg_placeholder+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-column-"+ID+" .video-container-placeholder {background-image:url("+video_bg_placeholder+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"video_bg_overlay\"]").val('');
                                                    edit_element.find("input[name=\"video_bg_placeholder\"]").val('');
                                                }

                                            /* Animated
                                            ---------------*/

                                                animated_bg = edit_element.find("input[name=\"animated_bg\"]");

                                                if(animated_bg.is(":checked")){

                                                    var animated_bg_image = edit_element.find("input[name=\"animated_bg_image\"]").val();

                                                    if(animated_bg_image.length){
                                                        animated_bg_image = edit_element.find("img[rel=\""+animated_bg_image+"\"]").attr("src").replace("-425x425", "").replace("-150x150", "");
                                                        CSS += ".vc-column-"+ID+" .animated-container {background-image:url("+animated_bg_image+");}";
                                                    }

                                                } else {
                                                    CSS += "";
                                                    edit_element.find("input[name=\"animated_bg_image\"]").val('');
                                                }

                                            /* Curtain
                                            ---------------*/

                                                var curtain_color = edit_element.find("input[name=\"curtain_color\"]").val();

                                                if (curtain_color.length) {
                                                    CSS += ".vc-column-"+ID+" .vc-curtain {background-color:"+curtain_color+";}";
                                                }

                                            /* Responsive padding
                                            ---------------*/

                                                if(crp.length){

                                                    for(var i=0;i<media_query.length;i++){
                                                        var query = jQuery(media_query[i]).data("query");
                                                        var left = jQuery(media_query[i]).find("td.left option:selected").val();
                                                        var right = jQuery(media_query[i]).find("td.right option:selected").val();
                                                        padding_array.push(query+":"+left+":"+right);
                                                    }

                                                    var padding_string = padding_array.join();
                                                    crp.val(padding_string);
                                                    padding_array= [];

                                                }

                                            element_id.val(ID);
                                            element_css.text(CSS);

                                            if (CSS) {
                                                element_css.text(CSS);
                                                iframeCSS(CSS);
                                                CSS = "";
                                            }

                                        }

                                    });

                                    return;

                                }

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .wpb_column');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                iframeSCRIPT(element,doc);
                                            }
                                        });
                                    }
                                    return;
                                }
                        });

                    })(jQuery);

                /* vc_column_text
                ---------------*/

                    (function($){

                        "use strict";

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_column_text";
                                });

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .wpb_text_column');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                iframeSCRIPT(element,doc);
                                            }
                                        });
                                    }
                                    return;
                                }
                        });

                    })(jQuery);

                /* vc_custom_heading
                ---------------*/

                    (function($){

                        "use strict";


                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_custom_heading";
                                });

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .vc_custom_heading');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                iframeSCRIPT(element,doc);
                                            }
                                        });
                                    }
                                    return;
                                }
                        });

                    })(jQuery);

                /* default widgets
                ---------------*/

                    (function($){

                        "use strict";


                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_wp_custommenu";
                                });

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .widget_nav_menu');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                jQuery(doc).find('.widget_nav_menu').each(function(){

                                                    var $this = jQuery(this);
                                                    var childItems = $this.find('.menu-item-has-children > a')
                                                    .append('<span class="toggle"></span>');

                                                    if ($this.find('.menu-item-has-children > a').attr( "href" ) == "#") {
                                                        $this.find('.menu-item-has-children > a').on('click',function(e){
                                                            jQuery(this).toggleClass('active');
                                                            if (jQuery(this).next('ul').length != 0) {
                                                                jQuery(this).toggleClass('animate');
                                                                jQuery(this).next('ul').stop().slideToggle(300, "easeOutExpo");
                                                                jQuery('.site-sidebar').getNiceScroll().resize();
                                                                jQuery('html').getNiceScroll().resize();
                                                            };
                                                            e.preventDefault();
                                                        });
                                                    } else {
                                                        $this.find('.menu-item-has-children > a > span.toggle').on('click',function(e){
                                                            jQuery(this).toggleClass('active');
                                                            if (jQuery(this).parent().next('ul').length != 0) {
                                                                jQuery(this).parent().toggleClass('animate');
                                                                jQuery(this).parent().next('ul').stop().slideToggle(300, "easeOutExpo");
                                                                jQuery('.site-sidebar').getNiceScroll().resize();
                                                                jQuery('html').getNiceScroll().resize();
                                                            };
                                                            e.preventDefault();
                                                        });
                                                    }

                                                });
                                            }
                                        });
                                    }
                                    return;
                                }


                        });

                        jQuery( document ).ajaxComplete(function( event, xhr, settings ) {

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
                                    return dataObj[key] === "vc_wp_calendar";
                                });

                            /* Load element
                            /*-------------*/

                                if((dataObj['action'] == "vc_load_shortcode") && elementExists){
                                    var iframe = jQuery('#vc_inline-frame');
                                    if (typeof(iframe) != 'undefined' && iframe != null){
                                        iframe.ready(function() {
                                            var doc = iframe.contents();
                                            var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .widget_calendar');
                                            if (typeof(element) != 'undefined' && element != null) {
                                                jQuery(doc).find('.widget_calendar').each(function(){

                                                    var $this = jQuery(this);
                                                    var caption = $this.find('caption');

                                                    $this.find('td#prev a').clone().addClass('prev').html('').appendTo(caption);
                                                    $this.find('td#next a').clone().addClass('next').html('').appendTo(caption);
                                                    $this.find('tfoot').remove();

                                                });
                                            }
                                        });
                                    }
                                    return;
                                }

                        });


                    })(jQuery);

            </script>
            <?php $output = ob_get_clean();
            echo $output;
        }

    }
?>