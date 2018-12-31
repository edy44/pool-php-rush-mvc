<?php
//Définition de l'ensemble des constantes du projet
define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('SRC', ROOT.DS.'Src');
define('MODELS', ROOT.DS.'Models');
define('CONTROLLERS', ROOT.DS.'Controllers');
define('VIEWS', ROOT.DS.'Views');
define('CONFIG', ROOT.DS.'Config');
define('VENDORS', ROOT.DS.'Vendors');
define('BASE_URL', '../../../../PHP_Rush_MVC');


require ROOT.DS.'Autoloader.php';
\App\Autoloader::register();

\App\Config\Core::getInstance()->getDispatcher();
