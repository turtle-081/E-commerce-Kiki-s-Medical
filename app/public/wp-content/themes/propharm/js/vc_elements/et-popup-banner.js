(function($){

    "use strict";

    $.fn.placeholder = function() {

        $.each(this, function(){

            var $this       = $(this);

            if ($this.attr('placeholder')) {
                $this.attr("data-placeholder", $this.attr('placeholder'));
                $this.removeAttr('placeholder');
            };

            var placeholder = $this.data("placeholder");

            if($this.val() == '') { $this.val(placeholder);}

            $this
            .on('focus', function(){
                if($this.val() == placeholder) { $this.val('');}
            })
            .on('focusout', function(){
                if($this.val() == '') { $this.val(placeholder);}
            });

        });

    }

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

            var $this  = $(this);
            var $delay = $this.data('delay');
            var cookie = $this.data('cookie');

            if (typeof($delay) == 'undefined' && $delay == null){
                $delay = 3000;
            }

            var bannerClone = $this.clone();

            doc.find('body').append(bannerClone);

            $this.remove();

            bannerClone.find('a').on('click',function(event){
                event.stopPropagation();
            });

            if (typeof($.cookie(bannerClone.attr('id'))) == 'undefined') {



                setTimeout(function(){
                    bannerClone.addClass('animate');

                    $('.et-contact-form input[type="text"], .et-contact-form textarea').placeholder();

                    $('.widget_login, .widget_reglog').each(function(){
                        var $this = $(this);

                        $this.find('label').each(function(){
                            var labelText = $(this).text();
                            $(this).next('input').attr('placeholder',labelText);
                            $(this).remove();
                        });

                        $this.find('input[type="text"]').placeholder();
                        $this.find('input[type="password"]').placeholder();

                        $this.find('input[type="submit"]').on("click",function(event) {

                            if (!$this.find('input[type="text"]').val() || !$this.find('input[type="password"]').val() || 
                                $this.find('input[type="text"]').val() == $this.find('input[type="text"]').data('placeholder') ||
                                $this.find('input[type="password"]').val() == $this.find('input[type="password"]').data('placeholder')) {
                                console.log("here");
                                event.preventDefault();
                            }

                        });
                    });

                    $('.search-form').each(function(){
                        var form  = $(this);
                        var search = form.find('#s');
                        search.placeholder();
                        form.submit(function(event){
                            if (search.val() === search.attr('data-placeholder')) {
                                event.preventDefault();
                            }
                        });
                    });

                },$delay);

                bannerClone.find('.popup-banner-toggle').bind('click',function(){
                    bannerClone.removeClass('animate');
                    if (cookie == true) {
                        $.cookie(bannerClone.attr('id'),'active',{ expires: 1,path: '/'});
                    }
                });

                bannerClone.on('click',function(){
                    bannerClone.removeClass('animate');
                    if (cookie == true) {
                        $.cookie(bannerClone.attr('id'),'active',{ expires: 1,path: '/'});
                    }
                });

            }

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
                        return dataObj[key] === "et_popup_banner";
                    });

                /* Edit element
                /*-------------*/

                    if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_popup_banner"){

                        var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_popup_banner"]');

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

                        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_popup_banner"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                            if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_popup_banner"]').length) {

                                var ID  = uniqueID();
                                var CSS = '';

                                edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_popup_banner"]');

                                /* Styling
                                ---------------*/

                                    var width        = edit_element.find('input[name="width"]').val(),
                                        height       = edit_element.find('input[name="height"]').val(),
                                        back_color   = edit_element.find('input[name="back_color"]').val(),
                                        border_color = edit_element.find('input[name="border_color"]').val(),
                                        back_img     = edit_element.find('input[name="back_img"]').val();

                                    CSS += '#et-popup-banner-'+ID+' {';

                                        if(back_img.length){
                                            back_img = edit_element.find("img[rel=\""+back_img+"\"]").attr("src").replace("-150x150", "");
                                            CSS += "background-image:url("+back_img+");";
                                        }

                                        if (width.length) {
                                            CSS += 'width:'+width+'px;';
                                            CSS += 'margin-left:-'+(width/2)+'px;';
                                        }

                                        if (height.length) {
                                            CSS += 'height:'+height+'px;';
                                            CSS += 'margin-top:-'+(height/2)+'px;';
                                        }

                                        if (back_color.length) {
                                            CSS += 'background-color:'+back_color+';';
                                        }

                                        if (border_color.length) {
                                            CSS += 'box-shadow:inset 0 0 0 4px '+border_color+';';
                                        }

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

                                    CSS += '#et-popup-banner-'+ID+' {';
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
                                var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-popup-banner');
                                if (typeof(element) != 'undefined' && element != null) {
                                    iframeSCRIPT(element,doc);
                                }
                            });
                        }
                        return;
                    }

                    

        });

})(jQuery);