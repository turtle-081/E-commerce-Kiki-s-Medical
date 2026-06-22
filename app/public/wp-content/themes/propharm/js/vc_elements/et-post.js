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

            if ($this.hasClass('comp')) {

                var $this  = $(this);
                var slides = this.querySelector('.slides');

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

                var CSS = '.tns-slider{transition: all 0.3s !important;}';
                var id    = '#'+$this.attr('id'),
                    nm    = $this.find('.slides').children().length;

                CSS += id+' .tns-item{width:calc('+100/nm+'%);padding-right:0}';
                CSS += id+' .tns-inner {margin: 0 0 0 0}';

                CSS += id+' .slides {width:calc('+100*nm+'%);}';

                CSS += '@media (min-width: 20em){';
                    CSS += id+' .slides {width:calc('+100*nm+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 48em){';
                    CSS += id+' .slides {width:calc('+100*nm+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 64em){';
                    CSS += id+' .slides {width:calc('+100*nm+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 80em){';
                    CSS += id+' .slides {width:calc('+100*ratio+'%);}';
                CSS += '}';

                $(doc).find("#dynamic-styles-inline-css").append(CSS);

            } else if ($this.hasClass('carousel') && !$this.find('.tns-ovh').length) {

                var slides    = $this.find('.slides'),
                    items     = slides.parent().data('columns'),
                    items767  = 1,
                    items768  = (items >= 3) ? 2 : items,
                    items1024 = items,
                    gatter    = 24,
                    autoplay  = (slides.parent().data('autoplay')) ? slides.parent().data('autoplay') : false,
                    nav       = (slides.parent().data('nav')) ? slides.parent().data('nav') : 'arrows';

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

                CSS += id+' .tns-item{width:calc('+100/nm+'%);padding-right:'+gatter+'px}';
                CSS += id+' .tns-inner {margin: 0 -'+gatter+'px 0 0}';

                CSS += id+' .slides {width:calc('+100*ratio+'%);}';

                CSS += '@media (min-width: 20em){';
                    CSS += id+' .slides {width:calc('+100*nm+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 48em){';
                    CSS += id+' .slides {width:calc('+100*(nm/items768)+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 64em){';
                    CSS += id+' .slides {width:calc('+100*(nm/items1024)+'%);}';
                CSS += '}';

                CSS += '@media (min-width: 80em){';
                    CSS += id+' .slides {width:calc('+100*ratio+'%);}';
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
                return dataObj[key] === "et_posts";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_posts"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_posts"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_posts"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_posts"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_posts"]');

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
                        iframe = document.getElementById('vc_inline-frame');
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"]');
                        element = element.parent().find('.et-shortcode-posts')
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);