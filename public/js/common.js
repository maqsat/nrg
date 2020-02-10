$('.slid-news').slick({
    arrows: false,
    dots: true,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 1000
});
$('.slid-big').slick({
    dots: false,
    arrows: true,
    fade: true,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 1000
});
$('.slid-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slid-nav'
});
$('.slid-nav').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    // slidesPerRow: 3,
    // rows: 2,
    asNavFor: '.slid-for',
    dots: false,
    centerMode: false,
    focusOnSelect: true
});
AOS.init({
    easing: 'ease-out-back',
    duration: 1000
});

$('#lightgallery').lightGallery({ });
