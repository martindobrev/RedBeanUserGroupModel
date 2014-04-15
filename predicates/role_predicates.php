<?php

require_once 'models/Model_User.php';
require_once 'models/Model_Group.php';

/**
 * file role_predicates
 * 
 * TODO: ADD YOUR DESCRIPTION 
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 15.04.2014 18:53:59
 * @copyright (c) 2013, WTEVR
 */
interface PermissionPredicateInterface {
    public function evaluateUser(Model_User $user);
    public function evaluateGroup(Model_Group $group);
}


