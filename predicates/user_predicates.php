<?php

require_once 'models/Model_User.php';

/**
 * file user_predicates
 * 
 * Contains interfaces and classes for filtering user data information
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 18:37:32
 */

/**
 * Main interface for user predicates
 * 
 * All predicates shall implement the evaluate method
 * that takes a user model as parameter and returns
 * a boolean value
 */
interface UserPredicateInterface {
    
    /**
     * Tests if the user shall be included in the filtered collection
     */
    public function evaluate(Model_User $user);
}

/**
 * Regular expression filtering predicate
 * 
 * Filters the users based on the regular expression provided
 * Currently only the names of the users are checked
 * 
 */
class UserNameRegexPredicate implements UserPredicateInterface {
    
    private $_regex;
    
    public function __construct($regex) {
        $this->_regex = $regex;
    }
    
    public function evaluate(Model_User $user) {
        if (!$user) return false;
        echo '<p>Testing User: ' . $user->name . ': ';
        if (1 === preg_match($this->_regex, $user->getName())) {
            echo ' SUCCESS!!!</p>';
            return true;
        }
        echo ' FAILED!!!</p>';
        return false;
    }
}
