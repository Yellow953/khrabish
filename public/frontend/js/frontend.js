$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        items: 6,
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: { items: 2 },
            600: { items: 4 },
            1000: { items: 6 },
        },
    });
});
