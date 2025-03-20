$(document).ready(function () {
    $(".owl-carousel.categories").owlCarousel({
        items: 6,
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: { items: 2 },
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
});
