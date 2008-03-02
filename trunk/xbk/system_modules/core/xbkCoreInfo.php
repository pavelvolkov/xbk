<?php

/**
 * xbkCoreInfo
 *
 * Информация модуля ядра
 *
 * @version    1.0   2008-01-27
 * @package    xBk
 * @author     Pavel Bakanov
 * @license	   LGPL
 * @link	   http://bakanov.info
 */

class xbkCoreInfo extends xbkModuleInfo
{
    public function getTitle ()
    {
    	return $this->_LANG['module'];
    }

    public function getDescription ()
    {
    	return $this->_LANG['description'];
    }

    public function getAuthor ()
    {
    	return $this->_LANG['author'];
    }

    public function getLicense ()
    {
    	return $this->_LANG['license'];
    }

    public function getMenu ()
    {    	$Uri = $this->factory('xbkUri');

    	$admin_menu = Array();

    	$Uri->goto('xbk/main');
    	array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_main'],
            	'link' => $Uri->build()
            )
        );
        $Uri->gotoBrother('core');
    	array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_core'],
            	'link' => $Uri->build()
            )
        );

        $Uri->gotoBrother('modules');
        array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_modules'],
            	'link' => $Uri->build()
            )
        );

        $Uri->gotoBrother('user');
        array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_user'],
            	'link' => $Uri->build()
            )
        );

        $Uri->gotoBrother('about');
        array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_about'],
            	'link' => $Uri->build()
            )
        );

        $Uri->gotoBrother('phpinfo');
        array_push($admin_menu,
        	Array(
            	'text' => $this->_LANG['superadmin_menu_phpinfo'],
            	'link' => $Uri->build()
            )
        );

    	return $admin_menu;
    }
}

?>