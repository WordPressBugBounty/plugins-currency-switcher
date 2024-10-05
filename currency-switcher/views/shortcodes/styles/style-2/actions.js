'use strict';

//https://codepen.io/ainalem/pen/RqYZNO
jQuery(function ($) {

    $('.wpcs-style-2-drop-down').on('click', function () {
        this.classList.add('wpcs-style-2-expand');
        $(this).css('height', $(this).data('expanded-height'));
        return true;
    });

    $('.wpcs-style-2-x').on('click', function () {
        $(this).parents('.wpcs-style-2-drop-down').removeClass('wpcs-style-2-expand');
        $(this).parents('.wpcs-style-2-drop-down').css('height', '32px');
        return false;
    });

    //***

    $('.wpcs-style-2-row').on('click', function () {
        
        var l = wpcs_remove_link_param('currency', window.location.href);
        l = l.replace("#", "");

        if (wpcs_special_ajax_mode) {
            var data = {
                action: "wpcs_set_currency_ajax",
                currency: jQuery(this).find('.wpcs-style-2-link').data('currency')
            };

            jQuery.post(wpcs_ajaxurl, data, function (value) {
                //location.reload();
               window.location = l;
            });
        } else {
            if (Object.keys(wpcs_array_of_get).length === 0) {
                window.location = l + '?currency=' + jQuery(this).find('.wpcs-style-2-link').data('currency');
            } else {
                wpcs_redirect(jQuery(this).find('.wpcs-style-2-link').data('currency'));
            }
        }
    });

    //***

    document.addEventListener('after_wpcs_get_products_price_html', function (e) {
        var current_currency = e.detail.current_currency;
        //current_currency='USD';
        jQuery.each(jQuery('.wpcs-style-2-drop-down'), function (i, d) {
            jQuery(d).find('.wpcs-style-2-name-large').html(jQuery(d).find(`div.wpcs-style-2-link[data-currency=${current_currency}]`).html());
            jQuery(d).find('.wpcs-style-2-name').html(jQuery(d).find(`div.wpcs-style-2-link[data-currency=${current_currency}]`).html());
            jQuery(d).find('.wpcs-style-2-avatar').css('background-image', 'url(' + jQuery(d).find(`div.wpcs-style-2-link[data-currency=${current_currency}]`).data('flag') + ')');
            jQuery(d).find('.wpcs-style-2-avatar-large').css('background-image', 'url(' + jQuery(d).find(`div.wpcs-style-2-link[data-currency=${current_currency}]`).data('flag') + ')');
        });
    });
});


