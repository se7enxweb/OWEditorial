$(document).ready(function() {
    $('.editorial-dashboard-page-filter li').click(function() {
        if ($(this).hasClass('workflow')) {
            if ($(this).hasClass('enabled')) {
                $("#" + $(this).attr('rel')).hide()
                $(this).siblings().hide()
                $(this).removeClass('enabled');
                $(this).addClass('disabled');
            } else {
                $("#" + $(this).attr('rel')).show()
                $(this).siblings().show()
                $(this).addClass('enabled');
                $(this).removeClass('disabled');
            }
        } else if ($(this).hasClass('workflow_state')) {
            if ($(this).hasClass('enabled')) {
                $("#" + $(this).attr('rel')).hide()
                $(this).removeClass('enabled');
                $(this).addClass('disabled');
            } else {
                $("#" + $(this).attr('rel')).show()
                $(this).addClass('enabled');
                $(this).removeClass('disabled');
            }
        }
    });
});
