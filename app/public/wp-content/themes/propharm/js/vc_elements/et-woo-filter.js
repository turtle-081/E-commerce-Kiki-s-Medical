(function($){

    "use strict";

    function uniqueID() {return Math.floor((Math.random() * 1000000) + 1);}

    String.prototype.replaceAll = function(str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    }

    function updateAttributes(){

        var atts = [];

        var attributes = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable li');

        attributes.each(function(index){
            atts.push($(this).attr('data-attribute'));
        });

        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('input.atts').val(atts.join('|'));
    }


    function removeAttribute(){

        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable li').each(function(){

            var li = $(this);

            li.find('.remove').on('click',function(){
                li.remove();
                updateAttributes();
            });
        });
    }

    function widgetSortableToggle(){

        $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.draggable li')
        .draggable({
            connectToSortable: $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable'),
            helper: "clone",
            revert: "invalid",
            start: function( event, ui ) {
               $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable').addClass('highlight');
            },
            stop: function( event, ui ) {
                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable').removeClass('highlight');

                var target = $(event.target).attr('data-title');
                if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable li[data-title="'+target+'"]').length  > 1) {
                    $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable li[data-title="'+target+'"]:first(:not(:only))').remove();
                }

                updateAttributes();

            }
        })
        .disableSelection();

       $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable')
        .sortable({
            stop: function( event, ui ) {
                removeAttribute();
                updateAttributes();
            }
        })
        .disableSelection();

    }


    function buildAttributes(){

        var attributes = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('input.atts').val();

        if (attributes.length) {

            attributes = attributes.split('|');

            for (var i = 0; i < attributes.length; i++) {

                var attribute  = attributes[i];
                var attrObject = [];

                attribute = attribute.split(',');

                for (var p = 0; p < attribute.length;p++) {
                    var att = attribute[p];
                    att = att.split(':');
                    attrObject[att[0]] = att[1];
                }
                
                var li = '<li data-attribute="'+attributes[i]+'" data-title="'+attrObject['label']+'" class="draggable-item">'+attrObject['label']+'<span class="remove"></span></li>';

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').find('.sortable').append(li);

            }

        }
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
                return dataObj[key] === "et_woo_filter";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_woo_filter"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]');

                var element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]');

                widgetSortableToggle();
                buildAttributes();
                removeAttribute();

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_woo_filter"]');

                        /* Styling
                        ---------------*/

                            var background_color               = edit_element.find('input[name="background_color"]').val(),
                                text_color                     = edit_element.find('input[name="text_color"]').val(),
                                select_background_color        = edit_element.find('input[name="select_background_color"]').val(),
                                select_border_color            = edit_element.find('input[name="select_border_color"]').val(),
                                select_text_color              = edit_element.find('input[name="select_text_color"]').val(),
                                button_background_color        = edit_element.find('input[name="button_background_color"]').val(),
                                button_text_color              = edit_element.find('input[name="button_text_color"]').val(),
                                button_background_color_hover  = edit_element.find('input[name="button_background_color_hover"]').val(),
                                button_text_color_hover        = edit_element.find('input[name="button_text_color_hover"]').val(),
                                box_shadow                     = edit_element.find('input[name="box_shadow"]'),
                                li                             = edit_element.find('.sortable li').length,
                                sku                            = edit_element.find('select[name="sku"] option:selected').val();

                            CSS += '#select-filter-'+ID+' {';
                                if (background_color.length) {
                                    CSS += 'background-color:'+background_color+' !important;';
                                }
                                if (text_color.length) {
                                    CSS += 'color:'+text_color+' !important;';
                                }
                                if (box_shadow.is(':checked')) {
                                    CSS += 'box-shadow:0 0 20px 0 rgba(0,0,0,0.1) !important;';
                                }
                            CSS += '}';

                            if (li) {

                                li++;

                                if (sku == 'true') {li++;}

                                CSS += '#select-filter-'+ID+' form {';
                                    CSS += 'grid-template-columns: repeat('+li+', '+li+'fr);';
                                CSS += '}';
                            }

                            CSS += '#select-filter-'+ID+' select, #select-filter-'+ID+' .sku input {';
                                if (select_background_color.length) {
                                    CSS += 'background-color:'+select_background_color+' !important;';
                                }
                                if (select_text_color.length) {
                                    CSS += 'color:'+select_text_color+' !important;';
                                }
                                if (select_border_color.length) {
                                    CSS += 'border-color:'+select_border_color+' !important;';
                                }

                                if (select_background_color.length && select_border_color.length && select_border_color != select_background_color) {
                                    CSS += 'box-shadow:none !important;';
                                }

                            CSS += '}';

                            CSS += '#select-filter-'+ID+' button {';
                                if (button_background_color.length) {
                                    CSS += 'background-color:'+button_background_color+' !important;';
                                }
                                if (button_text_color.length) {
                                    CSS += 'color:'+button_text_color+' !important;';
                                }
                            CSS += '}';

                            CSS += '#select-filter-'+ID+' button:hover {';
                                if (button_background_color_hover.length) {
                                    CSS += 'background-color:'+button_background_color_hover+' !important;';
                                }
                                if (button_text_color_hover.length) {
                                    CSS += 'color:'+button_text_color_hover+' !important;';
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
                        iframe = document.getElementById('vc_inline-frame');
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"]');
                        element = element.parent().find('.et-woo-products')
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);