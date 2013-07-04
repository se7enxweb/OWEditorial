{ezcss_require( 'editorial.css' )}
{ezscript_require( 'editorial_dashboard.js' )}

<div class="editorial-dashboard-page">
    <h1>{'Editorial dashboard'|i18n( 'oweditorial/module' )}</h1>
    {foreach $workflow_state_list as $workflow}
        <div class="workflow-block" id="workflow_{$workflow.identifier}">
            <h2>{$workflow.name}</h2>
            {foreach $workflow.states as $state}
                <div class="workflow-state-block" id="state_{$workflow.identifier}_{$state.identifier}">
                    <h3>{$state.name}</h3>
                    <div class="yui-dt">
                        <table>
                            <thead><tr>
                                <th><div class="yui-dt-liner">{'Content actions'|i18n( 'oweditorial/module' )}</div></th>
                                <th><div class="yui-dt-liner">{'Name'|i18n( 'oweditorial/module' )}</div></th>
                                <th><div class="yui-dt-liner">{'Author'|i18n( 'oweditorial/module' )}</div></th>
                                <th><div class="yui-dt-liner">{'Last Modification'|i18n( 'oweditorial/module' )}</div></th>
                                <th><div class="yui-dt-liner">{'Editorial actions'|i18n( 'oweditorial/module' )}</div></th>
                            </tr></thead>
                            <tbody class="yui-dt-data">
                                {foreach $state.content_list as $idex => $content_node sequence array( 'yui-dt-even', 'yui-dt-odd' ) as $style} 
                                    <tr class="{if $index|eq(0)}yui-dt-first{/if} {$style}">
                                        <td>
                                            <a class="defaultbutton" href={$content_node.url_alias|ezurl()}>{'View'|i18n( 'oweditorial/module' )}</a>
                                            <a class="button" href={concat('content/edit/',$content_node.contentobject_id)|ezurl()}>{'Edit'|i18n( 'oweditorial/module' )}</a>
                                        </td>
                                        <td><a href={$content_node.url_alias|ezurl()}>{$content_node.name|wash()}</td>
                                        <td>{$content_node.object.owner.name|wash()}</td>
                                        <td>{$content_node.object.modified|l10n( 'shortdatetime' )}</td>
                                        <td>
                                            {foreach $state.next_states as $next_state}
                                                <form name="statesform" method="post" action={'state/assign'|ezurl} class="next-actions">
		                                          <input type="hidden" name="ObjectID" value="{$content_node.contentobject_id}" />
		                                          <input type="hidden" name="RedirectRelativeURI" value={'editorial/dashboard'|ezurl()} />
		                                          <input type="hidden" name="SelectedStateIDList[]" value="{$next_state.id}" />
		                                          <input type="submit" value="{$next_state.action|wash|i18n( 'oweditorial' )}" name="AssignButton" class="defaultbutton" />
		                                      </form>
		                                    {/foreach}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            {/foreach}
        </div>
    {/foreach}
</div>