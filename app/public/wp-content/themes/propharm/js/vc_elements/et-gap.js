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

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_gap"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_gap"]'),
                    element_css  = edit_element.find('textarea[name="element_css"]'),
                    element_id   = edit_element.find('input[name="element_id"]'),
                    table        = edit_element.find(".responsive-visibility"),
                    media_query  = table.find(".media-query"),
                    rv           = edit_element.find("input[name=\"rv\"]"),
                    visibility_array = [];

                // Set defaults

                    var rv_val = rv.val();

                    if(typeof(rv_val) != "undefined" && rv_val.length){
                        var rv_array = rv_val.split(",");

                        media_query.each(function(index){
                            var $this = $(this);
                            var $thisQuery = String($this.data("query"));
                            if(isInArray($thisQuery, rv_array)){
                                $this.find("input[name=\""+$thisQuery +"\"]").attr("checked",true);
                            }
                        });
                    }

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_gap"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_gap"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_gap"]');

                        var height  = edit_element.find('input[name="height"]').val();
                            rv      = edit_element.find("input[name=\"rv\"]");

                        if (height.length) {
                            CSS += '.et-gap-'+ID+' {height:'+height+'px;}';
                        }

                        if(rv.length){

                            for(var i=0;i<media_query.length;i++){

                                var query = $(media_query[i]).data("query");
                                var checked = $(media_query[i]).find("input[type='checkbox']:checked").val();

                                if(typeof(checked) != "undefined" && checked.length){
                                    visibility_array.push(query);
                                }
                            }

                            var visibility_string = visibility_array.join();
                            rv.val(visibility_string);
                            visibility_array = [];
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

    });

})(jQuery);