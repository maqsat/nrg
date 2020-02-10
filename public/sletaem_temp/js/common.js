
$(document).ready(function(){
    $('.call-search').on('click', function () {
        $('.search-block').addClass('show-height');
        $('.overlay').addClass('overlay-showed');
    });
    $('.close-search').on('click', function () {
        $('.search-block').removeClass('show-height');
        $('.overlay').removeClass('overlay-showed');
    });
    $('.overlay').on('click', function () {
        $('.search-block').removeClass('show-height');
        $('.overlay').removeClass('overlay-showed');
    });



});

//  SLIDER-REF
$('.sl-ref').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    arrows: true,
    centerMode: false,
    responsive: [
        {
            breakpoint: 1190,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                centerMode: true
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                centerMode: true,
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ]
});





