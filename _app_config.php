<?php
/**
 * @package Autoresponse
 *
 * APPLICATION-WIDE CONFIGURATION SETTINGS
 *
 * This file contains application-wide configuration settings.  The settings
 * here will be the same regardless of the machine on which the app is running.
 *
 * This configuration should be added to version control.
 *
 * No settings should be added to this file that would need to be changed
 * on a per-machine basic (ie local, staging or production).  Any
 * machine-specific settings should be added to _machine_config.php
 */

/**
 * APPLICATION ROOT DIRECTORY
 * If the application doesn't detect this correctly then it can be set explicitly
 */
if (!GlobalConfig::$APP_ROOT) GlobalConfig::$APP_ROOT = realpath("./");

/**
 * check is needed to ensure asp_tags is not enabled
 */
if (ini_get('asp_tags')) 
	die('<h3>Server Configuration Problem: asp_tags is enabled, but is not compatible with Savant.</h3>'
	. '<p>You can disable asp_tags in .htaccess, php.ini or generate your app with another template engine such as Smarty.</p>');

/**
 * INCLUDE PATH
 * Adjust the include path as necessary so PHP can locate required libraries
 */
set_include_path(
		GlobalConfig::$APP_ROOT . '/libs/' . PATH_SEPARATOR .
		'phar://' . GlobalConfig::$APP_ROOT . '/libs/phreeze-3.3.8.phar' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/../phreeze/libs' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/vendor/phreeze/phreeze/libs/' . PATH_SEPARATOR .
		get_include_path()
);

/**
 * COMPOSER AUTOLOADER
 * Uncomment if Composer is being used to manage dependencies
 */
// $loader = require 'vendor/autoload.php';
// $loader->setUseIncludePath(true);

/**
 * SESSION CLASSES
 * Any classes that will be stored in the session can be added here
 * and will be pre-loaded on every page
 */
require_once "App/ExampleUser.php";

/**
 * RENDER ENGINE
 * You can use any template system that implements
 * IRenderEngine for the view layer.  Phreeze provides pre-built
 * implementations for Smarty, Savant and plain PHP.
 */
require_once 'verysimple/Phreeze/SavantRenderEngine.php';
GlobalConfig::$TEMPLATE_ENGINE = 'SavantRenderEngine';
GlobalConfig::$TEMPLATE_PATH = GlobalConfig::$APP_ROOT . '/templates/';

/**
 * ROUTE MAP
 * The route map connects URLs to Controller+Method and additionally maps the
 * wildcards to a named parameter so that they are accessible inside the
 * Controller without having to parse the URL for parameters such as IDs
 */
GlobalConfig::$ROUTE_MAP = array(

	// default controller when no route specified
	'GET:' => array('route' => 'Topic.ListView'),
		
	// example authentication routes
	'GET:loginform' => array('route' => 'SecureExample.LoginForm'),
	'POST:login' => array('route' => 'SecureExample.Login'),
	'GET:logout' => array('route' => 'SecureExample.Logout'),
		
	// Log
	'GET:logs' => array('route' => 'Log.ListView'),
	'GET:log/(:num)' => array('route' => 'Log.SingleView', 'params' => array('id' => 1)),
	'GET:api/logs' => array('route' => 'Log.Query'),
	'POST:api/log' => array('route' => 'Log.Create'),
	'GET:api/log/(:num)' => array('route' => 'Log.Read', 'params' => array('id' => 2)),
	'PUT:api/log/(:num)' => array('route' => 'Log.Update', 'params' => array('id' => 2)),
	'DELETE:api/log/(:num)' => array('route' => 'Log.Delete', 'params' => array('id' => 2)),
		
	// Setting
	'GET:settings' => array('route' => 'Setting.ListView'),
	'GET:setting/(:num)' => array('route' => 'Setting.SingleView', 'params' => array('id' => 1)),
	'GET:api/settings' => array('route' => 'Setting.Query'),
	'POST:api/setting' => array('route' => 'Setting.Create'),
	'GET:api/setting/(:num)' => array('route' => 'Setting.Read', 'params' => array('id' => 2)),
	'PUT:api/setting/(:num)' => array('route' => 'Setting.Update', 'params' => array('id' => 2)),
	'DELETE:api/setting/(:num)' => array('route' => 'Setting.Delete', 'params' => array('id' => 2)),
		
	// Topic
	'GET:topics' => array('route' => 'Topic.ListView'),
	'GET:topic/(:num)' => array('route' => 'Topic.SingleView', 'params' => array('id' => 1)),
	'GET:api/topics' => array('route' => 'Topic.Query'),
	'POST:api/topic' => array('route' => 'Topic.Create'),
	'GET:api/topic/(:num)' => array('route' => 'Topic.Read', 'params' => array('id' => 2)),
	'PUT:api/topic/(:num)' => array('route' => 'Topic.Update', 'params' => array('id' => 2)),
	'DELETE:api/topic/(:num)' => array('route' => 'Topic.Delete', 'params' => array('id' => 2)),

	// catch any broken API urls
	'GET:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'PUT:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'POST:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'DELETE:api/(:any)' => array('route' => 'Default.ErrorApi404')
);

/**
 * FETCHING STRATEGY
 * You may uncomment any of the lines below to specify always eager fetching.
 * Alternatively, you can copy/paste to a specific page for one-time eager fetching
 * If you paste into a controller method, replace $G_PHREEZER with $this->Phreezer
 */
?>