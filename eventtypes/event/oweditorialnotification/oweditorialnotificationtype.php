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
    	
  		// Create collaboration message (history)
    	$oweditorial_ini = eZIni::instance('oweditorial.ini');
    	if ($oweditorial_ini->hasVariable('Workflows', 'Workflows')) {
    		$workflows = $oweditorial_ini->variable('Workflows', 'Workflows');
	    	$user = eZUser::currentUser();
	    	$time = time();
	    	foreach ($parameters['state_id_list'] as $state_id) {
	    		$state = eZContentObjectState::fetchById($state_id);
	    		$group_identifier = $state->group()->Identifier;
	    		if ( in_array($group_identifier , $workflows) ) {
		    		owEditorialNotification::createCollaborationMessage( 
		    				$parameters['object_id'], 
		    				$content_object->CurrentVersion, 
		    				$state->currentTranslation()->Name, 
		    				$user->attribute( 'contentobject_id' ), 
		    				$time, 
		    				$time,
		    				$group_identifier,
		    				'objectstate_update'
		    		);
	    		}
	    	}
    	}
    	
    	// Send mail notifications
    	/*$owEditorialNotification = new owEditorialNotification( $parameters['object_id'], $parameters['state_id_list'] );
        return $owEditorialNotification->send( ) ? eZWorkflowType::STATUS_ACCEPTED : eZWorkflowType::STATUS_REJECTED;*/
    	return eZWorkflowType::STATUS_ACCEPTED;
    }

}

eZWorkflowEventType::registerEventType( oweditorialnotificationType::WORKFLOW_TYPE_STRING, "oweditorialnotificationType" );
?>
