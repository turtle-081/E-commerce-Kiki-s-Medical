	/* Helper functions
	----*/

		function getParams() {

	        var url = window.location.href;
	            url = url.split('?');

	        var query = url[1];
	        var params = new Object;

	        if (typeof(query) != 'undefined' && query != null) {
	            var vars = query.split('&');
	            for (var i = 0; i < vars.length; i++) {
	                var pair = vars[i].split('=');
	                params[pair[0]] = decodeURIComponent(pair[1]);
	            }
	            return (jQuery.isEmptyObject(params)) ? false : params;
	        }

	        return false;
	    }

	    function unique(array){
			return array.filter(function (value, index, self) {
		        return self.indexOf(value) === index;
		    });
		}


		function isInArray(value, array) {return array.indexOf(value) > -1;}

		jQuery.fn.inView = function(win,observe) {

	        var observe  = (observe) ? observe : 0.6,
	            win      = (win) ? win : window,
	        	height 	 = jQuery(this).outerHeight(),
	            scrolled = jQuery(win).scrollTop(),
	            viewed   = scrolled + jQuery(win).height(),
	            top 	 = jQuery(this).offset().top,
	            bottom   = top + height;
	        return (top + height * observe) <= viewed && (bottom - height * observe) >= scrolled;
	        
	    };

	    function toggleBack(element,toggle,scrollElement){

	        var $this  = jQuery(element),
	            isOpen = false;

	        toggle.unbind('click').on('click',function(){

	            toggle.toggleClass('active');

	            if (isOpen==false) {

	            	if (typeof scrollElement != "undefined" && scrollElement != null) {
	                    // Custom scroll bar
	                    setTimeout(function(){
	                        var scroll = element.querySelector(scrollElement);
	                        if (typeof scroll != "undefined" && scroll != null) {
	                            SimpleScrollbar.initEl(scroll);
	                        }
	                    },200);
	                }

	                isOpen=true;

	            } else {
	                isOpen=false;
	            }

	            if (toggle.hasClass('active')) {
					jQuery('.header .hbe-toggle.active').not(toggle).not('.mobile-toggle').not('.modal-toggle').each(function(){
						jQuery(this).parent().find('.hbe-toggle').trigger('click');
					});
					jQuery('.footer .hbe-toggle.active').not(toggle).not('.mobile-toggle').not('.modal-toggle').each(function(){
						jQuery(this).parent().find('.hbe-toggle').trigger('click');
					});
				}

	        });
	    }

	    function isolate($link){
			if ($link.next('ul').length != 0) {
	            if ($link.parent().hasClass('isolate')) {
					$link.parent().removeClass('isolate').removeClass('disable');
					if ($link.closest('.isolate').length) {
	                	$link.closest('.isolate').removeClass('disable').find('.hide').removeClass('hide');
	                } else {
	                	$link.parents('.mobile-menu').find('.hide').removeClass('hide');
	                }
				} else {
	                $link.parent().addClass('isolate');
	                $link.parents('.mobile-menu').find('.isolate').not($link.parent()).addClass('disable');
	                $link.parent().siblings().addClass('hide');
	            }
	        };
		}

	/* Lazy loading
	----*/

		function lazyLoad(container){

			if (container != null) {

				let lazyImages = [].slice.call(container.querySelectorAll("img.lazy"));
				let lazyBack   = [].slice.call(container.querySelectorAll(".lazy-back"));
				let lazyVideos = [].slice.call(container.querySelectorAll("video.lazy"));
				let lazyBanners = [].slice.call(container.querySelectorAll(".banner-back.image"));

				if ("IntersectionObserver" in window) {

					// Images

						let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
							entries.forEach(function(entry) {
								if (entry.isIntersecting) {
									let lazyImage = entry.target;
									lazyImage.src = lazyImage.dataset.src;

									if (lazyImage.classList.contains('single') && window.innerWidth < 768) {
										let respImg = lazyImage.getAttribute('data-img-resp');
										respImg = respImg.split('|');
										lazyImage.src = respImg[0];
										lazyImage.setAttribute('width',respImg[1]);
										lazyImage.setAttribute('height',respImg[2]);
									}

									lazyImage.onload = function() {
									    lazyImage.classList.remove("lazy");
									    lazyImage.parentElement.classList.add("loaded");
									    lazyImageObserver.unobserve(lazyImage);
									};
									
								}
							});
						});

						lazyImages.forEach(function(lazyImage) {
							lazyImageObserver.observe(lazyImage);
						});

					// Videos

						let lazyVideoObserver = new IntersectionObserver(function(entries, observer) {
							entries.forEach(function(video) {
								if (video.isIntersecting) {

									for (var source in video.target.children) {
										var videoSource = video.target.children[source];
										if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
											videoSource.src = videoSource.dataset.src;
										}
									}

									video.target.load();
									video.target.classList.remove("lazy");
									lazyVideoObserver.unobserve(video.target);
								}
							});
						});

						lazyVideos.forEach(function(lazyVideo) {
							lazyVideoObserver.observe(lazyVideo);
						});

					// Banners

						let lazyBannerObserver = new IntersectionObserver(function(entries, observer) {
							entries.forEach(function(entry) {
								if (entry.isIntersecting) {
									let lazyBanner = entry.target;
									lazyBanner.style.backgroundImage = 'url('+lazyBanner.dataset.background+')';
									lazyBanner.removeAttribute('data-background');
									lazyBannerObserver.unobserve(lazyBanner);
								}
							});
						});

						lazyBanners.forEach(function(lazyBanner) {
							lazyBannerObserver.observe(lazyBanner);
						});

				} else {

					let active = false;

					const lazyLoad = function() {
						if (active === false) {

						  	active = true;

							setTimeout(function() {

								lazyImages.forEach(function(lazyImage) {

									if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== "none") {

										lazyImage.src = lazyImage.dataset.src;

										lazyImage.onload = function() {
										    lazyImage.classList.remove("lazy");
										    lazyImage.parentElement.classList.add("loaded");
										    lazyImages = lazyImages.filter(function(image) {
												return image !== lazyImage;
											});
										};

										if (lazyImages.length === 0) {
											document.removeEventListener("scroll", lazyLoad);
											window.removeEventListener("resize", lazyLoad);
											window.removeEventListener("orientationchange", lazyLoad);
										}
									}
								});

								lazyVideos.forEach(function(lazyVideo) {

									if ((lazyVideo.getBoundingClientRect().top <= window.innerHeight && lazyVideo.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyVideo).display !== "none") {

										for (var source in lazyVideo.children) {
											var videoSource = lazyVideo.children[source];
											if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
												videoSource.src = videoSource.dataset.src;
											}
										}

										if (lazyVideos.length === 0) {
											document.removeEventListener("scroll", lazyLoad);
											window.removeEventListener("resize", lazyLoad);
											window.removeEventListener("orientationchange", lazyLoad);
										}
									}
								});

								active = false;

							}, 200);
						}
					};

					document.addEventListener("scroll", lazyLoad);
					window.addEventListener("resize", lazyLoad);
					window.addEventListener("orientationchange", lazyLoad);

				}

			}

		}

		document.addEventListener("DOMContentLoaded", lazyLoad(document));
		document.addEventListener("DOMContentLoaded", function(){
			var video = document.querySelector('.ftr-video');
			if (typeof(video) != 'undefined' && video != null) {video.play();}
			var videos = document.querySelectorAll('.video-container');
			if (typeof(videos) != 'undefined' && videos != null) {
				videos.forEach(function(item){
					item.play();
				})
			}
		});

		function changeSinglePostImage(){
			let lazyImage = document.querySelector('.image-container-single.loaded .single');
			if (lazyImage) {
				if (window.innerWidth > 768) {
					let respImg = lazyImage.getAttribute('data-img-desk');
					respImg = respImg.split('|');
					lazyImage.src = respImg[0];
					lazyImage.setAttribute('width',respImg[1]);
					lazyImage.setAttribute('height',respImg[2]);
				} else {
					let respImg = lazyImage.getAttribute('data-img-resp');
					respImg = respImg.split('|');
					lazyImage.src = respImg[0];
					lazyImage.setAttribute('width',respImg[1]);
					lazyImage.setAttribute('height',respImg[2]);
				}
			}
		}
		window.onresize = changeSinglePostImage;

	/* Gsap lightbox
	----*/

		function lightImage(src,overlay){

			if (
				src.includes('.jpg') ||
				src.includes('.jpeg') ||
				src.includes('.png') ||
				src.includes('.bmp') ||
				src.includes('.gif') ||
				src.includes('.svg')
			) {
				
				var img = document.createElement('img');
				img.src = src;

				var loaded = false;

				img.onload = function() {

					if (loaded) {
	                    return;
	                }

					if (overlay.find('img').length == 0) {
						overlay.prepend(img);
					}

					loaded = true;
				}
				
			} else if (src.includes('youtu') || src.includes('vimeo')) {
				var iframe = document.createElement('iframe');

				src = src.replace('watch?v=', 'embed/');
	            src = src.replace('//vimeo.com/', '//player.vimeo.com/video/');
	            src = (src.indexOf("?") == -1) ? src += '?' : src += '&';

				iframe.src = src+'autoplay=1';
				iframe.frameborder = '0';
				iframe.width  = '1280';
				iframe.height = '720';
				iframe.allow  = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
				iframe.allowfullscreen = true;
				overlay.prepend(iframe);
			} else if (src.includes('mp4') || src.includes('webm') || src.includes('ogv')) {
				var video = document.createElement('video');
				video.src = src;
				video.autoplay = true;
				video.controls = true;
				overlay.prepend(video);
			}
		}

		function gsapLightbox(element,gallery){

			var href = element.attr('href');

		  	if (
				href.includes('.jpg') ||
				href.includes('.jpeg') ||
				href.includes('.png') ||
				href.includes('.bmp') ||
				href.includes('.gif') ||
				href.includes('.svg') ||
				href.includes('youtu') ||
				href.includes('mp4') ||
				href.includes('webm') ||
				href.includes('ogv')
			){

				if (!jQuery('.gsap-lightbox-overlay').length) {

					var structure = (gallery == true) ? 
					jQuery('<div class="gsap-lightbox-overlay"><div class="image-wrapper"></div><a href="#" class="gsap-lightbox-controls gsap-lightbox-toggle"></a><a href="#" class="gsap-lightbox-controls gsap-lightbox-nav prev" data-direction="prev"></a><a href="#" class="gsap-lightbox-controls gsap-lightbox-nav next" data-direction="next"></a><svg class="placeholder" viewBox="0 0 20 4"><circle cx="2" cy="2" r="2" /><circle cx="10" cy="2" r="2" /><circle cx="18" cy="2" r="2" /></svg></div>') :
					jQuery('<div class="gsap-lightbox-overlay"><div class="image-wrapper"></div><a href="#" class="gsap-lightbox-controls gsap-lightbox-toggle"></a><svg class="placeholder" viewBox="0 0 20 4"><circle cx="2" cy="2" r="2" /><circle cx="10" cy="2" r="2" /><circle cx="18" cy="2" r="2" /></svg></div>');

					jQuery('body').append(structure);

					var overlay = jQuery('.gsap-lightbox-overlay'),
						wrapper = overlay.find('.image-wrapper'),
						toggle  = overlay.find('.gsap-lightbox-toggle'),
						loading = overlay.find('.gsap-lightbox-toggle');

					var tl = new gsap.timeline({paused: true});

					tl.from(toggle,0.2, {
					  opacity:0,
					  ease:"expo.out"
				  	},'+=0.2');

					tl.from(toggle,1.2, {
					  x:'-12px',
					  ease:"elastic.out(1, 0.5)"
				  	},'-=0.2');



					if (gallery == true) {

						var nav  	    = overlay.find('.gsap-lightbox-nav'),
							next        = overlay.find('.next'),
							prev        = overlay.find('.prev'),
							gallerySet  = [],
							count       = 0,
							galleryName = element.data('gallery');

						tl.from(nav,0.2, {
							opacity:0,
						},'-=1.1');

						tl.from(prev,1.2, {
						  x:'-40px',
						  ease:"elastic.out(1, 0.5)"
					  	},'-=1.1');

					  	tl.from(next,1.2, {
						  x:'40px',
						  ease:"elastic.out(1, 0.5)"
					  	},'-=1.2');

						jQuery('a[data-gallery="'+galleryName+'"]').each(function(){
							gallerySet.push(jQuery(this).attr('href'));
						});

						if (!gallerySet.length) {
							jQuery('a').each(function(){
								gallerySet.push(jQuery(this).attr('href'));
							});
						}

						count = gallerySet.indexOf(element.attr('href'));

						var max = gallerySet.length;

						if (max == 1) {
							jQuery('.gsap-lightbox-overlay .gsap-lightbox-nav').remove();
						}
						
						nav.on('click',function(e){

							overlay.find('img').remove();

							e.preventDefault();

							count += (jQuery(this).data('direction') == "next") ? 1 : -1;
							if (count < 0) {count = max - 1;}
							if (count >= max) {count = 0;}

							lightImage(gallerySet[count],wrapper);
						});

					}

					tl.add('active');

					tl.to(overlay,0.1, {
						opacity:0,
					});

					setTimeout(function(){
						overlay.addClass('active');
						tl.progress(0);
						tl.tweenTo('active');

						lightImage(element.attr('href'),wrapper);

					},50);

					toggle.on('click',function(e){
						e.preventDefault();
						tl.play();
						overlay.removeClass('active');
						setTimeout(function(){
							overlay.remove();
						},500);
					});

				}

			}
		}

	/* Video trigger
	----*/

		function videoTrigger(){
			jQuery('.video-btn').each(function(){

				var $this  = jQuery(this),
					video  = $this.parents('.post-video').find('.video-element'),
					image  = $this.parents('.post-video').find('.image-container'),
					embed  = (video.hasClass('iframevideo')) ? true : false,
					back   = $this.find('.back');

				$this.hover(
					function(){
						gsap.to(back,0.8, {
						  scale:1.15,
						  ease:"elastic.out"
						});
					},
					function(){
						gsap.to(back,0.8, {
						  scale:1,
						  ease:"expo.out"
						});
					}
				);

				$this.unbind('click').on('click',function(e){
					e.preventDefault();

					if (!$this.hasClass('video-modal')) {
						image.toggleClass('playing');
						video.toggleClass('playing');
					}

					if ($this.hasClass('video-modal')) {
						gsapLightbox($this,false);
					} else {
						setTimeout(function(){
							if (embed) {
								var src = video.attr('src');
								src =  (src.indexOf("?") == -1) ? src += '?' : src += '&';
								video.attr('src',src+'autoplay=1');
							} else {
								video.trigger('play');
							}
						},500);
					}

				});

			});
		}

		videoTrigger();

	/* GSAP config
	----*/
		
		gsap.config({ nullTargetWarn:false});
		gsap.registerPlugin(ScrollToPlugin);

	/* General
	----*/

		(function($){

			"use strict";

			/* WPML Language switcher
			----*/

				$('.widget_icl_lang_sel_widget .wpml-ls-current-language > a')
				.append('<span class="toggle"></span>');

				$('.wpml-ls-legacy-dropdown-click a > span.toggle').on('click',function(e){
					$(this).parent().toggleClass('active');
					if ($(this).parent().next('ul').length != 0) {
						$(this).parent().toggleClass('animate');
						$(this).parent().next('ul').stop().slideToggle(300);
					};
					e.preventDefault();
				});

				$('.wpml-ls-legacy-dropdown .wpml-ls-current-language').hover(
					function(){
						$(this).toggleClass('active');
						if ($(this).find('ul').length != 0) {
							$(this).toggleClass('animate');
							$(this).find('ul').stop().slideToggle(300);
						};
					},
					function(){
						$(this).toggleClass('active');
						if ($(this).find('ul').length != 0) {
							$(this).toggleClass('animate');
							$(this).find('ul').stop().slideToggle(300);
						};
					}
				);

			/* Widget navigation
			----*/

				$('.widget_nav_menu').each(function(){

					var $this = $(this);
					var childItems = $this.find('.menu-item-has-children > a')
					.append('<span class="toggle"></span>');

					if ($this.find('.menu-item-has-children > a').attr( "href" ) == "#") {
						$this.find('.menu-item-has-children > a').on('click',function(e){
							$(this).toggleClass('active');
							if ($(this).next('ul').length != 0) {
								$(this).toggleClass('animate');
								$(this).next('ul').stop().slideToggle(300);
							};
							e.preventDefault();
						});
					} else {
						$this.find('.menu-item-has-children > a > span.toggle').on('click',function(e){
							e.stopImmediatePropagation();
							$(this).toggleClass('active');
							if ($(this).parent().next('ul').length != 0) {
								$(this).parent().toggleClass('animate');
								$(this).parent().next('ul').stop().slideToggle(300);
							};
							e.preventDefault();
						});
					}

				});

				var activeParams = getParams();
				var categoryParam = false;

				$.each(activeParams,function(key,value){
					if (key == 'product_cat') {
						categoryParam = value;
					}
				})


				$('.widget_product_categories').each(function(){

					var $this = $(this);

					if ($this.find('.count').length != 0) {
						$this.find('a').each(function(){
							var $self = $(this);
							var countClone = $self.next('.count').clone();
							$self.next('.count').remove();
							$self.append(countClone);
						});
					}

					var childItems = $this.find('.cat-parent > a')
					.append('<span class="toggle"></span>');

					if (categoryParam) {
						$this.find('a[href*="'+categoryParam+'"]').each(function(){
							var href = $(this).attr('href');
							href = href.split('/');
							href.pop();
							href = href.slice(-1);
							if (categoryParam == href) {$(this).parent().addClass('current-cat');}
						});
					}

					$this.find('.current-cat').parents('.cat-parent').addClass('animate').children('a').addClass('active');
					$this.find('.current-cat').parents('.cat-parent').children('a').addClass('animate').children('span.toggle').addClass('active');
					$this.find('.current-cat').parents('ul.children').stop().slideDown(300);

					if ($this.find('.cat-parent > a').attr( "href" ) == "#") {
						$this.find('.cat-parent > a').on('click',function(e){
							$(this).toggleClass('active');
							if ($(this).parent().next('.children').length != 0) {
								$(this).parent().toggleClass('animate');
								$(this).parent().next('.children').stop().slideToggle(300);
							};
							e.preventDefault();
						});
					} else {
						$this.find('.cat-parent > a > span.toggle').on('click',function(e){
							e.stopImmediatePropagation();
							$(this).toggleClass('active');
							if ($(this).parent().next('.children').length != 0) {
								$(this).parent().toggleClass('animate');
								$(this).parent().next('.children').stop().slideToggle(300);
							};
							e.preventDefault();
						});
					}


				});

			/* Widget calendar
			----*/

				$('.widget_calendar').each(function(){

					var $this = $(this);
					var caption = $this.find('caption');

					$this.find('.wp-calendar-nav-prev a').clone().addClass('prev').html('').appendTo(caption);
					$this.find('.wp-calendar-nav-next a').clone().addClass('next').html('').appendTo(caption);
					$this.find('.wp-calendar-nav').remove();

				});

				$('.wp-block-calendar').each(function(){

					var $this = $(this);
					var caption = $this.find('caption');

					$this.find('.wp-calendar-nav a').clone().addClass('prev').html('').appendTo(caption);
					$this.find('.wp-calendar-nav a').clone().addClass('next').html('').appendTo(caption);
					$this.find('.wp-calendar-nav').remove();

				});

			/* Widget product search
			----*/

				$('.widget_product_search').each(function(){
					$('<div class="search-icon"></div>').insertAfter($(this).find('input[type="submit"]'));
				});

			/* Move to top button
			----*/

				var didScroll = false,
					top       = $('#to-top'),
					nav       = $('.bullets-container');
		
				function showOnScroll() {
					window.addEventListener( 'scroll', function( event ) {
					    if( !didScroll ) {
					        didScroll = true;
					        scrollPage(400);
					    }
					}, false );
				}

				function scrollPage(activateOn) {
					var sy = window.pageYOffset;
					if ( sy >= activateOn ) {
						top.addClass('animate');
						nav.addClass('animate');
					} else {
						top.removeClass('animate');
						nav.removeClass('animate');
					}

					didScroll = false;
				}

				showOnScroll();

				top.on('click',function(){
					gsap.to(window, {
						duration: 1, 
						scrollTo: {y:top.attr('href')},
						ease:Power3.easeOut 
					});
					return false;
				});

			/* Form placeholder
			----*/

				$('.widget_login, .widget_reglog').each(function(){
					var $this = $(this);

					$this.find('label').each(function(){
						var labelText = $(this).text();
						$(this).next('input').attr('placeholder',labelText);
						$(this).remove();
					});

					$this.find('input[type="submit"]').on("click",function(event) {

						if (!$this.find('input[type="text"]').val() || !$this.find('input[type="password"]').val() ||
							$this.find('input[type="text"]').val() == $this.find('input[type="text"]').data('placeholder') ||
							$this.find('input[type="password"]').val() == $this.find('input[type="password"]').data('placeholder')) {
							event.preventDefault();
						}

					});
				});

			/* Responsive tables
			----*/

				function responsiveTable(){

					if ($(window).outerWidth() <= 767) {
						$('table').addClass('responsive');
						$('table').parent().addClass('overflow-x');
					} else {
						$('table').removeClass('responsive');
						$('table').parent().removeClass('overflow-x');
					}

				}
				responsiveTable();
				$(window).resize(responsiveTable);

			/* Layered nav
			----*/

				var widgets = [].slice.call(document.querySelectorAll('.woocommerce-widget-layered-nav-list'));

	            if (typeof widgets != "undefined" && widgets != null) {
	                widgets.forEach(function(widget) {
	                	if (widget.querySelectorAll('li').length > 6) {
	                		widget.classList.add("max");
	                		SimpleScrollbar.initEl(widget);
	                	}
					});
	            }

			/* Color swatches
			----*/

				$('.swatch-color').each(function(){
					if ($(this).css('background-color') === 'rgb(255, 255, 255)') {
					   $(this).addClass('white');
					}
				});
				
			/* Products ask
			----*/

				$('.ask-toggle').on('click',function(e){
					e.preventDefault();
					$('.ask-form').addClass('active');
				});

				$('.ask-title').on('click',function(){
					$('.ask-toggle').trigger('click');
				});

				$('.ask-close').on('click',function(){
					$('.ask-form').removeClass('active');
				});

				$('.ask-after').on('click',function(){
					$('.ask-close').trigger('click');
				});

				var pTitle = $('.single-product .entry-summary .entry-title').text();
				var sku = $('.single-product .entry-summary .sku').text();

				if(typeof(sku) != 'undefined' && sku.length){
					pTitle += ' / '+sku;
				}

				if(typeof(pTitle) != 'undefined' && pTitle.length){
					$('.ask-form').find('input[name="product-name"]').val(pTitle);
				}

			/* Buy now
			----*/

				function updateBuyNowLink() {

					var data            = {};
				    var buyNowLink      = jQuery('.buy-now-button').attr('href');
					var productQuantity = jQuery('form.cart input[name="quantity"]').val();

					if(productQuantity >= 1) {
						data['quantity'] = productQuantity;
					}

			    	if (jQuery('input[name="variation_id"]').length) {

				        var variation_id = jQuery('input[name="variation_id"]').val();

				        if (variation_id.length && variation_id != '0') {
				        	data['variation_id'] = jQuery('input[name="variation_id"]').val();
				        }

			        }

			        if (!$.isEmptyObject(data)) {

				    	jQuery.each(data, function(key, value) {
				            if (value.length) {
				                buyNowLink += '&'+key+'='+value;
				            }
				        });

				    	window.location.assign(buyNowLink);
			        }

				}

				$(document).on('click','.buy-now-button',function(e){
					e.preventDefault();

					if (!$(this).prev('.single_add_to_cart_button').hasClass('disabled')) {
						updateBuyNowLink();
					}
				});

			/* Fbt
			----*/

				if ($('body').hasClass('woocommerce-js')) {

					$.ajax({
			            type: 'GET',
			            url: controller_opt.ajaxUrl,
			            data: {
			                action: 'update_mini_cart_contents',
			            },
			            success: function(response) {
			            	if (response && response != 0) {
			                	$('.mob-tabset .cart-contents > .cart-info').html(response);
			                	$('.mob-tabset .cart-contents').addClass('active');
			            	}
			            }
			        });

		        }

			    function updateMiniCart(productCount) {

					var cartContents = parseInt($('.cart-contents > .cart-info').html());

					$('.cart-contents > .cart-info').html(cartContents + productCount);

			        $.ajax({
			            type: 'GET',
			            url: controller_opt.ajaxUrl,
			            data: {
			                action: 'update_mini_cart_content',
			            },
			            success: function(response) {
			                // Update the mini cart content
			                $('.widget_shopping_cart_content').html(response);
			            }
			        });
			    }

				var fbt        = $('.fbt .product'),
				totalPrice = Number($('.fbt-info .total-price span').html());

				if (fbt.length) {
					// Toggle

					$('.fbt-item').on('click',function(){
						var $this = $(this),
							price = Number($this.data('price'));

						$this.toggleClass('chosen');
						if (!$this.hasClass('chosen')) {
							totalPrice = Number($('.fbt-info .total-price span').html()) - price;
						} else {
							totalPrice = Number($('.fbt-info .total-price span').html()) + price;
						}

						totalPrice = parseFloat(totalPrice);
						totalPrice = totalPrice.toFixed(2);

						$('.fbt-info .total-price span').html(totalPrice);

					});

					// Add to cart
					$('.add_to_cart_all').on('click',function(e){
						var $this = $(this),
							thiisText = $this.text();
						e.preventDefault();

						var fbtActive = $('.fbt-list li.chosen');

						if (!$this.hasClass('added')) {
							if (fbtActive.length) {

								$this.parent().addClass('loading');

								var products = [];


								fbtActive.each(function(){
									products.push($(this).data('product'));
								});

								if (products.length) {

									$.ajax({
							            type: 'POST',
							            url: controller_opt.ajaxUrl,
							            data: {
							                action: 'add_to_cart_all',
							                products: JSON.stringify(products),
							            },
							            success: function(response) {

							                $this
							                .addClass('added')
							                .html(controller_opt.allAdded)
							                .parent()
							                .removeClass('loading');

							                updateMiniCart(products.length);

							                setTimeout(function(){
							                	$this
							                	.removeClass('added')
							                	.html(thiisText);
							                },5000);
							            }
							        });

						        }
							}
						}
					});
				}

			/* Compare
			----*/

				$('.compare-table-single').find('.woocommerce-button[href="'+window.location.href+'"]').on('click',function(e){
					e.preventDefault();

					gsap.to(window, {
						duration: 1, 
						scrollTo: {y:'#wrap'},
						ease:Power3.easeOut 
					});
					return false;

				});

			/* Products carousel
			----*/

				function makeProductCarousel(products,sidebar){

					if (typeof products != 'undefined' && products != null) {

						$(products).addClass('manual-carousel').wrapInner('<div class="slides" />');

						var slides    = products.querySelector('.slides'),
							items     = sidebar ? 4 : 5,
							items768  = 4,
							items1024 = sidebar ? 3 : 5,
							gatter    = 24;

						if ($(products).parent().hasClass('cross-sells')) {items1024 = items = 5;}
						if ($(products).parent().hasClass('gap-true')) {gatter=((items > 5) ? 16 : 24);}

						var slider = tns({
							container: slides,
							mode:'carousel',
							controlsPosition:'bottom',
							navPosition:'bottom',
							gutter:gatter,
							autoplayButtonOutput:false,
							touch:true,
							mouseDrag:true,
							nav:false,
							controls:true,
							loop:false,
							items: items,
							responsive: {
								320: {items: 2},
								768: {items:items768},
								1024:{items:items1024},
								1280:{items:items}
							}
						});

					}
				}

				var sidebar = $('.post-layout-single.layout-sidebar-none').length ? false : true;

				makeProductCarousel(document.querySelector('.related > .loop-products'),sidebar);
				makeProductCarousel(document.querySelector('.up-sells > .loop-products'),sidebar);
				makeProductCarousel(document.querySelector('.cross-sells > .loop-products'),sidebar);

			/* comments responses
			----*/

				$('.see-responses').on('click',function(e){
					e.preventDefault();
					$(this).next('.responses').toggleClass('active');
					
				});

		})(jQuery);

	/* Tiny slider & Lightbox
	----*/

		(function($){

			"use strict";

			$('.post-media .slides').each(function(){
				if (!$(this).parent().hasClass('grid')) {

					if ($(this).parent().hasClass('slider') || $(this).parent().hasClass('post-gallery')) {

						var slider = tns({
							container: this,
							mode:'gallery',
							nav:false,
							items: 1,
						});

					} else {

						var items = $(this).parent().data('columns'),
							items768 = (items > 3) ? 3 : items;

						var slider = tns({
							container: this,
							mode:'carousel',
							gutter:8,
							touch:true,
							mouseDrag:true,
							nav:false,
							loop:false,
							items: items,
							responsive: {
								320: {items: 1},
								768: {items:items768,gutter:8},
								1024:{items:items},
							}
						});

					}

				}
			});

			$('.post.format-gallery').each(function(){

				var $this = $(this);

				setTimeout(function(){
					$this.find('.tns-controls-trigger button').on('click',function(){
						$this.find('.tns-controls button[data-controls="'+$(this).attr('data-controls')+'"]').trigger('click');
					});

					$this.find('.post-media .slides').each(function(){
						var lazyImage = $(this).find('.tns-item:last-child img.lazy');
						lazyImage.attr('src',lazyImage.data('src')).removeClass('lazy');
					});

				},200);

			});

			$('.gallery').each(function(){
				$(this).find('a').on('click',function(e){
					var href = $(this).attr('href');
				  	if (
						href.includes('.jpg') ||
						href.includes('.jpeg') ||
						href.includes('.png') ||
						href.includes('.bmp') ||
						href.includes('.gif') ||
						href.includes('.svg')
					){
						e.preventDefault();
						gsapLightbox($(this),true);
					}
				});
			});

		    $('.post-content a').each(function(){

		    	$(this).on('click',function(e){
						
			    	var $this = $(this),
			    		href  = $(this).attr('href');

				  	if (
						href.includes('.jpg') ||
						href.includes('.jpeg') ||
						href.includes('.png') ||
						href.includes('.bmp') ||
						href.includes('.gif') ||
						href.includes('.svg')
					){

						e.preventDefault();
						gsapLightbox($this,false);
					}

				});

		    	
		    });

		})(jQuery);

	/* Header
	----*/

		/* Submenu
		----*/

			(function($){

				"use strict";

				function submenuPosition(){

					$('.et-desktop .header-menu > .menu-item').each(function(){

						var $this = $(this);

						if ($this.children('.sub-menu:not(.megamenu)').length) {

							if( $this.offset().left + $this.width() + $this.children('.sub-menu').width() > $(window).innerWidth()){
								$this.addClass('submenu-left');
							} else {
								$this.removeClass('submenu-left');
							}

						}

					});

				}

				submenuPosition();
				$(window).resize(submenuPosition);

				$('.nav-menu:not(".megamenu-demo")').each(function(){

					var $this  		= $(this),
						menuEffect  = (!$this.parent().hasClass('menu-hover-none') && !$this.parent().hasClass('menu-hover-underline-default')) ? true : false;

					if ($this.parents('.header').length) {

						if (window.location.href.indexOf("data_blog") > -1) {
							$this.children('li.blog').addClass("active").siblings().removeClass("active");
						} else if (window.location.href.indexOf("data_shop") > -1) {
							$this.children('li.shop').addClass("active").siblings().removeClass("active");
						} else if ($('body').hasClass('single-header') || !$this.children('li.active').length) {
							$this.children('li').first().addClass('active');
						}
					}

					$this.children('.depth-0').hover(
						function(){
							var li = $(this);
							setTimeout(function(){li.addClass('hover');},200);
						},
						function(){
							$(this).removeClass('hover');
						}
					);

					if (menuEffect) {

						var active          	= '',
							activeOffset        = 0,
							currentMenuItem     = $this.children('li.active');

						if (typeof(currentMenuItem) == "undefined" || !currentMenuItem.length) {
							// Add active to first item
	                        $this.children('li').first().addClass('active');
						}

						currentMenuItem = $this.children('li.active').eq(0);
						currentMenuItem.siblings().removeClass('active');

						if (currentMenuItem.length) {
							active       = currentMenuItem;
							activeOffset = active.children('a').find('.effect').offset().left;

							if (active.length) {
								active = active.children('a').find('.effect');
							} else {
								active = $this.children('li:first-child').children('a').find('.effect')
							}

							$(window).resize(function(){
								activeOffset = $this.children('li.active').eq(0).children('a').find('.effect').offset().left;
							});

						}

						$.each($this.children('.depth-0'),function(){

							var li 		= $(this),
								effect  = li.children('a').find('.effect'),
								effectX = Math.round(effect.offset().left - activeOffset),
								effectW = Math.round(effect.outerWidth());

							li.on('mouseover touchstart',function(){

								gsap.to(active,1, {
									x:effectX,
									width:effectW,
									ease:"elastic.out(1, 1.15)"
								});

								li.addClass('in').siblings().removeClass('in');

								if (li.hasClass('active')) {
									li.removeClass('using');
								} else {
									li.parent().children('li.active').addClass('using');
								}

							});

						});


						$this.on('mouseleave',function(){

							var width = ($this.parent().hasClass('menu-hover-overline') || $this.parent().hasClass('menu-hover-underline')) ? Math.round($this.find('li.active .mi-link .txt').outerWidth()) : Math.round($this.find('li.active .mi-link').outerWidth()),
								x     = ($this.parent().hasClass('menu-hover-overline') || $this.parent().hasClass('menu-hover-underline')) ? Math.round($this.find('li.active .mi-link .txt').offset().left - activeOffset) : Math.round($this.find('li.active .mi-link').offset().left - activeOffset);

							gsap.to(active,1, {
								x:x,
								width:width,
								ease:"elastic.out(1, 1.15)"
							});

							$this.find('.in').removeClass('in');
							$this.find('.using').removeClass('using');
						});


					}

				});

			})(jQuery);

		/* Sticky
		----*/

			(function($){

				"use strict";

				var header = $( '.header.sticky-true' );

				header.each(function(){

					var $this = $(this);

					if ($this.length) {

						var docElem        = document.documentElement;
						var didScroll      = false;
				        var changeHeaderOn = 300 + $this.offset().top;

					    function init() {

					    	if( !didScroll ) {
				                didScroll = true;
				                scrollPage();
				            }

					        window.addEventListener( 'scroll', function( event ) {
					            if( !didScroll ) {
					                didScroll = true;
					                scrollPage();
					            }
					        }, false );

					    }

					    function scrollPage() {
					        var sy = scrollY();

				    		if ( sy >= changeHeaderOn ) {
				        		$this.addClass('active');
				        	} else {
				        		$this.removeClass('active');
				        	}

					        didScroll = false;
					    }

					    function scrollY() {
					        return window.pageYOffset || docElem.scrollTop;
					    }

					    $('<div class="header-placeholder" style="height:'+$this.outerHeight()+'px;"></div>').insertAfter($this);

					    init();

				    }
				});

			})(jQuery);

		/* Toggles
		----*/

			/* Header search
			----*/

				(function($){
		
					"use strict";

					$('.header-search').each(function(){

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

				})(jQuery);

			/* Shopping cart
			----*/

				(function($){
		
					"use strict";

					$('.header-cart').each(function(){
						var element      = this,
							$this   	 = $(element),
							toggle  	 = $this.find('.cart-toggle');

						toggleBack(element,toggle);
					});

					$('.ajax_add_to_cart').each(function(){
						$(this).on('click',function(){
							$('.header-cart').each(function(){
								var cartToggle = $(this).find('.close-toggle');
								if (cartToggle.hasClass('active')) {
									cartToggle.addClass('hide').trigger('click');
								}
							});
						});
					});

					setTimeout(function(){
						$('.et-desktop .cart-contents').each(function(){
							if ($(this).children().html() != "0") {
								$(this).addClass('active');
							}
						});
					},800);

				})(jQuery);

			/* Language switcher
			----*/

				(function($){
		
					"use strict";

					$('.wpml-ls-legacy-dropdown-click').each(function(){
						var $this = $(this);

						$this.find('.js-wpml-ls-item-toggle').on('click',function(){
							$this.find('.js-wpml-ls-sub-menu').toggleClass('active');
						});

					});

				})(jQuery);

			/* Login toggle
			----*/

				(function($){
			
					"use strict";

					$('.header-login').each(function(){

			            var element = this,
			                toggle  = $(element).find('.login-toggle');

			            toggleBack(element,toggle);
			            
			        });

			    })(jQuery);

			/* Widget navigation
			----*/

				(function($){
		
					"use strict";

					// Animate sidebar
					var sidebarArea    = $('.layout-sidebar'),
						sidebarOverlay = $('<div class="sidebar-layout-overlay"></div>').insertAfter(sidebarArea);

					$('body').on('click','.content-sidebar-toggle',function(e){

						e.preventDefault();

						sidebarArea.toggleClass('active');
						sidebarOverlay.toggleClass('active');

						if ($(this).hasClass('active')) {
							setTimeout(function(){$('#et-content').removeAttr('style');},1000);
						} else {
							$('#et-content').css('z-index',99);
						}

					});

					$('body').on('click','.mobile-total',function(e){

						e.preventDefault();

						$('html').animate({
			                scrollTop: ($('#loop-products').offset().top - 68)
			            }, 'slow');

					});

					$('.sidebar-layout-overlay').on('click',function(e){
						if(e.target !== e.currentTarget) return;
						sidebarArea.toggleClass('active');
						sidebarOverlay.toggleClass('active');
						setTimeout(function(){$('#et-content').removeAttr('style');},1000);
					});

				})(jQuery);

			/* Product search toggle
			----*/

				(function($){
			
					"use strict";

					$('.header-product-search-toggle').each(function(){

			            var element = this,
			                toggle  = $(element).find('.search-toggle'),
			                box     = $(element).find('.search-box'),
			                off     = $(element).find('.search-toggle-off');

			            toggle.on('click',function(){
			            	toggle.parents('.vc_row').css('z-index','15');
			            	box.toggleClass('active');
			            	box.find('input[type="search"]').focus();
			            	$('.et-mobile-tab').removeClass('active');
			            });

			            off.on('click',function(){
			            	toggle.parents('.vc_row').removeAttr('style');
			            	box.toggleClass('active');
			            	$('.et-mobile-tab').addClass('active');
			            });
			            
			        });

			    })(jQuery);

			/* Product search focus
			----*/

				(function($){
			
					"use strict";

					$('.product-search input.search').focusin(function(){
			            $(this).parents('div.product-search').addClass('focus');
			        });

			        $('.product-search input.search').focusout(function(){
			            $(this).parents('div.product-search').removeClass('focus');
			        });

			    })(jQuery);

		/* Mobile tabs
		----*/
			
			(function($){

				"use strict";

				$('.et-mobile-tab').each(function(){

					var $this    = $(this),
						tabs     = $this.find('.tab'),
						tabsQ    = tabs.length,
						toggle   = $this.find('.mob-tabset-toggle');

					toggle.on('click',function(){
						$(this).toggleClass('active');
						$this.find('.mob-tabset').toggleClass('active');
					});

					var docElem        = document.documentElement;
					var didScroll      = false;
			        var changeHeaderOn = 300;

			        function init() {

				    	if( !didScroll ) {
			                didScroll = true;
			                scrollPage();
			            }

				        window.addEventListener( 'scroll', function( event ) {

				        	if (!$this.find('.tab.active').length) {

					            if( !didScroll ) {
					                didScroll = true;
					                scrollPage();
					            }

				            }

				        }, false );

				    }

				    function scrollPage() {
				        var sy = scrollY();

			    		if ( sy <= changeHeaderOn ) {
			        		$this.addClass('active');
			        	} else {
			        		$this.removeClass('active');
			        		$this.find('.tab.active').trigger('click');
			        	}

				        didScroll = false;
				    }

				    function scrollY() {
				        return window.pageYOffset || docElem.scrollTop;
				    }

					setTimeout(function(){$this.addClass('active');},500);

					init();
				});

				$('.compare-toggle, .wishlist-toggle, .add_to_cart_button.ajax_add_to_cart').on('click',function(){
					setTimeout(function(){
						$('.et-mobile-tab').addClass('active');
						$('.et-mobile-tab .mob-tabset-toggle:not(.active)').trigger('click');
					},800);
				});

			})(jQuery);

		/* Sidebar menu
		----*/

			(function($){

				"use strict";

				$('.sidebar-container').each(function(){

					var $this   	 = $(this),
						toggle  	 = $this.find('.sidebar-container-toggle'),
						small   	 = toggle.find('.small'),
						normalTop    = toggle.find('.normal').find('.top'),
						normalBottom = toggle.find('.normal').find('.bottom'),
						crossTop     = toggle.find('.cross').find('.top'),
						crossBottom  = toggle.find('.cross').find('.bottom'),
						content 	 = $this.find('.sidebar-container-content'),
						menu    	 = $this.find('.sidebar-menu > .menu-item');

					var tl = new gsap.timeline({paused: true});

					tl.to(small,0.1, {
						 opacity:'0'
					});

					tl.to(normalTop,0.8, {
					  morphSVG:{shape:crossTop},
					  ease:"elastic.out(1, 1.75)"
				  	},'-=0.1');

					tl.to(normalBottom,0.8, {
					  morphSVG:{shape:crossBottom},
					  ease:"elastic.out(1, 1.75)"
				  	},'-=0.8');

					tl.to($this,0.8, {
						width:480,
						ease:"elastic.out(1, 1.75)"
					},'-=0.6');

					tl.to(content,0.2, {
					  opacity:'1'
				  	},'-=0.4');

					tl.staggerFrom(menu,1.2, {
					  x:'-12px',
					  opacity:0,
					  ease:"elastic.out(1, 0.75)",
				  	},0.05,'-=0.4');

					tl.add('open');

					tl.to(content,0.2, {
						opacity:0,
					},'+=0.2');

					tl.to($this,0.4, {
						width:64,
						ease:"expo.out"
					},'-=0.2');

					tl.to(normalTop,0.8, {
					  morphSVG:{shape:normalTop, shapeIndex:3},
					  ease:"elastic.out(1, 1.75)"
				  	},'-=0.4');

					tl.to(normalBottom,0.8, {
					  morphSVG:{shape:normalBottom, shapeIndex:3},
					  ease:"elastic.out(1, 1.75)"
				  	},'-=0.8');

					tl.to(small,0.1, {
						 opacity:'1'
					},'-=0.8');

					toggle.on('click',function(){

						if (!toggle.hasClass('active')) {
							toggle.addClass('active');

							content.addClass('active');

							tl.progress(0);
							tl.tweenTo('open');
						} else {
							tl.play();
							content.removeClass('active');
							toggle.removeClass('active');
						}

					});

					$.each($this.find('.sidebar-menu > .menu-item-has-children'),function(){

						var li 		= $(this),
							subMenu = li.children('.sub-menu');

						var tl = new gsap.timeline({paused: true});

						tl.from(subMenu,0.6, {
							x:'-12px',
							ease:"expo.out"
						},'+=0.2');

						tl.from(subMenu,0.1, {
							opacity:0,
							ease:"expo.out"
						},'-=0.6');

						li.hover(
							function(){

								li.parent().addClass('active');

								tl.progress(0);
								tl.play();
							},
							function(){
								tl.reverse();

								li.parent().removeClass('active');

							}
						);


					});

				});

			})(jQuery);

		/* Modal menu
		----*/

			(function($){

				"use strict";

				function responsiveModalContainer(){

					var element = document.getElementsByClassName('modal-container')[0];

					if (typeof(element) != 'undefined') {

						var	$this   = $(element),
							width   = $(window).width(),
							height  = $(window).height(),
							svg     = $this.find('.modal-back');

						// get svg viewBox
						var viewBox = element.querySelector('.modal-back').getAttribute('viewBox');

						viewBox = viewBox.split(' ');
						viewBox  = viewBox.splice(2, 2);

						var heightReplace = viewBox[1];
						var widthReplace  = viewBox[0];
						var widthReplace2  = parseInt(widthReplace ) - 100;
						var heightReplace2 = parseInt(heightReplace)  + 100;

						widthReplace2 = widthReplace2.toString();

						var start    = svg.find('.start').attr('d'),
							end      = svg.find('.end').attr('d'),
							original = svg.find('.start').attr('data-original'),
							clone 	 = svg.find('.start').attr('data-dclone');

						start   = start.replace(new RegExp(widthReplace,"g"),width);
						start   = start.replace(new RegExp(widthReplace2,"g"),(width - 100));

						clone   = clone.replace(new RegExp(widthReplace,"g"),width);
						clone   = clone.replace(new RegExp(widthReplace2,"g"),(width - 100));

						end     = end.replace(new RegExp(widthReplace,"g"),width);
						end     = end.replace(new RegExp(heightReplace2,"g"),(height + 100));

						if (typeof(original) != 'undefined') {
							original = original.replace(new RegExp(widthReplace,"g"),width);
							original = original.replace(new RegExp(widthReplace2,"g"),(width - 100));
						}

						element.querySelector('.modal-back').setAttribute('viewBox','0 0 '+width+' '+height);

						svg.find('.start').attr('d',start);
						svg.find('.start').attr('data-dclone',clone);
						svg.find('.end').attr('d',end);

						if (typeof(original) != 'undefined') {
							svg.find('.start').attr('data-original',original);
						}

					}
				}

				responsiveModalContainer();

				var modalContainer = $('.modal-container'),
					content        = modalContainer.find('.modal-container-inner'),
					menu           = modalContainer.find('.modal-menu > li'),
					close		   = modalContainer.find('.modal-toggle.active'),
					svg     	   = modalContainer.find('.modal-back'),
					start  		   = svg.find('.start'),
					startOriginal  = start.attr('d'),
					end    		   = svg.find('.end').attr('d');


				var tl = new gsap.timeline({paused: true});

				function buildModNavTimeline(){

				  	tl.to(modalContainer,0, {
					  top:0,
					  immediateRender:false
				  	});

					tl.from(start,1.6, {
					  x:'100%',
					  y:'-100%',
					  ease:"expo.out"
				  	},'+=0.2');

					tl.to(start,1.6, {
					  morphSVG:{shape:end, shapeIndex:4},
					  ease:"elastic.out(1, 0.75)",
				  	},'-=1.4');

				  	tl.to(content,0.2, {
					  opacity:'1'
				  	},'-=1.4');

				  	tl.from(close,0.2, {
					  opacity:0,
					  ease:"expo.out"
				  	},'-=1.4');

				  	tl.from(close,1.2, {
					  x:'-12px',
					  ease:"elastic.out(1, 0.5)"
				  	},'-=1.4');

					tl.staggerFrom(menu,1.2, {
					  x:'-12px',
					  opacity:0,
					  ease:"elastic.out(1, 0.75)",
				  	},0.05,'-=1.4');

					tl.add("open");

					tl.to(content,0.1, {
					  opacity:0,
					},'+=0.2');

					tl.to(close,0.2, {
					  opacity:0,
					},'-=0.1');

				  	tl.to(start,1.6, {
					  morphSVG:{shape:startOriginal, shapeIndex:4},
					  ease:"expo.out",
				  	},'-=0.1');

				  	tl.to(start,1.6, {
					  x:'100%',
					  y:'-100%',
					  ease:"expo.out"
				  	},'-=1.6');

					tl.to(modalContainer,0, {
					  top:'-100%',
					  immediateRender:false
				  	});

					tl.add("close");

				}

				buildModNavTimeline();


				$('.modal-toggle').on('click',function(){

					if ($(this).hasClass('active')) {
						tl.tweenTo('close');

						setTimeout(function(){
							modalContainer.removeClass('active');
						},1000);

					} else {

						modalContainer.addClass('active');

						tl.progress(0);
						tl.tweenTo("open");
					}

				});

				$(window).resize(function(){

					tl.seek('open').kill();

					responsiveModalContainer();

					modalContainer = $('.modal-container').removeClass('active').removeAttr('style');
					content        = modalContainer.find('.modal-container-inner').removeAttr('style');
					menu           = modalContainer.find('.modal-menu > li');
					close		   = modalContainer.find('.modal-toggle.active');
					svg     	   = modalContainer.find('.modal-back');
					start  		   = svg.find('.start');
					end    		   = svg.find('.end').attr('d');
				
					var startC = start.attr('data-dclone');
						start.attr('d',startC);

					startOriginal  = start.attr('d');

					tl = new gsap.timeline({paused: true});

					buildModNavTimeline();
				
				});

				$.each($('.modal-menu > .menu-item-has-children'),function(){

					var li 		= $(this),
						subMenu = li.children('.sub-menu');

					var tl = new gsap.timeline({paused: true});

					tl.from(subMenu,0.6, {
						x:'-12px',
						ease:"expo.out"
					},'+=0.2');

					tl.from(subMenu,0.1, {
						opacity:0,
						ease:"expo.out"
					},'-=0.6');

					li.hover(
						function(){

							li.parent().addClass('active');

							tl.progress(0);
							tl.play();
						},
						function(){
							tl.reverse();

							li.parent().removeClass('active');

						}
					);


				});

				$.each($('.modal-menu > li'),function(){

					var li = $(this);

					li.append('<span class="index">0'+(li.index()+1)+'</span>');

				});


			})(jQuery);

	/* Main
	----*/

		(function($){

			"use strict";

			$.fn.footerReveal=function(o){var t=$(this),e=t.prev(),i=$(window),s=$.extend({shadow:!0,shadowOpacity:.8,zIndex:-100},o);$.extend(!0,{},s,o);return t.outerHeight()<=i.outerHeight()&&t.offset().top>=i.outerHeight()&&(t.css({"z-index":s.zIndex,position:"fixed",bottom:0}),s.shadow&&e.css({"-moz-box-shadow":"0 20px 30px -20px rgba(0,0,0,"+s.shadowOpacity+")","-webkit-box-shadow":"0 20px 30px -20px rgba(0,0,0,"+s.shadowOpacity+")","box-shadow":"0 20px 30px -20px rgba(0,0,0,"+s.shadowOpacity+")"}),i.on("load resize footerRevealResize",function(){t.css({width:e.outerWidth()}),e.css({"margin-bottom":t.outerHeight()})})),this};
			

			function stickyFooter(){
				var footer = $('.footer.sticky-true');
				if (typeof(footer) != 'undefined' && footer.length) {
					$('.page-content-wrap').addClass('disable');
					footer.footerReveal({ shadow: false, zIndex: -101 });
					footer.addClass('active');
				}
			}

			function mobileNavigation(){

				// Animate mobile
				var mobileOverlay   = $('.mobile-container-overlay'),
					mobileContainer = $('.mobile-container');

				$('.mobile-toggle').on('click',function(){

					if ($(this).hasClass('active')) {
						mobileContainer.removeClass('active');
					} else {
						mobileContainer.addClass('active');
					}

				});

				$('.mobile-container-overlay').on('click',function(e){
					if(e.target !== e.currentTarget) return;
					mobileContainer.removeClass('active');
				});

				

				$('.mobile-menu .menu-item-has-children > a').each(function(){
					var $link = $(this);
					if ($link.attr( "href" ) == "#") {
						$link.on('click',function(e){
							e.preventDefault();
							$link.parent().toggleClass('active');
							isolate($link);
						});
					} else {
						$link.find('.arrow').on("click", function(e){
							e.preventDefault();
							var $this = $(this);
							// $link.parent().toggleClass('active');
							isolate($link);
						});
					}
				});

				if (window.matchMedia('(max-width: 767px)')) {
					$('.et-menu .menu-item-has-children > a').each(function(){
						var $link = $(this);
						if ($link.attr( "href" ) == "#") {
							$link.on('click',function(e){
								e.preventDefault();
								$link.find('.arrow').toggleClass('active');
								$link.next('ul').stop().slideToggle(200);
							});
						} else {
							$link.find('.arrow').on("click", function(e){
								e.preventDefault();
								var $this = $(this);
								$this.toggleClass('active');
								$link.next('ul').stop().slideToggle(200);
							});
						}
					});
				}

			}

			function lang_curr_Toggles(){
				$('.language-switcher').each(function(){

					var element      = this,
						$this   	 = $(element),
						toggle  	 = $this.find('.language-toggle');				

					toggleBack(element,toggle);

				});

				$('.currency-switcher').each(function(){

			    	var element = this,
		                toggle  = $(element).find('.currency-toggle');

		            $('#alg_currency_selector a').each(function(){
		            	var href = $(this).attr('href');
		            	if (href.indexOf("/ajax-api/mobile-query") > -1) {
		            		href = href.split("?").pop();
		            		$(this).attr('href',$('body').data('url')+'?'+href);
		            	}
		            });

		            toggleBack(element,toggle);
		            if (!toggle.find('.highlighted-currency').length) {
		            	$('<span class="highlighted-currency">'+$(element).find('.currency-list a:first-child').text()+'</span>').insertBefore(toggle.not('.close-toggle').find('svg'));
		            }
		            toggle.on('click',function(){
		            	toggle.find('.highlighted-currency').remove();
		            	$('<span class="highlighted-currency">'+$(element).find('.currency-list a:first-child').text()+'</span>').insertBefore(toggle.not('.close-toggle').find('svg'));
		            });

				});
			}

			function megamenuTab(){

				$('.megamenu-tab').each(function(){

					var $this   		  = $(this),
						tabs     		  = $this.find('.tab-item'),
						tabsQ    		  = tabs.length,
						tabsDefaultWidth  = 0,
						tabsDefaultHeight = 0,
						tabsContent 	  = $this.find('.tab-content'),
						action      	  = ($this.hasClass('action-hover')) ? 'hover' : 'click';

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
										// var currentHeight = tabsContent.eq($self.index()).height();
										// $this.parents('.megamenu').css('height',currentHeight);
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
										// var currentHeight = tabsContent.eq($self.index()).height();
										// $this.parents('.megamenu').css('height',currentHeight);
									}
								}
								
							});
						}
						
					}

				});
			}

			function mobileContainerTabs(){
				$('.mobile-container-inner').each(function(){

					var $this    	= $(this),
						tabs     	= $this.find('.mobile-container-tab');

					if (tabs.length) {

						var	tabsContent = $this.find('.mobile-container-tab-content');

						tabs.wrapAll('<div class="mob-container-tabset et-clearfix"></div>');
						tabsContent.wrapAll('<div class="mobile-container-tabs-container et-clearfix"></div>');

						$this.find('.mob-container-tabset, .mobile-container-tabs-container').wrapAll('<div class="mob-container-tabs"></div>');

						tabs.first().addClass('active');
						tabsContent.first().addClass('active');

						tabs.each(function(){

							var self 	    = $(this),
								selfContent = $('#'+self.data('target'));

							self.on('click', function(){
								self.addClass('active').siblings().removeClass('active');
								selfContent.addClass('active').siblings().removeClass('active');
							});

						});

					}
					
				});
			}

			function megamenuPosition(){

				$('.header-menu > .menu-item').each(function(){

					var $this = $(this);
					var megamenu = $this.children('.megamenu');
					
					if ($this.data('width') == '100') {
						$this.parents('.header-menu-container').css('position','static');
						if (megamenu.length) {
							var megamenuWidth = $(window).innerWidth();
							megamenu.css({
								'max-width':megamenuWidth+'px',
								'width':megamenuWidth+'px',
								'margin-left':'-'+(megamenuWidth/2)+'px',
							});
						}
					}

				});

				$('.et-header-button .megamenu[data-width="100"]').each(function(){

					var megamenu = $(this);

					if (megamenu.length) {
						if (megamenu.data('width') == '100') {
							megamenu.parent().css('position','static');
							var megamenuWidth = $(window).innerWidth();
							megamenu.css({
								'max-width':megamenuWidth+'px',
								'width':megamenuWidth+'px',
								'margin-left':'-'+(megamenuWidth/2)+'px',
							});
						}
					}

				});

			}

			function megamenuGrid(){

				$('.megamenu').each(function(){
					var $this = $(this);

					if ($this.data('width') == '1200') {

						var closestLink = $this.parent().children('a');
						if (closestLink.length) {
							var parentContainer = $this.parents('.container').eq(0);
							var offset = closestLink.offset().left - parseInt(closestLink.parent().css('padding-left')) - (parentContainer.offset().left + ((parentContainer.outerWidth() - 1240)/2));
							$this.attr('style','margin-left:-'+offset+'px !important;');
						}

					}

				});

			}

			function megamenuHoverCheck(){
				$('.mm-ajax').each(function(){
					if ($(this).find('.sub-menu').length) {
						$(this).children('.mi-link').removeClass('loading-menu');
					}

					$(this).on('mouseenter',function(){
						if (!$(this).find('.sub-menu').length) {
							$(this).children('.mi-link').addClass('loading-menu');
						} else {
							$(this).children('.mi-link').removeClass('loading-menu');
						}
					});
				});
			}

			function headerButton(){

				$('.et-header-button.submenu-toggle-click').each(function(){

					if ($(this).find('.sub-menu').length) {
						$(this).children('.et-button').removeClass('loading-menu');
					}

					$(this).children('.et-button').on('click',function(e){
						e.preventDefault();

						if (!$(this).next('.sub-menu').length) {
							$(this).addClass('loading-menu');
							return;
						} else {
							$(this).removeClass('loading-menu');
						}

						$(this).parent().toggleClass('active');
						$(this).next('.sub-menu').toggleClass('active');

						// if ($(this).next('.megamenu[data-width="100"]').length) {
						// 	if ($(this).parent().hasClass('active')) {
						// 		$(".megamenu-overlay:not(.active)").addClass('active').removeClass('hover');
						// 	} else {
						// 		$('.megamenu-overlay.active').removeClass('active');
						// 	}
						// }

						$('.header .hbe-toggle.active').not(this).each(function(){
							$(this).removeClass('active');
							$(this).parent().find('.active').removeClass('active');
						});
					});
				});

				// $('.et-desktop .depth-0.mm-true[data-width="100"]').hover(
				// 	function(){
				// 		if ($(this).find('.sub-menu').length) {
				// 			$(".megamenu-overlay:not(.active)").addClass('active').addClass('hover');
				// 		}
				// 	},
				// 	function(){
				// 		$('.megamenu-overlay.active').removeClass('active');
				// 	}
				// );

				$('.et-header-button.submenu-toggle-hover').each(function(){

					var li = $(this);

					li.hover(
						function(){
							if (!$(this).find('.sub-menu').length) {
								$(this).children('.et-button').addClass('loading-menu');
							} else {
								$(this).children('.et-button').removeClass('loading-menu');
							}

							setTimeout(function(){li.addClass('hover');},100);
						},
						function(){
							li.removeClass('hover');
						}
					);
					
				});

			}

			function animateBoxBack(box){

				var $this  = jQuery(box),
					width  = $this.width(),
					height = $this.height(),
					ratio  = Math.round(100*(height/width)),
					svg    = $this.find('.box-back'),
					path   = svg.find('path');

				// get svg viewBox
				var viewBox = box.querySelector('.box-back').getAttribute('viewBox');

				var viewBoxValues = viewBox.split(' ');

				viewBoxValues  = viewBoxValues.splice(2, 2);

				var replace = viewBoxValues[1];

				var start    = path.attr('d'),
					startC   = path.attr('data-dclone'),
					end      = path.attr('data-end'),
					original = path.attr('data-original');

				start  = start.replace(new RegExp((replace-10),"g"),(ratio-10));
				start  = start.replace(new RegExp(replace,"g"),ratio);
				startC = startC.replace(new RegExp((replace-10),"g"),(ratio-10));
				startC = startC.replace(new RegExp(replace,"g"),ratio);
				end    = end.replace(new RegExp(replace,"g"),ratio);

				if (typeof(original) != 'undefined') {
					original = original.replace(new RegExp(replace,"g"),ratio);
				}

				box.querySelector('.box-back').setAttribute('viewBox','0 0 100 '+ratio);

				path.attr('d',start);
				path.attr('data-end',end);
				path.attr('data-dclone',startC);

				if (typeof(original) != 'undefined') {
					path.attr('data-original',original);
				}

			}

			function buildAnimateBoxTimeline(tl,box,delay,animation,stagger,content){

				var path = box.find('.box-back path');

				tl.from(box,0.2, {
				  opacity:0,
				},delay);

				switch(animation){
					case 'top':

						tl.from(box,1.2, {
							y:-100,
							scaleY:0,
							rotationZ:8,
							force3D:true,
							transformOrigin:'right top',
							ease:"elastic.out(1, 0.5)"
						},'-=0.1');

					break;

					case 'bottom':

						tl.from(box,1.2, {
							y:100,
							scaleY:0,
							rotationZ:8,
							force3D:true,
							transformOrigin:'right bottom',
							ease:"elastic.out(1, 0.5)"
						},'-=0.1');
					
					break;

					case 'left':

						tl.from(box,1.2, {
							x:-100,
							scaleX:0,
							rotationZ:-8,
							force3D:true,
							transformOrigin:'left center',
							ease:"elastic.out(1, 0.5)"
						},'-=0.1');
					
					break;

					case 'right':

						tl.from(box,1.2, {
							x:100,
							scaleX:0,
							rotationZ:8,
							force3D:true,
							transformOrigin:'right center',
							ease:"elastic.out(1, 0.5)"
						},'-=0.1');
					
					break;
				}


				tl.to(path,1.2, {
				  morphSVG:{shape:path.attr('data-end'), shapeIndex:8},
				  ease:"elastic.out"
				},'-=1');

				switch(stagger){

					case "left":

						tl.from(content,{
						  	duration: 0.8,
							x:-50,
							stagger: 0.05,
							opacity:0,
							ease:"expo.out"
						},'-=1.1');

					break;

					case "right":

						tl.from(content,{
						  	duration: 0.8,
							x:50,
							stagger: 0.05,
							opacity:0,
							ease:"expo.out"
						},'-=1.1');

					break;

					case "top":

						tl.from(content,{
						  	duration: 0.8,
							y:-50,
							stagger: 0.05,
							opacity:0,
							ease:"expo.out"
						},'-=1.1');

					break;

					case "bottom":

						tl.from(content,{
						  	duration: 0.8,
							y:50,
							stagger: 0.05,
							opacity:0,
							ease:"expo.out"
						},'-=1.1');

					break;
				}

				tl.add('end');

			}

			function buildStaggerBoxTimeline(tl,delay,interval,stagger,content){

				switch(stagger){

					case "left":

						tl.from(content,{
						  	duration: 0.8,
							x:-50,
							stagger: interval,
							opacity:0,
							ease:"expo.out"
						},delay);

					break;

					case "right":

						tl.from(content,{
						  	duration: 0.8,
							x:50,
							stagger: interval,
							opacity:0,
							ease:"expo.out"
						},delay);

					break;

					case "top":

						tl.from(content,{
						  	duration: 0.8,
							y:-50,
							stagger: interval,
							opacity:0,
							ease:"expo.out"
						},delay);

					break;

					case "bottom":

						tl.from(content,{
						  	duration: 0.8,
							y:50,
							stagger: interval,
							opacity:0,
							ease:"expo.out"
						},delay);

					break;
				}

			}

			function disableParallax(){
				if ($(window).width() <= 1300) {
					$('.et-image.parallax').each(function(){
						$(this).addClass('parallax-off');
					});
				} else {
					$('.et-image.parallax').each(function(){
						$(this).removeClass('parallax-off');
					});
				}
			}

			function responsiveBannerImage(){

				if(window.matchMedia("(max-width: 374px)").matches) {
					$('.banner-image').each(function(){
						$(this).attr('style',$(this).attr('data-m-style')).addClass('resp');
					});
				} else if(window.matchMedia("(min-width: 375px) and (max-width: 767px)").matches) {
					$('.banner-image').each(function(){
						$(this).attr('style',$(this).attr('data-mm-style')).addClass('resp');
					});
				} else if (window.matchMedia("(min-width: 768px) and (max-width: 1023px)").matches) {
					$('.banner-image').each(function(){
						$(this).attr('style',$(this).attr('data-tp-style')).addClass('resp');
					});
				} else if(window.matchMedia("(min-width: 1024px) and (max-width: 1279px)").matches) {
					$('.banner-image').each(function(){
						$(this).attr('style',$(this).attr('data-tl-style')).addClass('resp');
					});
				} else {
					$('.banner-image').each(function(){
						$(this).removeAttr('style').removeClass('resp');
					});
				}

				if ($(window).width() <= 1300) {
					$('.banner-image.parallax').each(function(){
						$(this).addClass('parallax-off');
					});
				} else {
					$('.banner-image.parallax').each(function(){
						$(this).removeClass('parallax-off');
					});
				}
			}

			function ajaxAddToCart(){
				$('.loop-products .product, ul.products .product').each(function(){

					var $this = $(this);
					var addToCard = $this.find('.ajax_add_to_cart');
					var productProgress = $this.find('.ajax-add-to-cart-loading');
					var addToCardEvent  = true;

					if (addToCard.hasClass('added')) {
						addToCardEvent  = false;
					}

					if (addToCard.attr('data-product_status') == 'outofstock') {
						addToCardEvent  = false;
					}

					if (addToCard.attr('data-product_type') == 'variable') {
						addToCardEvent  = false;
					}

					addToCard.on('click',function(){
						if (addToCardEvent == true) {
							var $self = $(this);
							productProgress.addClass('active');
							setTimeout(function(){
								productProgress.addClass('load-complete');
								gsap.to(productProgress.find('.tick'),0.2, {
								  opacity:1,
								});
								gsap.to(productProgress.find('.tick'),0.8, {
								  scale:1.15,
								  ease:"elastic.out"
								});
								setTimeout(function(){
									productProgress.removeClass('active').removeClass('load-complete');
									addToCardEvent  = false;
								}, 500);
							}, 1500);
						} else {
							alert(controller_opt.already);
						}
					});
				});
			}

			function listImages(){
				if ($(window).width() <= 720) {
					$('.list .loop-posts .post img').each(function(){

						var $this   			= $(this),
							dataRespSrc 	    = $this.attr('data-resp-src'),
							dataRespSrcOriginal = $this.attr('data-resp-src-original'),
							dataWidth           = $this.attr('data-width'),
							dataHeight          = $this.attr('data-height');
						
						if ($this.hasClass('lazy')) {
							$this.attr('src',dataRespSrc);
							$this.attr('data-src',dataRespSrcOriginal);	
						} else {
							$this.attr('src',dataRespSrcOriginal);
						}	
						$this.attr('width',dataWidth);
						$this.attr('height',dataHeight);

						$this.addClass('changed');

					});
				} else {
					$('.list .loop-posts .post img').each(function(){

						var $this = $(this);

						if ($this.hasClass('changed')) {

							var	dataRespSrc 	    = $this.attr('data-clone-resp-src'),
								dataRespSrcOriginal = $this.attr('data-clone-resp-src-original'),
								dataWidth           = $this.attr('data-clone-width'),
								dataHeight          = $this.attr('data-clone-height');
							
							if ($this.hasClass('lazy')) {
								$this.attr('src',dataRespSrc);
								$this.attr('data-src',dataRespSrcOriginal);	
							} else {
								$this.attr('src',dataRespSrcOriginal);
							}	
							$this.attr('width',dataWidth);
							$this.attr('height',dataHeight);

							$this.removeClass('changed');

						}

					});
				}
			}


			function toggle_hidden_variation_btn() {
				const resetVariationNodes = document.getElementsByClassName('reset_variations');

				if (resetVariationNodes.length) {

					Array.prototype.forEach.call(resetVariationNodes, function (resetVariationEle) {

						let observer = new MutationObserver(function () {

							if (resetVariationEle.style.visibility !== 'hidden') {

								resetVariationEle.style.display = 'block';

							} else {

								resetVariationEle.style.display = 'none';

							}

						});

						observer.observe(resetVariationEle, {attributes: true, childList: true});

					})

				}
			}

			function quickView(){

				$("body").on('click','.en-quick-view',function(e){

					e.preventDefault();

					if (quickViewLoading) {return;}

					var quickView = $(this),
						product = quickView.parents('.product').attr('id');

					if (product) {product = product.replace('product-', '');}

					quickViewLoading = true;

					quickView.addClass('loading');
					$.ajax({
		                url:controller_opt.ajaxUrl,
		                type: 'post',
		                data: {
		                	action:'quick_view',
		                	id:product,
		                },
		                success: function(data) {
		                	if (data.length) {

								$('body').append(data);

								$('.quick-view-wrapper').find( '.summary' ).wrapInner('<div class="summary-inner-wrapper"></div>')

								if ($(window).outerWidth() >= 1280) {
	                                SimpleScrollbar.initEl(document.querySelector('.quick-view-wrapper .summary'));
	                            }

								var form = $('.quick-view-wrapper').find( '.variations_form' );

							    if (form) {
									form.tawcvs_variation_swatches_form();
									$( document.body ).trigger( 'tawcvs_initialized' );
									toggle_hidden_variation_btn();
									form.wc_variation_form();
							    }

							    if (typeof(quickview_opt) != "undefined") {
								    $('.quick-view-wrapper').find( '.woocommerce-product-gallery' ).each( function() {
										$( this ).trigger( 'wc-product-gallery-before-init', [ this, quickview_opt ] );
										$( this ).wc_product_gallery( quickview_opt );
										$( this ).trigger( 'wc-product-gallery-after-init', [ this, quickview_opt ] );
									} );
								}


								$('.quick-view-wrapper').removeClass('loaded');

								$('.quick-view-wrapper').find('.swatch-color').each(function(){
									if ($(this).css('background-color') === 'rgb(255, 255, 255)') {
									   $(this).addClass('white');
									}
								});

							    quickView.removeClass('loading');
								quickViewLoading   = false;

								ProductCount();

		                	} else {
		                		quickView.removeClass('loading');
								quickViewLoading   = false;
								console.log('No data');
							}
						},
						error: function(data){
							console.log('Something went wrong, please contact site administrator');
						}
		            });
				});

				$("body").on('click','.quick-view-wrapper-close, .quick-view-wrapper-after',function(e){

			    	var currentProduct = $('.qwc').find('.product').first().attr('id');

			    	var wish = $('.qwc').find('.wishlist-toggle.active');
			    	var comp = $('.qwc').find('.compare-toggle.active');

			    	$('.qwc').remove();

			    	$('#'+currentProduct).each(function(){
			    		if (wish.length) {$(this).find('.wishlist-toggle').addClass('active');}
			    		if (comp.length) {$(this).find('.compare-toggle').addClass('active');}
			    	});

			    });
			}

			function getPosts(postType,trigger,masonry){

				if(start <= max) {

					if (request) {
						return;
					}

					request = true;

					trigger.removeClass('disable').addClass('loading');
					trigger.find('.text').text(defaultText);

					$.get(next,function(content) {

						var content = $(content).find('#loop-'+postType+' > .post').addClass('append');

						if (typeof content !== "undefined") {

							start++;
							next = next.replace(/\/page\/[0-9]*/, '/page/'+ start);
							request = false;

							setTimeout(function(){
							
								$('#loop-'+postType).append(content);

								// plugins-recall

								if ($('#loop-posts').length) {

									if ($('.post-layout').hasClass('masonry')) {

										$('.masonry #loop-posts').masonry('destroy');
										$('.masonry #loop-posts').removeData('masonry');

										$('.masonry #loop-posts').masonry({
										  itemSelector: '.post',
										  columnWidth:'.post',
										  percentPosition:true,
										  gutter:0
										});

									}

									$('.post-media .slides').each(function(){
										var slider = tns({
											container: this,
											mode:'gallery',
											nav:false,
											items: 1
										});
									});

									videoTrigger();

									setTimeout(function(){
										$('.tns-controls-trigger button').on('click',function(){
											$('.tns-controls button[data-controls="'+$(this).attr('data-controls')+'"]').trigger('click');
										});

										$('.post-media .slides').each(function(){
											var lazyImage = $(this).find('li:last-child img.lazy');
											lazyImage.attr('src',lazyImage.data('src')).removeClass('lazy');
										});

									},200);

								}

								if ($('#loop-products').length) {
									quickView();
									ajaxAddToCart();
								}

								listImages();

								trigger.removeClass('loading');

								$('#loop-'+postType+' > .post').removeClass('append');

								lazyLoad(document.getElementById('loop-'+postType));

								if(start > max) {
									trigger.addClass("disable");
									trigger.find('.text').text(noMore);
								}

							},1000);


						} else {
							alert('Something went wrong, please contact site administrator');
						}

					});

				}
			}

			function fetchPosts(postType){

				var loop    = $('#loop-'+postType),
					nav     = loop.data('nav'),
					trigger = $('.post-ajax-button');

				switch(postType){
					case 'products':
						max  = controller_opt.productMax;
						next = controller_opt.productNextLink;
					break;
				}

				if(start > max) {
					trigger.addClass("disable");
					trigger.find('.text').text(noMore);
				}

				trigger.on('click',function(e){
					e.preventDefault();
				});

				if (nav == "loadmore") {

					trigger = $('#loadmore');
					trigger.on('click',function(){

						var activeParams = getParams(),
							disable = (activeParams && activeParams['ajax'] == 'true') ? true : false;

						if (disable) {return;}

						trigger = $(this);
						getPosts(postType,trigger);
					});

				} else if(nav == "infinite"){

					trigger = $('#infinite');

					$(window).scroll(function(){

						var activeParams = getParams(),
							disable = (activeParams && activeParams['ajax'] == 'true') ? true : false;

						if (disable) {return;}

						if  (trigger.inView()){
							getPosts(postType,trigger);
						}
					});

				}
			}

			function filterPosts(postType,link,count,perPage,id,layout,full,loopId = ''){

				if (request) {
					return;
				}

				var trigger = $('#loop-'+postType+loopId).next('.post-ajax-button');

				request = true;

				$('#loop-'+postType+loopId).addClass('loading');
				
				trigger.find('.text').text(defaultText);
				trigger.removeClass('disable').addClass('loading');

				$.ajax({
	                url:controller_opt.ajaxUrl,
	                type: 'post',
	                data: {
	                	action:'term_filter',
	                	id:id,
	                	count:perPage,
	                	layout:layout,
	                	full:full
	                },
	                success: function(data) {
	                	if (data.length) {

	                		request = false;

	                	 	start = parseInt(controller_opt.start) + 1;
							next  = link + '/page/' + start;
							max   = Math.ceil(count/perPage);

							setTimeout(function(){

								$('#loop-'+postType+loopId).html('');
								$('#loop-'+postType+loopId).append(data);

								// plugins-recall

								listImages();

								$('#loop-'+postType+loopId).removeClass('loading');
								trigger.removeClass("loading");

								$('#loop-'+postType+loopId+' > .post').removeClass('append');

								lazyLoad(document.getElementById('loop-'+postType+loopId));

								if(start > max) {
									trigger.addClass("disable");
									trigger.find('.text').text(noMore);
								}

							},1000);

	                	} else {
							alert('No data');
						}
					},
					error: function(data){
						alert('Something went wrong, please contact site administrator');
					}
	            });

			}

			function validateValue($value, $target, $placeholder,$email,valid){
		        if ($email == true) {
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

			function etElements(){

				/* et-button
				----*/

					$('.et-button').each(function(){

						var $this  = $(this),
							effect = $this.data('effect');

						var tl = new gsap.timeline({paused: true});

						switch (effect) {
							case 'fill':

								var hover 	    = $this.find('span.hover'),
									icon        = $this.find('.icon svg'),
									color 	    = $this.data('color'),
									color_hover = $this.data('color-hover');

								tl.to(hover,0.6, {
								  scaleX:1,
								  transformOrigin:'left top',
								  ease:"power3.out"
							    });

								tl.to($this,0.1, {
								  css:{color:color_hover}
							    },'-=0.6');

								tl.to(icon,0.1, {
								  css:{fill:color_hover}
							    },'-=0.6');

								tl.add("in");

								tl.to(hover,0.6, {
								  scaleX:0,
								  transformOrigin:'right top',
								  ease:"power3.out"
							    });

								tl.to($this,0.1, {
								  css:{color:color}
							   },'-=0.6');

							   tl.to(icon,0.1, {
								 css:{fill:color}
							   },'-=0.6');

							   $this.hover(
									function(){
										tl.progress(0);
										tl.tweenTo("in");
									},
									function(){
										tl.play();
									}
								);

							break;

							case 'scale':

								var back = $this.find('.button-back .regular');

								$this.on('mouseover',function(){
									gsap.to(back,0.8, {
										scale:1.05,
										ease:"elastic.out"
									});
								});

								$this.on('mouseout',function(){
									gsap.to(back,0.8, {
										scale:1,
										ease:"expo.out"
									});
								});

							break;

							case 'move':

								$this.on('mousemove',function(e){

									var sxPos =  e.pageX - ($this.width()/2  + $this.offset().left);
									var syPos =  e.pageY - ($this.height()/2 + $this.offset().top);

									gsap.to( $this, 0.4, { 
										x: Math.round(0.1 * sxPos), 
										y: Math.round(0.5 * syPos), 
									});

								});

								$this.on('mouseleave',function(){
									gsap.to( $this, 0.4, { 
										x: 0, 
										y: 0, 
									});
								});

							break;
							
						}

						if ($this.hasClass('click-smooth') && $this.hasClass('modal-false')) {
							$this.unbind('click').on('click',function(){
								gsap.to(window, {
									duration: 1, 
									scrollTo: {y:$this.attr('href')},
									ease:Power3.easeOut 
								});
								return false;
							});
						}

						if (!$this.hasClass('click-smooth') && $this.hasClass('modal-true')) {
							$this.unbind('click').on('click',function(e){
								e.preventDefault();
								gsapLightbox($(this),false);
							});
						}

					});

					$('.et-header-button.submenu-toggle-click').each(function(){
						if ($(this).find('.sub-menu').length) {
							$(this).children('.et-button').removeClass('loading-menu');
						}
					});

					$('.et-header-button.submenu-toggle-hover').each(function(){

						if ($(this).find('.sub-menu').length) {
							$(this).children('.et-button').removeClass('loading-menu');
						}

						$(this).unbind('mouseenter').on('mouseenter',function(){
							if (!$(this).find('.sub-menu').length) {
								$(this).children('.et-button').addClass('loading-menu');
							} else {
								$(this).children('.et-button').removeClass('loading-menu');
							}
						});
					});

				/* et-icon
				----*/
			
					$('.click-true .hicon, .et-icon.click-true').each(function(){

						var $this  		  = $(this),
							iconBack   	  = $this.find('.icon-back path'),
							morphPath 	  = iconBack.data('hover'),
							morphOriginal = iconBack.attr('d'),
							shapeIndex    = 8;

						$this.unbind('mousedown').unbind('touchstart').on('mousedown touchstart',function(){
							gsap.to(iconBack,0.6, {
							  morphSVG:{shape: morphPath, shapeIndex: shapeIndex},
							  ease:'elastic.out'
							});
						});

						$this.unbind('mouseup').unbind('mouseleave').unbind('touchend').on('mouseup mouseleave touchend',function(){
							gsap.to(iconBack,0.6, {
								morphSVG:{shape: morphOriginal, shapeIndex: shapeIndex},
								ease:'elastic.out'
							});
						});

					});

				/* et-heading
				----*/

					$('.et-heading.animate-true').each(function(){

						var $this = $(this),
							delay = '+='+(0.2 + parseInt($this.data('delay'))/1000),
							text  = $this.find('.text');

						if(!$this.hasClass('fired')){

							if (!$this.hasClass('timeline')) {

								var tl = new gsap.timeline({paused: true});

								if ($this.hasClass('curtain')) {

									var curtain = $this.find('.curtain');

									tl.to(curtain,0.8, {
									  scaleX:1,
									  transformOrigin:'left top',
									  ease:"power3.out"
								    },delay);

								    tl.to(curtain,0.8, {
									  scaleX:0,
									  transformOrigin:'right top',
									  ease:"power3.out"
								    });

								    tl.to(text,0.2, {
									  opacity:1,
								    },'-=0.8');
								}

								else if ($this.hasClass('letter')) {
									var letterText = new SplitText($this.find('.text'),{type:"chars"});

									gsap.set($this,{perspective:500});

									tl.from(letterText.chars,{
										duration: 0.2,
									},delay);

									tl.from(letterText.chars,{
										duration: 0.6,
										opacity:0,
										scale:3,
										x:100,
										y:50,
										force3D:true,
										stagger: 0.01,
										ease:"expo.out"
									},'-=0.2');

								}

								else if ($this.hasClass('words')) {

									var wordsText = new SplitText($this.find('.text'),{type:"words"});
									
									gsap.set($this,{perspective:500});

									tl.from(wordsText.words,{
										duration: 0.2,
									},delay);

									tl.from(wordsText.words,{
										duration: 0.8,
										opacity:0,
										scaleY:1.5,
										transformOrigin:'left top',
										y:24,
										force3D:true,
										stagger: 0.04,
										ease:"expo.out"
									},'-=0.2');

								}

								else if ($this.hasClass('rows')) {
									
									var rowsText = new SplitText($this.find('.text'),{type:"lines"});
									
									gsap.set($this,{perspective:1000});

									tl.from(rowsText.lines,{
										duration: 0.4,
									},delay);

									tl.from(rowsText.lines,{
										duration: 1.2,
										opacity:0,
										rotationX:8,
										rotationY:-50,
										rotationZ:8,
										y:50,
										x:-50,
										z:50,
										transformOrigin:'left top',
										force3D:true,
										stagger: 0.08,
										ease:"expo.out"
									},'-=0.2');

								}

								$this.addClass('timeline');

								$this.waypoint({
					                handler: function(direction) {

					                	tl.progress(0);
										tl.play();

					                    this.destroy();

					                    $this.addClass('fired');

					                },
					                offset: 'bottom-in-view'
					            });

						}

						}

					});

				/* et-icon-list
				----*/
			
					$('.et-icon-list.animate-true').each(function(){

						var $this = $(this),
							delay = '+='+(0.2 + parseInt($this.data('delay'))/1000);

						if(!$this.hasClass('fired')){

							var tl = new gsap.timeline({paused: true});

							tl.to($this.find('li'),{
								duration: 0.8,
								x:0,
								opacity:1,
								stagger: 0.1,
								ease:"expo.out"
							},delay);

							$this.waypoint({
				                handler: function(direction) {

				                	tl.progress(0);
									tl.play();

				                    this.destroy();

				                    $this.addClass('fired');

				                },
				                offset: 'bottom-in-view'
				            });

						}

					});
			
				/* et-accordion
				----*/
			
					$('.et-accordion').each(function(){

						var $this = $(this);

						gsap.set($this.find('.toggle-title.active').next(),{
							opacity: 1,
		    				height: 'auto'
						});

						$this.find('.toggle-content-group').on('click', function(){
							if (!$(this).hasClass('active')) {
								$(this).find('.toggle-title').trigger('click');
							}
						});

						$this.find('.toggle-title').unbind('click').on('click', function(e){

							e.stopPropagation();

							var $self = $(this);

								if(!$self.hasClass('active')){
									if($this.hasClass('collapsible-true')){

										$self.addClass("active");
										$self.parent().siblings().find('.active').removeClass("active");
										$self.parent().addClass("active").siblings().removeClass("active");

										gsap.to($self.next(),0.6, {
											height:'auto',
											ease:"expo.out"
									  	});

									  	gsap.to($self.next(),0.2, {
											opacity:1,
									  	});

									  	gsap.to($this.find('.toggle-content').not($self.next()),0.1, {
											opacity:0,
									  	});

										gsap.to($this.find('.toggle-content').not($self.next()),0.6, {
											height:0,
											ease:"expo.out"
									  	});

									} else {
										$self.addClass("active");
										$self.parent().addClass("active");

										gsap.to($self.next(),0.6, {
											height:'auto',
											ease:"expo.out"
									  	});

									  	gsap.to($self.next(),0.2, {
											opacity:1,
									  	});

									}
								} else {
									if(!$this.hasClass('collapsible-true')){
										$self.removeClass("active");
										$self.parent().removeClass("active");

										gsap.to($self.next(),0.1, {
											opacity:0,
									  	});

										gsap.to($self.next(),0.6, {
											height:0,
											ease:"expo.out"
									  	});
									}
								}

						});

						

					});

				/* et-tabs
				----*/

					$('.et-tab').each(function(){

						var $this    = $(this),
							tabs     = $this.find('.tab'),
							tabsQ    = tabs.length,
							tabsDefaultWidth  = 0,
							tabsDefaultHeight = 0,
							tabsContent = $this.find('.tab-content');

						if (!$this.find('.tabset').length) {
							tabs.wrapAll('<div class="tabset et-clearfix"></div>');
							tabsContent.wrapAll('<div class="tabs-container et-clearfix"></div>');
							var tabSet = $this.find('.tabset');
							if(!tabSet.find('.active').length){
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

								tabsDefaultWidth += $(this).outerWidth();
								tabsDefaultHeight += $(this).outerHeight();
							});

							if(tabsQ >= 2){

								tabs.unbind('click').on('click', function(){
									var $self = $(this);
									
									if(!$self.hasClass("active")){

										$self.addClass("active");

										$self.siblings()
										.removeClass("active");

										tabsContent.hide(0).removeClass('active');
										tabsContent.eq($self.index()).show(0).addClass('active');
									}
									
								});
							}

							if(tabsDefaultWidth >= $this.outerWidth()  && $this.hasClass('horizontal')){
				                $this.addClass('tab-full');
				            } else {
				                $this.removeClass('tab-full');
				            }

			            }			

					});

				/* et-animate-box
				----*/

					$('.et-animate-box').each(function(){

						var element   = this,
							$this     = $(element),
							id        = $this.attr('id'),
							delay     = '+='+(0.2 + parseInt($this.data('delay'))/1000),
							animation = $this.data('animation'),
							offset    = (animation == 'bottom') ? '100%': '70%',
							stagger   = $this.data('stagger'),
							content   = $this.find('.content').children();

						if(!$this.hasClass('fired')){

							animateBoxBack(element);

							var tl = new gsap.timeline({paused: true});

							buildAnimateBoxTimeline(tl,$this,delay,animation,stagger,content);

							$this.waypoint({
				                handler: function(direction) {

				                	$this.addClass('active');

				                	tl.progress(0);
									tl.play();

				                    this.destroy();

				                    $this.addClass('fired');

				                },
				                offset: offset
				            });

				            $(window).resize(function(){

				            	setTimeout(function(){

					            	element = document.getElementById(id);

					            	$this = $('#'+id);

					            	animateBoxBack(element);

					            	if (!$this.hasClass('active')) {

						            	var startC = $this.find('.box-back path').attr('data-dclone');
										$this.find('.box-back path').attr('d',startC);

						            	tl.seek('end').kill();

						            	tl = new gsap.timeline({paused: true});

						            	buildAnimateBoxTimeline(tl,$this,delay,animation,stagger,content);

						            	$this.waypoint({
							                handler: function(direction) {

							                	tl.progress(0);
												tl.play();

							                    this.destroy();

							                },
							                offset: offset
							            });

						            }

					            },50);

							});

			        }

					});

				/* et-stagger-box
				----*/

					$('.et-stagger-box').each(function(){

						var element   = this,
							$this     = $(element),
							id        = $this.attr('id'),
							delay     = '+='+(0.2 + parseInt($this.data('delay'))/1000),
							interval  = parseInt($this.data('interval'))/1000,
							stagger   = $this.data('stagger'),
							content   = $this.find('.content').children().not('.et-gap');

						if(!$this.hasClass('fired')){

							if (!$this.hasClass('timeline')) {
								var tl = new gsap.timeline({paused: true});
								buildStaggerBoxTimeline(tl,delay,interval,stagger,content);
								$this.addClass('timeline');
								$this.waypoint({
					                handler: function(direction) {

					                	$this.addClass('active');

					                	tl.progress(0);
										tl.play();

					                    this.destroy();

					                    $this.addClass('fired');

					                },
					                offset: '70%'
					            });

				            }

						}

					});

				/* et-content-box
				----*/

					$('.et-icon-box').each(function(){

						var $this  = $(this),
							effect = $this.data('effect');

						if (effect == "scale") {

							var back = $this.find('.et-icon .icon-back');

							$this.unbind('mouseover').on('mouseover',function(){
								gsap.to(back,0.8, {
									scale:1.2,
									ease:"elastic.out"
								});
							});

							$this.unbind('mouseout').on('mouseout',function(){
								gsap.to(back,0.8, {
									scale:1,
									ease:"expo.out"
								});
							});

						} else if(effect == "transform"){

							$this.unbind('mouseover').on('mouseover',function(){
								gsap.to($this,0.4, {
									y:-24,
									ease:"expo.out"
								});
							});

							$this.unbind('mouseout').on('mouseout',function(){
								gsap.to($this,0.4, {
									y:0,
									ease:"expo.out"
								});
							});

						}

					});

					$('.et-icon-box-container').each(function(){

						var $this 	  	     = $(this),
							animation 	     = $this.data('animation'),
							total            = $this.find('.et-icon-box').length;

						if (animation != "none") {

							$this.find('.et-icon-box').each(function(){

								var box     = $(this),
									delay   = (0.2 + box.index()*0.1),
									columns = box.data('columns');

									if(!box.hasClass('fired')){

										var tl = new gsap.timeline({paused: true});

										switch(animation){
											case 'fade':
												tl.to(box,{
													duration:0.4,
													delay:delay,
													opacity:1,
												});
											break;
											case 'appear':
												if (columns == 1) {

													tl.to(box,{
														duration:0.8,
														delay:delay,
														opacity:1,
														x:0,
														ease:"expo.out"
													});

												} else {

													tl.to(box,{
														duration:0.8,
														delay:delay,
														opacity:1,
														y:0,
														ease:"expo.out"
													});

												}
											break;
										}

										box.waypoint({
							                handler: function(direction) {

							                	box.addClass('active');

							                	tl.progress(0);
												tl.play();

							                    this.destroy();

											    box.addClass('fired');

							                },
							                offset: 'bottom-in-view'
							            });

									}

							});

						}

					});

				/* et-step-box
				----*/

					$('.et-step-box').each(function(){

						var $this = $(this),
							delay = (0.2 + $this.index()*0.05);

						$this.find('.step-count').text('0.'+($this.index()+1));

						if(!$this.hasClass('fired')){

							if (!$this.hasClass('timeline')) {

								var tl = new gsap.timeline({paused: true});

								tl.from($this,{
									duration:0.8,
									delay:delay,
									opacity:0,
									y:40,
									ease:"expo.out"
								});

								$this.addClass('timeline');

								$this.waypoint({
					                handler: function(direction) {

					                	$this.addClass('active');

					                	tl.progress(0);
										tl.play();

					                    this.destroy();

					                    $this.addClass('fired');

					                },
					                offset: 'bottom-in-view'
					            });

							}
			            }

					});
				
				/* et-image
				----*/

					$('.et-image').each(function(){

						var $this = $(this);

						if ($this.hasClass('animate-true')) {

							if(!$this.hasClass('fired')){

								var delay = '+='+(0.2 + parseInt($this.data('delay'))/1000),
									animation = $this.data('animation');

								var tl = new gsap.timeline({paused: true});

								if (
									animation == "curtain-left" || 
									animation == "curtain-right" || 
									animation == "curtain-top" || 
									animation == "curtain-bottom"
								) {

									var curtain = $this.find('.curtain');

									switch(animation){
										case "curtain-left":

											tl.to(curtain,0.8, {
											  scaleX:1,
											  transformOrigin:'left top',
											  ease:"power3.out"
										    },delay);

										    tl.to(curtain,0.8, {
											  scaleX:0,
											  transformOrigin:'right top',
											  ease:"power3.out"
										    });

										break;

										case "curtain-right":

											tl.to(curtain,0.8, {
											  scaleX:1,
											  transformOrigin:'right top',
											  ease:"power3.out"
										    },delay);

										    tl.to(curtain,0.8, {
											  scaleX:0,
											  transformOrigin:'left top',
											  ease:"power3.out"
										    });
										
										break;

										case "curtain-top":

											tl.to(curtain,0.8, {
											  scaleY:1,
											  transformOrigin:'left top',
											  ease:"power3.out"
										    },delay);

										    tl.to(curtain,0.8, {
											  scaleY:0,
											  transformOrigin:'left bottom',
											  ease:"power3.out"
										    });
										
										break;

										case "curtain-bottom":

											tl.to(curtain,0.8, {
											  scaleY:1,
											  transformOrigin:'left bottom',
											  ease:"power3.out"
										    },delay);

										    tl.to(curtain,0.8, {
											  scaleY:0,
											  transformOrigin:'left top',
											  ease:"power3.out"
										    });
										
										break;
									}

								    tl.to($this.find('img'),0.2, {
									  opacity:1,
								    },'-=0.8');
								}

								if (animation == "fade-blur") {

									tl.to($this,{
										duration: 0.6,
										opacity:1,
									},delay);
								}

								if (animation == "left") {
									
									tl.to($this,{
										duration: 0.8,
										opacity:1,
										x:0,
										transformOrigin:'left top',
										force3D:true,
										ease:"expo.out"
									},delay);

								}

								if (animation == "right") {
									
									tl.to($this,{
										duration: 0.8,
										opacity:1,
										x:0,
										transformOrigin:'left top',
										force3D:true,
										ease:"expo.out"
									},delay);

								}

								if (animation == "top") {
									
									tl.to($this,{
										duration: 0.8,
										opacity:1,
										y:0,
										transformOrigin:'left top',
										force3D:true,
										ease:"expo.out"
									},delay);

								}

								if (animation == "bottom") {
									
									tl.to($this,{
										duration: 0.8,
										opacity:1,
										y:0,
										transformOrigin:'left top',
										force3D:true,
										ease:"expo.out"
									},delay);

								}

								$this.waypoint({
					                handler: function(direction) {

					                	tl.progress(0);
										tl.play();

					                    this.destroy();

					                    $this.addClass('fired');

					                },
					                offset: '70%'
					            });

							}

						}

						if ($this.hasClass('parallax')) {

							var x     = $this.data('coordinatex'),
		                        y     = $this.data('coordinatey'),
		                        limit = $this.data('limit');

		                    if (typeof(limit) == 'undefined') {limit = 0}

							$(window).scroll(function(){

								if (!$this.hasClass('parallax-off')) {

									var yPos   = Math.round((0-$(window).scrollTop()) / $this.data('speed'))  +  y;
									var scroll = (Math.sign(y) == -1) ? Math.round((0-$(window).scrollTop()) / $this.data('speed')) : yPos;

									if (Math.abs(scroll) > limit && limit > 0) {
										yPos = (Math.sign(y) == -1) ? Math.sign(yPos)*(limit+Math.abs(y)) : Math.sign(yPos)*limit;
									}

									gsap.to($this,0.8,{
										x:x,
										y:yPos,
										force3D:true,
									});

								} else {
									$this.removeAttr('style');
								}

							});
						}
						
					});

					disableParallax();

				/* et-gallery
				----*/

					$('.et-gallery').each(function(){

						var $this = $(this);

						$this.find('a').on('click',function(e){
							e.preventDefault();
							gsapLightbox($(this),true);
						});

						if ($this.hasClass('slider')) {
							var slider = tns({
								container: this.querySelector('.slides'),
								mode:'gallery',
								nav:false,
								items: 1,
							});

							var lazyImage = $this.find('.slides .et-gallery-item:last-child img.lazy');

							lazyImage.attr('src',lazyImage.data('src')).removeClass('lazy');
							lazyImage.parent().addClass('loaded');

						}

					});

				/* et-carousel
				----*/

					$('.et-carousel > .slides').each(function(){

						if (!$(this).parents('.et-carousel').find('.tns-outer').length) {

							var $this    = $(this),
								woo      = $this.parent().parent(),
								items    = $this.parent().data('columns'),
								items767 = 1,
				 				items768 = (items > 2 && $this.parent().hasClass('et-testimonial-container')) ? 2 : (items > 3) ? 3 : items,
								items1024= (items > 3 && $this.parent().hasClass('et-testimonial-container')) ? 3 : items,
								gatter   = 24,
								loop     = false,
								autoplay = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
								nav      = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows';

							if ($this.parent().hasClass('et-testimonial-container')) {loop=true;}

							if ($this.parent().hasClass('loop-posts') && (items >= 3)) {items768 = 2;}
							if ($this.parent().hasClass('related-posts') && (items >= 3)) {gatter = 24;}

							if ($this.parent().hasClass('loop-products')) {
								gatter    = (woo.hasClass('list')) ? 0 : 24;
								items768  = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-port') : items;
								items1024 = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-land') : items;
								items767  = (woo.hasClass('list') || woo.hasClass('full')) ? 1 : 2;

								if (woo.hasClass('full')) {
									items768 = 1;
									items1024 = 1;
								}
							}

							if ($this.parent().hasClass('et-gallery')) {gatter = 12;}
							if ($this.parent().hasClass('et-client-container')) {gatter = 0;}

							var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
								arrows  = (nav == 'dottes') ? false : true;

							var slider = tns({
								container: this,
								mode:'carousel',
								controlsPosition:'bottom',
								navPosition:'bottom',
								gutter:gatter,
								autoplay:autoplay,
								autoplayButtonOutput:false,
								touch:true,
								mouseDrag:true,
								nav:bullets,
								controls:arrows,
								loop:loop,
								items: items,
								responsive: {
									320: {items: items767},
									768: {items:items768},
									1024:{items:items1024},
									1280:{items:items}
								}
							});

						}

					});


					$('.insta-placeholder-grid').each(function(){

						if (!$(this).parents('.et-carousel').find('.tns-outer').length) {

							var $this    = $(this),
								items    = $this.parent().data('columns'),
								items767 = 2,
				 				items768 = (items > 3) ? 3 : items,
								items1024= (items > 4) ? 4 : items,
								loop     = true;

							var slider = tns({
								container: this,
								mode:'carousel',
								controlsPosition:'bottom',
								navPosition:'bottom',
								gutter:24,
								autoplay:false,
								autoplayButtonOutput:false,
								touch:true,
								mouseDrag:true,
								nav:false,
								controls:true,
								loop:false,
								items: items,
								responsive: {
									320: {items: items767},
									768: {items:items768},
									1024:{items:items1024},
									1280:{items:items}
								}
							});
						}

					});

				/* mailchimp
				----*/

				    $('.et-mailchimp-form').each(function(){

				        var valid = "invalid";
				        var $this = $(this);

				        $this.next('.terms').find('a').on('click',function(e){
				            e.stopPropagation();
				        });

				        $this.next('.terms').unbind('click').on('click',function(){
				            $(this).toggleClass('active');
				            $this.find(".et-mailchimp-terms-error").removeClass('visible');
				        });

				        $this.unbind('submit').submit(function(event) {

				            event.preventDefault();

				            var formData = $this.serialize();

				            var email   = $this.find("input[name='email']"),
				                fname   = $this.find("input[name='fname']"),
				                lname   = $this.find("input[name='lname']"),
				                phone   = $this.find("input[name='phone']"),
				                list    = $this.find("input[name='list']");

				            var terms = $this.next('.terms');

				            if (terms.length && !terms.hasClass('active')) {
				                $this.find(".et-mailchimp-terms-error").addClass('visible');
				                setTimeout(function(){
				                    $this.find(".et-mailchimp-terms-error").removeClass('visible');
				                },2000);

				                return;
				            }

				            var emailValue = email.val();
				            var n = emailValue.indexOf("@");
				            var r = emailValue.lastIndexOf(".");
				            if (n < 1 || r < n + 2 || r + 2 >= emailValue.length) {
				                valid =  "invalid";
				            } else {
				                valid = "valid";
				            }

				            validateValue(email.val(), email.next(".alert"), email.attr('data-placeholder'), true, valid);

				            if (fname.length && fname.attr('data-required') == "true") {validateValue(fname.val(), fname.next(".alert"), fname.attr('data-placeholder'), false);}
				            if (lname.length && lname.attr('data-required') == "true") {validateValue(lname.val(), lname.next(".alert"), lname.attr('data-placeholder'), false);}
				            if (phone.length && phone.attr('data-required') == "true") {validateValue(phone.val(), phone.next(".alert"), phone.attr('data-placeholder'), false);}

				            if (email.val() != email.attr('data-placeholder') && valid == "valid"){

				                if(fname.length && fname.attr('data-required') == "true" && fname.val() == fname.attr('data-placeholder')){event.preventDefault();}
				                if(lname.length && lname.attr('data-required') == "true" && lname.val() == lname.attr('data-placeholder')){event.preventDefault();}
				                if(phone.length && phone.attr('data-required') == "true" && phone.val() == phone.attr('data-placeholder')){event.preventDefault();}

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

				        $this.find('input').on('focus',function(){
				            $(this).next('.visible').removeClass('visible');
				        });
				        
				    });

				/* banner-image
				----*/

					$('.banner-image.parallax').each(function(){

						var $this = $(this),
							x     = $this.data('coordinatex'),
		                    y     = $this.data('coordinatey'),
		                    limit = $this.data('limit');

		                if (typeof(limit) == 'undefined') {limit = 0}

						$(window).scroll(function(){

							if (!$this.hasClass('parallax-off')) {

								var yPos   = Math.round((0-$(window).scrollTop()) / $this.data('speed'))  +  y;
								var scroll = (Math.sign(y) == -1) ? Math.round((0-$(window).scrollTop()) / $this.data('speed')) : yPos;

								if (Math.abs(scroll) > limit && limit > 0) {
									yPos = (Math.sign(y) == -1) ? Math.sign(yPos)*(limit+Math.abs(y)) : Math.sign(yPos)*limit;
								}

								gsap.to($this,0.8,{
									x:x,
									y:yPos,
									force3D:true,
								});

							} else if(!$this.hasClass('resp')) {
								$this.removeAttr('style');
							}

						});
						
					});

				/* et-counter
				----*/

					$('.et-counter').each(function(){

						var $this    = $(this),
							dDelay   = $this.data('delay'),
							delay    = (dDelay) ? dDelay/1000 : (0.2 + $this.index()*0.01),
							value    = $this.data('value'),
							counterV = { var: 0 },
							counter  = $this.find('.counter');

						if (!$this.hasClass('fired')) {

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
								opacity:0.2,
							},'-=0.6');

							tl.to($this.find('.counter-icon'),{
								duration: 1.6,
								scale:1,
								force3D:true,
								ease:"elastic.out"
							},'-=0.6');

							$this.waypoint({
				                handler: function(direction) {

				                	$this.addClass('active');

				                	tl.progress(0);
									tl.play();

				                    this.destroy();

				                    $this.addClass('fired');

				                },
				                offset: 'bottom-in-view'
				            });

			            }


					});

				/* et-progress
				----*/

					$('.et-progress').each(function(){

						var $this    = $(this),
							type     = ($this.hasClass('circle')) ? 'circle' : 'default',
							delay    = (0.2 + $this.index()*0.01),
							value    = $this.data('percentage'),
							counterV = { var: 0 },
							counter  = $this.find('.percent');

						if (!$this.hasClass('fired')) {

							if (!$this.hasClass('timeline')) {

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

								$this.addClass('timeline');

								$this.waypoint({
					                handler: function(direction) {

					                	$this.addClass('active');

					                	tl.progress(0);
										tl.play();

					                    this.destroy();

					                    $this.addClass('fired');

					                },
					                offset: 'bottom-in-view'
					            });

							}
						}

					});

				/* et-timer
				----*/

					$('.et-timer').each(function(){

						var $this   = $(this),
							extend  = $this.data('number'),
							enddate = $this.data('enddate'),
							gmt     = $this.data('gmt'),
							reset   = (typeof(extend) != 'undefined' && extend != null) ? true : false,
							gmt     = (typeof(gmt) != 'undefined' && gmt != null) ? gmt : 0;

						if (!$this.hasClass('fired')) {

							var today   = new Date();
							var enddate = new Date(enddate);

							if (reset && today >= enddate) {
								enddate = new Date();
		  						enddate.setDate(enddate.getDate() + extend);
							}

				            $this.find('ul').countdown({
				                date: enddate,
				                offset: $this.data('gmt'),
				            });

				            $this.addClass('fired');

			            }

					});

				/* et-woo-categories
				----*/


					$('.et-woo-categories.carousel-true > ul').each(function(){

						var $this    = $(this),
							items    = $this.parent().attr('data-columns-desktop'),
							items767 = $this.parent().attr('data-columns-mob'),
			 				items768 = $this.parent().attr('data-columns-tab-port'),
							items1024= $this.parent().attr('data-columns-tab-land'),
							gatter   = ($this.parent().hasClass('gap-true')) ? 32 : 12,
							autoplay = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
							nav      = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows';

						$this.parent().addClass('manual-carousel');

						var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
							arrows  = (nav == 'dottes') ? false : true;

						var slider = tns({
							container: this,
							mode:'carousel',
							controlsPosition:'bottom',
							navPosition:'bottom',
							gutter:gatter,
							autoplay:autoplay,
							autoplayButtonOutput:false,
							touch:true,
							mouseDrag:true,
							nav:bullets,
							controls:arrows,
							loop:false,
							items: items,
							responsive: {
								320: {items: items767},
								768: {items:items768},
								1024:{items:items1024},
								1280:{items:items}
							}
						});

					});

				/* et-shortcode-posts
				----*/

					$('.et-shortcode-posts.comp').each(function(){

						var $this  = document.getElementById($(this).attr('id'));
						var	slides = $this.querySelector('.slides'),
							autoplay = ($(this).data('autoplay')) ? $(this).data('autoplay') : false;

						var slider = tns({
							container: slides,
							mode:'carousel',
							controlsPosition:'bottom',
							navPosition:'bottom',
							gutter:0,
							autoplayButtonOutput:false,
							autoplay:autoplay,
							touch:true,
							mouseDrag:true,
							nav:false,
							controls:true,
							loop:false,
							items: 1
						});

					});
			}

			function productCarousel(products){
				$('#'+products).find('.slides').each(function(){

					var $this     = $(this),
						woo       = $this.parent().parent(),
						items     = $this.parent().data('columns'),
						items767  = (woo.hasClass('list') || woo.hasClass('full')) ? 1 : 2,
		 				items768  = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-port') : items,
						items1024 = (woo.hasClass('list') || woo.hasClass('grid')) ? woo.data('columns-tab-land') : items,
						gatter    = (woo.hasClass('list')) ? 0 : 24,
						autoplay  = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
						nav       = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows';

						if(woo.hasClass('full')){
							items768 = 1;
							items1024 = 1;
						}

						if (items == 1) {
							items767 = 1;
						}


					var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
						arrows  = (nav == 'dottes') ? false : true;

					var slider = tns({
						container: this,
						mode:'carousel',
						controlsPosition:'bottom',
						navPosition:'bottom',
						gutter:gatter,
						autoplay:autoplay,
						autoplayButtonOutput:false,
						touch:true,
						mouseDrag:true,
						nav:bullets,
						controls:arrows,
						loop:false,
						items: items,
						responsive: {
							320: {items: items767},
							768: {items:items768},
							1024:{items:items1024},
							1280:{items:items}
						}
					});

				});
			}

			function postCarousel(posts){

				var full = $('#'+posts).hasClass('comp') ? true : false;

				if (full) {

					var $this  = document.getElementById(posts);
					var	slides = $this.querySelector('.slides'),
						autoplay = ($(this).data('autoplay')) ? $(this).data('autoplay') : false;


					var slider = tns({
						container: slides,
						mode:'carousel',
						controlsPosition:'bottom',
						navPosition:'bottom',
						gutter:0,
						autoplayButtonOutput:false,
						autoplay:autoplay,
						touch:true,
						mouseDrag:true,
						nav:false,
						controls:true,
						loop:false,
						items: 1
					});

				} else {

					$('#'+posts).find('.slides').each(function(){

						var $this    = $(this),
							items    = $this.parent().data('columns'),
							items767 = 1,
			 				items768 = (items >= 3) ? 2 : items,
							items1024= items,
							gatter   = 24,
							autoplay = ($this.parent().data('autoplay')) ? $this.parent().data('autoplay') : false,
							nav      = ($this.parent().data('nav')) ? $this.parent().data('nav') : 'arrows';

						var bullets = (nav == 'both' || nav == 'dottes') ? true : false,
							arrows  = (nav == 'dottes') ? false : true;

						var slider = tns({
							container: this,
							mode:'carousel',
							controlsPosition:'bottom',
							navPosition:'bottom',
							gutter:gatter,
							autoplay:autoplay,
							autoplayButtonOutput:false,
							touch:true,
							mouseDrag:true,
							nav:bullets,
							controls:arrows,
							loop:false,
							items: items,
							responsive: {
								320: {items: items767},
								768: {items:items768},
								1024:{items:items1024},
								1280:{items:items}
							}
						});

					});

				}
			}

			function afterProductsFetch(id = false){
				ajaxAddToCart();
				listImages();
				if (id) {
					lazyLoad(document.getElementById(id));
					productCarousel(id);
				}
				quickView();
			}

			function afterPostFetch(id = false){
				listImages();
				if (id) {
					lazyLoad(document.getElementById(id));
					postCarousel(id);
				}
			}

			const requestProducts = async (url,args) => {
				const response = await fetch(
					url,{method: 'POST',body: JSON.stringify(args)}
				);
				const json = await response.json();
				if (!$.isEmptyObject(json['data'])) {
					Object.entries(json['data']).forEach(entry => {
						const [key, value] = entry;
						jQuery('#'+key).replaceWith(value);
		            	lazyLoad(document.getElementById(key));
						productCarousel(key);
					});
					afterProductsFetch();
				}
			}

			const requestPosts = async (url,args) => {
				const response = await fetch(
					url,{method: 'POST',body: JSON.stringify(args)}
				);
				const json = await response.json();
				if (!$.isEmptyObject(json['data'])) {
					Object.entries(json['data']).forEach(entry => {
						const [key, value] = entry;
					  	jQuery('#'+key).replaceWith(value);
		            	lazyLoad(document.getElementById(key));
						postCarousel(key);
					});
		            afterPostFetch();
	        	}
			}

			function productLoad(){

				let products = [].slice.call(document.querySelectorAll(".ajax.only.et-woo-products"));
			    if (typeof(products) != 'undefined' && products.length) {

			    	var ajaxCalls = [];
			    	var queryID   = '';
			    	
			    	products.forEach(function(item, index) {
						let woo   = item,
							id    = woo.getAttribute('id'),
							atts  = woo.getAttribute('data-atts'),
							query = woo.getAttribute('data-query'),
							args  = [];

						args.push(id);
						args.push(atts);
						args.push(query);

						queryID+=id.replace('et-woo-products-','');

						ajaxCalls.push(args.join('|'));
						
					});

					var args = {};

					args['action']   = 'woo_products_ajax';
					args['ajax']     = 'true';
					if (ajaxCalls.length) {
						args['ajax_calls'] = ajaxCalls.join(",");
					}

					if ($('body').hasClass('cache-queries')) {
						var url = $('body').data('url')+'/ajax-api/product-query/'+queryID;
						requestProducts(url,args);
					} else {

						jQuery.ajax({
				            url:controller_opt.ajaxUrl,
				            type: 'post',
				            data: args,
				            success: function(output) {
				            	output = JSON.parse(output);

				            	Object.entries(output).forEach(entry => {
								  const [key, value] = entry;
								  jQuery('#'+key).replaceWith(value);
				                	lazyLoad(document.getElementById(key));
									productCarousel(key);
								});
								afterProductsFetch();
				            },
				            error:function () {
				                console.log(controller_opt.wooError);
				            }
				        });

			        }

			    }

			}

			function postLoad(){

				let posts = [].slice.call(document.querySelectorAll(".ajax.et-shortcode-posts"));

				if (typeof(posts) != 'undefined' && posts.length) {

					var ajaxCalls = [];
					var queryID   = '';

			    	posts.forEach(function(item, index) {
						let postList   = item,
							id         = postList.getAttribute('id'),
							atts       = postList.getAttribute('data-atts'),
							query      = postList.getAttribute('data-query'),
							args       = [];

						args.push(id);
						args.push(atts);
						args.push(query);

						ajaxCalls.push(args.join('|'));

						queryID+=id.replace('et-posts-','');

					});

					var args = {};

					args['action']   = 'et_posts_ajax';
					args['ajax']     = 'true';
					if (ajaxCalls.length) {
						args['ajax_calls'] = ajaxCalls.join(",");
					}

					if ($('body').hasClass('cache-queries')) {
						var url = $('body').data('url')+'/ajax-api/post-query/'+queryID;
						requestPosts(url,args);
					} else {

						jQuery.ajax({
				            url:controller_opt.ajaxUrl,
				            type: 'post',
				            data: args,
				            success: function(output) {
				                output = JSON.parse(output);

				            	Object.entries(output).forEach(entry => {
								  const [key, value] = entry;
								  jQuery('#'+key).replaceWith(value);
				                	lazyLoad(document.getElementById(key));
									postCarousel(key);
								});
				                afterPostFetch();
				            },
				            error:function () {
				                console.log(controller_opt.postError);
				            }
				        });

					}
			    }

			}

			const requestMegamenus = async (url,args) => {

				const response = await fetch(
					url,{method: 'POST',body: JSON.stringify(args)}
				);
				const json = await response.json();

				if (!$.isEmptyObject(json['data'])) {

					Object.entries(json['data']).forEach(entry => {
						const [key, value] = entry;
						$('.mm-true[data-megamenu="'+key+'"]').append(value);
						$('.mm-true[data-megamenu="'+key+'"]').find('.megamenu').unwrap('.wpb-content-wrapper');
					});

					megamenuTab();
					megamenuPosition();
					megamenuGrid();
					etElements();
					videoTrigger();
					megamenuHoverCheck();

					if ($('.et-desktop .et-woo-products').length || $('.et-menu .et-woo-products').length) {
						ajaxAddToCart();
						listImages();
						quickView();
					}

					var id = $('.et-desktop').attr('id');
					lazyLoad(document.getElementById(id));

					$('.et-menu').each(function(){
						var id = $(this).attr('id');
						lazyLoad(document.getElementById(id));
					});
	        	}
			}

			function megamenuAjax(megamenues){

				if ($('body').hasClass('cache-queries')) {
					var url  = $('body').data('url')+'/ajax-api/megamenu-query/'+megamenues.join('');
					var args = {};
					args['megamenues'] =  megamenues.join('|');
					requestMegamenus(url,args);
				} else {

					$.ajax({
		                url:controller_opt.ajaxUrl,
		                type: 'post',
		                data: {
		                	action:'megamenu_load',
		                	megamenues:megamenues.join('|'),
		                },
		                success: function(data) {

		                	if (!$.isEmptyObject(data)) {

			                	Object.entries(data).forEach(entry => {
									const [key, value] = entry;
									$('.mm-true[data-megamenu="'+key+'"]').append(value);
									$('.mm-true[data-megamenu="'+key+'"]').find('.megamenu').unwrap('.wpb-content-wrapper');
								});

								etElements();
								megamenuTab();
								megamenuPosition();
								megamenuGrid();
								videoTrigger();
								megamenuHoverCheck();

								if ($('.et-desktop .et-woo-products').length) {
									ajaxAddToCart();
									listImages();
									quickView();
								}

								var id = $('.et-desktop').attr('id');
								lazyLoad(document.getElementById(id));
								
							}

						},
						error: function(data){
							console.log('Something went wrong, please contact site administrator');
						}
		            });

	            }
			}

			const requestFooter = async (url,args) => {

				const response = await fetch(
					url,{method: 'POST',body: JSON.stringify(args)}
				);
				const json = await response.json();

				if (!$.isEmptyObject(json['data'])) {

	    			$('#footer-placeholder-'+Object.keys(json['data'])[0]).replaceWith(Object.values(json['data'])[0]);

					etElements();
					videoTrigger();
					lang_curr_Toggles();

					if ($('.et-footer .et-woo-products').length) {
						ajaxAddToCart();
						listImages();
						quickView();
					}

					var id = $('.et-footer').attr('id');
					lazyLoad(document.getElementById(id));

					stickyFooter();

	        	}
			}

			function footerAjax(footer){

				if ($('body').hasClass('cache-queries')) {
					var url  = $('body').data('url')+'/ajax-api/footer-query/'+footer;
					var args = {};
					args['footer'] =  footer;
					requestFooter(url,args);
				} else {
				
					$.ajax({
		                url:controller_opt.ajaxUrl,
		                type: 'post',
		                data: {
		                	action:'footer_load',
		                	footer:footer,
		                },
		                success: function(data) {

		                	if (!$.isEmptyObject(data)) {

		                		if (Object.values(data)[0]) {
		                			$('#footer-placeholder-'+Object.keys(data)[0]).replaceWith(Object.values(data)[0]);

									etElements();
									videoTrigger();
									lang_curr_Toggles();

									if ($('.et-footer .et-woo-products').length) {
										ajaxAddToCart();
										listImages();
										quickView();
									}

									var id = $('.et-footer').attr('id');
									lazyLoad(document.getElementById(id));

									stickyFooter();

								}
								
							}

						},
						error: function(data){
							console.log('Something went wrong, please contact site administrator');
						}
		            });

	            }
			}

			const requestMobile = async (url,args) => {

				const response = await fetch(
					url,{method: 'POST',body: JSON.stringify(args)}
				);
				const json = await response.json();
				if (!$.isEmptyObject(json['data'])) {
	    			$('#'+Object.keys(json['data'])[0]).find('.mobile-container-inner').html(Object.values(json['data'])[0]);
	    			mobileContainerTabs();
	    			lang_curr_Toggles();
	    			mobileNavigation();
					lazyLoad(document.getElementById(Object.keys(json['data'])[0]));
	        	}
			}

			function mobileAjax(mobile){

				var args = {};
					args['mobile']  =  mobile;
					args['content'] =  $('#'+mobile).find('.mobile-container').data('content');

				if ($('body').hasClass('cache-queries')) {
					var url  = $('body').data('url')+'/ajax-api/mobile-query/'+mobile.replace('et-mobile-', '');
					
					requestMobile(url,args);
				} else {
				
					$.ajax({
		                url:controller_opt.ajaxUrl,
		                type: 'post',
		                data: {
		                	action:'mobile_load',
		                	args:args,
		                },
		                success: function(data) {

		                	if (!$.isEmptyObject(data)) {

		                		if (Object.values(data)[0]) {
		                			$('#'+Object.keys(data)[0]).find('.mobile-container-inner').html(Object.values(data)[0]);
		                			mobileContainerTabs();
		                			mobileNavigation();
		                			lang_curr_Toggles();
									lazyLoad(document.getElementById(mobile));
								}
								
							}

						},
						error: function(data){
							console.log('Something went wrong, please contact site administrator');
						}
		            });

	            }
			}

			/* et-popup-banner
			----*/

				$('.et-popup-banner-wrapper').each(function(){

					var $this  = $(this);
					var	$delay = $this.find('.et-popup-banner').attr('data-delay');
					var cookie = $this.find('.et-popup-banner').attr('data-cookie');

					var bannerClone = $this.clone();

					$('body').append(bannerClone);

					$this.remove();

					bannerClone.find('a').on('click',function(event){
						event.stopPropagation();
					});

					if (typeof($.cookie(bannerClone.attr('id'))) == 'undefined') {

						setTimeout(function(){
							bannerClone.addClass('animate');
						},$delay);

						bannerClone.find('.popup-banner-toggle').bind('click',function(){
							bannerClone.removeClass('animate');
							if (cookie == 'true') {
								$.cookie(bannerClone.attr('id'),'active',{ expires: 1,path: '/'});
							}
						});

					}

				});

			/* et-toggle-banner
			----*/

				$('.et-toggle-banner-wrapper').each(function(){

					var $this  = $(this);
					var cookie = $this.find('.et-toggle-banner').attr('data-cookie');

					$this.find('a').on('click',function(event){
						event.stopPropagation();
					});

					if (typeof($.cookie($this.attr('id'))) == 'undefined') {

						$this.addClass('animate');
						
						$this.find('.toggle-banner-toggle').bind('click',function(){
							$(this).addClass('hide');
							$this.slideUp(300);
							if (cookie == 'true') {
								$.cookie($this.attr('id'),'active',{ expires: 1,path: '/'});
							}
						});

					}

				});

			/* loops
			----*/

				var max     	= parseInt(controller_opt.postMax),
					start   	= parseInt(controller_opt.start) + 1,
					next    	= controller_opt.postNextLink,
					noMore  	= controller_opt.noMore,
					defaultText = $('.post-ajax-button .text').text(),
					request 	= false;

				if ($('.blog-layout').hasClass('masonry')) {

					$('.masonry #loop-posts').imagesLoaded(function(){
						var masonry = $('.masonry #loop-posts').masonry({
						  itemSelector: '.post',
						  columnWidth:'.post',
						  percentPosition:true,
						  gutter:0
						});
					});

				}

				if ($('#loop-posts').length) {
					fetchPosts('posts');
				}

				if ($('#loop-products').length) {
					if (!$('#loop-products').parent().hasClass('related') && !$('#loop-products').hasClass('subcategories') && !$('#loop-products').hasClass('both')) {
						fetchPosts('products');
					}
				}

			/* product +-
			-----*/

				function toggleDisable(){
	            	if($( 'form.cart' ).find( '.qty' ).val() > 1){$('button.minus').removeAttr('disabled');}
	            	else if($( 'form.cart' ).find( '.qty' ).val() <= 1){$('button.minus').attr('disabled','disabled');}
	            }

	            function ProductCount(){
		            $('form.cart').unbind('click').on( 'click', 'button.plus, button.minus', function() {
		 
			            // Get current quantity values
			            var qty  = $( this ).closest( 'form.cart' ).find( '.qty' );
			            var val  = parseFloat(qty.val());
			            var max  = parseFloat(qty.attr( 'max' ));
			            var min  = parseFloat(qty.attr( 'min' ));
			 			var step = parseFloat(qty.attr( 'step' ));
			            // Change the value if plus or minus
			            if ( $( this ).is( '.plus' ) ) {
			               if ( max && ( max <= val ) ) {
			                  qty.val( max );
			               } 
			            else {
			               qty.val( val + step );
			                 }
			            } 
			            else {
			               if ( min && ( min >= val ) ) {
			                  qty.val( min );
			               } 
			               else if ( val > 1 ) {
			                  qty.val( val - step );
			               }
			            }
			            toggleDisable();
			        });

			        $( 'form.cart' ).find( '.qty' ).on('change',function(){
			            toggleDisable();
			        });
		        }

			/* quick-view
			-----*/

				if (typeof(quickview_opt) != "undefined") {
		
					var ProductGallery = function( $target, args ) {
						this.$target = $target;
						this.$images = $( '.woocommerce-product-gallery__image', $target );

						// No images? Abort.
						if ( 0 === this.$images.length ) {
							this.$target.css( 'opacity', 1 );
							return;
						}

						// Make this object available.
						$target.data( 'product_gallery', this );

						// Pick functionality to initialize...
						this.flexslider_enabled = 'function' === typeof $.fn.flexslider && quickview_opt.flexslider_enabled;
						this.zoom_enabled       = 'function' === typeof $.fn.zoom && quickview_opt.zoom_enabled;
						this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && quickview_opt.photoswipe_enabled;

						// ...also taking args into account.
						if ( args ) {
							this.flexslider_enabled = false === args.flexslider_enabled ? false : this.flexslider_enabled;
							this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
							this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
						}

						// ...and what is in the gallery.
						if ( 1 === this.$images.length ) {
							this.flexslider_enabled = false;
						}

						// Bind functions to this.
						this.initFlexslider       = this.initFlexslider.bind( this );
						this.initZoom             = this.initZoom.bind( this );
						this.initZoomForTarget    = this.initZoomForTarget.bind( this );
						this.initPhotoswipe       = this.initPhotoswipe.bind( this );
						this.onResetSlidePosition = this.onResetSlidePosition.bind( this );
						this.getGalleryItems      = this.getGalleryItems.bind( this );
						this.openPhotoswipe       = this.openPhotoswipe.bind( this );

						if ( this.flexslider_enabled ) {
							this.initFlexslider( args.flexslider );
							$target.on( 'woocommerce_gallery_reset_slide_position', this.onResetSlidePosition );
						} else {
							this.$target.css( 'opacity', 1 );
						}

						if ( this.zoom_enabled ) {
							this.initZoom();
							$target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
						}

						if ( this.photoswipe_enabled ) {
							this.initPhotoswipe();
						}
					};

					ProductGallery.prototype.initFlexslider = function( args ) {
						var $target = this.$target,
							gallery = this;

						var options = $.extend( {
							selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
							start: function() {
								$target.css( 'opacity', 1 );
							},
							after: function( slider ) {
								gallery.initZoomForTarget( gallery.$images.eq( slider.currentSlide ) );
							}
						}, args );

						$target.flexslider( options );

						// Trigger resize after main image loads to ensure correct gallery size.
						$( '.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:eq(0) .wp-post-image' ).one( 'load', function() {
							var $image = $( this );

							if ( $image ) {
								setTimeout( function() {
									var setHeight = $image.closest( '.woocommerce-product-gallery__image' ).height();
									var $viewport = $image.closest( '.flex-viewport' );

									if ( setHeight && $viewport ) {
										$viewport.height( setHeight );
									}
								}, 100 );
							}
						} ).each( function() {
							if ( this.complete ) {
								$( this ).trigger( 'load' );
							}
						} );
					};

				
					ProductGallery.prototype.initZoom = function() {
						this.initZoomForTarget( this.$images.first() );
					};

					ProductGallery.prototype.initZoomForTarget = function( zoomTarget ) {
						if ( ! this.zoom_enabled ) {
							return false;
						}

						var galleryWidth = this.$target.width(),
							zoomEnabled  = false;

						$( zoomTarget ).each( function( index, target ) {
							var image = $( target ).find( 'img' );

							if ( image.data( 'large_image_width' ) > galleryWidth ) {
								zoomEnabled = true;
								return false;
							}
						} );

						// But only zoom if the img is larger than its container.
						if ( zoomEnabled ) {
							var zoom_options = $.extend( {
								touch: false
							}, quickview_opt.zoom_options );

							if ( 'ontouchstart' in document.documentElement ) {
								zoom_options.on = 'click';
							}

							zoomTarget.trigger( 'zoom.destroy' );
							zoomTarget.zoom( zoom_options );

							setTimeout( function() {
								if ( zoomTarget.find(':hover').length ) {
									zoomTarget.trigger( 'mouseover' );
								}
							}, 100 );
						}
					};

					ProductGallery.prototype.initPhotoswipe = function() {
						if ( this.zoom_enabled && this.$images.length > 0 ) {
							this.$target.prepend( '<a href="#" class="woocommerce-product-gallery__trigger">🔍</a>' );
							this.$target.on( 'click', '.woocommerce-product-gallery__trigger', this.openPhotoswipe );
							this.$target.on( 'click', '.woocommerce-product-gallery__image a', function( e ) {
								e.preventDefault();
							});

							// If flexslider is disabled, gallery images also need to trigger photoswipe on click.
							if ( ! this.flexslider_enabled ) {
								this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
							}
						} else {
							this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
						}
					};

					ProductGallery.prototype.onResetSlidePosition = function() {
						this.$target.flexslider( 0 );
					};

					ProductGallery.prototype.getGalleryItems = function() {
						var $slides = this.$images,
							items   = [];

						if ( $slides.length > 0 ) {
							$slides.each( function( i, el ) {
								var img = $( el ).find( 'img' );

								if ( img.length ) {
									var large_image_src = img.attr( 'data-large_image' ),
										large_image_w   = img.attr( 'data-large_image_width' ),
										large_image_h   = img.attr( 'data-large_image_height' ),
										alt             = img.attr( 'alt' ),
										item            = {
											alt  : alt,
											src  : large_image_src,
											w    : large_image_w,
											h    : large_image_h,
											title: img.attr( 'data-caption' ) ? img.attr( 'data-caption' ) : img.attr( 'title' )
										};
									items.push( item );
								}
							} );
						}

						return items;
					};

					ProductGallery.prototype.openPhotoswipe = function( e ) {
						e.preventDefault();

						var pswpElement = $( '.pswp' )[0],
							items       = this.getGalleryItems(),
							eventTarget = $( e.target ),
							clicked;

						if ( eventTarget.is( '.woocommerce-product-gallery__trigger' ) || eventTarget.is( '.woocommerce-product-gallery__trigger img' ) ) {
							clicked = this.$target.find( '.flex-active-slide' );
						} else {
							clicked = eventTarget.closest( '.woocommerce-product-gallery__image' );
						}

						var options = $.extend( {
							index: $( clicked ).index(),
							addCaptionHTMLFn: function( item, captionEl ) {
								if ( ! item.title ) {
									captionEl.children[0].textContent = '';
									return false;
								}
								captionEl.children[0].textContent = item.title;
								return true;
							}
						}, quickview_opt.photoswipe_options );

						// Initializes and opens PhotoSwipe.
						var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
						photoswipe.init();
					};

					$.fn.wc_product_gallery = function( args ) {
						new ProductGallery( this, args || quickview_opt );
						return this;
					};

				}

				var quickViewLoading = false;
					
			/* main functions call
			-----*/

				lang_curr_Toggles();
				mobileNavigation();
				mobileContainerTabs();
				etElements();
				megamenuTab();
				headerButton();
				megamenuPosition();
				megamenuGrid();
				megamenuHoverCheck();
				responsiveBannerImage();
				stickyFooter();

				listImages();
				ajaxAddToCart();
				quickView();
				ProductCount();

			/* ajaxes
			----*/

				var Iframe = (window.frameElement) ? (window.frameElement.hasAttribute('id') && window.frameElement.id == 'vc_inline-frame') ? 'vc' : 'false' : 'false';

				// megamenues
				if (Iframe == 'false') {

					var megamenues = [];

					$('.menu-item.mm-true').each(function(){
						var $this = $(this),
							megamenu = $this.attr('data-megamenu');
						if (typeof(megamenu) != 'undefined') {
							megamenues.push(megamenu);						
						}
					});

					$('.et-header-button.megamenu-ajax-true').each(function(){
						var $this = $(this),
							megamenu = $this.attr('data-megamenu');
						if (typeof(megamenu) != 'undefined') {
							megamenues.push(megamenu);						
						}
					});

					if (megamenues.length) {
						megamenuAjax(megamenues);
					}

					var footerPlaceholder = $('.footer-placeholder');

					if (footerPlaceholder.length) {
						var footerID = footerPlaceholder.attr('data-footer');
						footerAjax(footerID);
					}

					var mobileContainer = $('.mobile-container');

					if (mobileContainer.length && mobileContainer.attr('data-content')) {
						mobileAjax(mobileContainer.parents('.et-mobile').attr('id'));
					}

					//loops
					document.addEventListener("DOMContentLoaded", productLoad());
					document.addEventListener("DOMContentLoaded", postLoad());

				}

			var actions = [
		        'filter_attributes'
		    ];

			$( document ).ajaxComplete(function( event, xhr, settings ) {

		        if (typeof(settings['data']) != 'undefined' && settings['data'] != null) {

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
		                ajaxAddToCart();
		            }

		        }
		    });

		})(jQuery);


		/* et-row/et-column
		----*/

		    (function($){

		        "use strict";

		        function backgroundScroll(el,speed,direction){
		    		var size = (direction == "horizontal") ? el.data('img-width') : el.data('img-height');
		    		if (direction == "horizontal") {
						el.animate({'background-position-x' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-x','0');backgroundScroll(el, speed,direction);}});
		    		} else if (direction == "vertical") {
		    			el.animate({'background-position-y' :size}, {duration:speed,easing:'linear',complete:function(){el.css('background-position-y','0');backgroundScroll(el, speed,direction);}});
		    		};
				}

		        $('.vc-parallax').each(function(){
		            var $this = $(this),
		            	plx = $this.find('.parallax-container');
		            
		            if ($this.hasClass('vc-video-parallax')) {
		           		plx = $this.find('.video-container');
		            }

		            var duration = parseInt($this.data('parallax-duration')),
		            	ratio    = (typeof(duration) != 'undefined' && duration != null && duration != 0) ? 0.1 : 1;

		            	if (duration == null) {duration = 0;}

		            	duration = duration/100;

		            $(window).scroll(function() {
		                var yPos = Math.round(($(window).scrollTop()-plx.offset().top) / $this.data('parallax-speed'));

		                yPos = ratio*yPos;

		                gsap.to(plx,{
		                	duration:duration,
		                	delay:0,
		                	y:yPos,
		            	});
		            });

		        });

			    $('.vc-animated-bg').each(function(){

			    	var $this         = $(this), 
			    		animatedBg    = $this.find('.animated-container'),
			    		animatedDir   = $this.data('animatedbg-dir'),
			    		animatedSpeed = $this.data('animatedbg-speed');

				    	if (animatedDir == 'horizontal') {
				    		backgroundScroll(animatedBg, animatedSpeed, 'horizontal');
				    	} else if (animatedDir == 'vertical') {
				    		backgroundScroll(animatedBg, animatedSpeed, 'vertical');
				    	};
			    });

			    $('.vc-curtain').each(function(){

			    	var curtain   = $(this),
			    		row       = curtain.parent(),
			    		content   = (row.find('.container').length) ? row.find('.container') : row.find('.wpb_wrapper'),
			    		animation = curtain.data('curtain'),
			    		delay     = 0.2;

					var tl = new gsap.timeline({paused: true});

					if (
						animation == "curtain-left" || 
						animation == "curtain-right" || 
						animation == "curtain-top" || 
						animation == "curtain-bottom"
					) {

						switch(animation){
							case "curtain-left":

								tl.to(curtain,0.8, {
								  scaleX:1,
								  transformOrigin:'left top',
								  ease:"power3.out"
							    },delay);

							break;

							case "curtain-right":

								tl.to(curtain,0.8, {
								  scaleX:1,
								  transformOrigin:'right top',
								  ease:"power3.out"
							    },delay);
							
							break;

							case "curtain-top":

								tl.to(curtain,0.8, {
								  scaleY:1,
								  transformOrigin:'left top',
								  ease:"power3.out"
							    },delay);
							
							break;

							case "curtain-bottom":

								tl.to(curtain,0.8, {
								  scaleY:1,
								  transformOrigin:'left bottom',
								  ease:"power3.out"
							    },delay);

							break;
						}

					    tl.to(content,0.2, {
						  opacity:1,
					    },'-=0.5');
					}

					row.waypoint({
		                handler: function(direction) {

		                	tl.progress(0);
							tl.play();

		                    this.destroy();

		                },
		                offset: '75%'
		            });

			    });

		    })(jQuery);