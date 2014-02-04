<?php

$Module = $Params['Module'];
$currentUser = eZUser::currentUser( );

if( $currentUser->hasAccessTo( 'content', 'edit' ) ) {

    $workflowStateList = eZFunctionHandler::execute( 'editorial', 'enabled_object_states', array());

    $tpl = eZTemplate::factory( );
    $Result = array( );
    $uri = eZURI::instance( eZSys::requestURI() );
 
    $viewParameters = $uri->UserParameters();
    $tpl->setVariable( 'view_parameters', $viewParameters );

    $tpl->setVariable( 'workflow_state_list', $workflowStateList );
    $Result['content'] = $tpl->fetch( 'design:editorial/dashboard.tpl' );
    $Result['left_menu'] = 'design:editorial/dashboard_left_menu.tpl';
    $Result['path'] = array(
        array(
            'url' => 'editorial/dashboard',
            'text' => ezpI18n::tr( 'oweditorial/module', 'Editorial' )
        ),
        array(
            'url' => false,
            'text' => ezpI18n::tr( 'oweditorial/module', 'Dashboard' )
        )
    );

} else {
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

?>