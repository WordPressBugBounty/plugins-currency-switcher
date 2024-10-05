'use strict';

//https://www.cssscript.com/demo/material-design-dialog-dudialog/
jQuery(function ($) {
    $('.wpcs-style-3-du-dialog').removeAttr('style');

    $('.wpcs-style-3-du-dialog-starter').on('click', function () {
        $(this).prev('.wpcs-style-3-du-dialog').addClass('wpcs-style-3-dlg--open');
    });

    $('.wpcs-style-3-close').on('click', function () {
        $('.wpcs-style-3-du-dialog').removeClass('wpcs-style-3-dlg--open');
    });

    $(document).keyup(function (e) {
        if (e.keyCode == 27) {
            $('.wpcs-style-3-close').trigger('click');
        }
    });

    //***

    $('.wpcs-style-3-du-dialog input[type=radio]').on('click', function () {

        //$(_this).parents('.wpcs-style-3-du-dialog').addClass('wpcs-style-3-dlg--closing');
        var _this = this;
        setTimeout(function () {
            $(_this).parents('.wpcs-style-3-du-dialog').addClass('wpcs-style-3-dlg--closing');
            $(_this).parents('.wpcs-style-3-du-dialog').removeClass('wpcs-style-3-dlg--open');
        }, 333);

        //***

        var l = wpcs_remove_link_param('currency', window.location.href);
        l = l.replace("#", "");

        if (wpcs_special_ajax_mode) {
            var data = {
                action: "wpcs_set_currency_ajax",
                currency: $(this).val()
            };

            $.post(wpcs_ajaxurl, data, function (value) {
                window.location = l;
            });
        } else {
            if (Object.keys(wpcs_array_of_get).length === 0) {
                window.location = l + '?currency=' + $(this).val();
            } else {
                wpcs_redirect($(this).val());
            }
        }

        return true;
    });

    //***

    document.addEventListener('after_wpcs_get_products_price_html', function (e) {
        var current_currency = e.detail.current_currency;
        //current_currency='USD';
        jQuery(`.wpcs-style-3-dlg-select-radio`).prop('checked', false);
        jQuery(`.wpcs-style-3-dlg-select-radio[value=${current_currency}]`).prop('checked', true);
        jQuery('.wpcs-style-3-du-dialog-starter').html(jQuery(`.wpcs-style-3-dlg-select-radio[value=${current_currency}]`).next('label').html());
        jQuery('.wpcs-style-3-du-dialog-starter').css('background-image', 'url(' + jQuery(`.wpcs-style-3-dlg-select-radio[value=${current_currency}]`).parent().data('flag') + ')');
    });
});

