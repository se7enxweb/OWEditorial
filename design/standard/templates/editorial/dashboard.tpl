{ezcss_require( 'editorial.css' )}
{ezscript_require( 'editorial_dashboard.js' )}
<div class="editorial-dashboard-page">
    <h1>{'Editorial dashboard'|i18n( 'oweditorial/module' )}</h1>
    {foreach $workflow_state_list as $workflow}
        <div class="workflow-block" id="workflow_{$workflow.state_group.identifier}">
            <h2>{$workflow.group.current_translation.name}</h2>
            {foreach $workflow.states as $state}
                {include uri='design:editorial/dashboard_list.tpl'
                     state=$state
                     state_group=$workflow.group}
            {/foreach}
        </div>
    {/foreach}
</div>