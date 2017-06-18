Captcha
===
Information
---
* Problem number : 1
* Email : reza.smart306@gmail.com
* Programming Language : PHP
* Framework : Codeigniter

Requirements
---
1. PHP 5.4 or greater
2. MySql 5.1+
3. PHP extensions : GD2

_cp folder inside public_html should have write premission_

Installation
---
1. write your database connection in `path/to/project/application/config/database.php`
	```php

	//path/to/project/application/config/database.php
	$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => '',
	'password' => '',
	'database' => '',

	```
2. Load migrate controller `http://localhost/path/to/project/public_html/migrate`
3. Load index controller `http://localhost/path/to/project/public_html`
4. Click on Documentaion link on top menu and read User Documentaion 
5. I created a script with fake information to test captcha in `path/to/project/test/index.php`


