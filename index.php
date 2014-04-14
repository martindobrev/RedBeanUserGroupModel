<?php

/**
 * file index
 * 
 * Entry point for our user-group model testing
 * 
 * 1. Sets up the database (SQLite DB with RedBeanPHP) 
 *
 * @author Martin Dobrev <md@wtevr.de>
 * @created 14.04.2014 13:35:30
 */

require_once 'lib/rb.php';  // load RedBeanPHP libraries
require_once 'models/Model_Group.php';

$dbFileDir = __DIR__ . '/sqlite/';
$dbFile = 'db.sqlite3';

// RedBean setup (sqlite)
R::setup('sqlite:' . $dbFileDir . $dbFile);

include_once 'init_db.php';

/******* INFINITE LOOP EXCEPTION TEST *******/
//$germany = R::load('group', 2);
//$berlin = R::load('group', 3);
//
//$germany->parent = $berlin;
//R::store($germany); // SHALL RAISE AN EXCEPTION


$user = R::load('user', 5);
$userBox = $user->box();

$predicate = new UserNameRegexPredicate('/^[a-zA-Z_]*2$/');
$predicate2 = new UserNameRegexPredicate('/Dresden/');

$userBox->addUserPredicate($predicate);
$userBox->addUserPredicate($predicate2);
$visibleUsers = $userBox->getVisibleUsers();

echo '<h2>Visible users for user "' . $user->name . '": </h2>';
echo '<pre>';
foreach ($visibleUsers as $u) {
    print_r($u->export());
}

echo '</pre>';
die();



/*   FILTERED USERS TEST 
$germany = R::load('group', 2);

$predicate = new UserNameRegexPredicate('/^[a-zA-Z_]*2$/');

$germanyModel = $germany->box();
$germanyModel->setUserPredicate($predicate);
$germanyUsersThatEndWith2 =$germanyModel->getAllUsers();



echo '<h2> Germany users ending by "2":</h2>';

foreach ($germanyUsersThatEndWith2 as $user) {
    echo '<pre>';
    print_r($user->export());
    echo '</pre>';
}
*/

/* GERMANY USERS TEST
$germanyUsers = $germany->box()->getAllUsers();

echo '<pre>';
foreach ($germanyUsers as $user) {
    print_r($user->export());
}
echo '</pre>';
die();
 */

/* INFINITE LOOP EXCEPTION
$flatGermanyStruct = array();
$germany->box()->extractFlatGroupIndentation(0, $flatGermanyStruct);
echo '<pre>';
print_r($flatGermanyStruct);
echo '</pre>';
die();
*/


/*  ROOT CHILDREN DISPLAY
$root = R::load('group', 1);
$children = $root->box()->getAllChildren();
echo '<h2>Company structure:</h2>';
echo '<pre>';
foreach ($children as $child) {
    print_r($child->export());
}
echo '</pre>';
 */



