(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    function isInArray(value, array) {return array.indexOf(value) > -1;}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

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

            var element   = this,
                $this     = $(element),
                id        = $this.attr('id'),
                delay     = '+='+(0.2 + parseInt($this.data('delay'))/1000),
                interval  = parseInt($this.data('interval'))/1000,
                stagger   = $this.data('stagger'),
                content   = $this.find('.content').children().not('.et-gap');

            var tl = new gsap.timeline({paused: true});

            buildStaggerBoxTimeline(tl,delay,interval,stagger,content);

            $this.addClass('active');

            tl.progress(0);
            tl.play();

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
                return dataObj[key] === "et_stagger_box";
            });

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){
                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-stagger-box');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);