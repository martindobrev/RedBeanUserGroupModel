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
     * Check if we are not creating an infinite loop 
     * by assigning some of the group's children as parent 
     * for the group (cyclic graph)
     * 
     * @throws Exception
     */
    public function update() {
        // Throws an exception if an infinite loop is present
        if ($this->bean->parent) {
            foreach($this->getAllChildren() as $child) {
                if ($child->id === $this->bean->parent->id) {
                    throw new Exception('Cannot save group bean -> Subgroups cannot be assigned as parent groups');
                }
            }
        }
    }
    
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
    
    /**
     * Returns a simple hierarchical array representation of the data
     * 
     * Useful if structure export is needed when presenting data
     * as html or json
     * 
     * Contains only the basic group structure, if you added some custom 
     * data, feel free to add it here as well
     * 
     * @param int $level - level identifier for different group depth
     * @return array
     * 
     * example result (displayed in browser with print_r):
     * Array
     *   (
     *       [id] => 2
     *       [name] => Germany
     *       [level] => 0
     *       [children] => Array
     *           (
     *               [0] => Array
     *                   (
     *                       [id] => 3
     *                       [name] => Office Berlin
     *                       [level] => 1
     *                   )
     *
     *               [1] => Array
     *                   (
     *                       [id] => 4
     *                       [name] => Office Dresden
     *                       [level] => 1
     *                   )
     *           )
     *   )
     */
    public function extractGroupIndentation($level = 0) {
        $rootArray = array();
        
        $rootArray['id'] = $this->bean->id;
        $rootArray['name'] = $this->bean->name;
        $rootArray['level'] = $level;
        
        $directChildren = $this->getOwnChildren();
        
        if (count($directChildren) > 0 ) {
            $rootArray['children'] = array();
            foreach ($directChildren as $child) {
                $rootArray['children'][] = $child->box()->extractGroupIndentation($level + 1);
            }
        }
        return $rootArray;
    }
    
    /**
     * 
     * Returns a flat array representation of the data
     * 
     * Comparable to the extractGroupIndentation but the result contains only 1 level
     * 
     * @param type $level
     * @param array $storage
     */
    public function extractFlatGroupIndentation($level = 0, &$storage) {
        $rootArray = array();
        $rootArray['id'] = $this->bean->id;
        $rootArray['name'] = $this->bean->name;
        $rootArray['level'] = $level;
        $storage[] = $rootArray;
        
        $directChildren = $this->getOwnChildren();
        foreach ($directChildren as $child) {
            $child->box()->extractFlatGroupIndentation($level + 1, $storage);
        }
    }
    
}
