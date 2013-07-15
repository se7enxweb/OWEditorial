{def $workflows = ezini( 'Workflows', 'Workflows', 'oweditorial.ini' )}
	{foreach $node.object.allowed_assign_state_list as $group}
		{if $workflows|contains($group.group.identifier)}
			{if $group.current.identifier|ne('none')}
				{def $group_identifier = $group.group.identifier
					 $group_name = $group.group.current_translation.name|wash}
				    <div id="node-tab-{$group_identifier}-content" class="tab-content{if $node_tab_index|ne( $group_identifier )} hide{else} selected{/if}">
						<div class="block">
									
							<ul class="editorial_states">
							{foreach $group.states as $state}
								{if $state.identifier|ne('none')}
									<li{if $state.id|eq($group.current.id)} class="current"{/if}>
										<form name="statesform" method="post" action={'state/assign'|ezurl} class="button-left"{*
				                        	*}{if ezini_hasvariable(concat('notifications_', $state.identifier), 'Alert', 'oweditorial.ini')}{*
												*}onsubmit="return confirm('{ezini(concat('notifications_', $state.identifier), 'Alert', 'oweditorial.ini')|explode("'")|implode("\\'")}');"{*
											*}{/if}{*
										*}>
											<input type="hidden" name="ObjectID" value="{$node.object.id}" />
											<input type="hidden" name="RedirectRelativeURI" value="{$node.url_alias}" />
											<input type="hidden" name="SelectedStateIDList[]" value="{$state.id}" />
											<input type="submit" value="{'Set state'|i18n( 'oweditorial' )}" name="AssignButton" {if $state.id|eq($group.current.id)}class="button-disabled" disabled="disabled"{else}class="button"{/if} />
										</form>
										
										{$state.current_translation.name}
										
										{if $state.id|eq($group.current.id)}
											{if ezini_hasvariable($group_identifier, $state.identifier, 'oweditorial.ini')}
						
												{foreach ezini($group_identifier, $state.identifier, 'oweditorial.ini') as $to => $action}
													{def $state_to = fetch('editorial' , 'object_state', hash( 'group_identifier' , $group.group.identifier,
							                                                                				'state_identifier' , $to))}
							                            {if $node.object.allowed_assign_state_id_list|contains($state_to.id)}
								                        	<form class="next-actions" name="statesform" method="post" action={'state/assign'|ezurl}{*
									                        	*}{if ezini_hasvariable(concat('notifications_', $state.identifier), 'Alert', 'oweditorial.ini')}{*
																	*}onsubmit="return confirm('{ezini(concat('notifications_', $state.identifier), 'Alert', 'oweditorial.ini')|explode("'")|implode("\\'")}');"{*
																*}{/if}{*
															*}>
																<input type="hidden" name="ObjectID" value="{$node.object.id}" />
																<input type="hidden" name="RedirectRelativeURI" value="{$node.url_alias}" />
																<input type="hidden" name="SelectedStateIDList[]" value="{$state_to.id}" />
																<input type="submit" value="{$action|wash|i18n( 'oweditorial' )}" name="AssignButton" class="defaultbutton" />
															</form>
														{/if}
							                        {undef $state}
												{/foreach}
											{/if}
										{/if}
									</li>
								{/if}
							{/foreach}
							</ul>
						</div>
					</div>
				{undef $group_identifier
					   $group_name}
			{/if}
		{/if}
	{/foreach}
{undef $workflows}