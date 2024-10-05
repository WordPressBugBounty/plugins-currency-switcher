'use strict';

//https://codepen.io/General-Dev/pen/JRjwPa
jQuery(function ($) {
    /*Dropdown Menu*/
    $('.wpcs-style-1-dropdown').click(function () {
        $(this).attr('tabindex', 1).focus();
        $(this).toggleClass('wpcs-style-1-active');
        $(this).find('.wpcs-style-1-dropdown-menu').slideToggle(300);
    });
    $('.wpcs-style-1-dropdown').focusout(function () {
        $(this).removeClass('wpcs-style-1-active');
        $(this).find('.wpcs-style-1-dropdown-menu').slideUp(300);
    });
    $('.wpcs-style-1-dropdown .wpcs-style-1-dropdown-menu li').click(function () {
       // $(this).parents('.wpcs-style-1-dropdown').find('span').text($(this).text());
        $(this).parents('.wpcs-style-1-dropdown').find('input').attr('value', $(this).attr('id'));
    });
    /*End Dropdown Menu*/

    jQuery('.wpcs-style-1-dropdown-menu li').on('click', function () {

        var l = wpcs_remove_link_param('currency', window.location.href);
        l = l.replace("#", "");

        if (wpcs_special_ajax_mode) {
            var data = {
                action: "wpcs_set_currency_ajax",
                currency: jQuery(this).data('currency')
            };

            jQuery.post(wpcs_ajaxurl, data, function (value) {
                //location.reload();
                window.location = l;
            });
        } else {
            if (Object.keys(wpcs_array_of_get).length === 0) {
                window.location = l + '?currency=' + jQuery(this).data('currency');
            } else {
                wpcs_redirect(jQuery(this).data('currency'));
            }
        }
    });

    //***

    document.addEventListener('after_wpcs_get_products_price_html', function (e) {
        var current_currency = e.detail.current_currency;
        //current_currency='USD';
        jQuery.each(jQuery('.wpcs-style-1-dropdown'), function (i, d) {
            jQuery(d).find('.wpcs-style-1-select > span').html(jQuery(d).find(`li[data-currency=${current_currency}]`).html());
        });
    });
});



