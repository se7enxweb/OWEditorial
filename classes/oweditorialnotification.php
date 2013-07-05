<?php 

class owEditorialNotification {
	
	var $site_ini;
	var $oweditorial_ini;
	var $object;
	var $state_id_list;
	
	const HTML_CONTENT_TYPE = 'text/html';
	const TEXT_CONTENT_TYPE = 'text/plain';
	
	public function __construct( $object_id, $state_id_list )
    {
	    if ( $object_id && $state_id_list ) {
	        $this->site_ini = eZIni::instance('site.ini');
	        $this->oweditorial_ini = eZIni::instance('oweditorial.ini');
	        $this->object = eZContentObject::fetch( $object_id );
	        $this->state_id_list = $state_id_list;
	    } else {
	    	$this->error( 'Input error' );
	    	return false;
	    }
    }
	
    
    static public function createCollaborationMessage( $object_id, $version_id, $message, $user_id, $created, $modified, $additional_message='', $message_type='objectstate_update' ) {
    	 
    	$comment = new eZCollaborationSimpleMessage(
    			array(
		    			'data_int1'    => $object_id,
		    			'data_int2'    => $version_id,
		    			'data_text1'   => $message,
		    			'data_text2'   => $additional_message,
		    			'creator_id'   => $user_id,
		    			'created'      => $created,
		    			'modified'     => $modified,
    			 		'message_type' => $message_type,
    			)
    	);
    	return $comment->store();
    }
    
    /**
     * Send notifications
     * 
     * @return boolean
     */
	public function send ( )
    {
		$return = true;
        $content_type = $this->getEmailContentType();
		$sender = $this->getEmailSender();
		
		foreach ($this->state_id_list as $state_id) {
			$state = eZContentObjectState::fetchById($state_id);
	        $receivers = $this->getReceivers($state->Identifier);
	        
	        $subject = $this->getEmailSubject( $state->Identifier );
	        $body = $this->getEmailBody( $subject, $this->object, $content_type, $state );
			
	        foreach ( $receivers as $mailAddress ) {

        		if ( $mailAddress ) {

			        $mail = new eZMail();
			        $mail->setContentType( $content_type );
			        $mail->setReceiver( $mailAddress );
			        $mail->setSender( $sender );
			        $mail->setSubject( $subject );
			        $mail->setBody( $body );
			        
			        $result = eZMailTransport::send( $mail );
			        $return = ($return && $result);
			        if ( !$result ) {
			        	$this->error( 'Error when sending notification at address :' . $mailAddress );
			        }
        		}

	    	}
		}
		return $return;
    }
    
    /**
     * Get email sender
     *
     * @return string
     */
	protected function getEmailSender()
    {
        if($this->site_ini->hasVariable( 'MailSettings', 'EmailSender' ))
        {
            $email_sender = $this->site_ini->variable( 'MailSettings', 'EmailSender' );
        }
        else
        {
            $email_sender = $this->site_ini->variable( "MailSettings", "AdminEmail" );
        }
        return $email_sender;
    }
	
    /**
     * Get subject for a state identifier
     *
     * @param string $state_identifier
     * @return string
     */
    protected function getEmailSubject( $state_identifier )
    {
    	if( $this->oweditorial_ini->hasVariable('notifications_'.$state_identifier, 'Subject') ) {
	        return $this->oweditorial_ini->variable('notifications_'.$state_identifier, 'Subject');
    	} else {
    		return '"' . $this->object->attribute('name') . '" setted to "' . $state_identifier . '"';
    	}
    }
	
    /**
     * Get mail content type
     *
     * @return string
     */
    protected function getEmailContentType()
    {
        return $this->site_ini->hasVariable('MailSettings', 'ContentType') ? $this->site_ini->variable('MailSettings', 'ContentType') : self::HTML_CONTENT_TYPE;
    }

    /**
     * Get receivers for a state identifier
     *
     * @param string $state_identifier
     * @return string
     */
	protected function getReceivers( $state_identifier ) {
		$receivers = array();
		if ( $this->oweditorial_ini->hasVariable('notifications_'.$state_identifier, 'Receivers') ) {
			$receivers_settings = array();
			foreach ( $this->oweditorial_ini->variable('notifications_'.$state_identifier, 'Receivers') as $notif_settings) {
				
				$notif_array = explode(';', $notif_settings);
				if (count($notif_array) > 1) {
					$receivers_settings[$notif_array[0]] = $notif_array[1];
				} else {
					$receivers_settings['others'] = $notif_array[0];
				}
			}
			if (isset($receivers_settings['attribute'])) {
				$receivers = array_merge( $receivers, $this->getReceiversByAttribute( (array)$receivers_settings['attribute'] ) );
			}
			if (isset($receivers_settings['group'])) {
				foreach ((array)$receivers_settings['group'] as $group_id) {
					$receivers = array_merge( $receivers, $this->getReceiversByUserGroupID( $group_id ) );
				}
			}
			if (isset($receivers_settings['user'])) {
				foreach ((array)$receivers_settings['user'] as $user_id) {
					$mail = $this->getReceiverByUserID( $user_id );
					if($mail) {
						$receivers = array_merge( $receivers, (array)$mail );
					}
				}
			}
			if (isset($receivers_settings['others'])) {
				foreach ((array)$receivers_settings['others'] as $setting ) {
					switch($setting) {
						case 'owner':
							$owner = $this->object->attribute( 'owner');
							$owner_email = $this->emailFromUser($owner);
							if($owner_email) {
								$receivers = array_merge( $receivers, (array)$owner_email);
							}
						break;
					}
				}
			}
		}
		
		return $receivers;
	}
	
