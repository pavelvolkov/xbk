<?php

set_include_path('');
require_once('config.php');

// Подключение на автозагрузку Doctrine
require_once('Doctrine/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

// Подключение всего остального
require_once($CONFIG['path']['php']['system_modules'].'core/xbkLoader.php');
spl_autoload_register(array('xbkLoader', 'autoload'));

// Предварительная обработка внешнего запроса
if (get_magic_quotes_gpc())
{	foreach ($_GET as $key => $value)
	{		$_GET[$key] = stripslashes($value);	}
	foreach ($_POST as $key => $value)
	{
		$_POST[$key] = stripslashes($value);
	}
	foreach ($_COOKIE as $key => $value)
	{
		$_COOKIE[$key] = stripslashes($value);
	}
}

// Подключение к б.д.
$DB = Doctrine_Manager::connection($CONFIG['db']['dsn']);
$DB->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
$DB->setCharset($CONFIG['db']['charset']);

// Объявить контекст
$Context = New xbkContext;

// Сообщить соединение с БД
$Context->setConnection($DB);

// Сообщить параметры запроса $_GET
$Context->set('_GET', $_GET);

// Сообщить данные $_POST
$Context->set('_POST', $_POST);

// Сообщить данные $HTTP_RAW_POST_DATA
$Context->set('HTTP_RAW_POST_DATA', xbkFunctions::get_HTTP_RAW_POST_DATA());

// Сообщить данные $_COOKIE
$Context->set('_COOKIE', $_COOKIE);

// Сообщить массив $_FILES
$Context->set('_FILES', $_FILES);

// Сообщить массив $_SERVER
$Context->set('_SERVER', $_SERVER);

// Упразднение предобъявленных переменных
unset($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, $_ENV, $HTTP_RAW_POST_DATA);

// Запустить главный контроллер на выполнение
$Context->execute();

// Отправить результат в броузер
$Context->flush();

?>