<?php

class oweditorialinitType extends eZWorkflowEventType {
    const WORKFLOW_TYPE_STRING = 'oweditorialinit';

    function __construct( ) {
        throw new Exception( "Not yet implemented" );
    }

    function execute( $process, $event ) {
        throw new Exception( "Not yet implemented" );
    }

}

eZWorkflowEventType::registerEventType( oweditorialinitType::WORKFLOW_TYPE_STRING, "oweditorialinitType" );
?>
