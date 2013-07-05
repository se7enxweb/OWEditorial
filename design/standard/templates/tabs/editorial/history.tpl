<div class="block">
	{def $message_list = fetch( 'editorial', 'message_list', hash('object_id', $node.object.id, 'sort_order', 'desc') )}
		{if $message_list|count}
			{def $aut = false()}
			<table class="list message_list" cellspacing="0">
				<tr>
				    <th>{'Auteur'|i18n('oweditorial')}</th>
				    <th>{'Action'|i18n('oweditorial')}</th>
				    <th>{'Date'|i18n('oweditorial')}</th>
				    <th>{'Version'|i18n('oweditorial')}</th>
				</tr>
				{foreach $message_list as $message sequence array( bglight, bgdark ) as $sequence}
					{set $aut = fetch( 'content', 'object', hash( 'object_id', $message.creator_id ) )}
					<tr class="{$sequence} type_{$message.message_type}">
						<td class="author">
							<a href="{$aut.main_node.url_alias|ezurl(no)}">{$aut.name|wash()}</a>
						</td>
						<td class="action">
							{$message.data_text2|wash()}
						</td>
						<td class="date">
							{$message.created|l10n( 'shortdatetime' )}
						</td>
						<td class="version">
							{$message.data_int2}
						</td>
					</tr>
				
				{/foreach}
			</table>
		{/if}
	{undef $message_list}
</div>