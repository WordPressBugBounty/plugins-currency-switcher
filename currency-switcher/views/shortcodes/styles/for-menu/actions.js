'use strict';

jQuery(function ($) {

    $('.wpcs-style-for-menu-dialog').parent().on('click', function (e) {
        e.stopPropagation();
        if ($(this).hasClass('wpcs-style-for-menu-active')) {
            $('.wpcs-style-for-menu-dialog').fadeOut(200);
            $(this).removeClass('wpcs-style-for-menu-active');
        } else {
            $('.wpcs-style-for-menu-dialog').delay(300).fadeIn(200);
            $(this).addClass('wpcs-style-for-menu-active');
        }

        return false;
    });

    $(document.body).on('click', function (e) {
        $('.wpcs-style-for-menu-dialog').fadeOut(200, function () {
            $('.wpcs-style-for-menu-add').removeClass('wpcs-style-for-menu-active');
        });
    });

    $(".wpcs-style-for-menu-dialog").on('click', function (e) {
        e.stopPropagation();
    });

});

