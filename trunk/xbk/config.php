<?php

$CONFIG = Array();

// ����� �����
$CONFIG['address'] = '127.0.0.1';

// ����
$CONFIG['path'] = Array();

// ���� ��� web
$CONFIG['path']['web'] = Array();
$CONFIG['path']['web']['root'] = '/xbk/0.3/';
$CONFIG['path']['web']['system_modules'] = $CONFIG['path']['web']['root'].'system_modules/';
$CONFIG['path']['web']['user_modules'] = $CONFIG['path']['web']['root'].'user_modules/';
$CONFIG['path']['web']['skins'] = $CONFIG['path']['web']['root'].'skins/';
$CONFIG['path']['web']['filedata'] = $CONFIG['path']['web']['root'].'filedata/';
$CONFIG['path']['web']['fileadmin'] = $CONFIG['path']['web']['root'].'fileadmin/';
$CONFIG['path']['web']['tmp'] = $CONFIG['path']['web']['root'].'tmp/';

// ���� ��� ��������
$CONFIG['path']['php'] = Array();
$CONFIG['path']['php']['base'] = '';
$CONFIG['path']['php']['system_modules'] = $CONFIG['path']['php']['base'].'system_modules/';
$CONFIG['path']['php']['user_modules'] = $CONFIG['path']['php']['base'].'user_modules/';
$CONFIG['path']['php']['skins'] = $CONFIG['path']['php']['base'].'skins/';
$CONFIG['path']['php']['filedata'] = $CONFIG['path']['php']['base'].'filedata/';
$CONFIG['path']['php']['fileadmin'] = $CONFIG['path']['php']['base'].'fileadmin/';
$CONFIG['path']['php']['tmp'] = $CONFIG['path']['php']['base'].'tmp/';

// ���������� �������������� ����
$CONFIG['path']['internal'] = Array();
$CONFIG['path']['internal']['lang'] = 'lang/';
$CONFIG['path']['internal']['skin'] = 'skin/';
$CONFIG['path']['internal']['html'] = 'html/';
$CONFIG['path']['internal']['img'] = 'img/';
$CONFIG['path']['internal']['css'] = 'css/';
$CONFIG['path']['internal']['js'] = 'js/';
$CONFIG['path']['internal']['models'] = 'models/';
$CONFIG['path']['internal']['migrations'] = 'migrations/';

// ��������� cookies �� ���������
$CONFIG['cookie'] = Array();
$CONFIG['cookie']['path'] = $CONFIG['path']['web']['root'];
$CONFIG['cookie']['domain'] = '.'.$CONFIG['address'];

// ����� ����������������� ������
$CONFIG['file'] = Array();
$CONFIG['file']['module'] = 'module.xml';
$CONFIG['file']['module_config'] = 'config.yml';

// ��������� ���� ������
$CONFIG['db'] = Array();
// ����� ����������� � ���� ������
$CONFIG['db']['dsn'] = 'mysql://root:@localhost/xbk';
// ������� ������
$CONFIG['db']['table_prefix'] = 'xbk_';
// ��������� ���������, ���� false
$CONFIG['db']['charset'] = 'utf8';

// ��������� �������������
$CONFIG['tpl'] = Array();
// ���������� ������ ��������
$CONFIG['tpl']['ext'] = 'tpl';

// ����������������� ������ �������� ������
// (������ ������������� ������ ������ ������������� ������)
$CONFIG['required_sections'] = Array('xbk');

// ��������� ������ �����������
// (��� ������������ � ������� �������������� ������� ������� � ������ xbk)
$CONFIG['superadmin'] = Array();
// ������ �������������
$CONFIG['superadmin']['enable'] = true;
// ������������� SSL-��������
$CONFIG['superadmin']['ssl'] = false;
// ������������ ����� �������, ����� �������� ��������� ���. �����������, �
$CONFIG['superadmin']['inactivity_time'] = 1800;
// ������ �������������� ('none', 'http', 'cookie')
$CONFIG['superadmin']['auth_type'] = 'cookie';
// ����������� IP-������. ����� ��������� �����, ��������� "*", "?",
// ������ ������� ��� null ��� ������ ip-������
$CONFIG['superadmin']['ip_mask'] = null;
// �����
$CONFIG['superadmin']['login'] = 'superadmin';
// ������
$CONFIG['superadmin']['pass'] = 'superpass';

// ����������
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

// ��������� ��-���������
$CONFIG['lang'] = 'ru';

// Cookie
$CONFIG['cookie'] = Array();
$CONFIG['cookie']['domain'] = '';
$CONFIG['cookie']['path'] = '/';

// ������ gzip (�������� ������ � ���������� mod_rewrite)
$CONFIG['gzip'] = Array();
$CONFIG['gzip']['html'] = true; // ���-��������
$CONFIG['gzip']['css'] = true; // ������� ������
$CONFIG['gzip']['js'] = true; // ���-�������

// ��������� ��������� ��������
$CONFIG['index'] = Array();
// ��� ������
$CONFIG['index']['section'] = 'xbk_index';
// ������������� ������ ����������
$CONFIG['index']['params'] = Array();

// ������-����
// (������������� ��������, ���� �� ������� �� �������� ������� cron)
$CONFIG['pseudo_cron'] = true;


?>