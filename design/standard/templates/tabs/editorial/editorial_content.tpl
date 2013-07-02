{ezcss_require( 'editorial.css' )}
{def $display_groups = array()}
	{foreach ezini( 'Workflows', 'Workflows', 'oweditorial.ini' ) as $workflow}
		{set $display_groups = $display_groups|append( ezini( $workflow, 'StateGroup', 'oweditorial.ini' ) )}
	{/foreach}
	{foreach $node.object.allowed_assign_state_list as $group}
		{if $display_groups|contains($group.group.identifier)}
			{def $group_identifier = $group.group.identifier
				 $group_name = $group.group.current_translation.name|wash}
			    <div id="node-tab-{$group_identifier}-content" class="tab-content{if $node_tab_index|ne( $group_identifier )} hide{else} selected{/if}">
					<div class="block">
								
						<ul class="editorial_states">
						{foreach $group.states as $state}
							<li{if $state.id|eq($group.current.id)} class="current"{/if}>
								<form name="statesform" method="post" action={'state/assign'|ezurl} class="button-left">
										<input type="hidden" name="ObjectID" value="{$node.object.id}" />
										<input type="hidden" name="RedirectRelativeURI" value="{$node.url_alias}" />
										<input type="hidden" name="SelectedStateIDList[]" value="{$state.id}" />
										<input type="submit" value="{'Set state'|i18n( 'extension/oweditorial' )}" name="AssignButton" {if $state.id|eq($group.current.id)}class="button-disabled" disabled="disabled"{else}class="button"{/if} />
								</form>
								{$state.current_translation.name}
							</li>
						{/foreach}
						</ul>
					</div>
				</div>
			{undef $group_identifier
				   $group_name}
		{/if}
	{/foreach}
{undef $display_groups}