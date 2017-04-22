//safreen JavaScript 

jQuery(window).ready(function($) {

    /*CHECK IF TOUCH ENABLED DEVICE*/
    function is_touch_device() {
        return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
    }

    if (is_touch_device()) {
        jQuery('body').addClass('onlytouch');
    }

    // Set options
    var options = {
        offset: '#showHere',
        offsetSide: 'top',
        classes: {
            clone: 'branding--clone',
            stick: 'branding--stick',
            unstick: 'branding--unstick'
        }
    };


    // Initialise with options
    var banner = new Headhesive('.branding,.branding-single', options);

    // Headhesive destroy
    // banner.destroy();
	
	
	//Next-Previous Post Image Check
	jQuery(".pre-nav-saf a").addClass('prev');
	jQuery(".next-nav-saf a").addClass('next');

    //MENU Animation
    if (jQuery(window).width() > 768) {
        jQuery('#navmenu ul > li').hoverIntent(function() {
            jQuery(this).find('.sub-menu:first, ul.children:first').slideDown({
                duration: 200
            });
            jQuery(this).find('a').not('.sub-menu a').stop().animate({
                "color": '#0000'
            }, 200);
        }, function() {
            jQuery(this).find('.sub-menu:first, ul.children:first').slideUp({
                duration: 200
            });
            jQuery(this).find('a').not('.sub-menu a').stop().animate({
                "color": '#ffff'
            }, 200);

        });

        jQuery('#navmenu ul li').not('#navmenu ul li ul li').hover(function() {
            jQuery(this).addClass('menu_hover');
        }, function() {
            jQuery(this).removeClass('menu_hover');
        });
        jQuery('#navmenu li').has("ul").addClass('zn_parent_menu');
        jQuery('.zn_parent_menu > a').append('<span class="menu_arrow"><i class="fa fa-angle-down"></i></span>');
    }

    - - - - - - - - - - - - - - - - - - - - - -
    // show / hide slider navigation
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 	

    jQuery('.nivo-directionNav').hide();
    jQuery('#nivo').on("mouseenter", function() {
        jQuery('.nivo-directionNav').stop().animate({
            opacity: 1
        }, 200);
    }).on("mouseleave", function() {
        jQuery('.nivo-directionNav').stop().animate({
            opacity: 0

        }, 200);
    });

    //Comment Form
    jQuery('.comment-form-author, .comment-form-email, .comment-form-url').wrapAll('<div class="field_wrap" />');

    jQuery(".comment-reply-link").click(function() {
        jQuery('#respond_wrap .single_skew_comm, #respond_wrap .single_skew').hide();
    });
    jQuery("#cancel-comment-reply-link").click(function() {
        jQuery('#respond_wrap .single_skew_comm, #respond_wrap .single_skew').show();
    });

    // scrollup
    jQuery(window).bind("scroll", function() {
        if (jQuery(this).scrollTop() > 800) {
            jQuery(".scrollup").fadeIn('slow');
        } else {
            jQuery(".scrollup").fadeOut('fast');
        }
    });
    jQuery(".scrollup").click(function() {
        jQuery("html, body").animate({
            scrollTop: 0
        }, "slow");
        return false;
    });

// smoothScroll
 jQuery('body').smoothScroll({
        delegateSelector: '#navmenu li a'
      });
	  
 

    // WOW
    new WOW().init();

    var docHeight = jQuery(window).height();
    var footerHeight = jQuery('#footer').height();
    var footerTop = jQuery('#footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        jQuery('#footer').css('margin-top', 1 + (docHeight - footerTop) + 'px');
    }

    jQuery('.matchhe').matchHeight(options);

    //TOP Header Search

    jQuery(".social-safreen a .fa-search").click(function() {
        jQuery("#navmenu .search-form .search-field ,#navmenu .search-form .search-submit").slideToggle("fade");
    });
	
	
	    jQuery('.box-container div:nth-child(4),#section-features > div:nth-child(3),#safreen-clients >div:nth-child(2)').attr('style', 'display: none !important');

    /* Side responsive menu	 */
    $('.menu-toggle').sidr({
        name: 'sidr-left',
        side: 'left',
        source: '#navmenu',
        onOpen: function() {
            $('.menu-toggle').animate({
                marginLeft: "260px"
            }, 200);
        },
        onClose: function() {
            $('.menu-toggle').animate({
                marginLeft: "0px"
            }, 200);
        }
    });

});

window.matchMedia = window.matchMedia || (function(e, f) {
    var c, a = e.documentElement,
        b = a.firstElementChild || a.firstChild,
        d = e.createElement("body"),
        g = e.createElement("div");
    g.id = "mq-test-1";
    g.style.cssText = "position:absolute;top:-100em";
    d.style.background = "none";
    d.appendChild(g);
    return function(h) {
        g.innerHTML = '&shy;<style media="' + h + '"> #mq-test-1 { width: 42px; }</style>';
        a.insertBefore(d, b);
        c = g.offsetWidth == 42;
        a.removeChild(d);
        return {
            matches: c,
            media: h
        }
    }
})(document);