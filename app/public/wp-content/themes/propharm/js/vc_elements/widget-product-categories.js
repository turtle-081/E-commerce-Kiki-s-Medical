(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function iframeSCRIPT(element,doc){

        $(element).each(function(){

            var $this = $(this);

            if ($this.find('.count').length != 0) {
                $this.find('a').each(function(){
                    var $self = $(this);
                    var countClone = $self.next('.count').clone();
                    $self.next('.count').remove();
                    $self.append(countClone);
                });
            }

            var childItems = $this.find('.cat-parent > a')
            .append('<span class="toggle"></span>');

            if ($this.find('.cat-parent > a').attr( "href" ) == "#") {
                $this.find('.cat-parent > a').on('click',function(e){
                    $(this).toggleClass('active');
                    if ($(this).parent().next('.children').length != 0) {
                        $(this).parent().toggleClass('animate');
                        $(this).parent().next('.children').stop().slideToggle(300, "easeOutExpo");
                    };
                    e.preventDefault();
                });
            } else {
                $this.find('.cat-parent > a > span.toggle').on('click',function(e){
                    $(this).toggleClass('active');
                    if ($(this).parent().next('.children').length != 0) {
                        $(this).parent().toggleClass('animate');
                        $(this).parent().next('.children').stop().slideToggle(300, "easeOutExpo");
                    };
                    e.preventDefault();
                });
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
                return dataObj[key] === "widget_product_categories";
            });

        /* Load element
        /*-------------*/

            if((dataObj['action'] == "vc_load_shortcode" && elementExists)){
                var iframe = $('#vc_inline-frame');
                if (typeof(iframe) != 'undefined' && iframe != null){
                    iframe.ready(function() {
                        var doc = iframe.contents();
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .widget_product_categories');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }
    });

})(jQuery);