    {* Editorial *}
{def $workflows = ezini( 'Workflows', 'Workflows', 'oweditorial.ini' )
	 $i = 0}
	{foreach $node.object.allowed_assign_state_list as $group}
		{if $workflows|contains($group.group.identifier)}
			{if $group.current.identifier|ne('none')}
				{set $i = $i|sum(1)}
				{def $group_identifier = $group.group.identifier
					 $group_name = $group.group.current_translation.name|wash}
					<li id="node-tab-{$group_identifier}" class="{if $i|eq($workflows|count)}last{else}middle{/if}{if $node_tab_index|eq($group_identifier)} selected{/if}">
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
{undef $workflows $i}