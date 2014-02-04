<?php

include_once( 'kernel/common/template.php' );

class ezJsOwEditorial
{
	
	public static function getDashboardItems( $args )
    {
    	$input = $_POST;

        $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( $input['group_identifier'] );
        $state = eZFunctionHandler::execute( 'editorial', 'object_state',
                                              array( 'state_identifier' => $input['state_identifier'], 
                                                      'group_identifier' => $input['group_identifier']
                                              )
                                        );

		$tpl = templateInit();
    $tpl->setVariable( 'state_group', $stateGroup );
		$tpl->setVariable( 'state', $state );
    $tpl->setVariable( 'offset', $input['offset'] );
		return $tpl->fetch( 'design:editorial/dashboard_list.tpl' );

    }

}
?>
