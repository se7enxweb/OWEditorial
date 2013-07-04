<div class="editorial-dashboard-left-menu">
	<div class="box-header"><div class="box-ml">
	<h4>{'Filter by workflow and state'|i18n( 'oweditorial/module' )}</h4>
	</div></div>
    <div class="editorial-dashboard-page-filter">
		{foreach $workflow_state_list as $workflow}
		    <ul class="menu-block">
			    <li rel="workflow_{$workflow.identifier}" class="workflow enabled">{$workflow.name}</li>
			    <ul>
			    {foreach $workflow.states as $state}
			        <li rel="state_{$workflow.identifier}_{$state.identifier}" class="workflow_state enabled">{$state.name}</li>
			    {/foreach}
			    </ul>
		    </ul>
		{/foreach}
	</div>
	<div class="float-break"></div>
	<div class="box-header"><div class="box-ml">
    <h4>{'Filter by name'|i18n( 'oweditorial/module' )}</h4>
    </div></div>
    <input type="text" id="name-search" /></label></p>
    <div class="float-break"></div>
    <div class="box-header"><div class="box-ml">
    <h4>{'Filter by author'|i18n( 'oweditorial/module' )}</h4>
    </div></div>
    <input type="text" id="author-search" /></label></p>
</div>
