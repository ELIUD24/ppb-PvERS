<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::parseExtensions('json', 'pdf', 'xml', 'xlsx', 'csv');
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	// Role-specific routes - use role-specific dashboard actions
	Router::connect('/reporter', array('controller' => 'users', 'action' => 'reporter_dashboard'));	
	Router::connect('/partner', array('controller' => 'users', 'action' => 'partner_dashboard'));	
	Router::connect('/manager', array('controller' => 'users', 'action' => 'manager_dashboard'));		
	Router::connect('/admin', array('controller' => 'users', 'action' => 'admin_dashboard'));	
	Router::connect('/admin/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/api', array('controller' => 'users', 'action' => 'reporter_dashboard'));	
	Router::connect('/reviewer', array('controller' => 'users', 'action' => 'reviewer_dashboard'));	

	// Role-specific routes for menu items
	// Reporter routes
	Router::connect('/reporter/sadrs', array('controller' => 'sadrs', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/aefis', array('controller' => 'aefis', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/pqmps', array('controller' => 'pqmps', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/devices', array('controller' => 'devices', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/medications', array('controller' => 'medications', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/transfusions', array('controller' => 'transfusions', 'action' => 'index', 'reporter' => true));
	Router::connect('/reporter/notifications', array('controller' => 'notifications', 'action' => 'index', 'reporter' => true));

	// Manager routes
	Router::connect('/manager/sadrs', array('controller' => 'sadrs', 'action' => 'index', 'manager' => true));
	Router::connect('/manager/padrs', array('controller' => 'padrs', 'action' => 'index', 'manager' => true));
	Router::connect('/manager/aefis', array('controller' => 'aefis', 'action' => 'index', 'manager' => true));
	Router::connect('/manager/pqmps', array('controller' => 'pqmps', 'action' => 'index', 'manager' => true));
	Router::connect('/manager/reports', array('controller' => 'reports', 'action' => 'index', 'manager' => true));

	// Admin routes - no prefix to avoid ACL issues
	Router::connect('/admin/users', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/admin/sadrs', array('controller' => 'sadrs', 'action' => 'index'));
	Router::connect('/admin/padrs', array('controller' => 'padrs', 'action' => 'index'));
	Router::connect('/admin/groups', array('controller' => 'groups', 'action' => 'index'));
	Router::connect('/admin/logout', array('controller' => 'users', 'action' => 'logout'));

	// Partner routes - no prefix
	Router::connect('/partner/sadrs', array('controller' => 'sadrs', 'action' => 'index'));
	Router::connect('/partner/aefis', array('controller' => 'aefis', 'action' => 'index'));
	Router::connect('/partner/pqmps', array('controller' => 'pqmps', 'action' => 'index'));
	Router::connect('/partner/logout', array('controller' => 'users', 'action' => 'logout'));

	// Reviewer routes - no prefix
	Router::connect('/reviewer/sadrs', array('controller' => 'sadrs', 'action' => 'index'));
	Router::connect('/reviewer/padrs', array('controller' => 'padrs', 'action' => 'index'));
	Router::connect('/reviewer/aefis', array('controller' => 'aefis', 'action' => 'index'));
	Router::connect('/reviewer/pqmps', array('controller' => 'pqmps', 'action' => 'index'));
	Router::connect('/reviewer/reports', array('controller' => 'reports', 'action' => 'index'));
	Router::connect('/reviewer/logout', array('controller' => 'users', 'action' => 'logout'));
	
	// Manager routes - no prefix
	Router::connect('/manager/sadrs', array('controller' => 'sadrs', 'action' => 'index'));
	Router::connect('/manager/padrs', array('controller' => 'padrs', 'action' => 'index'));
	Router::connect('/manager/aefis', array('controller' => 'aefis', 'action' => 'index'));
	Router::connect('/manager/pqmps', array('controller' => 'pqmps', 'action' => 'index'));
	Router::connect('/manager/reports', array('controller' => 'reports', 'action' => 'index'));
	Router::connect('/manager/logout', array('controller' => 'users', 'action' => 'logout'));	

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
