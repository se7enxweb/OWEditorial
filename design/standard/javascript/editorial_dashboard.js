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

    $('#name-search').keyup(function() {
        searchNameInTable($(this).val());
    });

    $('#author-search').keyup(function() {
        searchAuthorInTable($(this).val());
    });

    function searchNameInTable(inputVal) {
        searchTable(inputVal, "name_col");
    }

    function searchAuthorInTable(inputVal) {
        searchTable(inputVal, "owner_col");
    }

    function searchTable(inputVal, col_class) {
        $('.workflow-state-block table').each(function(index, table) {
            $(table).find('tr').each(function(index, row) {
                var allCells = $(row).find('td.' + col_class);
                if (allCells.length > 0) {
                    var found = false;
                    allCells.each(function(index, td) {
                        var regExp = new RegExp(inputVal, 'i');
                        if (regExp.test($(td).text())) {
                            found = true;
                            return false;
                        }
                    });
                    if(found == true)$(row).show();else $(row).hide();
                }
            });
        });
    }

});
function getDashboardItems( args ) {
    
    var container = args.container;
    
    $(args['container'] + ' .yui-dt').css('opacity','0.5');
    
    $.ez('ezJsOwEditorial::getDashboardItems', args, function(data) {
        if ( data.content!="false" )
        {
            $(container)[0].outerHTML = data.content;
            ajax_pagination( args['container'], args['state_identifier'], args['group_identifier'] );
        }
    });
    
    return false;
}
function ajax_pagination( container, state_identifier, group_identifier) {
    $(container + ' a.ajax-pagination').click(function(e){
        e.preventDefault();
        getDashboardItems( {
            'container': container,
            'state_identifier': state_identifier,
            'group_identifier': group_identifier,
            'offset': $(this).attr('data-offset')
        } );
    });
}