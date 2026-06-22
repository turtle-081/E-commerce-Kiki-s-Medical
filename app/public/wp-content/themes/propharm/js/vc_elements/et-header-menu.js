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

    function iframeSCRIPT(element,doc){

        $(element).each(function(){

            var $this  = $(this);
            var width  = $this.parents('.container').first().width();
            var gap    = (width - 1200);
            var offset = $this.offset();
            var correction = Math.round(($(doc).outerWidth()/2) - offset.left,0);

            /* Megamenu
            ---------------*/

                $this.find('.megamenu-tab').each(function(){

                    var $this             = $(this),
                        tabs              = $this.find('.tab-item'),
                        tabsQ             = tabs.length,
                        tabsDefaultWidth  = 0,
                        tabsDefaultHeight = 0,
                        tabsContent       = $this.find('.tab-content'),
                        action            = ($this.hasClass('action-hover')) ? 'hover' : 'click';

                    tabs.wrapAll('<div class="tabset et-clearfix"></div>');
                    tabsContent.wrapAll('<div class="tabs-container et-clearfix"></div>');

                    var tabSet = $this.find('.tabset');

                    if(!tabs.hasClass('active')){
                        tabs.first().addClass('active');
                    }

                    tabs.each(function(){

                        var $thiz = $(this);

                        if ($thiz.hasClass('active')) {
                            $thiz.siblings()
                            .removeClass("active");
                            tabsContent.hide(0).removeClass('active');
                            tabsContent.eq($thiz.index()).show(0).addClass('active');
                        }

                    });

                    if(tabsQ >= 2){

                        if (action == 'click') {
                            tabs.on('click', function(event){
                                event.stopImmediatePropagation();

                                var $self = $(this);

                                if(!$self.hasClass("active")){

                                    $self.addClass("active");

                                    $self.siblings()
                                    .removeClass("active");

                                    tabsContent.hide(0).removeClass('active');
                                    tabsContent.eq($self.index()).show(0).addClass('active');

                                    if ($this.parents('.submenu-appear-none').length) {
                                        var currentHeight = tabsContent.eq($self.index()).height();
                                        $this.parents('.megamenu').css('height',currentHeight);
                                    }
                                }
                            });
                        } else {
                            tabs.on('mouseover', function(event){

                                event.stopImmediatePropagation();

                                var $self = $(this);

                                if(!$self.hasClass("active")){

                                    $self.addClass("active");

                                    $self.siblings()
                                    .removeClass("active");

                                    tabsContent.hide(0).removeClass('active');
                                    tabsContent.eq($self.index()).show(0).addClass('active');

                                    if ($this.parents('.submenu-appear-none').length) {
                                        var currentHeight = tabsContent.eq($self.index()).height();
                                        $this.parents('.megamenu').css('height',currentHeight);
                                    }
                                }
                                
                            });
                        }
                        
                    }

                });

                $this.find('.megamenu[data-width="100"]').each(function(){
                    if (this) {
                        var megamenu = $(this);

                        if (megamenu.data('stretch') == "stretch") {
                            gap = 0;
                        }

                        CSS = '#'+megamenu.attr('id')+'{';
                            CSS += 'width:'+width+'px !important;';
                            CSS += 'max-width:'+width+'px !important;';
                            CSS += 'margin-left:-'+Math.round((width/2)+gap)+'px !important;';
                            CSS += 'right:auto !important;';
                            CSS += 'left:'+correction+'px !important;';
                        CSS += '}';
                        $(doc).find("#dynamic-styles-inline-css").append(CSS);
                    }
                });

                $this.find('.megamenu').each(function(){
                    var $this = $(this);

                    if ($this.data('width') == '1200') {

                        var closestLink = $this.parent().children('a');
                        if (closestLink.length) {
                            var parentContainer = $this.parents('.container').eq(0);
                            var offset = closestLink.offset().left - parseInt(closestLink.parent().css('padding-left')) - (parentContainer.offset().left + ((parentContainer.outerWidth() - 1200)/2));
                            $this.attr('style','margin-left:-'+offset+'px !important;');
                        }

                    }

                });

            /* Submenu
            ---------------*/

                var hover               = $this.find('.header-menu .menu-item-has-children'),
                    subMenuEffect       = ($this.hasClass('submenu-appear-none')) ? 'default' : 'fade',
                    menuEffect          = (!$this.hasClass('menu-hover-none')) ? true : false;

                // Add active to first item
                $this.find('.header-menu').children('li').first().addClass('active');

                $this.children('.depth-0').hover(
                    function(){
                        var li = $(this);
                        setTimeout(function(){li.addClass('hover');},100);
                    },
                    function(){
                        $(this).removeClass('hover');
                    }
                );

                hover.push($this.find('.header-menu').children('.mm-true'));

                if (menuEffect) {

                    var active              = '',
                        activeOffset        = 0,
                        currentMenuItem     = $this.find('.header-menu').children('li.active'),
                        color               = $this.find('.header-menu').data('color'),
                        color_hover         = $this.find('.header-menu').data('color-hover');

                    if (currentMenuItem.length) {
                        active       = currentMenuItem;
                        activeOffset = active.position().left;
                    }

                    if (active.length) {
                        active = active.children('a').find('.effect');
                    } else {
                        active = $this.find('.header-menu').children('li:first-child').children('a').find('.effect')
                    }

                    $.each($this.find('.header-menu').children('.depth-0'),function(){

                        var li      = $(this),
                            effect  = li.children('a').find('.effect'),
                            effectX = li.position().left - activeOffset,
                            effectW = effect.width();

                        li.on('mouseover touchstart',function(){

                            gsap.to(active,1, {
                                x:effectX,
                                width:effectW,
                                ease:'elastic.out(1, 0.75)'
                            });

                            li.addClass('in').siblings().removeClass('in');

                            if (li.hasClass('active')) {
                                li.removeClass('using');
                            } else {
                                li.parent().children('li.active').addClass('using');
                            }

                        });

                    });


                    $this.find('.header-menu').on('mouseleave',function(){

                        var width = $this.find('.header-menu').children('li.active').width();

                        if ($this.hasClass('menu-hover-underline')) {
                            width = $this.find('.header-menu').children('li.active').find('.txt').width();
                        }

                        gsap.to(active,1, {
                            x:$this.find('.header-menu').children('li.active').position().left - activeOffset,
                            width:width,
                            ease:'elastic.out(1, 0.75)'
                        });

                        $this.find('.in').removeClass('in');
                        $this.find('.using').removeClass('using');
                    });

                }
        });
    }

    var font_weight_array = [];

    for (var i = 1; i <= 9; i++) {
        font_weight_array.push(i+'00italic');
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
                return dataObj[key] === "et_header_menu";
            });

        /* Edit element
        /*-------------*/

            if(dataObj['action'] == "vc_edit_form" && dataObj['tag'] == "et_header_menu"){

                var edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_menu"]');

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

                $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_menu"] .vc_ui-button-action[data-vc-ui-element="button-save"]').on('click',function(){

                    if ($('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_menu"]').length) {

                        var CSS = '';
                        var ID  = uniqueID();

                        edit_element = $('#vc_ui-panel-edit-element[data-vc-shortcode="et_header_menu"]');

                        /* Top level
                        ---------------*/

                            var align                 = edit_element.find('select[name="align"] option:selected').val(),
                                font_weight           = edit_element.find('select[name="font_weight"] option:selected').val(),
                                font_family           = edit_element.find('select[name="font_family"] option:selected').val(),
                                menu_separator        = edit_element.find('select[name="menu_separator"] option:selected').val(),
                                menu_separator_color  = edit_element.find('input[name="menu_separator_color"]').val(),
                                menu_separator_height = edit_element.find('input[name="menu_separator_height"]').val(),
                                menu_space            = edit_element.find('input[name="menu_space"]').val(),
                                menu_color            = edit_element.find('input[name="menu_color"]').val(),
                                menu_color_hover      = edit_element.find('input[name="menu_color_hover"]').val(),
                                menu_hover            = edit_element.find('select[name="menu_hover"] option:selected').val(),
                                menu_effect_color     = edit_element.find('input[name="menu_effect_color"]').val(),
                                font_size             = edit_element.find('input[name="font_size"]').val(),
                                letter_spacing        = edit_element.find('input[name="letter_spacing"]').val(),
                                text_transform        = edit_element.find('select[name="text_transform"] option:selected').val();

                            if (menu_space.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 {padding-left:'+menu_space+'px;}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .sub-menu {left:'+(menu_space/2)+'px;}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0.submenu-left dir-child* .sub-menu {right:'+(menu_space/2)+'px;}';
                            }

                            if (menu_separator.length && menu_separator_color.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0:before, #header-menu-'+ID+' dir-child* .menu-item.depth-0:after {background:'+menu_separator_color+';}';
                                if (menu_space.length) {
                                    CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 {padding-left:'+(menu_space/2)+'px !important;padding-right:'+(menu_space/2)+'px !important;}';
                                }
                                if (menu_separator_height.length) {
                                    CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0:before, #header-menu-'+ID+' dir-child* .menu-item.depth-0:after {height:'+menu_separator_height+'px;}';
                                }
                            }

                            CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .mi-link {';

                                if (menu_color.length) {CSS += 'color:'+menu_color+';';}
                                if (font_size.length) {CSS += 'font-size:'+font_size+'px;';}

                                if (font_weight.length && font_weight != "italic" && font_weight != "regular") {

                                    if (isInArray(font_weight,font_weight_array)) {
                                        font_weight = font_weight.substring(0, 3);
                                        CSS += 'font-style:italic;';
                                    }

                                    CSS += 'font-weight:'+font_weight+';';
                                }

                                if (letter_spacing.length) {CSS += 'letter-spacing:'+letter_spacing+'px;';}
                                if (text_transform.length) {CSS += 'text-transform:'+text_transform+';';}
                                if (font_family.length && font_family != "Theme default") {CSS += 'font-family:\''+font_family+'\';';}

                            CSS += '}';

                            if (menu_color.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .mi-link dir-child* .arrow svg {';
                                    CSS += 'fill:'+menu_color+';';
                                CSS += '}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .mi-link dir-child* .menu-icon, #header-menu-'+ID+' dir-child* .menu-item.depth-0.active.using dir-child* .mi-link dir-child* .menu-icon {';
                                    CSS += 'background:'+menu_color+';';
                                CSS += '}';
                            }

                            if (menu_color_hover.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0:hover dir-child* .mi-link, #header-menu-'+ID+' dir-child* .menu-item.depth-0.active dir-child* .mi-link, #header-menu-'+ID+' dir-child* .menu-item.depth-0.in dir-child* .mi-link {color:'+menu_color_hover+';}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0:hover dir-child* .mi-link dir-child* .arrow svg, #header-menu-'+ID+' dir-child* .menu-item.depth-0.active dir-child* .mi-link dir-child* .arrow svg, #header-menu-'+ID+' dir-child* .menu-item.depth-0.in dir-child* .mi-link dir-child* .arrow svg {';
                                    CSS += 'fill:'+menu_color_hover+';';
                                CSS += '}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0:hover dir-child* .mi-link dir-child* .menu-icon, #header-menu-'+ID+' dir-child* .menu-item.depth-0.active dir-child* .mi-link dir-child* .menu-icon {';
                                    CSS += 'background:'+menu_color_hover+';';
                                CSS += '}';
                            }

                            if (menu_color.length) {
                               CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0.active.using dir-child* .mi-link {color:'+menu_color+'}';
                            }

                            if (menu_hover.length && menu_hover !="none" && menu_effect_color.length ) {
                                if (menu_hover == "outline") {
                                    CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .mi-link .effect {box-shadow:inset 0 0 0 1px '+menu_effect_color+';}';
                                } else {
                                    CSS += '#header-menu-'+ID+' dir-child* .menu-item.depth-0 dir-child* .mi-link .effect {background-color:'+menu_effect_color+';}';
                                }
                            }

                        /* Submenu
                        ---------------*/

                            var submenuoffset            = edit_element.find('input[name="submenuoffset"]').val(),
                                subfont_weight           = edit_element.find('select[name="subfont_weight"] option:selected').val(),
                                subfont_family           = edit_element.find('select[name="subfont_family"] option:selected').val(),
                                submenu_back_color       = edit_element.find('input[name="submenu_back_color"]').val(),
                                submenu_back_color_hover = edit_element.find('input[name="submenu_back_color_hover"]').val(),
                                submenu_color            = edit_element.find('input[name="submenu_color"]').val(),
                                submenu_color_hover      = edit_element.find('input[name="submenu_color_hover"]').val(),
                                subfont_size             = edit_element.find('input[name="subfont_size"]').val(),
                                subletter_spacing        = edit_element.find('input[name="subletter_spacing"]').val(),
                                submenu_separator        = edit_element.find('select[name="submenu_separator"] option:selected').val(),
                                subtext_transform        = edit_element.find('select[name="subtext_transform"] option:selected').val();

                            if (submenuoffset.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item dir-child* .sub-menu {top:'+submenuoffset+';}';
                            }

                            if (submenu_back_color.length) {
                                CSS += '#header-menu-'+ID+' .sub-menu {background-color:'+submenu_back_color+';}';
                            }

                            if (submenu_separator.length && submenu_color.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item .mi-link:before {background-color:'+submenu_color+';}';
                            }

                            CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item .mi-link {';

                                if (submenu_color.length) {CSS += 'color:'+submenu_color+';';}
                                if (subfont_size.length) {CSS += 'font-size:'+subfont_size+'px;';}

                                if (subfont_weight.length && subfont_weight != "italic" && subfont_weight != "regular") {

                                    if (isInArray(subfont_weight,font_weight_array)) {
                                        subfont_weight = subfont_weight.substring(0, 3);
                                        CSS += 'font-style:italic;';
                                    }

                                    CSS += 'font-weight:'+subfont_weight+';';
                                }

                                if (subletter_spacing.length) {CSS += 'letter-spacing:'+subletter_spacing+'px;';}
                                if (subtext_transform.length) {CSS += 'text-transform:'+subtext_transform+';';}
                                if (subfont_family.length && subfont_family != "Theme default") {CSS += 'font-family:\''+subfont_family+'\';';}

                            CSS += '}';

                            CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item:hover dir-child* .mi-link {';
                                if (submenu_color_hover.length) {
                                    CSS += 'color:'+submenu_color_hover+';';
                                }
                                if (submenu_back_color_hover.length ) {
                                    CSS += 'background-color:'+submenu_back_color_hover+';';
                                }
                            CSS += '}';

                            if (submenu_color.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item dir-child* .mi-link dir-child* .arrow svg {';
                                    CSS += 'fill:'+submenu_color+';';
                                CSS += '}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item dir-child* .mi-link dir-child* .menu-icon {';
                                    CSS += 'background:'+submenu_color+';';
                                CSS += '}';
                            }

                            if (submenu_color_hover.length) {
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item:hover dir-child* .mi-link dir-child* .arrow svg {';
                                    CSS += 'fill:'+submenu_color_hover+';';
                                CSS += '}';
                                CSS += '#header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item:hover dir-child* .mi-link dir-child* .menu-icon, #header-menu-'+ID+' dir-child* .menu-item:not(.mm-true) .sub-menu .menu-item.active dir-child* .mi-link dir-child* .menu-icon {';
                                    CSS += 'background:'+submenu_color_hover+';';
                                CSS += '}';
                            }

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

                            CSS += '#header-menu-container-'+ID+' {';
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
                        var element = doc.find('.vc_element[data-model-id="'+dataObj['shortcodes[0][id]']+'"] .header-menu-container');
                        if (typeof(element) != 'undefined' && element != null) {
                            iframeSCRIPT(element,doc);
                            hbeAlign(element,doc);
                        }
                    });
                }
                return;
            }

    });

})(jQuery);
