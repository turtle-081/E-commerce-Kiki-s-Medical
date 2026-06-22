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

    function iframeSCRIPT(element){
        $(element).each(function(){
            var $this    = $(this),
                type     = ($this.hasClass('circle')) ? 'circle' : 'default',
                delay    = (0.2 + $this.index()*0.01),
                value    = $this.data('percentage'),
                counterV = { var: 0 },
                counter  = $this.find('.percent');

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
                        return dataObj[key] === "et_progress";
                    });

                /* Edit element
                /*-------------*/

                    if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_progress"){

                        var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_progress"]');

                        var element_css  = edit_element.find('textarea[name="element_css"]'),
                            element_id   = edit_element.find('input[name="element_id"]');


                        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_progress"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                            if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_progress"]').length) {

                                var ID  = uniqueID();
                                var CSS = '';

                                edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_progress"]');

                                /* Styling
                                ---------------*/

                                    var bar_color   = edit_element.find('input[name="bar_color"]').val(),
                                        track_color = edit_element.find('input[name="track_color"]').val(),
                                        text_color  = edit_element.find('input[name="text_color"]').val(),
                                        percentage  = edit_element.find('input[name="percentage"]').val(); 

                                    
                                    CSS += '#et-progress-'+ID+' .bar {';
                                        if (bar_color.length) {
                                            CSS += 'background-color:'+bar_color+';';
                                        }
                                        if (percentage.length) {
                                            CSS += 'width:'+percentage+'%;';
                                        }
                                    CSS += '}';

                                    if (bar_color.length) {
                                        CSS += '#et-progress-'+ID+' .bar-circle {';
                                            CSS += 'stroke:'+bar_color+';';
                                        CSS += '}';
                                    }

                                    if (track_color.length) {
                                        CSS += '#et-progress-'+ID+' .track {';
                                            CSS += 'background-color:'+track_color+';';
                                        CSS += '}';
                                        CSS += '#et-progress-'+ID+' .track-circle {';
                                            CSS += 'stroke:'+track_color+';';
                                        CSS += '}';
                                    }

                                    if (text_color.length) {
                                        CSS += '#et-progress-'+ID+' .text {';
                                            CSS += 'color:'+text_color+';';
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
                                var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-progress');
                                if (typeof(element) != 'undefined' && element != null) {
                                    iframeSCRIPT(element);
                                }
                            });
                        }
                        return;
                    }

                    

        });

})(jQuery);