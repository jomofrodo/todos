Todos
John Moore
July 2005

Todos is a collection of php scripts to create, update, edit, search and display entries in a Todos database.


Installation:
Create a web project with the following directory structure:

	/<root>
	/www 	-- root for website files
	/www/_include		-- configuration files
	/www/_lib		--  3rd party libraries
	/www/IDX		-- root for Todos pages

	Checkout Todos to /www/todos or /www/_lib/todos


	Add any pages neccessary for the app, other than todos pages, under /www, e.g., /www/index.php

	

2. Config
	Copy files from /www/todos/_include to /www/_include. Do not include the /www/todos/_include/CVS directory
	o Rename *.eo to *.php
	o edit todosConfig.php
	o edit login.conf.php

3. User account
	On db machine, as root
	# useradd <login_name>

5. Checkout Module to db/web machine
	# cd /home
	# cvs checkout -d <login> <module>
	# chown -R login.group <login>


4. MYSQL database
	# mysqladmin create <db_name> -p
	# mysql -p
	mysql> GRANT ALL ON <db_name>.* TO <login_name> identified by '<passwd>';
	mysql> exit;
	# mysqladmin reload -p
	# mysql -p <db_name> < todosTD.sql


5. Create index.php
	Under <site_name>/www create index.php
	As the first line:

	<?php include_once("$_SERVER[DOCUMENT_ROOT]/_include/ch.php"); ?>



