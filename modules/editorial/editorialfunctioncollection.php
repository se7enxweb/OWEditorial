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

}

?>
