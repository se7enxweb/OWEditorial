<?php

class oweditorialinitType extends eZWorkflowEventType {

    const WORKFLOW_TYPE_STRING = 'oweditorialinit';

    function __construct( ) {
        $this->eZWorkflowEventType( oweditorialinitType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'oweditorial/event', "Editorial init" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before' ) ) ) );
    }

    function execute( $process, $event ) {
        $parameters = $process->attribute( 'parameter_list' );
        $objectID = $parameters['object_id'];
        $object = eZContentObject::fetch( $objectID );

        if( !$object instanceof eZContentObject ) {
            eZDebug::writeError( "Object with ID $objectID not found" );
            return eZWorkflowType::STATUS_REJECTED;
        }
        if( $object->attribute( 'current_version' ) != 1 ) {
            return eZWorkflowType::STATUS_ACCEPTED;
        }

        $ini = eZINI::instance( "oweditorial.ini" );
        if( !$ini->hasVariable( "Workflows", "Workflows" ) || count( $ini->variable( "Workflows", "Workflows" ) ) == 0 ) {
            eZDebug::writeError( "[Workflows]Workflows not set in oweditorial.ini" );
            return eZWorkflowType::STATUS_REJECTED;
        }
        $workflowNameList = $ini->variable( "Workflows", "Workflows" );
        foreach( $workflowNameList as $workflowName ) {
            if( !$ini->hasVariable( $workflowName, "FirstState" ) || $ini->variable( $workflowName, "FirstState" ) == '' ) {
                eZDebug::writeError( "[$workflowName]FirstState not set in oweditorial.ini" );
                continue;
            }

            $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( $workflowName );
            if( !$stateGroup instanceof eZContentObjectStateGroup ) {
                eZDebug::writeError( "Object state group $stateGroup not found" );
                continue;
            }
            $firstStateIdentifier = $ini->variable( $workflowName, "FirstState" );
            $firstState = eZContentObjectState::fetchByIdentifier( $firstStateIdentifier, $stateGroup->attribute( 'id' ) );
            if( !$stateGroup instanceof eZContentObjectStateGroup ) {
                eZDebug::writeError( "Object state $firstStateIdentifier not find in group $stateGroup" );
                continue;
            }
            $object->assignState( $firstState );
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }

}

eZWorkflowEventType::registerEventType( oweditorialinitType::WORKFLOW_TYPE_STRING, "oweditorialinitType" );
?>
