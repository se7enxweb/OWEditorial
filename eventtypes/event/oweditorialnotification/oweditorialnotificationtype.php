<?php

class oweditorialnotificationType extends eZWorkflowEventType {

    const WORKFLOW_TYPE_STRING = 'oweditorialnotification';

    function __construct( ) {
    	$this->eZWorkflowEventType( oweditorialnotificationType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'oweditorial/event', "Editorial notifications" ) );
    	$this->setTriggerTypes( array( 'content' => array( 'updateobjectstate' => array( 'after' ) ) ) );
    	$this->site_ini = eZIni::instance('site.ini');
    }

    function execute( $process, $event )
    {
    	$parameters = $process->attribute( 'parameter_list' );
    	$content_object = eZContentObject::fetch( $parameters['object_id'] );
    	$owEditorialNotification = new owEditorialNotification( $parameters['object_id'], $parameters['state_id_list'] );
    	$owEditorialNotification->send( );
    	exit;
		$owEditorialNotification = new owEditorialNotification( $parameters['object_id'], $parameters['state_id_list'] );
        return $owEditorialNotification->send( ) ? eZWorkflowType::STATUS_ACCEPTED : eZWorkflowType::STATUS_REJECTED;
    }

}

eZWorkflowEventType::registerEventType( oweditorialnotificationType::WORKFLOW_TYPE_STRING, "oweditorialnotificationType" );
?>
