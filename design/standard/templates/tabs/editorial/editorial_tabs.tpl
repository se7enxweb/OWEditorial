    {* Editorial *}
{def $display_groups = array()
	 $i = 0}
	{foreach ezini( 'Workflows', 'Workflows', 'oweditorial.ini' ) as $workflow}
		{set $display_groups = $display_groups|append( ezini( $workflow, 'StateGroup', 'oweditorial.ini' ) )}
	{/foreach}
	{foreach $node.object.allowed_assign_state_list as $group}
		{if $display_groups|contains($group.group.identifier)}
			{if $group.current.identifier|ne('none')}
				{set $i = $i|sum(1)}
				{def $group_identifier = $group.group.identifier
					 $group_name = $group.group.current_translation.name|wash}
					<li id="node-tab-{$group_identifier}" class="{if $i|eq($display_groups|count)}last{else}middle{/if}{if $node_tab_index|eq($group_identifier)} selected{/if}">
				        {if $tabs_disabled}
				            <span class="disabled" title="{'Tab is disabled, enable with toggler to the left of these tabs.'|i18n( 'design/admin/node/view/full' )}">{$group_name}</span>
				        {else}
				            <a href={concat( $node_url_alias, '/(tab)/', $group_identifier )|ezurl} title="{'Show editorial overview.'|i18n( 'oweditorial' )}">{$group_name}</a>
				        {/if}
				    </li>
				{undef $group_identifier
					   $group_name}
			{/if}
		{/if}
	{/foreach}
{undef $display_groups $i}