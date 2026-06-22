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

            if (max == 1) {
                $(doc).find('body .gsap-lightbox-overlay .gsap-lightbox-nav').remove();
            }
    
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

            var $this = $(this);

            $this.find('img.lazy').each(function(){
                var lazyImg = $(this);
                lazyImg.attr('src',lazyImg.data('src')).removeClass('lazy');
                lazyImg.parent().addClass('loaded').removeClass('lazy-inline-image');
                lazyImg.parent().find('svg').remove();
            });

            $this.find('a').on('click',function(e){
                e.preventDefault();
                gsapLightbox($(this),true,doc);
            });

            if ($this.hasClass('slider') && !$this.find('.tns-ovh').length) {
                var slider = tns({
                    container: this.querySelector('.slides'),
                    mode:'gallery',
                    nav:false,
                    items: 1,
                });
            }

            if ($this.hasClass('et-carousel') && !$this.find('.tns-ovh').length) {

                var items     = $this.data('columns'),
                    items767  = 1,
                    items768  = (items > 3) ? 3 : items,
                    items1024 = items,
                    gatter    = 8,
                    autoplay  = ($this.data('autoplay')) ? $this.data('autoplay') : false,
                    nav       = ($this.data('nav')) ? $this.data('nav') : 'arrows';

                var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
                    arrows  = (nav == 'dottes') ? false : true;

                var slider = tns({
                    container: this.querySelector('.slides'),
                    mode:'carousel',
                    controlsPosition:'bottom',
                    navPosition:'bottom',
                    gutter:gatter,
                    autoplay:autoplay,
                    autoplayButtonOutput:false,
                    touch:true,
                    mouseDrag:true,
                    nav:bullets,
                    controls:arrows,
                    loop:false,
                    items: items,
                    responsive: {
                        320: {items: items767},
                        768: {items:items768},
                        1024:{items:items1024},
                        1280:{items:items}
                    }
                });

                var CSS = '.tns-slider{transition: all 0.3s !important;}';
                var id    = '#'+$this.attr('id'),
                    nm    = $this.find('.slides').children().length,
                    width = $this.find('.slides').children().first().width(),
                    ratio = nm/items;

                CSS += id+'.et-carousel .tns-item{width:calc('+100/nm+'%);padding-right:'+gatter+'px}';
                CSS += id+'.et-carousel .tns-inner {margin: 0 -'+gatter+'px 0 0}';

                CSS += id+'.et-carousel .slides {width:calc('+100*ratio+'%);}';

                CSS += '@media (min-width: 20em){';
                    CSS += id+'.et-carousel .slides {width:calc('+100*nm+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 48em){';
                    CSS += id+'.et-carousel .slides {width:calc('+100*(nm/items768)+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 64em){';
                    CSS += id+'.et-carousel .slides {width:calc('+100*(nm/items1024)+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 80em){';
                    CSS += id+'.et-carousel .slides {width:calc('+100*ratio+'%);}';
                CSS += '}';

                $(doc).find("#dynamic-styles-inline-css").append(CSS);

                if (autoplay) {

                    var index = 1,
                        max   = 3;

                    $this.find('.tns-nav > button').on('click',function(){
                        index = $(this).index();
                    });

                    var autoplayInterval = setInterval(function(){
                        
                        if ($this.find('.tns-controls > button').length) {
                            $this.find('.tns-controls > button[data-controls="next"]').trigger('click');
                        } else if($this.find('.tns-nav > button').length){
                            $this.find('.tns-nav > button').eq(index).trigger('click');
                        }
                        index++;

                        if (index >= max) {clearInterval(autoplayInterval);}

                    }, 5000);
                }

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
                return dataObj[key] === "et_gallery";
            });

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){
                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-gallery');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);