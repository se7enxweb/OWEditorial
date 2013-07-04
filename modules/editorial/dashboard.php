<?php

$Module = $Params['Module'];
$currentUser = eZUser::currentUser( );

if( $currentUser->hasAccessTo( 'content', 'edit' ) ) {
    $tpl = eZTemplate::factory( );

    $INI = eZINI::instance( 'oweditorial.ini' );
    $workflowStateList = array( );

    if( $INI->hasVariable( 'Workflows', 'Workflows' ) && is_array( $INI->variable( 'Workflows', 'Workflows' ) ) ) {
        $workflowList = $INI->variable( 'Workflows', 'Workflows' );
        foreach( $workflowList as $workflow ) {
            $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( $workflow );
            if( !$stateGroup instanceof eZContentObjectStateGroup ) {
                eZDebug::writeError( "State group $workflow not found", "editorial/dashboard module" );
                continue;
            }
            $stateList = $stateGroup->states( );
            if( !empty( $stateList ) ) {
                $workflowArray = array(
                    'name' => $stateGroup->attribute( 'current_translation' )->attribute( 'name' ),
                    'identifier' => $stateGroup->attribute( 'identifier' ),
                    'states' => array( )
                );
                $ignoreState = $INI->hasVariable( 'dashboard_' . $workflow, 'IgnoreState' ) ? $INI->variable( 'dashboard_' . $workflow, 'IgnoreState' ) : array( );
                foreach( $stateList as $state ) {
                    if( !in_array( $state->attribute( 'identifier' ), $ignoreState ) ) {
                        $contentList = eZFunctionHandler::execute( 'content', 'tree', array(
                            'parent_node_id' => 1,
                            'attribute_filter' => array( array(
                                    'state',
                                    '=',
                                    $state->attribute( 'id' )
                                ) ),
                            'sort_by' => array( 'modified' => FALSE )
                        ) );
                        if( !empty( $contentList ) ) {
                            $nextStateList = array( );
                            if( $INI->hasVariable( $stateGroup->attribute( 'identifier' ), $state->attribute( 'identifier' ) ) ) {
                                $ININextStateArray = $INI->variable( $stateGroup->attribute( 'identifier' ), $state->attribute( 'identifier' ) );
                                foreach( $ININextStateArray as $nextStateIdentifier => $actionName ) {
                                    $nextState = $stateGroup->stateByIdentifier( $nextStateIdentifier );
                                    if( !$nextState instanceof eZContentObjectState ) {
                                        eZDebug::writeError( "State $nextStateIdentifier not found in group $workflow", "editorial/dashboard module" );
                                        continue;
                                    }
                                    $nextStateList[] = array(
                                        'id' => $nextState->attribute( 'id' ),
                                        'action' => $actionName
                                    );
                                }
                            }
                            $workflowArray['states'][] = array(
                                'name' => $state->attribute( 'current_translation' )->attribute( 'name' ),
                                'identifier' => $state->attribute( 'identifier' ),
                                'content_list' => $contentList,
                                'next_states' => $nextStateList
                            );
                        }
                    }
                }
                $workflowStateList[] = $workflowArray;
            } else {
                eZDebug::writeWarning( "State group $workflow is empty", "editorial/dashboard module" );
            }
        }
    }

    $tpl->setVariable( 'workflow_state_list', $workflowStateList );

    $Result = array( );
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