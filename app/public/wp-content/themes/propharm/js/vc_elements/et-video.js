(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function lightImage(src,overlay,doc){

        if (
            src.includes('.jpg') ||
            src.includes('.jpeg') ||
            src.includes('.png') ||
            src.includes('.bmp') ||
            src.includes('.gif') ||
            src.includes('.svg')
        ) {
            
            var img = $('<img src="'+src+'" />');
            if (overlay.find('img').length == 0) {
                overlay.prepend(img);
            }
            
        }

        if (src.includes('youtu') || src.includes('vimeo')) {
            var iframe = doc.createElement('iframe');

            src = src.replace('watch?v=', 'embed/');
            src = src.replace('//vimeo.com/', '//player.vimeo.com/video/');
            src = (src.indexOf("?") == -1) ? src += '?' : src += '&';
            
            iframe.src = src+'autoplay=1';
            iframe.frameborder = '0';
            iframe.width  = '1280';
            iframe.height = '720';
            iframe.allow  = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowfullscreen = true;
            overlay.prepend(iframe);
        }

        if (src.includes('mp4') || src.includes('webm') || src.includes('ogv')) {
            var video = doc.createElement('video');
            video.src = src;
            video.autoplay = true;
            video.controls = true;
            overlay.prepend(video);
        }
    }

    function gsapLightbox(element,gallery,doc){

        var structure = (gallery == true) ? 
        $('<div class="gsap-lightbox-overlay"><div class="image-wrapper"></div><a href="#" class="gsap-lightbox-controls gsap-lightbox-toggle"></a><a href="#" class="gsap-lightbox-controls gsap-lightbox-nav prev" data-direction="prev"></a><a href="#" class="gsap-lightbox-controls gsap-lightbox-nav next" data-direction="next"></a><svg class="placeholder" viewBox="0 0 20 4"><circle cx="2" cy="2" r="2" /><circle cx="10" cy="2" r="2" /><circle cx="18" cy="2" r="2" /></svg></div>') :
        $('<div class="gsap-lightbox-overlay"><div class="image-wrapper"></div><a href="#" class="gsap-lightbox-controls gsap-lightbox-toggle"></a><svg class="placeholder" viewBox="0 0 20 4"><circle cx="2" cy="2" r="2" /><circle cx="10" cy="2" r="2" /><circle cx="18" cy="2" r="2" /></svg></div>');

        $(doc).find('body').append(structure);

        var overlay = $(doc).find('.gsap-lightbox-overlay'),
            wrapper = overlay.find('.image-wrapper'),
            toggle  = overlay.find('.gsap-lightbox-toggle'),
            loading = overlay.find('.gsap-lightbox-toggle');

        var tl = new gsap.timeline({paused: true});

        tl.from(toggle,0.2, {
          opacity:0,
          ease:"expo.out"
        },'+=0.2');

        tl.from(toggle,1.2, {
          x:'-12px',
          ease:"elastic.out(1, 0.5)"
        },'-=0.2');

        if (gallery == true) {

            var nav         = overlay.find('.gsap-lightbox-nav'),
                next        = overlay.find('.next'),
                prev        = overlay.find('.prev'),
                gallerySet  = [],
                count       = 0,
                galleryName = element.data('gallery');

            tl.from(nav,0.2, {
                opacity:0,
            },'-=1.1');

            tl.from(prev,1.2, {
              x:'-40px',
              ease:"elastic.out(1, 0.5)"
            },'-=1.1');

            tl.from(next,1.2, {
              x:'40px',
              ease:"elastic.out(1, 0.5)"
            },'-=1.2');

            $(doc).find('a[data-gallery="'+galleryName+'"]').each(function(){
                gallerySet.push($(this).attr('href'));
            });

            count = gallerySet.indexOf(element.attr('href'));

            var max = gallerySet.length;
            
            nav.on('click',function(e){

                overlay.find('img').remove();

                e.preventDefault();

                count += ($(this).data('direction') == "next") ? 1 : -1;
                if (count < 0) {count = max - 1;}
                if (count >= max) {count = 0;}

                lightImage(gallerySet[count],wrapper,doc);
            });

        }

        tl.add('active');

        tl.to(overlay,0.1, {
            opacity:0,
        });

        setTimeout(function(){
            overlay.addClass('active');
            tl.progress(0);
            tl.tweenTo('active');

            lightImage(element.attr('href'),wrapper,doc);

        },50);

        toggle.on('click',function(e){
            e.preventDefault();
            tl.play();
            overlay.removeClass('active');
            setTimeout(function(){
                overlay.remove();
            },500);
        });
    }

    function videoTrigger(doc){
        jQuery(doc).find('.video-btn').each(function(){

            var $this  = jQuery(this),
                video  = $this.parents('.post-video').find('.video-element'),
                image  = $this.parents('.post-video').find('.image-container'),
                embed  = (video.hasClass('iframevideo')) ? true : false,
                back   = $this.find('.back');

            $this.hover(
                function(){
                    gsap.to(back,0.8, {
                      scale:1.15,
                      ease:"elastic.out"
                    });
                },
                function(){
                    gsap.to(back,0.8, {
                      scale:1,
                      ease:"expo.out"
                    });
                }
            );

            $this.on('click',function(e){
                e.preventDefault();

                if (!$this.hasClass('video-modal')) {
                    image.toggleClass('playing');
                    video.toggleClass('playing');
                }

                if ($this.hasClass('video-modal')) {
                    gsapLightbox($this,false);
                } else {
                    setTimeout(function(){
                        if (embed) {
                            var src = video.attr('src');
                            src =  (src.indexOf("?") == -1) ? src += '?' : src += '&';
                            video.attr('src',src+'autoplay=1');
                        } else {
                            video.trigger('play');
                        }
                    },500);
                }

            });

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

    function iframeSCRIPT(element,doc){
        $(element).each(function(){

            var $this  = $(this),
                video  = $this.parents('.post-video').find('.video-element'),
                image  = $this.parents('.post-video').find('.image-container'),
                embed  = (video.hasClass('iframevideo')) ? true : false,
                back   = $this.find('.back');

            $this.find('img.lazy').each(function(){
                var lazyImg = $(this);
                lazyImg.attr('src',lazyImg.data('src')).removeClass('lazy');
                lazyImg.parent().addClass('loaded');
            });

            $this.hover(
                function(){
                    gsap.to(back,0.8, {
                      scale:1.15,
                      ease:"elastic.out"
                    });
                },
                function(){
                    gsap.to(back,0.8, {
                      scale:1,
                      ease:"expo.out"
                    });
                }
            );

            $this.on('click',function(e){
                e.preventDefault();

                if (!$this.hasClass('video-modal')) {
                    image.toggleClass('playing');
                    video.toggleClass('playing');
                } else {
                    gsapLightbox($this,false);
                }

            });

            videoTrigger(doc);

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
                return dataObj[key] === "et_video";
            });

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){
                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-video');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);