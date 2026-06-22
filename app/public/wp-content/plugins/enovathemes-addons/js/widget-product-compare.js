(function($){

    "use strict";

    /*
        1. Add product to compare
        2. Display compare items in the table
        3. Remove product from the compare

    */

    function unique(array){
        return array.filter(function (value, index, self) {
            return self.indexOf(value) === index;
        });
    }

    function isInArray(value, array) {return array.indexOf(value) > -1;}

    function compareCountUpdate(mult){
        var compare_count = parseInt($('.compare-contents').html());
        compare_count += mult;
        if (compare_count < 0) {compare_count = 0;}
        $('.compare-contents').html(compare_count);
        if (compare_count > 0) {
            $('.compare-contents').addClass('count');
        } else {
            $('.compare-contents').removeClass('count');
        }
    }

    function onCompareComplete(target, title){
        setTimeout(function(){
            target
            .removeClass('loading')
            .addClass('active')
            .attr('title',title);

            compareCountUpdate(1);

        },800);
    }

    function compareTableWidth(table,index){
        var lists = table.find('li').not('.clone').length;

        if (lists > index) {

            var W = (lists/index)*100.1;

            table.addClass('overflow-y');
            table.find('ul').css('width',W+'%');
        } else {
            table.find('ul').css('width','100%');
            table.removeClass('overflow-y');
        }
    }

    function changeIndex(single,sidebar = false){

        var index = single ? 4 : 6;

        var ww    = $(window).width();

        if (single) {
            if (window.matchMedia("(max-width: 767px)").matches) {index = 2;} else
            if (window.matchMedia("(min-width: 768px) and (max-width: 1023px)").matches) { index = 4;} else
            if (window.matchMedia("(min-width: 1024px) and (max-width: 1279px)").matches) { index = (sidebar) ? 3 : 5;} else {
                index = (sidebar) ? 4 : 5;
            }
        } else {
            if (window.matchMedia("(max-width: 767px)").matches) {index = 2;} else
            if (window.matchMedia("(min-width: 768px) and (max-width: 1023px)").matches) { index = 4;} else
            if (window.matchMedia("(min-width: 1024px) and (max-width: 1279px)").matches) { index = 5;} else {
                index = 6;
            }
        }

        return index;
    }

    function compareToggle() {
        $('.compare-toggle').each(function(){

            var $this = $(this);

            var currentProduct = $this.data('product');

            currentProduct = currentProduct.toString();

            if (isInArray(currentProduct,compare)) {
                $this.addClass('active').attr('title',inCompare);
                $this.prev('.compare-title').html(addedCompare).attr('title',inCompare);
            }

        });
    }

    var shopName      = compare_opt.shopName+'-compare',
        inCompare     = compare_opt.inCompare,
        addedCompare  = compare_opt.addedCompare,
        compare       = new Array,
        ls            = localStorage.getItem(shopName),
        sidebar       = $('.compare-table-single').data('sidebar');

        sidebar = (sidebar == "none") ? false : true;

        var index         = changeIndex(false),
            indexSingle   = changeIndex(true,sidebar);

    $(window).resize(function(){
        setTimeout(function(){
            index = changeIndex(false);
            indexSingle = changeIndex(true,sidebar);
            compareTableWidth($('.compare-table'),index);
            compareTableWidth($('.compare-table-single'),indexSingle);

            var frozen = $('.compare-table .frozen');
            if (frozen.length) {
                frozen.css({
                    'width':(100/index)+'%',
                });
            }

            var frozenSingle = $('.compare-table-single .frozen');
            if (frozenSingle.length) {
                frozenSingle.css({
                    'width':(100/indexSingle)+'%',
                });
            }

        },50);
    });

    if (typeof(ls) != 'undefined' && ls != null) {
        if (ls.length) {
            ls = ls.split(',');
            ls = unique(ls);
            compare = ls;
        }
    }

    compareToggle();

    $(document).on('click', '.compare-toggle', function(e){

        var $this = $(this);

        var currentProduct = $this.data('product');

        currentProduct = currentProduct.toString();

        if (!$this.hasClass('active') && !$this.hasClass('loading')) {
            e.preventDefault();
            $this.addClass('loading');

            compare.push(currentProduct);
            compare = unique(compare);

            localStorage.setItem(shopName, compare.toString());
            onCompareComplete($this, inCompare);

        }

    });

    $('.comp .compare-title,.entry-summary .compare-title').on('click',function(){
        var $this = $(this);
        if ($this.html() != addedCompare) {
                $this.addClass('loading');
            $this.next('.compare-toggle').trigger('click');
            setTimeout(function(){
                $this.removeClass('loading');
                $this.html(addedCompare);
            },600);
        } else {
            window.location.replace($this.next('.compare-toggle').attr('href'));
        }
    });

    setTimeout(function(){

        if (compare.length) {

            $('.compare-contents').html(compare.length);

            if (compare.length) {
                $('.compare-contents').addClass('count');
            }

            if ($('.compare-table').length) {

                $.ajax({
                    url:compare_opt.ajaxUrl,
                    type: 'post',
                    data: { action: 'compare_fetch', compare:compare.join(','), aj:'true'},
                    success: function(data) {

                        $('.compare-table').each(function(){

                            var compareTable = $(this);

                            compareTable.append(data);

                            compareTableWidth(compareTable,index);

                            compareTable.parent().removeClass('loading');

                        });
                    },
                    fail:function(){
                        $('.compare-table').parent().removeClass('loading');
                    }
                });

            }

        } else {
            if ($('.compare-table').length) {
                $('.compare-table').append('<li class="no-results">'+compare_opt.noCompare+'</li>').parent().removeClass('loading');
            }
        }

        $('.compare-table-single').each(function(){

            var compareTable = $(this);

            compareTableWidth(compareTable,indexSingle);

        });

    },1000);

    $('.cbt').on('scroll',function(e){

        var $this      = $(this),
            horizontal = e.currentTarget.scrollLeft,
            ix         = ($this.hasClass('compare-table-single')) ? indexSingle : index;

        if (horizontal > 0) {

            if (!$this.find('.clone').length) {
                var clone = $this.find('.labels').clone().addClass('clone');
                $this.find('.compare-item-list').prepend(clone);
                $this.find('.labels').not('.clone').addClass('frozen').css({
                    'width':(100/ix)+'%',
                });
            }
            $this.find('.labels').not('.clone').addClass('frozen').css({
                'transform':'translateX('+horizontal+'px)'
            });

        } else {
            $this.find('.clone').remove();
            $this.find('.labels').removeClass('frozen').removeAttr('style');
        }
        
    });

    $(document).on('click', '.compare-remove', function(e){

        e.preventDefault();

        if (confirm(compare_opt.confirm)) {

            var $this = $(this);

            $this.closest('.compare-table').parent().addClass('loading');

            compare = [];

            $this.closest('.compare-table').find('li').not('.labels').each(function(){
                if ($(this).data('product') != $this.closest('li').data('product')) {
                    compare.push($(this).attr('data-product'));
                }
            });

            var compareLength = compare.length + 1;

            localStorage.setItem(shopName, compare.toString());
            setTimeout(function(){
                $this.closest('li').remove();
                if (compare.length == 0) {
                    $('.loading > .compare-table').html('').append('<li class="no-results">'+compare_opt.noCompare+'</li>');
                }
                $('.compare-item-list').css('grid-template-columns','repeat('+compareLength+', '+compareLength+'fr)');

                compareTableWidth($('.loading > .compare-table'),index);

                if (compareLength <= 6) {
                    $('.compare-table .clone').remove();
                    $('.compare-table .frozen').removeAttr('style').removeClass('frozen');
                }

                $('.compare-table').parent().removeClass('loading');
                compareCountUpdate(-1);
            },500);

        }

    });

    let actions = [
        'quick_view',
        'woo_products_ajax',
        'megamenu_load',
        'footer_load',
        'filter_attributes'
    ];

    $( document ).ajaxComplete(function( event, xhr, settings ) {

        if (settings['url'].includes('shop')) {

            compareToggle();

        } else if (typeof(settings['data']) != 'undefined' && settings['data'] != null) {

            var data = decodeURIComponent(settings['data']);

            data = data.split("&");

            var dataObj = [{}];

            for (var i = 0; i < data.length; i++) {
                var property = data[i].split("=");
                var key      = (property[0]);
                var value    = (property[1]);
                if (typeof(value) != 'undefined') {
                    dataObj[key] = value;
                }
            }

            if(actions.includes(dataObj['action'])){
                compareToggle();
            }

        }
    });

})(jQuery);