<?php

require_once 'lib/rb.php';

/**
 * class Model_Group
 * 
 * RedBean Model for group objects
 * 
 * Provides some additional methods to conveniently access group-related
 * information such as all subgroups etc...
 * 
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 14:12:52
 */
class Model_Group extends \RedBean_SimpleModel {
    
    /**
     * returns only the direct children of the current group
     * 
     * @return array of RedBean_OODBBean objects
     */
    public function getOwnChildren() {
        return $this->bean->alias('parent')->ownGroup;
    }
    
    /**
     * Returns all children of the current group
     * 
     * @return array of RedBean_OODBBean objects
     */
    public function getAllChildren() {
        $directChildren = $this->getOwnChildren();
        $children = $directChildren;
        foreach ($directChildren as $child) {
            $children = array_merge($children, $child->box()->getAllChildren());
        }
        return $children;
    }
}
