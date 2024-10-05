"use strict";
var wpcs_sd_current_edit_id = 0;
var wpcs_sd_dd = null;

window.addEventListener('load', function () {
    if (document.getElementById('wpcs-sd-create')) {
        document.getElementById('wpcs-sd-create').addEventListener('click', function (e) {
            e.preventDefault();

            wpcs_show_info_popup(wpcs_sd.lang.creating, 7777);
            jQuery.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'wpcs_sd_create',
		    nonce: wpcs_sd.nonce
                },
                success: function (id) {
                    let tr = document.createElement('tr');
                    tr.setAttribute('id', `wpcs-sd-dashboard-tr-${id}`);
                    let td1 = document.createElement('td');
                    td1.innerText = id;
                  
                    let td3 = document.createElement('td');
                    td3.innerHTML = `<input type="text" value="[wpcs sd=${id}]" readonly="">`;
                    let td4 = document.createElement('td');
                    td4.innerText = '';

                    let btn_edit = document.createElement('a');
                    btn_edit.setAttribute('href', `javascript: wpcs_sd_edit(${id});void(0);`);
                    btn_edit.className = 'wpcs_button wpcs_btn_light';
                    btn_edit.innerHTML = '<span class="icon-edit"></span>';

                    let btn_delete = document.createElement('a');
                    btn_delete.setAttribute('href', `javascript: wpcs_sd_delete(${id});void(0);`);
                    btn_delete.className = 'wpcs_button wpcs_btn_light';
                    btn_delete.innerText = 'x';

                    td4.appendChild(btn_edit);
                    td4.appendChild(btn_delete);

                    tr.appendChild(td1);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    document.getElementById('wpcs-sd-table').querySelector('tbody').prepend(tr);
                    wpcs_show_info_popup(wpcs_sd.lang.created, 111);
                },
                error: function () {
                    alert(wpcs_sd.lang.smth_wrong);
                }
            });

            return false;
        });
    }

    window.addEventListener('scroll', function (e) {
        if (document.getElementById('selectron23-example-container')) {
            let distanceY = window.pageYOffset || document.documentElement.scrollTop;
            let container = document.getElementById('selectron23-example-container');

            if (distanceY > document.getElementById('selectron23-example-container').offsetHeight) {
                container.classList.add('selectron23-example-container-fixed');
                let padding = 2 * 11;
                container.style.width = `calc(100% - ${padding}px)`;
            } else {
                container.classList.remove('selectron23-example-container-fixed');
                container.removeAttribute('style');
            }
        }
    });


});


function wpcs_sd_edit(id) {
    wpcs_show_info_popup(wpcs_sd.lang.loading, 7777);
    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'wpcs_sd_get',
            id: id,
	    nonce: wpcs_sd.nonce
        },
        success: function (options) {
            wpcs_show_info_popup(wpcs_sd.lang.loaded, 111);

            wpcs_sd_current_edit_id = id;
            let area = document.getElementById('wpcs-sd-work-area');
            area.innerHTML = document.getElementById('wpcs-sd-work-area-tpl').innerHTML;
            document.getElementById('wpcs-sd-manage-area').style.display = 'none';
            document.getElementById('wpcs-sd-work-area').style.display = 'block';

            wpcs_sd_dd = new WPCS_SD_DD(JSON.parse(document.getElementById('selectron23-example').getAttribute('data-wpcs-sd-currencies')), JSON.parse(options));
            document.addEventListener('keydown', function (event) {
                if (event.ctrlKey && event.key === 'z' && wpcs_sd_current_edit_id > 0) {

                    document.dispatchEvent(new CustomEvent('wpcs-sd-ctrl-z', {detail: {
                            id: wpcs_sd_current_edit_id,
                            wpcs_sd_dd: wpcs_sd_dd
                        }}));

                }
            });

            wpcs_sd_init_tabs();
        },
        error: function () {
            alert(wpcs_sd.lang.smth_wrong);
        }
    });
}

function wpcs_sd_delete(id) {
    if (confirm(wpcs_sd.lang.are_you_sure)) {
        wpcs_show_info_popup(wpcs_sd.lang.deleting, 7777);
        document.getElementById(`wpcs-sd-dashboard-tr-${id}`).remove();
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'wpcs_sd_delete',
                id: id,
		nonce: wpcs_sd.nonce
            },
            success: function () {
                wpcs_show_info_popup(wpcs_sd.lang.deleted, 111);
            },
            error: function () {
                alert(wpcs_sd.lang.smth_wrong);
            }
        });
    }
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function wpcs_sd_init_tabs() {
    document.querySelectorAll('#wpcs-sd-tabs a').forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            wpcs_sd_click_tab(this);
            return false;
        });
    });
}

function wpcs_sd_click_tab(_this) {
    document.querySelectorAll('#wpcs-sd-tabs a').forEach(function (a) {
        a.classList.remove('wpcs-panel-button-selected');
    });

    document.querySelectorAll('.wpcs-sd-panel').forEach(function (tp) {
        tp.classList.remove('wpcs-sd-panel-current');
    });


    _this.classList.add('wpcs-panel-button-selected');
    document.querySelector(_this.getAttribute('href')).classList.add('wpcs-sd-panel-current');
}


function wpcs_sd_save() {
    wpcs_show_info_popup(wpcs_sd.lang.saving, 7777);
    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'wpcs_sd_save',
            id: wpcs_sd_current_edit_id,
            options: JSON.stringify(wpcs_sd_dd.settings),
	    nonce: wpcs_sd.nonce
        },
        success: function () {
            wpcs_show_info_popup(wpcs_sd.lang.saved, 111);
        },
        error: function () {
            alert(wpcs_sd.lang.smth_wrong);
        }
    });
}

function wpcs_sd_save_exit() {
    wpcs_sd_save();
    wpcs_sd_exit();
}

function wpcs_sd_exit_no_save() {
    if (confirm(wpcs_sd.lang.are_you_sure)) {
        wpcs_sd_exit();
    }
}

function wpcs_sd_exit() {
    document.getElementById('wpcs-sd-manage-area').style.display = 'block';
    document.getElementById('wpcs-sd-work-area').style.display = 'none';
    document.getElementById('wpcs-sd-work-area').innerHTML = '';
    wpcs_sd_current_edit_id = 0;
}

function wpcs_sd_reset() {
    if (confirm(wpcs_sd.lang.are_you_sure)) {
        wpcs_sd_dd.reset();
    }
}


