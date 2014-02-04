{def $cur_offset = first_set($offset, $view_parameters.offset, 0)
     $cur_limit = first_set($limit, 10)
     $node_list_count = fetch('editorial', 'nodes_by_object_state_count', hash('group', $state_group.identifier, 'state', $state))
     $node_list = fetch('editorial', 'nodes_by_object_state', 
                            hash('group', $state_group.identifier,
                                 'state', $state,
                                 'limit', $cur_limit,
                                 'offset', $cur_offset ))
     $container=concat('state_', $state_group.identifier, '_', $state.identifier)}
    {if $node_list|count}

        <div class="workflow-state-block" id="state_{$state_group.identifier}_{$state.identifier}">
           <h3>{$state.current_translation.name}</h3> 
            <div class="yui-dt">
                <table>
                    <thead><tr>
                        <th><div class="yui-dt-liner content_actions_head">{'Content actions'|i18n( 'oweditorial/module' )}</div></th>
                        <th><div class="yui-dt-liner name_head">{'Name'|i18n( 'oweditorial/module' )}</div></th>
                        <th><div class="yui-dt-liner owner_head">{'Author'|i18n( 'oweditorial/module' )}</div></th>
                        <th><div class="yui-dt-liner modified_head">{'Last Modification'|i18n( 'oweditorial/module' )}</div></th>
                        <th><div class="yui-dt-liner editorial_actions_head">{'Editorial actions'|i18n( 'oweditorial/module' )}</div></th>
                    </tr></thead>
                    <tbody class="yui-dt-data">
                        {foreach $node_list as $index => $content_node sequence array( 'yui-dt-even', 'yui-dt-odd' ) as $style}
                            {node_view_gui content_node=$content_node style=$style state=$state state_group=$state_group view='editorial_list'}
                        {/foreach}
                    </tbody>
                </table>
            </div>
            {include name=navigator
                 uri='design:navigator/ajax_pagination.tpl'
                 page_uri='/editorial/dashboard'
                 item_count=$node_list_count
                 view_parameters=$view_parameters
                 offset=$cur_offset
                 item_limit=$cur_limit
                 elements_limit=12
             }
            <script>

                $(function(){ldelim}
                    ajax_pagination( '#{$container}', '{$state.identifier}', '{$state_group.identifier}' );
                {rdelim});

            </script>
        </div>
    {/if}
{undef $node_list}