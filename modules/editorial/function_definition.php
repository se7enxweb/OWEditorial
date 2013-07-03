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

                                         
?>