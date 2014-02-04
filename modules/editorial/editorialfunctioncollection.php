<?php


class editorialFunctionCollection
{
    /*
     * Constructor
     */
    function __construct()
    {
    }

    
    /*
     * Return ezcontentobjectstate object from identifier
     * @param string $state_identifier
     * @param string $group_identifier
     */
    static public function objectState($state_identifier, $group_identifier) {

    	if (!$state_identifier || !$group_identifier) 
    	   return $result = array( 'error' => array( 'error_type' => 'kernel',
                                                     'error_code' => eZError::KERNEL_NOT_FOUND ) );
    	
    	$group = eZContentObjectStateGroup::fetchByIdentifier($group_identifier);
        if (!($group instanceof eZContentObjectStateGroup))
        	return $result = array( 'error' => array( 'error_type' => 'kernel',
                                                      'error_code' => eZError::KERNEL_NOT_FOUND ) );
        	
    	$state = $group->stateByIdentifier( $state_identifier );
        if (!($state instanceof eZContentObjectState))
        	return $result = array( 'error' => array( 'error_type' => 'kernel',
                                                      'error_code' => eZError::KERNEL_NOT_FOUND ) );
        	
        return array('result' => $state);
    }
    
    
    /*
     * Return eZCollaborationSimpleMessage list from object id
    * @param integer $object_id
    */
    static public function messageList($object_id, $sort_order='desc') {
    	if( $sort_order != 'asc' ) {
    		$sort_order = 'desc';
    	}
    	$message_list = eZPersistentObject::fetchObjectList( 
    				eZCollaborationSimpleMessage::definition(),
    				null,
    				array( "data_int1" => $object_id ),
    				array( 'created' => $sort_order ),
    				true );
    	return array('result' => $message_list);
    }

    static public function nodesByObjectStateCount( $state, $group ) {
      return editorialFunctionCollection::fetchNodesByObjectState( true, $state, $group );
    }

    static public function nodesByObjectState( $state, $group, $offset=0, $limit=10 ) {
      return editorialFunctionCollection::fetchNodesByObjectState( false, $state, $group, $offset, $limit );
    }

    static public function fetchNodesByObjectState( $count=false, $state, $group, $offset=0, $limit=10 ) {

      $currentUser = eZUser::currentUser( );
      if( $currentUser->hasAccessTo( 'content', 'edit' ) ) {
        
        $INI = eZINI::instance( 'oweditorial.ini' );

        if( is_string($state) ) {

            $state = eZFunctionHandler::execute( 'editorial', 'object_state',
                                              array( 'state_identifier' => $state, 
                                                      'group_identifier' => $group
                                              )
                                        );

        }
        if( !$state instanceof eZContentObjectState ) {
            eZDebug::writeError( "State $state not found", "editorial/dashboard module" );
            return $result = array( 'error' => array( 'error_type' => 'kernel',
                                                      'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
            

        $classesFilter = array();
        if( $INI->hasVariable( 'dashboard_' . $group, 'Classes' ) && 
            is_array( $dashboardClasses = $INI->variable( 'dashboard_' . $group, 'Classes' ) ) &&
            count( $dashboardClasses ) ) {

            $classesFilter = array(
                'class_filter_type' => 'include',
                'class_filter_array' => $dashboardClasses
            );
        }

        $fetchParams = array(
            'parent_node_id' => 1,
            'attribute_filter' => array( array(
                    'state',
                    '=',
                    $state->attribute( 'id' )
                ) ),
            'sort_by' => array( 'modified' => FALSE )
        );
        $fetchParams = array_merge( $fetchParams, $classesFilter );
        $contentListCount = eZFunctionHandler::execute( 'content', 'tree_count', $fetchParams );
        if ($count) {
          return array('result' => $contentListCount);
        }
        $fetchParams = array_merge(
          array(
            'limit' => $limit,
            'offset' => $offset
            ),
            $fetchParams
        );
        $contentList = array( );
        if( $contentListCount > 0 ) {
            $contentList = eZFunctionHandler::execute( 'content', 'tree', $fetchParams );
            return array('result' => $contentList);
        }

    } else {
        return $result = array( 'error' => array( 'error_type' => 'kernel',
                                                      'error_code' => eZError::KERNEL_NOT_FOUND ) );
    }
  }


  static public function enabledObjectStatesByGroup ( $group ) {
    $INI = eZINI::instance( 'oweditorial.ini' );
    $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( $group );
    if( !$stateGroup instanceof eZContentObjectStateGroup ) {
        eZDebug::writeError( "State group $group not found", "oweditorial" );
        return $result = array( 'error' => array( 'error_type' => 'kernel',
                                              'error_code' => eZError::KERNEL_NOT_FOUND ) );
    }
    $result = array( );
    $stateList = $stateGroup->states( );

    if( !empty( $stateList ) ) {

        $ignoreState = $INI->hasVariable( 'dashboard_' . $group, 'IgnoreState' ) ? $INI->variable( 'dashboard_' . $group, 'IgnoreState' ) : array( );
        foreach( $stateList as $state ) {
            if( !in_array( $state->attribute( 'identifier' ), $ignoreState ) ) {
                $contentListCount = eZFunctionHandler::execute( 'editorial', 'nodes_by_object_state_count', 
                    array( 'state' => $state, 
                            'group' => $group
                ));

                $contentList = array( );
                if( $contentListCount > 0 ) {
                    $result['group'] = $stateGroup;
                    $result['states'][] = $state;
                }
            }
        }
    }
    return array('result' => $result);
  }

  static public function enabledObjectStates () {
    $INI = eZINI::instance( 'oweditorial.ini' );
    $workflowStateList = array( );

    if( $INI->hasVariable( 'Workflows', 'Workflows' ) && is_array( $INI->variable( 'Workflows', 'Workflows' ) ) ) {
        $workflowList = $INI->variable( 'Workflows', 'Workflows' );
        foreach( $workflowList as $workflow ) {
          $states = eZFunctionHandler::execute( 'editorial', 'enabled_object_states_by_group', 
              array( 'group' => $workflow )
          );
          if (count($states)) {
            $workflowStateList[] = $states;
          }
        }
    }
    return array('result' => $workflowStateList);
  }
}

?>