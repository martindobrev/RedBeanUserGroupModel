<?php

require_once 'lib/rb.php';

/**
 * Initiates the database
 * 
 * Also adds some entries to the tables for testing purposes
 * Since this is a test project, the database is 
 * resetted if this script is included. If you want to change this
 * behaviour, just don't include the script 
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 13:41:40
 */

/**
 * Creates a new permission
 * 
 * @param string $name
 * @return RedBean_OODBBean permission bean
 */
function createPermission($name) {
    echo '<p>Creating permission ' . $name . '</p>';
    $bean = R::dispense('permission');
    $bean->setAttr('name', $name);
    R::store($bean);
    return $bean;
}

/**
 * Creates a new role
 * 
 * @param string $name
 * @param array of RedBean_OODBBean objects $permissions
 * @return RedBean_OODBBean role bean
 */
function createRole($name, $permissions) {
    $bean = R::dispense('role');
    $bean->setAttr('name', $name);
    if (0 < count($permissions)) {
        $bean->sharedPermission = $permissions;
    } 
    R::store($bean);
    return $bean;
}


/**
 * Creates a new group
 * 
 * For our tests, the group will have only one attribute - name
 * You can of course add additional attributes if you want
 * 
 * @param string $name
 * @param RedBean_OODBBean $parentGroup
 */
function createGroup($name, RedBean_OODBBean $parentGroup = null) {
    $bean = R::dispense('group');
    $bean->setAttr('name', $name);
    if (null !== $parentGroup) {
        $bean->setAttr('parent', $parentGroup);
    }
    R::store($bean);
    return $bean;
}

/**
 * Creates a new user
 * 
 * Users will also have only a name attribute,
 * Feel free to add additional attributes
 * 
 * @param type $name
 * @param type $group
 * @return type
 */
function createUser($name, $group = null, $role = null) {
    $bean = R::dispense('user');
    $bean->setAttr('name', $name);
    if (null !== $group) {
        $bean->setAttr('group', $group);
    }
    
    if (null !== $role) {
        $bean->setAttr('role', $role);
    }
    
    R::store($bean);
    return $bean;
}

/**
 * Creates some dummy permissions for testing purposes
 */
function initPermissions() {
    createPermission('VIEW_SUBUSERS');
    createPermission('EDIT_SUBUSERS');
    createPermission('VIEW_OWN_GROUP_USERS');
    createPermission('EDIT_OWN_GROUP_USERS');
    createPermission('VIEW_SUBGROUPS');
    createPermission('EDIT_SUBGROUPS');
    createPermission('EDIT_OWN_GROUP');
}

/**
 * Creates some dummy roles for testing purposes
 */
function initRoles() {
    $viewSubusers = R::load('permission', 1);
    $editSubusers = R::load('permission', 2);
    $viewOwnGroupUsers = R::load('permission', 3);
    $editOwnGroupUsers = R::load('permission', 4);
    $viewSubgroups = R::load('permission', 5);
    $editSubgroups = R::load('permission', 6);
    $editOwnGroup = R::load('permission', 7);
    
    
    // ADMIN CAN ACCESS AND MODIFY DATA CORRESPONDING TO HIS VISIBILITY LEVEL
    $adminRole = createRole('ADMIN', array($viewSubusers, $editSubusers
                                          , $viewOwnGroupUsers, $editOwnGroupUsers
                                          , $viewSubgroups, $editSubgroups
                                          , $editOwnGroup
                                          )
                            );
    
    // NORMAL USER CAN SEE EVERYTHING FROM HIS LEVEL DOWN, BUT CANNOT EDIT
    $normalUser = createRole('USER', array($viewSubusers, $viewSubgroups));
    
    // UNTRUSTED USER DOES NOT HAVE ANY PRIVILEGES !!!
    $restrictedUser = createRole('UNTRUSTED_USER', array());
    
}

/**
 * Creates a simple dummy company structure for testing purposes
 * 
 * The created structure is:
 * 
 * COMPANY_ROOT   - Root group for the company
 *     * Germany - Group for offices in germany 
 *         * Office Berlin - group for the employees in Berlin
 *         * Office Dresden - group for the employees in Dresden
 *     * UK      - Group for offices in the UK
 *         * Office London - group for the employees in London
 *         * Office Edinburgh - group for employees in Edinburgh
 */ 
function initGroups() {
    $root = createGroup('COMPANY_ROOT');
    $germany = createGroup('Germany', $root);
    createGroup('Office Berlin', $germany);
    createGroup('Office Dresden', $germany);
    
    $uk = createGroup('UK', $root);
    createGroup('Office London', $uk);
    createGroup('Office Edinburgh', $uk);
}

/**
 * Creates dummy users
 * 
 * Three users are added to each group. They are named after the group
 */
function initUsers() {
    $groups = R::findAll('group');
    
    foreach ($groups as $group) {
        for ($i = 0; $i < 3; $i++) {
            createUser(str_replace(' ', '_', $group->name) . '_User_' . $i, $group, R::load('role', $i + 1));
        }
    }
}

R::nuke(); // RESET DATABASE
initPermissions(); // Create dummy permissions
initRoles(); // Create dummy roles
initGroups(); // Create dummy groups
initUsers(); // Create dummy users