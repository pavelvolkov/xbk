<?php

/**
 * xbkLoader - подгружает классы.
 *
 * @version    1.0   2008-02-21
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkLoader
{
    /**
     * Начальная автозагрузка классов
     *
	 * @access	public
	 * @param	string		имя класса
     */
    public static function autoload($classname)
    {    	global $CONFIG;
    	// Предварительная проверка
        if (class_exists($classname, false) || interface_exists($classname, false))
        {
            return false;
            break;
        }
        if ($classname == 'patTemplate') {
        	require_once('patTemplate/patTemplate.php');
        	return false;
            break;
        } else if ($classname == 'patError') {
        	require_once('patError/patError.php');
        	return false;
            break;
        } else if ($classname == 'patErrorManager') {
        	require_once('patError/patErrorManager.php');
        	return false;
            break;
        } else if (substr($classname, 0, 5) == 'Zend_') {
        	self::loadFromZend($classname);
        	return false;
            break;
        } else if (self::loadFromModules($classname)) {        	return false;
            break;        }
        return true;
    }

    /**
     * Пытается подгрузить класс из модулей
     *
	 * @access	public
	 * @param	string		имя класса
     */
    private function loadFromModules ($classname)
    {
    	global $CONFIG;
    	$return = false;
    	$modules = scandir($CONFIG['path']['php']['system_modules']);
		foreach ($modules as $module)
		{
        	// Попытка подгрузить класс базового модуля
            $file = $CONFIG['path']['php']['system_modules'].$module.'/'.$classname.'.php';
            if (file_exists($file))
            {
            	require_once($file);
            	if (class_exists($classname, false) || interface_exists($classname, false)) {            		return true;
            	}
            }
        }

    	$modules = scandir($CONFIG['path']['php']['user_modules']);
		foreach ($modules as $module)
		{
            // Попытка подгрузить класс пользовательского модуля
            $file = $CONFIG['path']['php']['user_modules'].$module.'/'.$classname.'.php';
            if (file_exists($file))
            {
            	require_once($file);
            	if (class_exists($classname, false) || interface_exists($classname, false)) {
            		return true;
            	}
            }
        }

        return false;
    }

    /**
     * Пытается подгрузить класс из библиотеки Zend
     *
	 * @access	public
	 * @param	string		имя класса
     */
    private function loadFromZend ($classname)
    {    	global $CONFIG;    	$parts = explode('_', $classname);
    	$path = 'Zend/';
    	for ($i=1; isset($parts[$i]); $i++) {    		if (isset($parts[($i+1)])) {        		$path .= $parts[$i].'/';
    		} else {    			$path .= $parts[$i].'.php';    		}
     	}
		require_once($path);
    	if (class_exists($classname, false) || interface_exists($classname, false)) {
    		return true;
    	}    }
}

?>