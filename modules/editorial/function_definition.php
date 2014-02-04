<?php

$FunctionList = array();

$FunctionList['object_state'] = array(  'name' => 'object_state',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'editorialFunctionCollection',
                                                                'method' => 'objectState' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array(
                                                                array('name' => 'state_identifier',
                                                                      'type' => 'string',
                                                                      'required' => true,
                                                                      'default' => false ),
                                                                array('name' => 'group_identifier',
                                                                      'type' => 'string',
                                                                      'required' => true,
                                                                      'default' => false )
                                                                )
                                         );
$FunctionList['message_list'] = array(  'name' => 'messages',
									'operation_types' => array( 'read' ),
									'call_method' => array( 'class' => 'editorialFunctionCollection',
											'method' => 'messageList' ),
									'parameter_type' => 'standard',
									'parameters' => array(
											array('name' => 'object_id',
													'type' => 'integer',
													'required' => true,
													'default' => false ),
											array('name' => 'sort_order',
													'type' => 'string',
													'required' => false,
													'default' => 'desc' )
									)
);

$FunctionList['nodes_by_object_state'] = array(  'name' => 'nodes_by_object_state',
                  'operation_types' => array( 'read' ),
                  'call_method' => array( 'class' => 'editorialFunctionCollection',
                      'method' => 'nodesByObjectState' ),
                  'parameter_type' => 'standard',
                  'parameters' => array(
                      array('name' => 'state',
                          'type' => 'mixed',
                          'required' => true,
                          'default' => '' ),
                      array('name' => 'group',
                          'type' => 'mixed',
                          'required' => true,
                          'default' => '' ),
                      array('name' => 'offset',
                          'type' => 'integer',
                          'required' => false,
                          'default' => 0 ),
                      array('name' => 'limit',
                          'type' => 'integer',
                          'required' => false,
                          'default' => 10 ),
                  )
);

$FunctionList['nodes_by_object_state_count'] = array(  'name' => 'nodes_by_object_state_count',
                  'operation_types' => array( 'read' ),
                  'call_method' => array( 'class' => 'editorialFunctionCollection',
                      'method' => 'nodesByObjectStateCount' ),
                  'parameter_type' => 'standard',
                  'parameters' => array(
                      array('name' => 'state',
                          'type' => 'mixed',
                          'required' => true,
                          'default' => '' ),
                      array('name' => 'group',
                          'type' => 'array',
                          'required' => true,
                          'default' => '' )
                      
                  )
);

$FunctionList['enabled_object_states_by_group'] = array(  'name' => 'enabled_object_states_by_group',
                  'operation_types' => array( 'read' ),
                  'call_method' => array( 'class' => 'editorialFunctionCollection',
                      'method' => 'enabledObjectStatesByGroup' ),
                  'parameter_type' => 'standard',
                  'parameters' => array(
                      array('name' => 'group',
                          'type' => 'string',
                          'required' => true,
                          'default' => '' )
                      
                  )
);

$FunctionList['enabled_object_states'] = array(  'name' => 'enabled_object_states',
                  'operation_types' => array( 'read' ),
                  'call_method' => array( 'class' => 'editorialFunctionCollection',
                      'method' => 'enabledObjectStates' ),
                  'parameter_type' => 'standard',
                  'parameters' => array(
                  )
);
                                         
?>