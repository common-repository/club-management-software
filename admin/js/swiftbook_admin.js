jQuery(document).ready(function($) {
    $('.custom-tab').click(function(e) {
        e.preventDefault();
        var $tab = $(this),
                $panel_id = $tab.attr('href');

        /*Remoe active class from all other items*/
        $('.custom-tab').each(function() {
            $(this).removeClass('nav-tab-active');
        });
        /*Add active class to curent*/
        $tab.addClass('nav-tab-active');

        /*Remoe active class from all other panels*/
        $('.panel').each(function() {
            $(this).removeClass('active');
        });
        /*Add active class to curent*/
        $('div' + $panel_id).addClass('active');


    });

    tab_id = window.location.hash.substr(1);
    var index = $('#wpseo-tabs a[href="#' + tab_id + '"]').index();
    if (index > 0) {
        $('.panel').each(function() {
            $(this).removeClass('active');
        });
        $('#wpseo-tabs .nav-tab').each(function() {
            $(this).removeClass('nav-tab-active');
        });
        $('#wpseo-tabs .nav-tab').eq(index).addClass('nav-tab-active');
        $('.panel').eq(index).addClass('active');
    }
    
     /* plugin activation notice dismis.*/
    jQuery(".swiftbook-notice .notice-dismiss").on('click', function() {
        var data = {
            'action': 'swiftbook_dismiss_notice'
        };
        jQuery.post(swiftbook_ajax_object.ajax_url, data, function(response) {

        });
    });
});