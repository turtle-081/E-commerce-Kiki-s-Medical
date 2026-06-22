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

    var valid = "invalid";
    function validateValue($value, $target, $placeholder,$email){
        if ($email == true) {
            var n = $value.indexOf("@");
            var r = $value.lastIndexOf(".");
            if (n < 1 || r < n + 2 || r + 2 >= $value.length) {
                valid =  "invalid";
            } else {
                valid = "valid";
            }
            
            if ($value == null || $value == "" || valid == "invalid") {
                $target.addClass('visible');
            } else {
                $target.removeClass('visible');
            }

        } else {
            if ($value == null || $value == "" || $value == $placeholder) {
                $target.addClass('visible');
            } else {
                $target.removeClass('visible');
            }
        }
    };

    function iframeSCRIPT(element){
        $(element).each(function(){

            var $this = $(this);

            $this.submit(function(event) {

                event.preventDefault();

                var formData = $this.serialize();

                var email   = $this.find("input[name='email']"),
                    fname   = $this.find("input[name='fname']"),
                    lname   = $this.find("input[name='lname']"),
                    phone   = $this.find("input[name='phone']"),
                    list    = $this.find("input[name='list']");

                validateValue(email.val(), email.next(".alert"), email.attr('data-placeholder'), true);
                if (fname.length && fname.attr('data-required') == "true") {validateValue(fname.val(), fname.next(".alert"), fname.attr('data-placeholder'), false);}
                if (lname.length && lname.attr('data-required') == "true") {validateValue(lname.val(), lname.next(".alert"), lname.attr('data-placeholder'), false);}
                if (phone.length && phone.attr('data-required') == "true") {validateValue(phone.val(), phone.next(".alert"), phone.attr('data-placeholder'), false);}

                if (email.val() != email.attr('data-placeholder') && valid == "valid" && 
                    (fname.length && fname.attr('data-required') == "true" && fname.val() != fname.attr('data-placeholder')) &&
                    (lname.length && lname.attr('data-required') == "true" && lname.val() != lname.attr('data-placeholder')) &&
                    (phone.length && phone.attr('data-required') == "true" && phone.val() != phone.attr('data-placeholder'))
                ){

                    $this.find(".sending").addClass('visible');

                    $.ajax({
                        type: 'POST',
                        url: $this.attr('action'),
                        data: formData
                    })
                    .done(function(response) {
                        $this.find(".sending").removeClass('visible');
                        $this.find(".et-mailchimp-success").addClass('visible');
                        setTimeout(function(){
                            $this.find(".et-mailchimp-success").removeClass('visible');
                        },2000);
                    })
                    .fail(function(data) {
                        $this.find(".sending").removeClass('visible');
                        $this.find(".et-mailchimp-error").addClass('visible');
                        setTimeout(function(){
                            $this.find(".et-mailchimp-error").removeClass('visible');
                        },2000);
                    })
                    .always(function(){
                        setTimeout(function(){
                            // Clear the form.
                            $this.find("input[name='email']").val(email.attr('data-placeholder'));
                            $this.find("input[name='fname']").val(fname.attr('data-placeholder'));
                            $this.find("input[name='lname']").val(lname.attr('data-placeholder'));
                            $this.find("input[name='phone']").val(phone.attr('data-placeholder'));
                        },2000);
                    });

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
                return dataObj[key] === "et_mailchimp";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_mailchimp"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mailchimp"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mailchimp"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_mailchimp"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_mailchimp"]');

                        /* Styling
                        ---------------*/

                            var text_color                    = edit_element.find('input[name="text_color"]').val(),
                                text_color_focus              = edit_element.find('input[name="text_color_focus"]').val(),
                                text_background_color         = edit_element.find('input[name="text_background_color"]').val(),
                                text_background_color_focus   = edit_element.find('input[name="text_background_color_focus"]').val(),
                                text_border_color             = edit_element.find('input[name="text_border_color"]').val(),
                                text_border_color_focus       = edit_element.find('input[name="text_border_color_focus"]').val(),
                                button_color                  = edit_element.find('input[name="button_color"]').val(),
                                button_color_hover            = edit_element.find('input[name="button_color_hover"]').val(),
                                button_background_color       = edit_element.find('input[name="button_background_color"]').val(),
                                button_background_color_hover = edit_element.find('input[name="button_background_color_hover"]').val(),
                                terms_color                   = edit_element.find('input[name="terms_color"]').val(),
                                terms_color_hover             = edit_element.find('input[name="terms_color_hover"]').val();

                                CSS += '#et-mailchimp-'+ID+' .field,#et-mailchimp-'+ID+' .terms:before {';

                                    if (text_color.length) {CSS += 'color:'+text_color+';';}
                                    if (text_background_color.length) {
                                        CSS += 'background-color:'+text_background_color+';';
                                    } else {
                                        CSS += 'background-color:transparent;';
                                    }

                                    if (text_border_color.length) {
                                        CSS += 'border:1px solid '+text_border_color+';';
                                    } else {
                                        CSS += 'border:none:;';
                                    }

                                CSS += '}';
                                
                                CSS += '#et-mailchimp-'+ID+' .button {';
                                    if (button_color.length) {
                                        CSS += 'color:'+button_color+';';
                                    }
                                    if (button_background_color.length) {
                                        CSS += 'background-color:'+button_background_color+';';
                                    }
                                CSS += '}';

                                CSS += '#et-mailchimp-'+ID+' .button:hover {';
                                    if (button_color_hover.length) {
                                        CSS += 'color:'+button_color_hover+' !important;';
                                    }
                                    if (button_background_color_hover.length) {
                                        CSS += 'background-color:'+button_background_color_hover+' !important;';
                                    }
                                CSS += '}';

                                if (text_color.length) {
                                    CSS += '#et-mailchimp-'+ID+' ::placeholder {';
                                        CSS += 'color:'+text_color+';';
                                    CSS += '}';
                                }

                                if (terms_color.length) {
                                    CSS += '#et-mailchimp-'+ID+' .terms {';
                                        CSS += 'color:'+terms_color+';';
                                    CSS += '}';
                                }

                                if (terms_color_hover.length) {
                                    CSS += '#et-mailchimp-'+ID+' .terms a:hover {';
                                        CSS += 'color:'+terms_color_hover+';';
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
                        iframe = document.getElementById('vc_inline-frame');
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .et-mailchimp');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);