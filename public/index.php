<?php
require_once 'Zend/View.php';
ini_set('display_errors', 1);
require_once "Zend/Controller/Front.php";
require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';
require_once 'Zend/Controller/Router/Rewrite.php';

require_once('./spyc.php');

$front = Zend_Controller_Front::getInstance();

$front->setParam("noViewRenderer", false);

defined('ROOT_PATH')||define('ROOT_PATH',realpath(dirname(__FILE__)));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH',
realpath(dirname(__FILE__) . '/../application'));

defined('LIB_PATH')
|| define('LIB_PATH',
realpath(dirname(__FILE__) . '/../libs'));

defined('DATA_PATH')
|| define('DATA_PATH',
realpath(dirname(__FILE__) . '/../data'));

defined('IMAGES_PATH')
|| define('IMAGES_PATH',
realpath(dirname(__FILE__) . '/../images'));

define('CONTROLLER_DIR', APPLICATION_PATH . '/controllers');
define('MODEL_DIR',      APPLICATION_PATH . '/models');
define('VIEW_DIR',       APPLICATION_PATH . '/views');
define('SMARTY_PATH', APPLICATION_PATH.'/smarty/');

defined('APP_ENV')
|| define('APP_ENV',(getenv('APP_ENV')?getenv('APP_ENV'):'production'));

set_include_path(
	implode( PATH_SEPARATOR,array(
		realpath(LIB_PATH),
		get_include_path(),
		)
	)
);

##$str = file_get_contents("phppro.yml");
##$ydoc = yaml_parse($str);
//var_dump($ydoc);exit();
//var_dump(phpinfo());exit();

require_once 'Zend/Application.php';
require_once( LIB_PATH .'/Smarty-2.6.27/libs/Smarty.class.php' );
require_once (LIB_PATH.'/Smarty-2.6.27/Zend_View_Smarty.class.php');
require_once (CONTROLLER_DIR.'/AbstractController.php');

#exit;
/*
$application = new Zend_Application(
	APP_ENV,
	APPLICATION_PATH . '/configs/app.ini'
);*/

$router = new Zend_Controller_Router_Rewrite();

$front->setControllerDirectory(APPLICATION_PATH."/controllers/")
->setRouter($router);

$view = new Zend_View_Smarty(
	APPLICATION_PATH . '/views/scripts', array(
        'compile_dir' => APPLICATION_PATH . '/views/templates_c',
        'config_dir'  => APPLICATION_PATH . '/views/configs',
        'cache_dir'   => APPLICATION_PATH . '/views/cache',
        'left_delimiter'   => '{{',
        'right_delimiter'  => '}}'
	)
);

$viewRenderer = Zend_Controller_Action_HelperBroker::
										getStaticHelper('ViewRenderer');
$viewRenderer->setView($view)
->setViewBasePathSpec($view->_smarty->template_dir)
->setViewScriptPathSpec(':controller/:action.:suffix')
->setViewScriptPathNoControllerSpec(':action.:suffix')
->setViewSuffix('html');

$front->dispatch();