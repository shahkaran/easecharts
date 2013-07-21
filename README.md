easecharts
==========
This project is aimed to build a codeigniter based system for easy and quick chart generation

As the name says you can build charts easily with this project 

All you need to do is to add the given controllers, views , models , images , css at their appropriate place 

And also import the tables required 

A sql file is also included to help you add the database table 

Version 1.1:

-> No need to have access to "information_schema" database 

______________________

Version 1.0:

issues: 
-> Due to a bug in current version of CodeIgniter the code needs access to information_schema database 

	To provide this access you need to add the following line in "application/config/database.php" file : 


	$db['info']['hostname'] = [your host name];

	$db['info']['username'] = [your username];

	$db['info']['password'] = [your password];

	$db['info']['database'] = 'information_schema';

	$db['info']['dbdriver'] = 'mysql';

	$db['info']['dbprefix'] = '';

	$db['info']['pconnect'] = FALSE;

	$db['info']['db_debug'] = TRUE;

	$db['info']['cache_on'] = FALSE;

	$db['info']['cachedir'] = '';

	$db['info']['char_set'] = 'utf8';

	$db['info']['dbcollat'] = 'utf8_general_ci';

	$db['info']['swap_pre'] = '';

	$db['info']['autoinit'] = TRUE;

	$db['info']['stricton'] = FALSE;

-> Query Builder is still in beta stage
