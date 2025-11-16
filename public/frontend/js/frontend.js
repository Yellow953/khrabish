$(document).ready(function () {
    $(".owl-carousel.categories").owlCarousel({
        items: 6,
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: { items: 3 },
            600: { items: 4 },
            1080: { items: 6 },
        },
    });

    $(".owl-carousel.products").owlCarousel({
        items: 4,
        loop: false,
        margin: 20,
        nav: false,
        dots: true,
        responsive: {
            0: { items: 2 },
            600: { items: 3 },
            1080: { items: 4 },
        },
    });

    // Desktop mega menu hover functionality
    function isDesktop() {
        return window.innerWidth >= 992;
    }

    function initDesktopHover() {
        if (isDesktop()) {
            $('.desktop-category-item').each(function() {
                const $item = $(this);
                const $dropdown = $item.find('.dropdown-menu');
                
                // Show on hover
                $item.on('mouseenter', function() {
                    if ($dropdown.length) {
                        $dropdown.addClass('show');
                        $item.find('.dropdown-toggle').attr('aria-expanded', 'true');
                    }
                });
                
                // Hide on mouse leave
                $item.on('mouseleave', function() {
                    if ($dropdown.length) {
                        $dropdown.removeClass('show');
                        $item.find('.dropdown-toggle').attr('aria-expanded', 'false');
                    }
                });
            });
        }
    }

    // Initialize on page load
    initDesktopHover();

    // Handle window resize
    $(window).on('resize', function() {
        $('.desktop-category-item').off('mouseenter mouseleave');
        initDesktopHover();
    });
});
