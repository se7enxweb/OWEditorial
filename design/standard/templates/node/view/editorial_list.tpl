<tr class="{if $index|eq(0)}yui-dt-first{/if} {first_set($style, 'yui-dt-even')}">
    <td class="content_actions_col">
        <a class="defaultbutton" href={$node.url_alias|ezurl()}>{'View'|i18n( 'oweditorial/module' )}</a>
        <a class="button" href={concat('content/edit/',$node.contentobject_id)|ezurl()}>{'Edit'|i18n( 'oweditorial/module' )}</a>
    </td>
    <td class="name_col"><a href={$node.url_alias|ezurl()}>{$node.name|wash()}</a></td>
    <td class="owner_col">{$node.object.owner.name|wash()}</td>
    <td class="modified_col">{$node.object.modified|l10n( 'shortdatetime' )}</td>
    <td class="editorial_actions_col">
      {def $workflows = ezini( 'Workflows', 'Workflows', 'oweditorial.ini' )}
        {foreach $node.object.allowed_assign_state_list as $state_group}
          {if $workflows|contains($state_group.group.identifier)}
            {if $state_group.current.identifier|ne('none')}
              {if ezini_hasvariable($state_group.group.identifier, $state_group.current.identifier, 'oweditorial.ini')}
                {foreach ezini($state_group.group.identifier, $state_group.current.identifier, 'oweditorial.ini') as $to => $action}
                  {def $st = fetch('editorial' , 'object_state', hash( 'group_identifier' , $state_group.group.identifier,
                                                                              'state_identifier' , $to))}
                      {if $node.object.allowed_assign_state_id_list|contains($st.id)}
                          <form name="statesform" method="post" action={'state/assign'|ezurl} class="next-actions"{*
                          *}{if ezini_hasvariable(concat('notifications_', $st.identifier), 'Alert', 'oweditorial.ini')}{*
                          *}onsubmit="return confirm('{ezini(concat('notifications_', $st.identifier), 'Alert', 'oweditorial.ini')|explode("'")|implode("\\'")}');"{*
                        *}{/if}{*
                      *}>
                        <input type="hidden" name="ObjectID" value="{$node.object.id}" />
                        <input type="hidden" name="RedirectRelativeURI" value="{$node.url_alias}" />
                        <input type="hidden" name="SelectedStateIDList[]" value="{$st.id}" />
                        <input type="submit" value="{$action|wash|i18n( 'oweditorial' )}" name="AssignButton" class="defaultbutton" />
                      </form>
                    {/if}
                  {undef $st}
                {/foreach}
              {/if}
            {/if}
          {/if}
        {/foreach}
      {undef $workflows}
    </td>
</tr>