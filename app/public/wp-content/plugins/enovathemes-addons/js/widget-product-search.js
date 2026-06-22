jQuery.fn.highlight=function(c){function e(b,c){var d=0;if(3==b.nodeType){var a=b.data.toUpperCase().indexOf(c),a=a-(b.data.substr(0,a).toUpperCase().length-b.data.substr(0,a).length);if(0<=a){d=document.createElement("span");d.className="highlight";a=b.splitText(a);a.splitText(c.length);var f=a.cloneNode(!0);d.appendChild(f);a.parentNode.replaceChild(d,a);d=1}}else if(1==b.nodeType&&b.childNodes&&!/(script|style)/i.test(b.tagName))for(a=0;a<b.childNodes.length;++a)a+=e(b.childNodes[a],c);return d} return this.length&&c&&c.length?this.each(function(){e(this,c.toUpperCase())}):this};jQuery.fn.removeHighlight=function(){return this.find("span.highlight").each(function(){this.parentNode.firstChild.nodeName;with(this.parentNode)replaceChild(this.firstChild,this),normalize()}).end()};

function debounce(callback, wait) {
  let timeout;
  return (...args) => {
      clearTimeout(timeout);
      timeout = setTimeout(function () { callback.apply(this, args); }, wait);
  };
}

(function($){

    "use strict";

    function productSearch(form,query,currentQuery,element){

            var search      = form.find('.search'),
                inTag       = form.attr('data-tag'),
                inAttr      = form.attr('data-attr'),
                category    = form.find('select.category').val(),
                sku         = form.attr('data-sku'),
                description = form.attr('data-description'),
                loading     = false;

            form.find('.search-results').html('').removeClass('active');

            query = query.trim();

            if (query.length >= 3) {

                form.find('.search-results').removeClass('empty');

                search.parent().addClass('loading');
                if (query != currentQuery) {
                    $.ajax({
                            url:controller_opt.ajaxUrl,
                            type: 'post',
                            data: {
                                action: 'search_product',
                                keyword: query,
                                category: category,
                                sku: sku,
                                description: description,
                                in_attr:inAttr,
                                in_tag:inTag,
                            },
                            success: function(data) {
                                currentQuery = query;
                                search.parent().removeClass('loading');

                                if (!form.find('.search-results').hasClass('empty')) {

                                    if (data.length) {

                                        let output = '';

                                        data = JSON.parse(data);

                                        // if (typeof(data['args']) != 'undefined') {
                                        //     console.log(data['args']);
                                        // }

                                        if (typeof(data['output']) != 'undefined') {
                                            output += '<ul class="product-list">'+data['output']+'</ul>';
                                        } else {
                                            output += '<ul><li class="no-results">'+controller_opt.noProduct+'</li></ul>';

                                        }

                                        if (typeof(data['terms']) != 'undefined') {
                                            output += data['terms'];
                                        }

                                        form.find('.search-results').html(output).addClass('active');

                                        if (form.find('.search-results .no-results').length) {
                                            form.find('.search-results').addClass('no-results');
                                        } else {
                                            form.find('.search-results').removeClass('no-results');
                                        }

                                        var scroll = element.querySelector('ul');
                                        if (typeof scroll != "undefined" && scroll != null && form.find('.search-results ul li').length > 2) {
                                            SimpleScrollbar.initEl(scroll);
                                        }

                                        query = query.split(' ');

                                        for (var i = 0; i < query.length; i++) {
                                            form.find('.search-results').highlight(query[i]);
                                        }


                                    }

                                }

                            }
                        });
                }
            } else {

                search.parent().removeClass('loading');
                form.find('.search-results').empty().removeClass('active').addClass('empty');

            }
        }

        function createSearchURL(shopURL,data,reload = true){

            if (shopURL.indexOf("?") == -1){
                shopURL += '?';
            }

            $.each(data, function(key, value) {
                if (value.length) {
                    if (key == 'year') {key = 'yr';}
                    if (!shopURL.includes(key+'='+value)) {
                        shopURL += '&'+key+'='+value;
                    }
                }
            });

            shopURL = shopURL.replace('?&', '?');

            shopURL = encodeURI(shopURL);

            if (reload) {
                window.location.assign(shopURL);
            } else {
                history.pushState({}, null, shopURL);
                $('.reload-all-attribute').trigger('click');
            }

        }

        $('form[name="product-search"]').each(function(){

            var element       = this,
                form          = $(this),
                search        = form.find('.search'),
                category      = form.find('.category'),
                inCat         = form.attr('data-in-category'),
                currentQuery  = '',
                button        = form.find('input[type="submit"]');

            var mouse_is_inside = false;
            var typingTimer;

            category
            .on('change',function(){
                currentQuery  = '';
                var query = search.val();
                productSearch(form,query,currentQuery,element);

                mouse_is_inside = true;

                let text = $(this).parent().find('.select2-selection__rendered').text();
                $(this).parent().find('.select2-selection__rendered').text(text.trim());

            });

            search.on('keyup',function(){
                search.parent().addClass('loading');
            });

            window.addEventListener('keyup', debounce( () => {
                var query = search.val();
                productSearch(form,query,currentQuery,element);
                mouse_is_inside = true;
            }, 200));


            button.on('click',function(e){

                e.preventDefault();

                var ajx        = ($('.widget_product_filter_widget').length && getParams() && typeof(getParams()['ajax']) != "undefined") ? true : false,
                    url        = ajx ? window.location.href : button.data('shop'),
                    reload     = ajx ? false : true,
                    activeCat  = category.find('option:selected').val(),
                    searchVal  = search.val(),
                    data       = {}; 

                if (typeof(activeCat) != 'undefined' && activeCat.length) {
                    if (ajx) {
                        data['ca'] = activeCat;
                    } else {
                        data['product_cat'] = activeCat;
                    }
                }

                if (typeof(searchVal) != 'undefined' && searchVal.length) {

                    data['s'] = searchVal;

                    createSearchURL(url,data,reload);

                }

            });

            search.on('click',function(){
                if(mouse_is_inside){
                    $('.search-results').removeClass('active');
                }
            });

        });

})(jQuery);