RedBeanUserGroupModel
=====================

This is a simple project that demonstrates how to create a simple group-based structure for a dummy web project. The project is designed as a tutorial, so it will be 
extended step-by-step. Each phase will add additional features.

Project Structure 
-----------------

The project uses RedBeanPHP ORM (version 3.5) and SQLite as database engine. If you are not familiar with RedBean, check out the documentation on the project's [link](http://redbeanphp.com/).


The structure is pretty simple:
  * lib folder - contains the redbeanphp libraries
  * models folder - contains custom redbeanphp models
  * sqlite folder - contains the sqlite database file
  * index.php     - main script to test the created structures
  * init_db.php   - initialize a database to test

Installation
------------

Just clone the project to your web server and call the index.php script from your browser. If you don't have SQLITE installed, change the RedBean setup to whatever database you use.


Part 1 - Basic setup and custom group model
-------------------------------------------

In this part we initialize the basic project setup, create a simple database and a basic model for the group beans. The model is extended with two simple functions 
that return the the own subgroups and the complete list of subgroups respenctively.

