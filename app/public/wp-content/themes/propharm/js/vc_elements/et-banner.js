(function($){

    "use strict";


    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    function isInArray(value, array) {return array.indexOf(value) > -1;}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function responsiveBannerImage(doc){

        if(window.matchMedia("(max-width: 374px)").matches) {
            $('.banner-image').each(function(){
                $(this).attr('style',$(this).attr('data-mm-style')).addClass('resp');
            });
        } else if(window.matchMedia("(min-width: 375px) and (max-width: 767px)").matches) {
            $('.banner-image').each(function(){
                $(this).attr('style',$(this).attr('data-m-style')).addClass('resp');
            });
        } else if (window.matchMedia("(min-width: 768px) and (max-width: 1023px)").matches) {
            $('.banner-image').each(function(){
                $(this).attr('style',$(this).attr('data-tp-style')).addClass('resp');
            });
        } else if(window.matchMedia("(min-width: 1024px) and (max-width: 1279px)").matches) {
            $('.banner-image').each(function(){
                $(this).attr('style',$(this).attr('data-tl-style')).addClass('resp');
            });
        } else {
            $('.banner-image').each(function(){
                $(this).removeAttr('style').removeClass('resp');
            });
        }

        if ($(doc).width() <= 1300) {
            $('.banner-image.parallax').each(function(){
                $(this).addClass('parallax-off');
            });
        } else {
            $('.banner-image.parallax').each(function(){
                $(this).removeAttr('style').removeClass('parallax-off');
            });
        }
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

            let lazyImages = [].slice.call(this.querySelectorAll("img.lazy"));
            let lazyBanners = [].slice.call(this.querySelectorAll(".banner-back.image"));

            if ("IntersectionObserver" in window) {

                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;

                            lazyImage.onload = function() {
                                lazyImage.classList.remove("lazy");
                                lazyImage.parentElement.classList.add("loaded");
                                lazyImageObserver.unobserve(lazyImage);
                            };
                            
                        }
                    });
                });

                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });

                let lazyBannerObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyBanner = entry.target;
                            lazyBanner.style.backgroundImage = 'url('+lazyBanner.dataset.background+')';
                            lazyBanner.removeAttribute('data-background');
                            lazyBannerObserver.unobserve(lazyBanner);
                        }
                    });
                });

                lazyBanners.forEach(function(lazyBanner) {
                    lazyBannerObserver.observe(lazyBanner);
                });

            }

            var $this  = $(this);

            $this.find('.banner-image.parallax').each(function(){

                var $this = $(this),
                    x     = $this.data('coordinatex'),
                    y     = $this.data('coordinatey'),
                    limit = $this.data('limit');

                if (typeof(limit) == 'undefined') {limit = 0}

                $(doc).scroll(function(){

                    if (!$this.hasClass('parallax-off')) {

                        var yPos   = Math.round((0-$(doc).scrollTop()) / $this.data('speed'))  +  y;
                        var scroll = (Math.sign(y) == -1) ? Math.round((0-$(doc).scrollTop()) / $this.data('speed')) : yPos;

                        if (Math.abs(scroll) > limit && limit > 0) {
                            yPos = (Math.sign(y) == -1) ? Math.sign(yPos)*(limit+Math.abs(y)) : Math.sign(yPos)*limit;
                        }
                        gsap.to($this,0.8,{
                            x:x,
                            y:yPos,
                            force3D:true,
                            ease:"expo.out"
                        });

                    } else if(!$this.hasClass('resp')) {
                        $this.removeAttr('style');
                    }

                });
                
            });

            responsiveBannerImage(doc);
            $(doc).resize(function(){
                responsiveBannerImage(doc);
            });

        });
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
                        return dataObj[key] === "et_banner";
                    });

                /* Edit element
                /*-------------*/

                    if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_banner"){

                        var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_banner"]');

                        var element_css  = edit_element.find('textarea[name="element_css"]'),
                            element_id   = edit_element.find('input[name="element_id"]'),
                            padding_box   = edit_element.find(".padding-box"),
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

                        var table              = edit_element.find(".column-responsive-padding"),
                            media_query        = table.find(".media-query"),
                            crp                = edit_element.find("input[name=\"crp\"]"),
                            resp_padding_array = [];

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

                        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_banner"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                            if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_banner"]').length) {

                                var ID  = uniqueID();
                                var CSS = '';

                                edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_banner"]');

                                /* Styling
                                ---------------*/

                                    var back_color     = edit_element.find('input[name="back_color"]').val(),
                                        back_image     = edit_element.find('input[name="back_image"]').val(),
                                        back_size      = edit_element.find('select[name="back_size"] option:selected').val(),
                                        back_repeat    = edit_element.find('select[name="back_repeat"] option:selected').val(),
                                        back_position  = edit_element.find('input[name="back_position"]').val(),
                                        parallax_x     = edit_element.find('input[name="parallax_x"]').val(),
                                        parallax_y     = edit_element.find('input[name="parallax_y"]').val();

                                    if (!parallax_x.length) {
                                        parallax_x = 0;
                                    }

                                    if (!parallax_y.length) {
                                        parallax_y = 0;
                                    }

                                    CSS += '#et-banner-'+ID+' .banner-image {';
                                        CSS += 'transform:translate3d('+parallax_x+'px,'+parallax_y+'px,0px);';
                                    CSS += '}';

                                    if (back_color.length) {
                                        CSS += '#et-banner-'+ID+' .banner-back {';
                                            CSS += 'background-color:'+back_color+';';
                                        CSS += '}';
                                    }

                                    CSS += '#et-banner-'+ID+' .banner-back {';

                                        if(back_size.length){
                                            CSS += "background-size:"+back_size+";";
                                        }

                                        if(back_repeat.length){
                                            CSS += "background-repeat:"+back_repeat+";";
                                        }

                                        if(back_position.length){
                                            CSS += "background-position:"+back_position+";";
                                        }

                                        if (back_color.length) {
                                            CSS += 'background-color:'+back_color+';';
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

                                    CSS += '#et-banner-'+ID+' {';
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
                                var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-banner');
                                if (typeof(element) != 'undefined' && element != null) {
                                    iframeSCRIPT(element,doc);
                                }
                            });
                        }
                        return;
                    }

                    

        });

})(jQuery);