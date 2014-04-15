<?php

require_once 'lib/rb.php';

/**
 * class Model_User
 * 
 * RedBean Model for user objects
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 17:30:21
 */
class Model_User extends \RedBean_SimpleModel {
    
    /**
     * Variable to store the user predicates for filtering users
     * 
     * @var UserPredicateInterface
     */
    private $_userPredicates = array();
    
    /**
     * Adds a new user predicate for user filtering
     * 
     * @param UserPredicateInterface $userPredicate
     */
    public function addUserPredicate(UserPredicateInterface $userPredicate) {
        $this->_userPredicates[] = $userPredicate;
    }
    
    /**
     * Resets user predicates (empty array)
     */
    public function resetUserPredicates() {
        $this->_userPredicates = array();
    }
    
    /**
     * Sets user predicates 
     * @param array $userPredicates
     */
    public function setUserPredicates($userPredicates) {
        $this->_userPredicates = $userPredicates;
    }
    
    /**
     * Returns the name of the user
     * 
     * Beans are protected property of RedBean models, so
     * to access the name from the model a public function is necessary
     * 
     * @return string
     */
    public function getName() {
        return $this->bean->name;
    }
    
    /**
     * Returns the visible users for the current user
     * 
     * If group is set, returns the visible users for the group,
     * else returns an empty array
     * 
     * @return array of RedBean_OODBBean objects
     */
    public function getVisibleUsers() {
        if ($this->bean->group) {
            $groupModel = $this->bean->group->box();
            $groupModel->setActivePermissions($this->exportPermissions());
            $groupModel->setUserPredicates($this->_userPredicates);
            return $groupModel->getAllUsers();
        } else {
            return array();
        }
    }
    
    /**
     * Gets the user role
     * @return RedBean_OODBBean
     */
    public function getRole() {
        return $this->bean->role;
    }
    
    /**
     * Gets the user permissions
     * 
     * @return array of RedBean_OODBBean objects
     */
    public function getPermissions() {
        return $this->getRole()->sharedPermission;
    }
    
    /**
     * Exports the users permissions as simple array
     * 
     * @return type
     */
    public function exportPermissions() {
        $permissionBeans = $this->getRole()->sharedPermission;
        $output = array();
        foreach ($permissionBeans as $bean) {
            $output[] = $bean->name;
        }
        return $output;
    }
}