    /**
     * Returns an array containing all receivers for a content object attribute (ezstring or ezobjectrelationlist of users)
     *
     * @return array
     */
    protected function getReceiversByAttribute( $receiverAttributeIdentifierList )
    {
    	$dataMap = $this->object->dataMap();
    	foreach ( (array)$receiverAttributeIdentifierList as $receiverAttributeIdentifier ) {
        	$receiverAttribute = $dataMap[$receiverAttributeIdentifier];
        	if ( $receiverAttribute instanceof eZContentObjectAttribute ) {
        		// Supports User Object relation list
        		if ( $receiverAttribute->DataTypeString == 'ezobjectrelationlist' ) {
        			$relationListContent = $receiverAttribute->content();
        			$relationList = $relationListContent['relation_list'];
        			foreach($relationList as $relation) {
        				$objectRelation = eZContentObject::fetch($relation['contentobject_id']);
        				$email = $this->emailFromUser($objectRelation);
        				if($email) {
        					$receivers[] = $email;
        				}
        			}
        		} elseif ( $receiverAttribute->DataTypeString == 'ezstring' ) {
		        	if ( $receiverAttribute->value() ) {
		        		$receivers[] = $receiverAttribute->value();
		        	}
        		}
        	}
    	}
    	
    	return $receivers;
    }
	
    /**
     * Returns email for a user object id
     *
     * @return array
     */
    protected function getReceiverByUserID( $user_id ) {
    	if( $user_id ) {
    		return $this->emailFromUser( eZContentObject::fetch( $user_id ) );
    	}
    	return false;
    }
    
    /**
     * Returns an array containing all receivers for a user group object id
     *
     * @return array
     */
    protected function getReceiversByUserGroupID( $group_id ) {
    	$receivers = array();
    	if ( $group_id ) {
    		$group = eZContentObject::fetch( $group_id );
    		$users = eZFunctionHandler::execute( 
    					'content', 'tree', 
    					array( 'parent_node_id' => $group->attribute( 'main_node_id' ),
    							'class_filter_type' => 'include',
    							'class_filter_array' => array( 'user' ),
    							'limitation' => array()
    					)
    		);
    	
    		if( count( $users ) ) {
    			foreach( $users as $user ) {
    				$mail = $this->emailFromUser( $user->attribute( 'object' ) );
    				if ($mail) {
    					$receivers[] = $mail;
    				}
    			}
    		}
    	}
    	return $receivers;
    }
    
    /**
     * Get content of template, based on state identifier
     *
     * @param string $subject
     * @param eZContentObject $content_object
     * @param string $content_type
     * @param string $state_identifier
     * @return string
     */
    protected function getEmailBody( $subject, $content_object, $content_type=self::HTML_CONTENT_TYPE, $state )
    {

        if ( $content_type == self::HTML_CONTENT_TYPE ) {
        	$template_type = 'Html';
        } else {
        	$template_type = 'Text';
        }
        if( $this->oweditorial_ini->hasVariable('notifications_'.$state->Identifier, $template_type.'Template') ) {
        	$template = $this->oweditorial_ini->variable('notifications_'.$state->Identifier, $template_type.'Template');
        } else {
        	$template = 'editorial/mail/'.strtolower($template_type).'.tpl';
        }
        
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'subject', $subject );
        $tpl->setVariable( 'content_object', $content_object );
        $tpl->setVariable( 'hostname', eZSys::hostname() );
        $tpl->setVariable( 'state', $state );
        
        return $tpl->fetch( 'design:' . $template );
    }
    
    
    /**
     * Get email address from an eZContentObject user
     *
     * @param eZContentObject $user
     * @return string
     */
    protected function emailFromUser($user) {
    	if ($user instanceof eZContentObject) {
	    	$user_data_map = $user->dataMap();
	    	foreach($user_data_map as $user_attribute) {
	    		if ( $user_attribute->DataTypeString == 'ezuser' ) {
	    			$user_attribute_content = $user_attribute->content();
	    			if($user_attribute_content->Email) {
	    				return $user_attribute_content->Email;
	    			}
	    		}
	    	}
    	} else {
    		return false;
    	}
    }
    
    /**
     * Display error message
     *
     * @param string $msg
     * @return boolean
     */
	protected function error ( $msg ) {
		
		if ($msg) {
			eZDebug::writeError( "[OWEditorial notifications] : " . $msg );
			return true;
		} else {
			return false;
		}
		
	}
}

?>