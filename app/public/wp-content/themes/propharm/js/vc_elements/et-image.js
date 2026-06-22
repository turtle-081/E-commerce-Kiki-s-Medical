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

    function disableParallax(doc){
        if ($(doc).width() <= 1300) {
            $('.et-image.parallax').each(function(){
                $(this).addClass('parallax-off');
            });
        } else {
            $('.et-image.parallax').each(function(){
                $(this).removeClass('parallax-off');
            });
        }
    }

    function iframeSCRIPT(element,doc){

        $(element).each(function(){

            let lazyImages = [].slice.call(this.querySelectorAll("img.lazy"));

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

            }

            var $this = $(this);

            if ($this.hasClass('animate-true')) {

                var delay = '+='+(0.2 + parseInt($this.data('delay'))/1000),
                    animation = $this.data('animation');

                var tl = new gsap.timeline({paused: true});

                if (
                    animation == "curtain-left" || 
                    animation == "curtain-right" || 
                    animation == "curtain-top" || 
                    animation == "curtain-bottom"
                ) {

                    var curtain = $this.find('.curtain');

                    switch(animation){
                        case "curtain-left":

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

                        break;

                        case "curtain-right":

                            tl.to(curtain,0.8, {
                              scaleX:1,
                              transformOrigin:'right top',
                              ease:"power3.out"
                            },delay);

                            tl.to(curtain,0.8, {
                              scaleX:0,
                              transformOrigin:'left top',
                              ease:"power3.out"
                            });
                        
                        break;

                        case "curtain-top":

                            tl.to(curtain,0.8, {
                              scaleY:1,
                              transformOrigin:'left top',
                              ease:"power3.out"
                            },delay);

                            tl.to(curtain,0.8, {
                              scaleY:0,
                              transformOrigin:'left bottom',
                              ease:"power3.out"
                            });
                        
                        break;

                        case "curtain-bottom":

                            tl.to(curtain,0.8, {
                              scaleY:1,
                              transformOrigin:'left bottom',
                              ease:"power3.out"
                            },delay);

                            tl.to(curtain,0.8, {
                              scaleY:0,
                              transformOrigin:'left top',
                              ease:"power3.out"
                            });
                        
                        break;
                    }

                    tl.to($this.find('img'),0.2, {
                      opacity:1,
                    },'-=0.8');
                }

                if (animation == "fade-blur") {

                    tl.to($this,{
                        duration: 0.6,
                        opacity:1,
                    },delay);
                }

                if (animation == "left") {
                    
                    tl.to($this,{
                        duration: 0.8,
                        opacity:1,
                        x:0,
                        transformOrigin:'left top',
                        force3D:true,
                        ease:"expo.out"
                    },delay);

                }

                if (animation == "right") {
                    
                    tl.to($this,{
                        duration: 0.8,
                        opacity:1,
                        x:0,
                        transformOrigin:'left top',
                        force3D:true,
                        ease:"expo.out"
                    },delay);

                }

                if (animation == "top") {
                    
                    tl.to($this,{
                        duration: 0.8,
                        opacity:1,
                        y:0,
                        transformOrigin:'left top',
                        force3D:true,
                        ease:"expo.out"
                    },delay);

                }

                if (animation == "bottom") {
                    
                    tl.to($this,{
                        duration: 0.8,
                        opacity:1,
                        y:0,
                        transformOrigin:'left top',
                        force3D:true,
                        ease:"expo.out"
                    },delay);

                }

                tl.progress(0);
                tl.play();

            }

            if ($this.hasClass('parallax')) {

                var x = $this.data('coordinatex'),
                    y = $this.data('coordinatey'),
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

                    } else {
                        $this.removeAttr('style');
                    }

                });
            }

            disableParallax(doc);
            $(doc).resize(function(){
                disableParallax(doc);
            });
        
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
                return dataObj[key] === "et_image";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_image"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_image"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');


                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_image"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_image"]').length) {

                        var ID  = uniqueID();
                        var CSS = '';

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_image"]');

                        /* Styling
                        ---------------*/

                            var curtain_color = edit_element.find('input[name="element_color"]').val(),
                                parallax      = edit_element.find('input[name="parallax"]:checked').val(),
                                parallax_x    = edit_element.find('input[name="parallax_x"]').val(),
                                parallax_y    = edit_element.find('input[name="parallax_y"]').val();

                            if (!parallax_x.length) {
                                parallax_x = 0;
                            }

                            if (!parallax_y.length) {
                                parallax_y = 0;
                            }

                            if (curtain_color.length) {
                                CSS += '#et-image-'+ID+' .curtain {';
                                    CSS += 'background-color:'+curtain_color+';';
                                CSS += '}';
                            }

                            if(typeof(parallax) != "undefined" && parallax.length && parallax != null){
                                CSS += '#et-image-'+ID+' {';
                                    CSS += 'transform:translate3d('+parallax_x+'px,'+parallax_y+'px,0px);';
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

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){
                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-image');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);