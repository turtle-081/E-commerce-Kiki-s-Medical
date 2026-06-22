/* Helpers
----*/

    !function(a){"use strict";var e=a('.ocdi__button-container a[href*="page=one-click-demo-import&step=import&import=0"]');void 0!==e&&e.length&&(a("body").hasClass("demo-import-activation")?(e.addClass("disabled"),a(".custom-intro-text").replaceWith('<div class="activate-demo-import"><input type="text" placeholder="Paste your purchase code to activate demo data import" /><a target="_blank" href="//help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Where Is My Purchase Code?</a></div>'),a("body").on("input",".activate-demo-import > input",function(t){var s,d,i;s=this,void 0===(i=(d=a(s).removeClass("invalid")).val())||!i.length||d.hasClass("valid")||d.hasClass("disabled")||(d.addClass("disabled"),a.ajax({url:admin_opt.ajaxUrl,type:"post",data:{action:"wB_4QM_pd2zE_Hv9X_W",code:i},success:function(a){try{if(a)switch(a){case"valid":d.removeClass("disabled").addClass("valid").val("Purchase code validated successfully! You can now import demo data.").attr("disabled","disabled"),d.next("a").remove(),e.removeClass("disabled");break;case"invalid":alert("Purchase code not found"),d.addClass("invalid").removeClass("disabled");break;default:alert(a),d.removeClass("disabled")}else d.removeClass("disabled"),alert("No data retured")}catch(t){console.log(t)}},error:function(){alert(ajaxUrl.adminAJAXError)}}))}),e.on("click",function(e){a(this).hasClass("disabled")&&e.preventDefault()})):(e.attr("href",e.attr("href").replace("step=import","step=activate")),e.text("Active demo data import")))}(jQuery);

    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    

/* GSAP config
----*/
    
    gsap.config({ nullTargetWarn:false});

