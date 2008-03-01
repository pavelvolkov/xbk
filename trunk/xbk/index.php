<?php

set_include_path('');
require_once('config.php');

// ����������� �� ������������ Doctrine
require_once('Doctrine/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));

// ����������� ����� ����������
require_once($CONFIG['path']['php']['system_modules'].'core/xbkLoader.php');
spl_autoload_register(array('xbkLoader', 'autoload'));

// ��������������� ��������� �������� �������
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

// ����������� � �.�.
$DB = Doctrine_Manager::connection($CONFIG['db']['dsn']);
$DB->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
$DB->setCharset($CONFIG['db']['charset']);

// �������� ��������
$Context = New xbkContext;

// �������� ���������� � ��
$Context->setConnection($DB);

// �������� ��������� ������� $_GET
$Context->set('_GET', $_GET);

// �������� ������ $_POST
$Context->set('_POST', $_POST);

// �������� ������ $HTTP_RAW_POST_DATA
$Context->set('HTTP_RAW_POST_DATA', xbkFunctions::get_HTTP_RAW_POST_DATA());

// �������� ������ $_COOKIE
$Context->set('_COOKIE', $_COOKIE);

// �������� ������ $_FILES
$Context->set('_FILES', $_FILES);

// �������� ������ $_SERVER
$Context->set('_SERVER', $_SERVER);

// ����������� ��������������� ����������
unset($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, $_ENV, $HTTP_RAW_POST_DATA);

// ��������� ������� ���������� �� ����������
$Context->execute();

// ��������� ��������� � �������
$Context->flush();

?>