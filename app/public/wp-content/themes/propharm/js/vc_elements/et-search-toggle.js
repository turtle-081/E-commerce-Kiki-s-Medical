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

    function hbeAlign(element,doc){
        var CSS = '';

        if (element.hasClass('hbe-right')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:right;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-left')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:left;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
        if (element.hasClass('hbe-center') || element.hasClass('hbe-none')) {
            CSS = '.vc_element[data-model-id="'+element.parent().attr('data-model-id')+'"] {float:none;display:inline-block;}';
            doc.find("#dynamic-styles-inline-css").append(CSS);
            return;
        }
    }

    function iframeSCRIPT(element){
        $(element).each(function(){

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
                return dataObj[key] === "et_search_toggle";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_search_toggle"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_toggle"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    margin_box   = edit_element.find(".margin-box"),
                    margin       = edit_element.find('input[name="margin"]'),
                    margin_val   = margin.val(),
                    margin_array = [];

                if(typeof(margin_val) != "undefined" && margin_val.length){

                    var margin_array = margin_val.split(",");

                    margin_box.find("input[name=\"margin-top\"]").attr('value',margin_array[0]);
                    margin_box.find("input[name=\"margin-right\"]").attr('value',margin_array[1]);
                    margin_box.find("input[name=\"margin-bottom\"]").attr('value',margin_array[2]);
                    margin_box.find("input[name=\"margin-left\"]").attr('value',margin_array[3]);

                }


                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_toggle"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_toggle"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_search_toggle"]');

                        /* Styling
                        ---------------*/

                            var align                        = edit_element.find('select[name="align"] option:selected').val(),
                                icon_color                   = edit_element.find('input[name="icon_color"]').val(),
                                icon_background_color        = edit_element.find('input[name="icon_background_color"]').val(),
                                icon_border_color            = edit_element.find('input[name="icon_border_color"]').val(),
                                icon_border_width            = edit_element.find('input[name="icon_border_width"]').val(),
                                search_color                 = edit_element.find('input[name="search_color"]').val(),
                                search_background_color      = edit_element.find('input[name="search_background_color"]').val(),
                                search_icon_color            = edit_element.find('input[name="search_icon_color"]').val(),
                                search_icon_background_color = edit_element.find('input[name="search_icon_background_color"]').val(),
                                search_icon_background_color_hover = edit_element.find('input[name="search_icon_background_color_hover"]').val();

                            CSS += '#search-toggle-'+ID+' {';

                                if (icon_background_color.length) {
                                    CSS += 'background-color:'+icon_background_color+';';
                                } else {
                                    CSS += 'background-color:transparent;';
                                }

                                if (icon_border_width.length) {

                                    if (!icon_border_color.length) {
                                        icon_border_color = icon_color;
                                    }

                                    CSS += 'box-shadow:inset 0 0 0 '+icon_border_width+'px '+icon_border_color+';';
                                } else {
                                    CSS += 'box-shadow:none;';
                                }

                            CSS += '}';

                            if (icon_color.length) {
                                CSS += '#search-toggle-'+ID+' svg {';
                                    CSS += 'fill:'+icon_color+';';
                                CSS += '}';
                            }

                            if (search_color.length) {
                                CSS += '#search-box-'+ID+' #s {';
                                    CSS += 'color:'+search_color+' !important;';
                                CSS += '}';
                            }

                            if (search_icon_color.length) {
                                CSS += '#search-box-'+ID+' .search-icon svg {';
                                    CSS += 'fill:'+search_icon_color+' !important;';
                                CSS += '}';
                            }

                            if (search_icon_background_color.length) {
                                CSS += '#search-box-'+ID+' .search-icon {';
                                    CSS += 'background-color:'+search_icon_background_color+' !important;';
                                CSS += '}';
                            }

                            if (search_icon_background_color_hover.length) {
                                CSS += '#search-box-'+ID+' #searchsubmit:hover + .search-icon {';
                                    CSS += 'background-color:'+search_icon_background_color_hover+' !important;';
                                CSS += '}';
                            }

                            CSS += '#search-box-'+ID+' .search-back {';
                                if (search_background_color.length) {
                                    CSS += 'fill:'+search_background_color+';';
                                } else {
                                    CSS += 'fill:none;';
                                }
                            CSS += '}';

                        /* Margin
                        ---------------*/

                            var margin_left   = edit_element.find(".margin-box input[name=\"margin-left\"]").val(),
                                margin_top    = edit_element.find(".margin-box input[name=\"margin-top\"]").val(),
                                margin_right  = edit_element.find(".margin-box input[name=\"margin-right\"]").val(),
                                margin_bottom = edit_element.find(".margin-box input[name=\"margin-bottom\"]").val();

                            margin_top = (margin_top.length) ? margin_top : '0';
                            margin_right = (margin_right.length) ? margin_right : '0';
                            margin_bottom = (margin_bottom.length) ? margin_bottom : '0';
                            margin_left = (margin_left.length) ? margin_left : '0';

                            var margin_output = margin_top+','+margin_right+','+margin_bottom+','+margin_left,
                                margin_value  = margin_top+'px '+margin_right+'px '+margin_bottom+'px '+margin_left+'px';

                            margin.val(margin_output);

                            CSS += '#header-search-'+ID+' {';
                                CSS += 'margin:'+margin_value+';';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-search');
                        if (typeof(element) != 'undefined' && element != null) {
                            hbeAlign(element,doc);
                            iframeSCRIPT(element);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);
