jQuery(document).ready(function ($){


    waitForElementToDisplay('#mce-WP_REFERRA');

    var allTables = $('#dashboard-all-affiliates-table, #dashboard-referrals-table, #dashboard-all-referrals-table, table.display').DataTable();

    $('.dashboard-tabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show')
    });

});

function run_select2(){

    jQuery('#mce-WP_REFERRA').select2({
        data: mynfa_plugin.users_record,
        multiple: false,
        tags: false
    });
}

function waitForElementToDisplay(selector, time) {
    if(document.querySelector(selector)!=null) {
        run_select2();
        return;
    }
    else {
        setTimeout(function() {
            waitForElementToDisplay(selector, time);
        }, time);
    }
}