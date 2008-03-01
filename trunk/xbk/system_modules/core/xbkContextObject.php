<?php

/**
 * xbkContextObject
 *
 * Класс контекстного объекта
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkContextObject
{
    /**
     * Объект шаблона
     *
     * @access	protected
     * @param	mixed	          type (html или tex) либо массив параметров
     * @return	object or false   объект шаблона или false в случае неудачи
     */
    protected function template ($input = null, $type = 'html')
    {    	global $CONFIG;

    	// Имя текущего модуля
    	$moduleName = $this->_Module->getName();
    	if (is_array($type))
    	{    		$options = array_merge(Array('module' => $moduleName), $type);    	} else if (is_string($type))
    	{    		$options = Array('type' => $type, 'module' => $moduleName);    	} else {    		$options = Array();    	}

        // Добавление языковых переменных в кач-ве глобальных
    	$tmpl = $this->factory('xbkTemplate', $options);
    	if ($this->_LANG != null)
    	{
        	foreach ($this->_LANG as $key => $value)
        	{        		$tmpl->addGlobalVar('LANG_'.$key, $value);        	}
    	}

    	// Загрузка файла шаблона
    	if ($input != null)
    	{    		$tmpl->readTemplatesFromInput($input);    	}

    	// Добавление путей
    	$path_to_skin = $tmpl->getpathToSkin('web');
    	$tmpl->addGlobalVar('PATH_TO_MODULE', xbkFunctions::getModuleWebPath($moduleName));
    	$tmpl->addGlobalVar('PATH_TO_SKIN', $path_to_skin);
    	$tmpl->addGlobalVar('PATH_TO_IMG', $path_to_skin.$CONFIG['path']['internal']['img']);
    	$tmpl->addGlobalVar('PATH_TO_CSS', $path_to_skin.$CONFIG['path']['internal']['css']);
    	$tmpl->addGlobalVar('PATH_TO_JS', $path_to_skin.$CONFIG['path']['internal']['js']);
    	return $tmpl;    }

    /**
     * Создаёт экземпляр класса, регистрирует всю необходимую информацию и связывает с объектом окружение
     *
	 * @access	protected
	 * @params	string, mixed, mixed...		имя класса контекстного объекта, далее следует список параметров
	 * @return	object or false		        контекстный объект или false в случае неудачи
     */
    protected function factory ($name)
    {    	$args = func_get_args();
    	$Registry =& $this->_Registry;
    	return call_user_func_array(array($Registry, 'factory'), $args);
    }

    /**
     * Возвращает php-путь к текущему модулю
     *
	 * @return	string
     */
    protected function getModulePath ()
    {    	return xbkFunctions::getModulePath($this->_Module->getName());
    }

    /**
     * Возвращает web-путь к текущему модулю
     *
	 * @return	string
     */
    protected function getModuleWebPath ()
    {
    	return xbkFunctions::getModuleWebPath($this->_Module->getName());
    }

}

?>