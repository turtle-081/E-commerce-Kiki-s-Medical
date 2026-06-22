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

    function iframeSCRIPT(element,doc){
        $(element).each(function(){

            var $this    = $(this),
                delay    = (0.2 + $this.index()*0.01),
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

            $this.addClass('active');
            
            tl.progress(0);
            tl.play();
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
                        return dataObj[key] === "et_counter";
                    });

                /* Edit element
                /*-------------*/

                    if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_counter"){

                        var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_counter"]');

                        var element_css  = edit_element.find('textarea[name="element_css"]'),
                            element_id   = edit_element.find('input[name="element_id"]');


                        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_counter"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                            if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_counter"]').length) {

                                var ID  = uniqueID();
                                var CSS = '';

                                edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_counter"]');

                                /* Styling
                                ---------------*/

                                    var value_color = edit_element.find('input[name="value_color"]').val(),
                                        title_color = edit_element.find('input[name="title_color"]').val(),
                                        icon_color  = edit_element.find('input[name="icon_color"]').val(),
                                        value_font_size = edit_element.find('input[name="value_font_size"]').val();
                                
                                    CSS += '#et-counter-'+ID+' .counter-value, #et-counter-'+ID+' .counter-value dir-child* * {';
                                        if (value_color.length) {
                                            CSS += 'color:'+value_color+';';
                                        }
                                        if (value_font_size.length) {
                                            CSS += 'font-size:'+value_font_size+'px;';
                                        }
                                    CSS += '}';

                                    if (icon_color.length) {
                                        CSS += '#et-counter-'+ID+' .counter-icon svg, #et-counter-'+ID+' .counter-icon svg * {';
                                            CSS += 'fill:'+icon_color+' !important;';
                                        CSS += '}';
                                    }

                                    CSS += '#et-counter-'+ID+' .counter-title {';
                                        if (title_color.length) {
                                            CSS += 'color:'+title_color+';';
                                        }
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
                                var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-counter');
                                if (typeof(element) != 'undefined' && element != null) {
                                    iframeSCRIPT(element,doc);
                                }
                            });
                        }
                        return;
                    }

                    

        });

})(jQuery);