/* Visual composer front-end editor
----*/

    (function($){

        "use strict";

        /* Gsap Lightbox
        ----*/

            function lightImage(src,overlay,doc){

                if (
                    src.includes('.jpg') ||
                    src.includes('.jpeg') ||
                    src.includes('.png') ||
                    src.includes('.bmp') ||
                    src.includes('.gif') ||
                    src.includes('')
                ) {
                    
                    var img = doc.createElement('img');
                    img.src = src;

                    var loaded = false;

                    img.onload = function() {

                        if (loaded) {
                            return;
                        }

                        if (overlay.find('img').length == 0) {
                            overlay.prepend(img);
                        }

                        loaded = true;
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

        /* Video trigger
        ----*/

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

        /* Lazy loading
        ----*/

            function lazyLoad(container){

                let lazyImages = [].slice.call(container.querySelectorAll("img.lazy"));
                let lazyVideos = [].slice.call(container.querySelectorAll("video.lazy"));
                let lazyBanners = [].slice.call(container.querySelectorAll(".banner-back.image"));

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

                    var lazyVideoObserver = new IntersectionObserver(function(entries, observer) {
                        entries.forEach(function(video) {
                            if (video.isIntersecting) {

                                for (var source in video.target.children) {
                                    var videoSource = video.target.children[source];
                                    if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
                                        videoSource.src = videoSource.dataset.src;
                                    }
                                }

                                video.target.load();
                                video.target.classList.remove("lazy");
                                lazyVideoObserver.unobserve(video.target);
                            }
                        });
                    });

                    lazyVideos.forEach(function(lazyVideo) {
                        lazyVideoObserver.observe(lazyVideo);
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

            }        

        /* Box move
        ----*/

            function boxMove(target,x = 0.02,y = 0.02){

                jQuery(target).each(function(){

                    var $this = jQuery(this);

                    $this.on('mousemove',function(e){

                        var sxPos =  e.pageX - ($this.width()/2  + $this.offset().left);
                        var syPos =  e.pageY - ($this.height()/2 + $this.offset().top);

                        gsap.to( $this, 1, { 
                            rotationY: Math.round(y * sxPos), 
                            rotationX: Math.round(x * syPos), 
                            rotationZ: 0,
                            force3D:true,
                            transformPerspective:1000, 
                            transformOrigin:'center center'
                        });

                    });

                    $this.on('mouseleave',function(){
                        gsap.to( $this, 1, { 
                            rotationY: 0, 
                            rotationX: 0, 
                            rotationZ: 0, 
                            force3D:true,
                            transformPerspective:1000, 
                            transformOrigin:'center center'
                        });
                    });

                });

            }

        var iframe = document.getElementById('vc_inline-frame');

        if (typeof(iframe) != 'undefined' && iframe != null){

            iframe.addEventListener("load", function() {

                var win = iframe.contentWindow;
                var doc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;

                setTimeout(function(){

                    /* Gsap Lightbox
                    ----*/

                        $(doc).find('.et-gallery').each(function(){

                            var $this = $(this);

                            $this.find('a').on('click',function(e){
                                e.preventDefault();
                                gsapLightbox($(this),true,doc);
                            });

                            if ($this.hasClass('slider')) {

                                $this.find('img.lazy').each(function(){
                                    var lazyImg = $(this);
                                    lazyImg.attr('src',lazyImg.data('src')).removeClass('lazy');
                                    lazyImg.parent().addClass('loaded').removeClass('lazy-inline-image');
                                    lazyImg.parent().find('svg').remove();
                                });

                                var slider = tns({
                                    container: this.querySelector('.slides'),
                                    mode:'gallery',
                                    nav:false,
                                    items: 1,
                                });
                                
                            }
                        });

                        $(doc).find('.et-button.modal-true').on('click',function(e){
                            e.preventDefault();
                            gsapLightbox($(this),false,doc);
                        });

                    /* Video trigger
                    ----*/

                        videoTrigger(doc);

                    /* Lazy load
                    ----*/

                        doc.addEventListener("DOMContentLoaded", lazyLoad(doc));

                    /* Header builder
                    ----*/

                        $(doc).find(".hbe").each(function(){
                            var $this = $(this);
                            var attr = $this.parent().attr('data-tag');
                            var hasAttribute = (typeof attr !== 'undefined') ? true : false;
                            if ($this.hasClass('hbe-right') && hasAttribute) {$this.parent().addClass('hbe-right');}
                            if ($this.hasClass('hbe-left') && hasAttribute) {$this.parent().addClass('hbe-left');}
                            if ($this.hasClass('hbe-center') && hasAttribute) {$this.parent().addClass('hbe-center');}
                        });

                    /* Title section
                    ----*/

                        $(doc).find(".tse").each(function(){
                            var $this = $(this);
                            var attr = $this.parent().attr('data-tag');
                            var hasAttribute = (typeof attr !== 'undefined') ? true : false;
                            if ($this.hasClass('tse-right') && hasAttribute) {$this.parent().addClass('tse-right');}
                            if ($this.hasClass('tse-left') && hasAttribute) {$this.parent().addClass('tse-left');}
                            if ($this.hasClass('tse-center') && hasAttribute) {$this.parent().addClass('tse-center');}
                        });

                    /* VC core animations
                    ----*/

                        $(doc).find('.wpb_animate_when_almost_visible').each(function(){
                            $(this).waypoint({
                                handler: function(direction) {

                                    $(this.element)
                                    .addClass('wpb_start_animation')
                                    .addClass('animated');

                                    this.destroy();
                                },
                                offset: 'bottom-in-view',
                                context: win
                            });
                        });

                    /* Tiny slider
                    ----*/

                        var CSS = '.tns-slider{transition: all 0.3s !important;}';

                        $(doc).find('.et-carousel > .slides').each(function(){

                            var $this    = $(this),
                                woo      = $this.parent().parent(),
                                items    = $this.parent().data('columns'),
                                items767 = 1,
                                items768 = (items > 2 && $this.parent().hasClass('et-testimonial-container')) ? 2 : (items > 3) ? 3 : items,
                                items1024= (items > 3 && $this.parent().hasClass('et-testimonial-container')) ? 3 : items,
                                gatter   = 24,
                                autoplay = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
                                nav      = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows',
                                nm       = $this.children().length;

                            if ($this.parent().hasClass('loop-posts') && (items >= 3)) {items768 = 2;}

                            if ($this.parent().hasClass('loop-products')) {
                                gatter    = (woo.hasClass('list')) ? 0 : 24;
                                items768  = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-port') : items;
                                items1024 = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-land') : items;
                                items767  = (woo.hasClass('list') || woo.hasClass('full')) ? 1 : 2;
                            }

                            if ($this.parent().hasClass('et-gallery')) {gatter = 12;}
                            if ($this.parent().hasClass('et-client-container')) {gatter = 0;}

                            var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
                                arrows  = (nav == 'dottes') ? false : true;

                            var slider = tns({
                                container: this,
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

                            var id = '#'+$this.attr('id');

                            CSS += id+' .tns-item{width:calc('+100/nm+'%) !important;padding-right:'+gatter+'px !important;}';
                            CSS += id+' .tns-inner {margin: 0 -'+gatter+'px 0 0 !important;}';
                            CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';

                            CSS += '@media (min-width: 20em){';
                                CSS += id+' {width:calc('+100*(nm/items767)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 48em){';
                                CSS += id+' {width:calc('+100*(nm/items768)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 64em){';
                                CSS += id+' {width:calc('+100*(nm/items1024)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 80em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                        });

                        $(doc).find('.et-woo-categories.carousel-true > ul').each(function(){

                            var $this    = $(this),
                                items    = $this.parent().attr('data-columns-desktop'),
                                items767 = $this.parent().attr('data-columns-mob'),
                                items768 = $this.parent().attr('data-columns-tab-port'),
                                items1024= $this.parent().attr('data-columns-tab-land'),
                                gatter   = ($this.parent().hasClass('gap-true')) ? 32 : 12,
                                autoplay = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
                                nav      = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows',
                                nm       = $this.children().length;

                            $this.parent().addClass('manual-carousel');

                            var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
                                arrows  = (nav == 'dottes') ? false : true;

                            var slider = tns({
                                container: this,
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

                            var id = '#'+$this.attr('id');

                            CSS += id+' .tns-item{width:calc('+100/nm+'%) !important;padding-right:'+gatter+'px !important;}';
                            CSS += id+' .tns-inner {margin: 0 -'+gatter+'px 0 0 !important;}';
                            CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';

                            CSS += '@media (min-width: 20em){';
                                CSS += id+' {width:calc('+100*(nm/items767)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 48em){';
                                CSS += id+' {width:calc('+100*(nm/items768)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 64em){';
                                CSS += id+' {width:calc('+100*(nm/items1024)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 80em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                        });

                        $(doc).find('.et-shortcode-posts.comp').each(function(){

                            var $this  = $(this);
                            var slides = this.querySelector('.slides');
                            var nm = $this.find('.slides').children().length;

                            var slider = tns({
                                container: slides,
                                mode:'carousel',
                                controlsPosition:'bottom',
                                navPosition:'bottom',
                                gutter:0,
                                autoplayButtonOutput:false,
                                touch:true,
                                mouseDrag:true,
                                nav:false,
                                controls:true,
                                loop:false,
                                items: 1
                            });

                            var id     = '#'+$this.find('.slides').attr('id');
                            var items  = 1;
                            var gatter = 0;

                            CSS += id+' .tns-item{width:calc('+100/nm+'%) !important;padding-right:'+gatter+'px !important;}';
                            CSS += id+' .tns-inner {margin: 0 -'+gatter+'px 0 0 !important;}';
                            CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';

                            CSS += '@media (min-width: 20em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 48em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 64em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                            CSS += '@media (min-width: 80em){';
                                CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                            CSS += '}';

                        });

                        $(doc).find('.insta-placeholder-grid').each(function(){

                            if (!$(this).parents('.et-carousel').find('.tns-outer').length) {

                                var $this    = $(this),
                                    items    = 6,
                                    items767 = 2,
                                    items768 = 3,
                                    items1024= 4,
                                    loop     = true;

                                var nm = $this.children().length;

                                var slider = tns({
                                    container: this,
                                    mode:'carousel',
                                    controlsPosition:'bottom',
                                    navPosition:'bottom',
                                    gutter:24,
                                    autoplay:false,
                                    autoplayButtonOutput:false,
                                    touch:true,
                                    mouseDrag:true,
                                    nav:false,
                                    controls:true,
                                    loop:false,
                                    items: items,
                                    responsive: {
                                        320: {items: items767},
                                        768: {items:items768},
                                        1024:{items:items1024},
                                        1280:{items:items}
                                    }
                                });

                                var id     = '.insta-placeholder-grid';
                                var gatter = 24;

                                CSS += id+' .tns-item{width:calc('+100/nm+'%) !important;padding-right:'+gatter+'px !important;}';
                                CSS += '.vc_et_instagram .tns-inner {margin: 0 -'+gatter+'px 0 0 !important;}';
                                CSS += '.vc_et_instagram {width:calc('+100*(nm/items)+'%) !important;}';

                                CSS += '@media (min-width: 20em){';
                                    CSS += id+' {width:calc('+100*(nm/items767)+'%) !important;}';
                                CSS += '}';

                                CSS += '@media (min-width: 48em){';
                                    CSS += id+' {width:calc('+100*(nm/items768)+'%) !important;}';
                                CSS += '}';

                                CSS += '@media (min-width: 64em){';
                                    CSS += id+' {width:calc('+100*(nm/items1024)+'%) !important;}';
                                CSS += '}';

                                CSS += '@media (min-width: 80em){';
                                    CSS += id+' {width:calc('+100*(nm/items)+'%) !important;}';
                                CSS += '}';

                            }

                        });


                        $(doc).find("#dynamic-styles-inline-css").append(CSS);

                    /* Header
                    ----*/

                        /* Megamenu tabs
                        ----*/

                            $(doc).find('.megamenu-tab').each(function(){

                                var $this             = $(this),
                                    tabs              = $this.find('.tab-item'),
                                    tabsQ             = tabs.length,
                                    tabsDefaultWidth  = 0,
                                    tabsDefaultHeight = 0,
                                    tabsContent       = $this.find('.tab-content'),
                                    action            = ($this.hasClass('action-hover')) ? 'hover' : 'click';

                                if (!$this.find('.tabset').length) {

                                    tabs.wrapAll('<div class="tabset et-clearfix"></div>');
                                    tabsContent.wrapAll('<div class="tabs-container et-clearfix"></div>');

                                    var tabSet = $this.find('.tabset');

                                    if(!tabs.hasClass('active')){
                                        tabs.first().addClass('active');
                                    }

                                    tabs.each(function(){

                                        var $thiz = $(this);

                                        if ($thiz.hasClass('active')) {
                                            $thiz.siblings()
                                            .removeClass("active");
                                            tabsContent.hide(0).removeClass('active');
                                            tabsContent.eq($thiz.index()).show(0).addClass('active');
                                        }

                                    });

                                    if(tabsQ >= 2){

                                        if (action == 'click') {
                                            tabs.on('click', function(event){
                                                event.stopImmediatePropagation();

                                                var $self = $(this);

                                                if(!$self.hasClass("active")){

                                                    $self.addClass("active");

                                                    $self.siblings()
                                                    .removeClass("active");

                                                    tabsContent.hide(0).removeClass('active');
                                                    tabsContent.eq($self.index()).show(0).addClass('active');

                                                    if ($this.parents('.submenu-appear-none').length) {
                                                        var currentHeight = tabsContent.eq($self.index()).height();
                                                        $this.parents('.megamenu').css('height',currentHeight);
                                                    }
                                                }
                                            });
                                        } else {
                                            tabs.on('mouseover', function(event){

                                                event.stopImmediatePropagation();

                                                var $self = $(this);

                                                if(!$self.hasClass("active")){

                                                    $self.addClass("active");

                                                    $self.siblings()
                                                    .removeClass("active");

                                                    tabsContent.hide(0).removeClass('active');
                                                    tabsContent.eq($self.index()).show(0).addClass('active');

                                                    if ($this.parents('.submenu-appear-none').length) {
                                                        var currentHeight = tabsContent.eq($self.index()).height();
                                                        $this.parents('.megamenu').css('height',currentHeight);
                                                    }
                                                }
                                                
                                            });
                                        }
                                        
                                    }

                                }

                            });

                        /* Megamenu
                        ----*/

                            function megamenuPosition(){

                                $(doc).find('.header-menu > .menu-item').each(function(){

                                    var $this = $(this);
                                    var megamenu = $this.children('.megamenu');

                                    if (megamenu.length) {
                                        if ($this.data('width') == '100') {
                                            $this.parents('.header-menu-container').css('position','static');
                                            $this.parents('.vc_element').css('position','static');
                                            var megamenuWidth = $(window).innerWidth();
                                            megamenu.css({
                                                'max-width':megamenuWidth+'px',
                                                'width':megamenuWidth+'px',
                                                'margin-left':'-'+(megamenuWidth/2)+'px',
                                            });
                                        }
                                    }

                                });

                            }

                            megamenuPosition();
                            $(win).resize(megamenuPosition);

                        /* Megamenu grid autoalign
                        ----*/

                            $(doc).find('.megamenu').each(function(){
                                var $this = $(this);

                                if ($this.data('width') == '1200') {

                                    var closestLink = $this.parent().children('a');
                                    if (closestLink.length) {
                                        var parentContainer = $this.parents('.container').eq(0);
                                        var offset = closestLink.offset().left - parseInt(closestLink.parent().css('padding-left')) - (parentContainer.offset().left + ((parentContainer.outerWidth() - 1200)/2));
                                        $this.attr('style','margin-left:-'+offset+'px !important;');
                                    }

                                }

                            });

                        /* Submenu
                        ----*/

                            function submenuPosition(){

                                $('.et-desktop .header-menu > .menu-item').each(function(){

                                    var $this = $(this);

                                    if ($this.children('.sub-menu:not(.megamenu)').length) {

                                        if( $this.offset().left + $this.width() + $this.children('.sub-menu').width() > $(window).innerWidth()){
                                            $this.addClass('submenu-left');
                                        } else {
                                            $this.removeClass('submenu-left');
                                        }

                                    }

                                });

                            }

                            function navigationInIt(){

                                var $this               = $(this),
                                    hover               = $this.find('.menu-item-has-children'),
                                    subMenuEffect       = ($this.parent().hasClass('submenu-appear-none')) ? 'default' : 'fade',
                                    menuEffect          = (!$this.parent().hasClass('menu-hover-none')) ? true : false,
                                    iconCorrection      = ($this.parent().hasClass('menu-hover-underline') || $this.parent().hasClass('menu-hover-overline')) ? true : false;

                                if ($('body').hasClass('single-header')) {
                                    $this.children('li').first().addClass('active');
                                }

                                $this.children('.depth-0').hover(
                                    function(){
                                        var li = $(this);
                                        setTimeout(function(){li.addClass('hover');},100);
                                    },
                                    function(){
                                        $(this).removeClass('hover');
                                    }
                                );

                                hover.push($this.children('.mm-true'));

                                if (menuEffect) {

                                    var active              = '',
                                        activeOffset        = 0,
                                        currentMenuItem     = $this.children('li.active'),
                                        color               = $this.data('color'),
                                        color_hover         = $this.data('color-hover');


                                    if (typeof(currentMenuItem) == "undefined" || !currentMenuItem.length) {
                                        // Add active to first item
                                        $this.children('li').first().addClass('active');
                                    }

                                    if (currentMenuItem.length) {
                                        active       = currentMenuItem;
                                        activeOffset = active.position().left;
                                    }

                                    if (active.length) {
                                        active = active.children('a').find('.effect');
                                    } else {
                                        active = $this.children('li:first-child').children('a').find('.effect')
                                    }

                                    $.each($this.children('.depth-0'),function(){

                                        var li      = $(this),
                                            effect  = li.children('a').find('.effect'),
                                            effectX = li.position().left - activeOffset,
                                            effectW = effect.width();

                                        if (iconCorrection) {

                                            if (active.parents('a').find('.menu-icon').length && !li.children('a').find('.menu-icon').length) {
                                                effectX -= 24;
                                            }

                                            if (!active.parents('a').find('.menu-icon').length && li.children('a').find('.menu-icon').length) {
                                                effectX += 24;
                                            }

                                        }

                                        li.on('mouseover touchstart',function(){

                                            gsap.to(active,1, {
                                                x:effectX,
                                                width:effectW,
                                                ease:"elastic.out(1, 0.75)"
                                            });

                                            li.addClass('in').siblings().removeClass('in');

                                            if (li.hasClass('active')) {
                                                li.removeClass('using');
                                            } else {
                                                li.parent().children('li.active').addClass('using');
                                            }

                                        });

                                    });


                                    $this.on('mouseleave',function(){

                                        var width = $this.children('li.active').width();

                                        if ($this.parent().hasClass('menu-hover-underline')) {
                                            width = $this.children('li.active').find('.txt').width();
                                        }

                                        gsap.to(active,1, {
                                            x:$this.children('li.active').position().left - activeOffset,
                                            width:width,
                                            ease:"elastic.out(1, 0.75)"
                                        });

                                        $this.find('.in').removeClass('in');
                                        $this.find('.using').removeClass('using');
                                    });

                                }

                            }

                            submenuPosition();
                            $(win).resize(submenuPosition);

                            $(doc).find('.header-menu:not(".megamenu-demo")').each(navigationInIt);

                            $(doc).find('.et-menu').each(navigationInIt);

                        /* Toggles
                        ----*/

                            function toggleBack(element,toggle,scrollElement){

                                var $this  = jQuery(element),
                                    isOpen = false;

                                toggle.on('click',function(){

                                    toggle.toggleClass('active');

                                    if (isOpen==false) {

                                        if (typeof scrollElement != "undefined" && scrollElement != null) {
                                            // Custom scroll bar
                                            setTimeout(function(){
                                                var scroll = element.querySelector(scrollElement);
                                                if (typeof scroll != "undefined" && scroll != null) {
                                                    SimpleScrollbar.initEl(scroll);
                                                }
                                            },200);
                                        }

                                        isOpen=true;

                                    } else {
                                        isOpen=false;
                                    }

                                    if (toggle.hasClass('active')) {
                                        jQuery('.header .hbe-toggle.active').not(toggle).each(function(){
                                            jQuery(this).parent().find('.close-toggle').trigger('click');
                                        });
                                    }

                                });
                            }

                            if ($('body').hasClass('post-type-header')) {

                                /* Header search
                                ----*/

                                    $(doc).find('.header-search').each(function(){

                                        var $this  = $(this),
                                            toggle = $this.find('.search-toggle'),
                                            close  = $this.find('.close-toggle'),
                                            box    = $this.find('.search-box'),
                                            start  = $this.find('.start'),
                                            end    = $this.find('.end'),
                                            icon   = $this.find('.search-icon'),
                                            input  = $this.find('input[type="text"]'),
                                            isOpen = false;

                                        var tl = new gsap.timeline({paused: true});

                                        tl.to(box,0, {
                                          visibility:'visible', immediateRender:false
                                        },'+=0.2');

                                        tl.to(start,1.2, {
                                          morphSVG:{shape:end, shapeIndex:8},
                                          ease:"elastic.out(1, 0.75)"
                                        });

                                        tl.from(icon,1.2, {
                                          x:'12px',
                                          ease:"elastic.out(1, 0.75)"
                                        },'-=1.2');

                                        tl.add("open");

                                        tl.to(start,0.6, {
                                          morphSVG:{shape:start},
                                          ease:"elastic.out(1, 1.75)"
                                        },'+=0.2');

                                        tl.to(box,0.1, {
                                          opacity:0,
                                          ease:"sine.in"
                                        },'-=0.45');

                                        tl.to(box,0, {
                                          visibility:'hidden', immediateRender:false
                                        });

                                        tl.add("close");

                                        tl.to(start,0.1, {
                                          morphSVG:{shape:start}, immediateRender:false
                                        });

                                        tl.to(box,0.1, {
                                          opacity:0, immediateRender:false
                                        });

                                        tl.to(box,0, {
                                          visibility:'hidden', immediateRender:false
                                        });

                                        tl.add("hide");

                                        toggle.on('click',function(e){

                                            box.removeClass('hide');

                                            toggle.addClass('active');

                                            input.val('');

                                            if (isOpen==false) {

                                                tl.progress(0);
                                                tl.tweenTo("open");

                                                setTimeout(function(){
                                                    input.focus();
                                                },700);

                                                isOpen=true;

                                            }

                                        });

                                        close.on('click',function(e){

                                            toggle.removeClass('active');

                                            if (close.hasClass('hide')) {

                                                box.addClass('hide');

                                                e.preventDefault();
                                                input.val('');

                                                tl.seek("close");
                                                tl.play();

                                                close.removeClass('hide');

                                                isOpen=false;

                                            } else {

                                                if (!input.val()) {
                                                    tl.tweenTo("close");
                                                    isOpen=false;
                                                }
                                            }

                                        });

                                        $this.find('#searchsubmit').on('click',function(e){
                                            if (!input.val()) {
                                                e.preventDefault();
                                                input.val('');
                                                tl.tweenTo("close");
                                            }
                                        });

                                    });

                                /* Shopping cart
                                ----*/

                                    $(doc).find('.header-cart').each(function(){
                                        var element      = this,
                                            $this        = $(element),
                                            toggle       = $this.find('.cart-toggle');

                                        toggleBack(element,toggle,'.cart_list');
                                    });

                                    $(doc).find('.ajax_add_to_cart').each(function(){
                                        $(this).on('click',function(){
                                            $('.header-cart').each(function(){
                                                var cartToggle = $(this).find('.close-toggle');
                                                if (cartToggle.hasClass('active')) {
                                                    cartToggle.addClass('hide').trigger('click');
                                                }
                                            });
                                        });
                                    });

                                /* Language switcher
                                ----*/

                                    $(doc).find('.language-switcher').each(function(){

                                        var element      = this,
                                            $this        = $(element),
                                            toggle       = $this.find('.language-toggle');              

                                        toggleBack(element,toggle);

                                    });

                                    $(doc).find('.wpml-ls-legacy-dropdown-click').each(function(){
                                        var $this = $(this);

                                        $this.find('.js-wpml-ls-item-toggle').on('click',function(){
                                            $this.find('.js-wpml-ls-sub-menu').toggleClass('active');
                                        });

                                    });

                                /* Header login
                                ----*/

                                    $(doc).find('.header-login').each(function(){

                                        var element = this,
                                            toggle  = $(element).find('.login-toggle');

                                        toggleBack(element,toggle);

                                    });

                                /* Currency switcher
                                ----*/

                                    $(doc).find('.currency-switcher').each(function(){

                                        var element = this,
                                            toggle  = $(element).find('.currency-toggle');

                                        toggleBack(element,toggle);

                                        $('<span class="highlighted-currency">'+$(element).find('.currency-list a:first-child').text()+'</span>').insertBefore(toggle.not('.close-toggle').find('svg'));

                                        toggle.on('click',function(){
                                            toggle.find('.highlighted-currency').remove();
                                            $('<span class="highlighted-currency">'+$(element).find('.currency-list a:first-child').text()+'</span>').insertBefore(toggle.not('.close-toggle').find('svg'));
                                        });

                                    });

                            }
                            
                    /* Elements
                    ----*/

                        /* et-button
                        ----*/

                            $(doc).find('.et-button').each(function(){

                                var $this  = $(this),
                                    effect = $this.data('effect');

                                var tl = new gsap.timeline({paused: true});

                                switch (effect) {
                                    case 'fill':

                                        var hover       = $this.find('span.hover'),
                                            icon        = $this.find('.icon svg'),
                                            color       = $this.data('color'),
                                            color_hover = $this.data('color-hover');

                                        tl.to(hover,0.6, {
                                          scaleX:1,
                                          transformOrigin:'left top',
                                          ease:"power3.out"
                                        });

                                        tl.to($this,0.1, {
                                          css:{color:color_hover}
                                        },'-=0.6');

                                        tl.to(icon,0.1, {
                                          css:{fill:color_hover}
                                        },'-=0.6');

                                        tl.add("in");

                                        tl.to(hover,0.6, {
                                          scaleX:0,
                                          transformOrigin:'right top',
                                          ease:"power3.out"
                                        });

                                        tl.to($this,0.1, {
                                            css:{color:color}
                                        },'-=0.6');

                                        tl.to(icon,0.1, {
                                            css:{fill:color}
                                        },'-=0.6');

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
                                        gsap.to(window, {
                                            duration: 1, 
                                            scrollTo: {y:$this.attr('href')},
                                            ease:Power3.easeOut 
                                        });
                                        return false;
                                    });
                                }

                                if ($this.hasClass('wpb_animate_when_almost_visible')) {

                                    var delay = $(this).data('animation_delay');

                                    $this.waypoint({
                                        handler: function(direction) {

                                            setTimeout(function(){
                                                $(this.element)
                                                .addClass('wpb_start_animation')
                                                .addClass('animated');
                                            },delay);

                                            this.destroy();

                                        },
                                        offset: '25%',
                                    });

                                }

                            });

                        /* et-icon
                        ----*/

                            $(doc).find('.click-true .hicon, .et-icon.click-true').each(function(){

                                var $this         = $(this),
                                    iconBack      = $this.find('.icon-back path'),
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

                            });

                        /* et-icon-list
                        ----*/

                            $(doc).find('.et-icon-list.animate-true').each(function(){

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

                                $this.waypoint({
                                    handler: function(direction) {

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: 'bottom-in-view',
                                    context: win
                                });

                            });

                        /* et-heading
                        ----*/

                            $(doc).find('.et-heading.animate-true').each(function(){

                                var $this = $(this),
                                    delay = '+='+(parseInt(0.2 + $this.data('delay'))/1000),
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
                                }

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

                                $this.waypoint({
                                    handler: function(direction) {

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: 'bottom-in-view',
                                    context: win
                                });

                            });
                        
                        /* et-accordion
                        ----*/

                            $(doc).find('.et-accordion').each(function(){

                                var $this = $(this);

                                gsap.set($this.find('.toggle-title.active').next(),{
                                    opacity: 1,
                                    height: 'auto'
                                });

                                $this.find('.toggle-title').on('click', function(){

                                    var $self = $(this);

                                        if(!$self.hasClass('active')){
                                            if($this.hasClass('collapsible-true')){

                                                $self.addClass("active");
                                                $this.find('.toggle-title').not($self).removeClass("active")

                                                gsap.to($self.next(),0.6, {
                                                    height:'auto',
                                                    ease:"expo.out"
                                                });

                                                gsap.to($self.next(),0.2, {
                                                    opacity:1,
                                                });

                                                gsap.to($this.find('.toggle-content').not($self.next()),0.1, {
                                                    opacity:0,
                                                });

                                                gsap.to($this.find('.toggle-content').not($self.next()),0.6, {
                                                    height:0,
                                                    ease:"expo.out"
                                                });

                                            } else {
                                                $self.addClass("active");

                                                gsap.to($self.next(),0.6, {
                                                    height:'auto',
                                                    ease:"expo.out"
                                                });

                                                gsap.to($self.next(),0.2, {
                                                    opacity:1,
                                                });

                                            }
                                        } else {
                                            if(!$this.hasClass('collapsible-true')){
                                                $self.removeClass("active");

                                                gsap.to($self.next(),0.1, {
                                                    opacity:0,
                                                });

                                                gsap.to($self.next(),0.6, {
                                                    height:0,
                                                    ease:"expo.out"
                                                });
                                            }
                                        }

                                });

                            });

                        /* et-tab
                        ----*/

                            $(doc).find('.et-tab').each(function(){

                                var $this    = $(this),
                                    tabs     = $this.find('.tab'),
                                    tabsQ    = tabs.length,
                                    tabsDefaultWidth  = 0,
                                    tabsDefaultHeight = 0,
                                    tabsContent = $this.find('.tab-content');

                                tabs.wrapAll('<div class="tabset et-clearfix"></div>');
                                tabsContent.wrapAll('<div class="tabs-container et-clearfix"></div>');

                                var tabSet = $this.find('.tabset');

                                    if(!tabs.hasClass('active')){
                                        tabs.first().addClass('active');
                                    }
                                    
                                    tabs.each(function(){

                                        var $thiz = $(this);

                                        if ($thiz.hasClass('active')) {
                                            $thiz.siblings()
                                            .removeClass("active");
                                            tabsContent.hide(0).removeClass('active');
                                            tabsContent.eq($thiz.index()).show(0).addClass('active');
                                        }

                                        tabsDefaultWidth += $(this).outerWidth();
                                        tabsDefaultHeight += $(this).outerHeight();
                                    });

                                    if(tabsQ >= 2){

                                        tabs.on('click', function(){
                                            var $self = $(this);
                                            
                                            if(!$self.hasClass("active")){

                                                $self.addClass("active");

                                                $self.siblings()
                                                .removeClass("active");

                                                tabsContent.hide(0).removeClass('active');
                                                tabsContent.eq($self.index()).show(0).addClass('active');
                                            }
                                            
                                        });
                                    }

                                    function OverflowCorrection(){
                                        if(tabsDefaultWidth >= $this.outerWidth()  && $this.hasClass('horizontal')){
                                            $this.addClass('tab-full');
                                        } else {
                                            $this.removeClass('tab-full');
                                        }
                                    }

                                    OverflowCorrection();

                                    $(window).resize(OverflowCorrection);           

                            });

                        /* et-animate-box
                        ----*/

                            function animateBoxBack(box){

                                var $this  = $(box),
                                    width  = $this.width(),
                                    height = $this.height(),
                                    ratio  = Math.round(100*(height/width)),
                                    svg    = $this.find('.box-back'),
                                    path   = svg.find('path');

                                // get svg viewBox
                                var viewBox = box.querySelector('.box-back').getAttribute('viewBox');

                                var viewBoxValues = viewBox.split(' ');

                                viewBoxValues  = viewBoxValues.splice(2, 2);

                                var replace = viewBoxValues[1];

                                var start    = path.attr('d'),
                                    startC   = path.attr('data-dclone'),
                                    end      = path.attr('data-end'),
                                    original = path.attr('data-original');

                                start  = start.replace(new RegExp((replace-10),"g"),(ratio-10));
                                start  = start.replace(new RegExp(replace,"g"),ratio);
                                startC = startC.replace(new RegExp((replace-10),"g"),(ratio-10));
                                startC = startC.replace(new RegExp(replace,"g"),ratio);
                                end    = end.replace(new RegExp(replace,"g"),ratio);

                                if (typeof(original) != 'undefined') {
                                    original = original.replace(new RegExp(replace,"g"),ratio);
                                }

                                box.querySelector('.box-back').setAttribute('viewBox','0 0 100 '+ratio);

                                path.attr('d',start);
                                path.attr('data-end',end);
                                path.attr('data-dclone',startC);

                                if (typeof(original) != 'undefined') {
                                    path.attr('data-original',original);
                                }

                            }

                            function buildAnimateBoxTimeline(tl,box,delay,animation,stagger,content){

                                var path = box.find('.box-back path');

                                tl.from(box,0.2, {
                                  opacity:0,
                                },delay);

                                switch(animation){
                                    case 'top':

                                        tl.from(box,1.2, {
                                            y:-100,
                                            scaleY:0,
                                            rotationZ:8,
                                            force3D:true,
                                            transformOrigin:'right top',
                                            ease:"elastic.out(1, 0.5)"
                                        },'-=0.1');

                                    break;

                                    case 'bottom':

                                        tl.from(box,1.2, {
                                            y:100,
                                            scaleY:0,
                                            rotationZ:8,
                                            force3D:true,
                                            transformOrigin:'right bottom',
                                            ease:"elastic.out(1, 0.5)"
                                        },'-=0.1');
                                    
                                    break;

                                    case 'left':

                                        tl.from(box,1.2, {
                                            x:-100,
                                            scaleX:0,
                                            rotationZ:-8,
                                            force3D:true,
                                            transformOrigin:'left center',
                                            ease:"elastic.out(1, 0.5)"
                                        },'-=0.1');
                                    
                                    break;

                                    case 'right':

                                        tl.from(box,1.2, {
                                            x:100,
                                            scaleX:0,
                                            rotationZ:8,
                                            force3D:true,
                                            transformOrigin:'right center',
                                            ease:"elastic.out(1, 0.5)"
                                        },'-=0.1');
                                    
                                    break;
                                }


                                tl.to(path,1.2, {
                                  morphSVG:{shape:path.attr('data-end'), shapeIndex:8},
                                  ease:"elastic.out"
                                },'-=1');

                                switch(stagger){

                                    case "left":

                                        tl.from(content,{
                                            duration: 0.8,
                                            x:-50,
                                            stagger: 0.05,
                                            opacity:0,
                                            ease:"expo.out"
                                        },'-=1.1');

                                    break;

                                    case "right":

                                        tl.from(content,{
                                            duration: 0.8,
                                            x:50,
                                            stagger: 0.05,
                                            opacity:0,
                                            ease:"expo.out"
                                        },'-=1.1');

                                    break;

                                    case "top":

                                        tl.from(content,{
                                            duration: 0.8,
                                            y:-50,
                                            stagger: 0.05,
                                            opacity:0,
                                            ease:"expo.out"
                                        },'-=1.1');

                                    break;

                                    case "bottom":

                                        tl.from(content,{
                                            duration: 0.8,
                                            y:50,
                                            stagger: 0.05,
                                            opacity:0,
                                            ease:"expo.out"
                                        },'-=1.1');

                                    break;
                                }

                                tl.add('end');

                            }

                            $(doc).find('.et-animate-box').each(function(){

                                var element   = this,
                                    $this     = $(element),
                                    id        = $this.attr('id'),
                                    delay     = '+='+(0.2 + parseInt($this.data('delay'))/1000),
                                    animation = $this.data('animation'),
                                    offset    = (animation == 'bottom') ? '100%': '70%',
                                    stagger   = $this.data('stagger'),
                                    content   = $this.find('.content').children();

                                animateBoxBack(element);

                                var tl = new gsap.timeline({paused: true});

                                buildAnimateBoxTimeline(tl,$this,delay,animation,stagger,content);

                                $this.waypoint({
                                    handler: function(direction) {

                                        $this.addClass('active');

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: offset,
                                    context: win
                                });

                                $(win).resize(function(){

                                    setTimeout(function(){

                                        element = doc.getElementById(id);

                                        $this = $(element);

                                        animateBoxBack(element);

                                        if (!$this.hasClass('active')) {

                                            var startC = $this.find('.box-back path').attr('data-dclone');
                                            $this.find('.box-back path').attr('d',startC);

                                            tl.seek('end').kill();

                                            tl = new gsap.timeline({paused: true});

                                            buildAnimateBoxTimeline(tl,$this,delay,animation,stagger,content);

                                            $this.waypoint({
                                                handler: function(direction) {

                                                    tl.progress(0);
                                                    tl.play();

                                                    this.destroy();

                                                },
                                                offset: offset,
                                                context: win
                                            });

                                        }

                                    },50);

                                });

                            });

                        /* et-stagger-box
                        ----*/

                            function buildStaggerBoxTimeline(tl,delay,interval,stagger,content){

                                switch(stagger){

                                    case "left":

                                        tl.from(content,{
                                            duration: 0.8,
                                            x:-50,
                                            stagger: interval,
                                            opacity:0,
                                            ease:"expo.out"
                                        },delay);

                                    break;

                                    case "right":

                                        tl.from(content,{
                                            duration: 0.8,
                                            x:50,
                                            stagger: interval,
                                            opacity:0,
                                            ease:"expo.out"
                                        },delay);

                                    break;

                                    case "top":

                                        tl.from(content,{
                                            duration: 0.8,
                                            y:-50,
                                            stagger: interval,
                                            opacity:0,
                                            ease:"expo.out"
                                        },delay);

                                    break;

                                    case "bottom":

                                        tl.from(content,{
                                            duration: 0.8,
                                            y:50,
                                            stagger: interval,
                                            opacity:0,
                                            ease:"expo.out"
                                        },delay);

                                    break;
                                }

                            }

                            $(doc).find('.et-stagger-box').each(function(){

                                var element   = this,
                                    $this     = $(element),
                                    id        = $this.attr('id'),
                                    delay     = '+='+(0.2 + parseInt($this.data('delay'))/1000),
                                    interval  = parseInt($this.data('interval'))/1000,
                                    stagger   = $this.data('stagger'),
                                    content   = $this.find('.content').children().not('.et-gap');

                                var tl = new gsap.timeline({paused: true});

                                buildStaggerBoxTimeline(tl,delay,interval,stagger,content);

                                $this.waypoint({
                                    handler: function(direction) {

                                        $this.addClass('active');

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: 'top-in-view',
                                    context: win
                                });

                            });

                        /* et-content-box
                        ----*/

                            $(doc).find('.et-icon-box-container').each(function(){

                                var $this            = $(this),
                                    animation        = $this.data('animation'),
                                    stagger          = $this.data('content-animation');

                                if (animation != "none") {

                                    $this.find('.et-icon-box').each(function(){

                                        var box     = $(this),
                                            delay   = (0.2 + box.parent().index()*0.05);

                                        var tl = new gsap.timeline({paused: true});

                                        switch(animation){
                                            case 'fade':
                                                tl.to(box,{
                                                    duration:0.4,
                                                    delay:delay,
                                                    opacity:1,
                                                });
                                            break;
                                            case 'appear':
                                                if (columns == 1) {

                                                    tl.to(box,{
                                                        duration:0.8,
                                                        delay:delay,
                                                        opacity:1,
                                                        x:0,
                                                        ease:"expo.out"
                                                    });

                                                } else {

                                                    tl.to(box,{
                                                        duration:0.8,
                                                        delay:delay,
                                                        opacity:1,
                                                        y:0,
                                                        ease:"expo.out"
                                                    });

                                                }
                                            break;
                                        }

                                        box.waypoint({
                                            handler: function(direction) {

                                                box.addClass('active');

                                                tl.progress(0);
                                                tl.play();

                                                this.destroy();

                                            },
                                            offset: 'bottom-in-view',
                                            context: win
                                        });

                                   

                                    });

                                }

                            });

                            $(doc).find('.et-icon-box').each(function(){

                                var $this  = $(this),
                                    effect = $this.data('effect');

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

                        /* et-step-box
                        ----*/

                            $(doc).find('.et-step-box').each(function(){

                                var $this = $(this),
                                    delay = (0.2 + $this.parent().index()*0.05);

                                $this.find('.step-count').text('0.'+($this.parent().index()+1));

                                var tl = new gsap.timeline({paused: true});

                                tl.from($this,{
                                    duration:0.8,
                                    delay:delay,
                                    opacity:0,
                                    y:40,
                                    ease:"expo.out"
                                });

                                $this.waypoint({
                                    handler: function(direction) {

                                        $this.addClass('active');
                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: 'bottom-in-view',
                                    context: win
                                });

                            });

                        /* et-tagline
                        ----*/

                            $(doc).find('.et-tagline').each(function(){

                                var $this = $(this);

                                var tl = new gsap.timeline({paused: true});

                                tl.to($this.find('.media-placeholder'),{
                                    duration: 0.3,
                                    opacity:0,
                                },'+=0.2');

                                tl.from($this.find('.in'),{
                                    duration: 1.2,
                                    x:50,
                                    stagger: 0.1,
                                    opacity:0,
                                    ease:"expo.out"
                                },'-=0.2');

                                $this.waypoint({
                                    handler: function(direction) {

                                        setTimeout(function(){$this.addClass('active');},200);

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: '70%',
                                    context: win
                                });
                            });

                            boxMove($(doc).find('.et-tagline'));

                        /* et-image
                        ----*/

                            $(doc).find('.et-image').each(function(){

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

                                    $this.waypoint({
                                        handler: function(direction) {

                                            tl.progress(0);
                                            tl.play();

                                            this.destroy();

                                        },
                                        offset: '70%',
                                        context: win
                                    });

                                }

                                if ($this.hasClass('parallax')) {

                                    var x     = $this.data('coordinatex'),
                                        y     = $this.data('coordinatey'),
                                        limit = $this.data('limit');

                                     if (typeof(limit) == 'undefined') {limit = 0}

                                    $(win).scroll(function(){

                                        if (!$this.hasClass('parallax-off')) {

                                            var yPos   = Math.round((0-$(win).scrollTop()) / $this.data('speed'))  +  y;
                                            var scroll = (Math.sign(y) == -1) ? Math.round((0-$(win).scrollTop()) / $this.data('speed')) : yPos;

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
                                
                            });

                        /* et-counter
                        ----*/

                            $(doc).find('.et-counter').each(function(){

                                var $this    = $(this),
                                    dDelay   = $this.data('delay'),
                                    delay    = (dDelay) ? dDelay/1000 : (0.2 + $this.index()*0.01),
                                    value    = $this.data('value'),
                                    counterV = { var: 0 },
                                    counter  = $this.find('.counter');


                                var tl = new gsap.timeline({paused: true});

                                tl.to($this.find('.in'),{
                                    duration: 0.8,
                                    delay:delay,
                                    opacity:1,
                                    stagger: 0.1,
                                    x:0,
                                    transformOrigin:'left top',
                                    force3D:true,
                                    ease:"expo.out"
                                });


                                tl.to(counterV,{
                                    var:value,
                                    duration:1,
                                    onUpdate: function () {
                                        counter.html(Math.ceil(counterV.var));
                                    },
                                },'-=0.85');

                                tl.to($this.find('.counter-icon'),{
                                    duration: 0.2,
                                    opacity:0.05,
                                },'-=0.6');

                                tl.to($this.find('.counter-icon'),{
                                    duration: 1.6,
                                    scale:1,
                                    force3D:true,
                                    ease:"elastic.out"
                                },'-=0.6');

                                $this.waypoint({
                                    handler: function(direction) {

                                        $this.addClass('active');

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: 'bottom-in-view',
                                    context: win
                                });


                            });

                        /* et-progress
                        ----*/

                            $(doc).find('.et-progress').each(function(){

                                var $this    = $(this),
                                    type     = ($this.hasClass('circle')) ? 'circle' : 'default',
                                    delay    = (0.2 + $this.index()*0.01),
                                    value    = $this.data('percentage'),
                                    counterV = { var: 0 },
                                    counter  = $this.find('.percent');

                                if (!$this.hasClass('fired')) {

                                    var tl = new gsap.timeline({paused: true});

                                    if (type == 'default') {

                                        tl.from($this.find('.bar'),{
                                            duration: 1.6,
                                            delay:delay,
                                            scaleX:0,
                                            force3D:true,
                                            transformOrigin:'left top',
                                            ease:"expo.out"
                                        });

                                        tl.from($this.find('.text'),{
                                            duration: 0.8,
                                            opacity:0,
                                            x:50,
                                            transformOrigin:'left top',
                                            force3D:true,
                                            ease:"expo.out"
                                        },'-=1.6');

                                        tl.to(counterV,{
                                            var:value,
                                            duration:1,
                                            onUpdate: function () {
                                                $this.find('.bar').html('<span class="percent">'+Math.ceil(counterV.var)+'</span>');
                                            },
                                        },'-=1.4');

                                    } else {

                                        var bar           = this.querySelector('.bar-circle'),
                                            circumference = 27 * 2 * Math.PI,
                                            offset        = circumference - value / 100 * circumference;

                                        bar.style.strokeDasharray = circumference+' '+circumference;
                                        bar.style.strokeDashoffset = circumference;

                                        tl.to(bar,{
                                            duration: 0.2,
                                            delay:delay,
                                            opacity:1
                                        });

                                        tl.to(bar,{
                                            duration: 2,
                                            strokeDashoffset:offset,
                                            ease:"expo.out"
                                        },'-=0.2');

                                        tl.from($this.find('.text').children(),{
                                            duration: 0.8,
                                            opacity:0,
                                            y:50,
                                            stagger:0.1,
                                            transformOrigin:'left top',
                                            force3D:true,
                                            ease:"expo.out"
                                        },'-=2');

                                        tl.to(counterV,{
                                            var:value,
                                            duration:1,
                                            onUpdate: function () {
                                                counter.html(Math.ceil(counterV.var));
                                            },
                                        },'-=2');

                                    }

                                    $this.waypoint({
                                        handler: function(direction) {

                                            $this.addClass('active');

                                            tl.progress(0);
                                            tl.play();

                                            this.destroy();

                                            $this.addClass('fired');

                                        },
                                        offset: 'bottom-in-view',
                                        context: win
                                    });
                                }


                            });
                        
                        /* et-timer
                        ----*/

                            $(doc).find('.et-timer').each(function(){

                               var $this  = $(this),
                                    extend = $this.data('number'),
                                    gmt    = $this.data('gmt'),
                                    reset  = (typeof(extend) != 'undefined' && extend != null) ? true : false,
                                    gmt    = (typeof(gmt) != 'undefined' && gmt != null) ? gmt : 0;

                                    $this.find('ul').countdown({
                                        date: $this.data('enddate'),
                                        offset: $this.data('gmt'),
                                        day: $this.data('days'),
                                        days: $this.data('days'),
                                        hour: $this.data('hours'),
                                        hours: $this.data('hours'),
                                        minute: $this.data('minutes'),
                                        minutes: $this.data('minutes'),
                                        second: $this.data('seconds'),
                                        seconds: $this.data('seconds'),
                                        extend:extend,
                                        reset:reset
                                    });

                            });

                        /* et-row/et-column
                        ----*/

                            function backgroundScroll(el,speed,direction){
                                var size = (direction == "horizontal") ? el.data('img-width') : el.data('img-height');
                                if (direction == "horizontal") {
                                    el.animate({'background-position-x' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-x','0');backgroundScroll(el, speed,direction);}});
                                } else if (direction == "vertical") {
                                    el.animate({'background-position-y' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-y','0');backgroundScroll(el, speed,direction);}});
                                };
                            }

                            $(doc).find('.vc-parallax').each(function(){
                                var $this = $(this),
                                    plx = $this.find('.parallax-container');
                                
                                if ($this.hasClass('vc-video-parallax')) {
                                    plx = $this.find('.video-container');
                                }

                                var duration = parseInt($this.data('parallax-duration')),
                                    ratio    = (typeof(duration) != 'undefined' && duration != null && duration != 0) ? 0.1 : 1;

                                    duration = duration/100;

                                $(doc).scroll(function() {
                                    var yPos = Math.round(($(doc).scrollTop()-plx.offset().top) / $this.data('parallax-speed'));
                                    yPos = ratio*yPos;
                                    gsap.to(plx,{
                                        duration:duration,
                                        delay:0,
                                        y:yPos,
                                    });
                                });

                            });

                            $(doc).find('.vc-fixed-bg').each(function(){

                                var $this      = $(this), 
                                    fx         = $this.find('.fixed-container'),
                                    $secHeight = $(this).outerHeight(),         
                                    $secWidth  = $(this).outerWidth(),
                                    fxHeight   = ($secHeight > $(window).height()) ? $secHeight : $(window).height();

                                fx.css({'height':fxHeight*1.2+'px'});

                                $(window).resize(function(){
                                    fx.css({'height':fxHeight+100+'px'});
                                });
                            });

                            $(doc).find('.vc-animated-bg').each(function(){

                                var $this         = $(this), 
                                    animatedBg    = $this.find('.animated-container'),
                                    animatedDir   = $this.data('animatedbg-dir'),
                                    animatedSpeed = $this.data('animatedbg-speed');

                                    if (animatedDir == 'horizontal') {
                                        backgroundScroll(animatedBg, animatedSpeed, 'horizontal');
                                    } else if (animatedDir == 'vertical') {
                                        backgroundScroll(animatedBg, animatedSpeed, 'vertical');
                                    };
                            });

                            $(doc).find('.vc-curtain').each(function(){

                                var curtain   = $(this),
                                    row       = curtain.parent(),
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

                                row.waypoint({
                                    handler: function(direction) {

                                        tl.progress(0);
                                        tl.play();

                                        this.destroy();

                                    },
                                    offset: '75%',
                                    context: win
                                });

                            });
                            
                },1);      
            });

            /* Front-end save
            ----*/

                $( document ).ajaxComplete(function( event, xhr, settings ) {

                    if (settings['type'] != 'POST') {return;}

                    var data = settings['data'];

                    data = data.split("&");

                    var dataObj = [{}];

                    for (var i = 0; i < data.length; i++) {
                        var property = data[i].split("=");
                        dataObj[property[0]] = property[1];
                    }

                    if (dataObj['action'] == "vc_save") {

                        var url  = settings['url'];

                        $.ajax({
                            type: 'POST',
                            url:url,
                            data:{
                                action:'et_vc_save',
                                post_id :dataObj['post_id'],
                                content :dataObj['content'],
                            }
                        })
                        .fail(function(data) {
                            console.log("Ajax error");
                        });

                        return;
                    }

                });

        }

    })(jQuery);

/* Megamenu
----*/

    (function($){

        "use strict";

        var mmo = $('.megamenu-options');

        mmo.each(function(){

            var $this = $(this),
                mms   = $this.find('.mms select'),
                mmc   = $this.find('.mmc');

            if ( mms.val() == "true") {
                mmc.show();
            }

            mms.on("change",function(){
                if ($(this).val() == "false") {
                    mmc.hide();
                } else {
                    mmc.show();
                }
            });

        });

        function megamenuToggle(selected){
            if ( selected == 100) {
                $('.megamenu-toggle').hide(0);
            } else {
                $('.megamenu-toggle').show(0);
            }
        }

        var selected = $('select[name="enovathemes_addons_megamenu_width"] option:selected').val();
        megamenuToggle(selected);

        $('select[name="enovathemes_addons_megamenu_width"]').on("change",function(){
            selected = $(this).val();
            megamenuToggle(selected);
        });

        function megamenuFormStyles(formChecked){
            if (formChecked) {
                $('.custom-form-styling').show(0);
            } else {
                $('.custom-form-styling').hide(0);
            }
        }

        function megamenuWidgetStyles(widgetChecked){
            if (widgetChecked) {
                $('.custom-widget-styling').show(0);
            } else {
                $('.custom-widget-styling').hide(0);
            }
        }

        var formChecked = ($('input[name="enovathemes_addons_custom_form_styling"]')).is(':checked') ? true : false;

        megamenuFormStyles(formChecked);
        $('input[name="enovathemes_addons_custom_form_styling"]').on("change",function(){
            formChecked = (this.checked) ? true: false;
            megamenuFormStyles(formChecked);
        });

        var widgetChecked = ($('input[name="enovathemes_addons_custom_widget_styling"]')).is(':checked') ? true : false;
        megamenuWidgetStyles(widgetChecked);
        $('input[name="enovathemes_addons_custom_widget_styling"]').on("change",function(){
            widgetChecked = (this.checked) ? true: false;
            megamenuWidgetStyles(widgetChecked);
        });

    })(jQuery);

/* Colorpicker
----*/

    (function( $ ) {

        "use strict";

        $(function() {
            $('.enovathemes-color-picker').wpColorPicker();
            $('#propharm_enovathemes_label1_color').wpColorPicker();
            $('#propharm_enovathemes_label2_color').wpColorPicker();
        });

    })( jQuery );

/* Posts
----*/

    (function($){

        "use strict";

        function formatSwitch($value){
            if ($value == "link") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.link-format').show(0);
                $('.post-data:not(.link-format)').hide(0);
            }else
            if ($value == "status") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.status-format').show(0);
                $('.post-data:not(.status-format)').hide(0);
            }else
            if ($value == "quote") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.quote-format').show(0);
                $('.post-data:not(.quote-format)').hide(0);
            }else
            if ($value == "gallery") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.gallery-format').show(0);
                $('.post-data:not(.gallery-format)').hide(0);
            }else
            if ($value == "audio") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.audio-format').show(0);
                $('.post-data:not(.audio-format)').hide(0);
            }else
            if ($value == "video") {
                $('#_enovathemes_addons_post_options_metabox').show(0);
                $('.video-format').show(0);
                $('.post-data:not(.video-format)').hide(0);
            }else {
                $('.post-data').hide(0);
                $('#_enovathemes_addons_post_options_metabox').hide(0);
            }
        }

        $('#formatdiv input[type="radio"]').each(function(){
            var $this = $(this);

            $this.on('click', function(){
                formatSwitch($this.val());
            });

            if($this.is(":checked")){
                formatSwitch($this.val());
            }
        });

    })(jQuery);

/* Sortable
----*/

    (function( $ ) {

        "use strict";

        var filterText = JSON.parse(admin_opt.filterText);

        function updateAttributes($this){

            $this.closest('form').find('input[name="savewidget"]').removeAttr('disabled');

            var atts = [];

            var attributes = $this.closest('.widget-product-filter').find('.sortable li');

            attributes.each(function(index){
                atts.push(JSON.parse($(this).attr('data-attribute')));
            });

            $this.closest('.widget-product-filter').find('input.atts').val(JSON.stringify(atts));
        }


        function removeAttribute($this){

            $this.find('.sortable li').each(function(){

                var li = $(this);

                li.find('.remove').on('click',function(){
                    li.remove();
                    updateAttributes($this);
                });
            });
        }

        function setAttributeOptions($this){
            $this.find('.sortable li').each(function(){
                var li = $(this);

                li.find('select.dis').on('change',function(){
                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    var val = $(this).val(),
                        col = $(this).parent().next('.image-on');

                    if (val == 'image') {
                       col.show(0);
                       att['column'] = col.find('select').val();
                    } else {
                        col.hide(0);
                        att['column'] = '2';
                    }

                    att['display'] = val;

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);
                });

                li.find('.image-on select').on('change',function(){
                    li.find('select.dis').trigger('change');
                });

                li.find('select.cats').on('change',function(){

                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    var val = $(this).val(),
                        next = $(this).parent().next('.include');

                    var containsNonEmpty = val.some(function(element) {
                        return element !== '' && (Array.isArray(element) ? element.length > 0 : true);
                      });

                    if (containsNonEmpty) {
                       next.show(0);
                       att['category'] = val;
                    } else {
                       next.hide(0);
                       att['category'] = '';
                    }

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);

                });

                li.find('select.cats-hide').on('change',function(){

                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    var val = $(this).val(),
                        next = $(this).parent().next('.include');

                    var containsNonEmpty = val.some(function(element) {
                        return element !== '' && (Array.isArray(element) ? element.length > 0 : true);
                      });

                    if (containsNonEmpty) {
                       next.show(0);
                       att['category-hide'] = val;
                    } else {
                       next.hide(0);
                       att['category-hide'] = '';
                    }

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);

                });

                li.find('input[name="children"]').on('click',function(){

                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    if(this.checked) {
                        att['children'] = 'true';
                    } else {
                        att['children'] = 'false';
                    }

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);

                });

                li.find('input[name="children-hide"]').on('click',function(){

                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    if(this.checked) {
                        att['children-hide'] = 'true';
                    } else {
                        att['children-hide'] = 'false';
                    }

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);

                });

                li.find('input[name="lock"]').on('click',function(){

                    var att = li.attr('data-attribute');
                    att = JSON.parse(att);

                    if(this.checked) {
                        att['lock'] = 'true';
                    } else {
                        att['lock'] = 'false';
                    }

                    li.attr('data-attribute',JSON.stringify(att));
                    updateAttributes($this);

                });

            });
        }

        function toggleAttribute($this){

            $this.find('.sortable li').each(function(){

                var li = $(this);

                li.find('.display').on('click',function(e){
                    e.stopImmediatePropagation();
                    li.toggleClass('active');
                });

            });
        }

        function widgetSortableToggle($this){

            $this.find('.draggable li')
            .draggable({
                connectToSortable: $this.find('.sortable'),
                helper: "clone",
                revert: "invalid",
                start: function( event, ui ) {
                    $this.parent().find('.sortable').addClass('highlight');
                },
                stop: function( event, ui ) {
                    $this.parent().find('.sortable').removeClass('highlight');

                    var target = $(event.target).attr('data-title');
                    if ($this.find('.sortable li[data-title="'+target+'"]').length  > 1) {
                        $this.find('.sortable li[data-title="'+target+'"]:first(:not(:only))').remove();
                    }

                    updateAttributes($this);

                }
            })
            .disableSelection();

            $this.find('.sortable')
            .sortable({
                stop: function( event, ui ) {
                    toggleAttribute($this);
                    setAttributeOptions($this);
                    removeAttribute($this);
                    updateAttributes($this);
                }
            })
            .disableSelection();

        }


        function buildAttributes($this){

            var attributes = $this.find('input.atts').val();

            if (attributes.length) {

                attributes = JSON.parse(attributes);

                for (var i = 0; i < attributes.length; i++) {

                    var attributeObject = attributes[i];
                    
                    var li = '<li data-attribute=\''+JSON.stringify(attributeObject)+'\' data-title="'+attributeObject['label']+'" class="draggable-item">'+attributeObject['label'];
                        li += '<span class="remove" title="'+filterText['remove']+'"></span>';
                    if (attributeObject['attr'] != 'price' && attributeObject['attr'] != 'rating') {
                        li += '<span class="display" title="'+filterText['display']+'"></span>';
                        li += '<div>';
                            if (attributeObject['attr'] != 'cat') {
                                li += '<label>'+filterText['limit']+'<select class="cats" multiple><option value="">'+filterText['all']+'</option>'+admin_opt.categories+'</select></label><label class="include"><input name="children" type="checkbox" value="true" />'+filterText['include']+'</label><br/><br/>';
                                li += '<label>'+filterText['hide']+'<select class="cats-hide" multiple><option value="">'+filterText['all']+'</option>'+admin_opt.categories+'</select></label><label class="include-hide"><input name="children-hide" type="checkbox" value="true" />'+filterText['include']+'</label><br/><br/>';
                            }
                            li += '<label>'+filterText['display']+'';
                            li += '<select class="dis">';
                                li += '<option value="select">'+filterText['select']+'</option>';
                                li += '<option value="list">'+filterText['list']+'</option>';
                                li += '<option value="image">'+filterText['image']+'</option>';
                                if (attributeObject['attr'] == 'cat') {
                                    li += '<option value="image-list">'+filterText['image-list']+'</option>';
                                }
                                if (attributeObject['attr'] != 'cat') {
                                    li += '<option value="label">'+filterText['label']+'</option>';
                                    li += '<option value="col">'+filterText['color']+'</option>';
                                    li += '<option value="slider">'+filterText['slider']+'</option>';
                                }
                            li += '</select></label>';
                            li += '<label class="image-on">'+filterText['columns'];
                            li += '<select>';
                                li += '<option value="2">2</option>';
                            li += '</select></label>';
                            if (attributeObject['attr'] != 'cat') {
                                li += '<p>'+filterText['desc1']+'</p>';
                            } else {
                                li += '<p>'+filterText['desc2']+'</p>';
                            }
                            li += '<br><label class="lock"><input name="lock" type="checkbox" value="true">'+filterText['lock']+'</label>';
                            li += '<p>'+filterText['lock-desk']+'</p>';

                        li += '</div>';
                    }
                    li += '</li>';


                    $this.find('.sortable').append(li);

                    let list = $this.find('.sortable li');
                    list = $.unique( list );
                    $this.find('.sortable').html(list);

                    
                }

                $this.find('.sortable li').each(function(){

                    var $this           = $(this),
                        attributeObject = JSON.parse($this.attr('data-attribute')),
                        display         = (attributeObject['display']) ? attributeObject['display'] : '',
                        column          = (attributeObject['column']) ? attributeObject['column'] : '2',
                        category        = (attributeObject['category']) ? attributeObject['category'] : '',
                        children        = (attributeObject['children']) ? attributeObject['children'] : 'false',
                        lock            = (attributeObject['lock']) ? attributeObject['lock'] : 'false',
                        category_hide   = (attributeObject['category-hide']) ? attributeObject['category-hide'] : '',
                        children_hide   = (attributeObject['children-hide']) ? attributeObject['children-hide'] : 'false';

                    if (display) {
                        if (display == 'image') {
                            $this.find('.image-on').show(0);
                            $this.find('.image-on select option[value="'+column+'"]').attr("selected","selected");
                        } else {
                            $this.find('.image-on').hide(0);
                        }
                        $this.find('option[value="'+display+'"]').attr("selected","selected");
                    }

                    if (category) {
                        $this.find('label.include').show(0);
                        if (Array.isArray(category)) {
                            for (var i = 0; i <= category.length; i++) {
                                $this.find('select.cats').find('option[value="'+category[i]+'"]').attr("selected","selected");
                            }
                        } else {
                            $this.find('select.cats').find('option[value="'+category+'"]').attr("selected","selected");
                        }
                        if (children == 'true') {
                            $this.find('input[name="children"]').attr('checked','checked');
                        }
                    }

                    if (lock == 'true') {
                        $this.find('input[name="lock"]').attr('checked','checked');
                    }

                    if (category_hide) {

                        $this.find('label.include-hide').show(0);
                        if (Array.isArray(category_hide)) {
                            for (var i = 0; i <= category_hide.length; i++) {
                                $this.find('select.cats-hide').find('option[value="'+category_hide[i]+'"]').attr("selected","selected");
                            }
                        } else {
                           $this.find('select.cats-hide').find('option[value="'+category_hide+'"]').attr("selected","selected");
                        }
                        if (children_hide == 'true') {
                            $this.find('input[name="children-hide"]').attr('checked','checked');
                        }
                    }

                });

            }
        }

        function widgetSortable(){

            $('.widget-product-filter').each(function(){

                var $this = $(this);

                widgetSortableToggle($this);
                buildAttributes($this);
                toggleAttribute($this);
                setAttributeOptions($this);
                removeAttribute($this);

            });

        }

        widgetSortable();

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

                if(dataObj['action'] == "save-widget" && dataObj['id_base'] == "product_filter_widget"){
                    widgetSortable();
                }

        });

    })( jQuery );

/* Header options
----*/

    (function($){

        "use strict";

        function toggleHeader(selected){
            switch(selected){
                case "sidebar":
                    $('.sidebar-off').hide(0);
                    $('.sidebar-on').show(0);
                break;
                case "desktop":
                    $('.sidebar-on').hide(0);
                    $('.sidebar-off').show(0);
                    $('.desktop-on').show(0);
                break;
                case "mobile":
                    $('.sidebar-off').show(0);
                    $('.sidebar-on').hide(0);
                    $('.desktop-on').hide(0);
                break;
            }
        }

        var selected = $('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"] option:selected').val();

        toggleHeader(selected)

        $('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"]').on('change', function(){

            selected = $(this).find("option:selected").val();

            toggleHeader(selected)

        });

        if ($('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"] option:selected').val() == 'desktop' || $('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"] option:selected').val() == 'sidebar') {
            $('#enovathemes_addons_header_options_metabox input[name="enovathemes_addons_desktop"]').attr('checked','checked');
        }

        if ($('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"] option:selected').val() == 'mobile') {
            $('#enovathemes_addons_header_options_metabox input[name="enovathemes_addons_desktop"]').removeAttr('checked','');
        }

        $('#enovathemes_addons_header_options_metabox select[name="enovathemes_addons_header_type"]').on('change',function(){
            if ($(this).val() == 'mobile') {
                $('#enovathemes_addons_header_options_metabox input[name="enovathemes_addons_desktop"]').removeAttr('checked','');
            } else
            if ($(this).val() == 'desktop' || $(this).val() == 'sidebar') {
                $('#enovathemes_addons_header_options_metabox input[name="enovathemes_addons_desktop"]').attr('checked','checked');
            }
        });

    })(jQuery);