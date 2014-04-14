<?php

require_once 'lib.rb.php';

/**
 * class Model_User
 * 
 * RedBean Model for user objects
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 17:30:21
 */
class Model_User extends \RedBean_SimpleModel {
    
    public function getName() {
        return $this->bean->name;
    }
}