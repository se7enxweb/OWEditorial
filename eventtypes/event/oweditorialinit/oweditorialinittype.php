<?php

class oweditorialinitType extends eZWorkflowEventType {
    const WORKFLOW_TYPE_STRING = 'oweditorialinit';

    function __construct( ) {
        $this->eZWorkflowEventType( oweditorialinitType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'kernel/workflow/event', "Editorial" ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'before' ) ) ) );
    }

    function execute( $process, $event ) {
        throw new Exception( "Not yet implemented" );
    }

    function validateHTTPInput( $http, $base, $workflowEvent, &$validation ) {
    }

    function fetchHTTPInput( $http, $base, $event ) {
    }

}

eZWorkflowEventType::registerEventType( oweditorialinitType::WORKFLOW_TYPE_STRING, "oweditorialinitType" );
?>
