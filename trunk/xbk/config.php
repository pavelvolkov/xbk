<?php

$CONFIG = Array();

// Адрес сайта
$CONFIG['address'] = '127.0.0.1';

// Пути
$CONFIG['path'] = Array();

// Пути для web
$CONFIG['path']['web'] = Array();
$CONFIG['path']['web']['root'] = '/xbk/0.3/';
$CONFIG['path']['web']['system_modules'] = $CONFIG['path']['web']['root'].'system_modules/';
$CONFIG['path']['web']['user_modules'] = $CONFIG['path']['web']['root'].'user_modules/';
$CONFIG['path']['web']['skins'] = $CONFIG['path']['web']['root'].'skins/';
$CONFIG['path']['web']['filedata'] = $CONFIG['path']['web']['root'].'filedata/';
$CONFIG['path']['web']['fileadmin'] = $CONFIG['path']['web']['root'].'fileadmin/';
$CONFIG['path']['web']['tmp'] = $CONFIG['path']['web']['root'].'tmp/';

// Пути для скриптов
$CONFIG['path']['php'] = Array();
$CONFIG['path']['php']['base'] = '';
$CONFIG['path']['php']['system_modules'] = $CONFIG['path']['php']['base'].'system_modules/';
$CONFIG['path']['php']['user_modules'] = $CONFIG['path']['php']['base'].'user_modules/';
$CONFIG['path']['php']['skins'] = $CONFIG['path']['php']['base'].'skins/';
$CONFIG['path']['php']['filedata'] = $CONFIG['path']['php']['base'].'filedata/';
$CONFIG['path']['php']['fileadmin'] = $CONFIG['path']['php']['base'].'fileadmin/';
$CONFIG['path']['php']['tmp'] = $CONFIG['path']['php']['base'].'tmp/';

// Внутренние дополнительные пути
$CONFIG['path']['internal'] = Array();
$CONFIG['path']['internal']['lang'] = 'lang/';
$CONFIG['path']['internal']['skin'] = 'skin/';
$CONFIG['path']['internal']['html'] = 'html/';
$CONFIG['path']['internal']['img'] = 'img/';
$CONFIG['path']['internal']['css'] = 'css/';
$CONFIG['path']['internal']['js'] = 'js/';
$CONFIG['path']['internal']['models'] = 'models/';
$CONFIG['path']['internal']['migrations'] = 'migrations/';

// Настройки cookies по умолчанию
$CONFIG['cookie'] = Array();
$CONFIG['cookie']['path'] = $CONFIG['path']['web']['root'];
$CONFIG['cookie']['domain'] = '.'.$CONFIG['address'];

// Имена зарезервированных файлов
$CONFIG['file'] = Array();
$CONFIG['file']['module'] = 'module.xml';
$CONFIG['file']['module_config'] = 'config.yml';

// Параметры базы данных
$CONFIG['db'] = Array();
// Схема подключения к базе данных
$CONFIG['db']['dsn'] = 'mysql://root:@localhost/xbk';
// Префикс таблиц
$CONFIG['db']['table_prefix'] = 'xbk_';
// Кодировка сравнения, либо false
$CONFIG['db']['charset'] = 'utf8';

// Параметры шаблонизатора
$CONFIG['tpl'] = Array();
// Расширение файлов шаблонов
$CONFIG['tpl']['ext'] = 'tpl';

// Зарезервированные секции верхнего уровня
// (поверх перечисленных секций нельзя устанавливать алиасы)
$CONFIG['required_sections'] = Array('xbk');

// Настройки панели суперадмина
// (для разработчика и первого администратора модулей системы в секции xbk)
$CONFIG['superadmin'] = Array();
// Панель задействована
$CONFIG['superadmin']['enable'] = true;
// Задействовать SSL-протокол
$CONFIG['superadmin']['ssl'] = false;
// Максимальное время простоя, свыше которого требуется доп. авторизация, с
$CONFIG['superadmin']['inactivity_time'] = 1800;
// Способ аутентификации ('none', 'http', 'cookie')
$CONFIG['superadmin']['auth_type'] = 'cookie';
// Разрешённые IP-адреса. Можно указывать маску, используя "*", "?",
// массив адресов или null для любого ip-адреса
$CONFIG['superadmin']['ip_mask'] = null;
// Логин
$CONFIG['superadmin']['login'] = 'superadmin';
// Пароль
$CONFIG['superadmin']['pass'] = 'superpass';

// Интерфейсы
$CONFIG['interface'] = Array(
                            'ru' => Array(
                                        'index' => 'ru',
                                        'locale' => 'ru_RU',
                                        'charset' => 'utf-8',
                                        'mail_charset' => 'windows-1251'
                                    ),
                            'en' => Array(
                                        'index' => 'en',
                                        'locale' => 'en_EN',
                                        'charset' => 'utf-8',
                                        'mail_charset' => 'utf-8'
                                    )
);

// Интерфейс по-умолчанию
$CONFIG['lang'] = 'ru';

// Cookie
$CONFIG['cookie'] = Array();
$CONFIG['cookie']['domain'] = '';
$CONFIG['cookie']['path'] = '/';

// Сжатие gzip (работает только с включенным mod_rewrite)
$CONFIG['gzip'] = Array();
$CONFIG['gzip']['html'] = true; // Веб-страницы
$CONFIG['gzip']['css'] = true; // Таблицы стилей
$CONFIG['gzip']['js'] = true; // Ява-скрипты

// Параметры стартовой страницы
$CONFIG['index'] = Array();
// Имя секции
$CONFIG['index']['section'] = 'xbk_index';
// Ассоциативный массив параметров
$CONFIG['index']['params'] = Array();

// Псевдо-крон
// (рекомендуется включить, если на сервере не настроен обычный cron)
$CONFIG['pseudo_cron'] = true;


?>