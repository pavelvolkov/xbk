<?php

/**
 * xbkContextObject
 *
 * ����� ������������ �������
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
     * ������ �������
     *
     * @access	protected
     * @param	mixed	          type (html ��� tex) ���� ������ ����������
     * @return	object or false   ������ ������� ��� false � ������ �������
     */
    protected function template ($input = null, $type = 'html')
    {    	global $CONFIG;

    	// ��� �������� ������
    	$moduleName = $this->_Module->getName();
    	if (is_array($type))
    	{    		$options = array_merge(Array('module' => $moduleName), $type);    	} else if (is_string($type))
    	{    		$options = Array('type' => $type, 'module' => $moduleName);    	} else {    		$options = Array();    	}

        // ���������� �������� ���������� � ���-�� ����������
    	$tmpl = $this->factory('xbkTemplate', $options);
    	if ($this->_LANG != null)
    	{
        	foreach ($this->_LANG as $key => $value)
        	{        		$tmpl->addGlobalVar('LANG_'.$key, $value);        	}
    	}

    	// �������� ����� �������
    	if ($input != null)
    	{    		$tmpl->readTemplatesFromInput($input);    	}

    	// ���������� �����
    	$path_to_skin = $tmpl->getpathToSkin('web');
    	$tmpl->addGlobalVar('PATH_TO_MODULE', xbkFunctions::getModuleWebPath($moduleName));
    	$tmpl->addGlobalVar('PATH_TO_SKIN', $path_to_skin);
    	$tmpl->addGlobalVar('PATH_TO_IMG', $path_to_skin.$CONFIG['path']['internal']['img']);
    	$tmpl->addGlobalVar('PATH_TO_CSS', $path_to_skin.$CONFIG['path']['internal']['css']);
    	$tmpl->addGlobalVar('PATH_TO_JS', $path_to_skin.$CONFIG['path']['internal']['js']);
    	return $tmpl;    }

    /**
     * ������ ��������� ������, ������������ ��� ����������� ���������� � ��������� � �������� ���������
     *
	 * @access	protected
	 * @params	string, mixed, mixed...		��� ������ ������������ �������, ����� ������� ������ ����������
	 * @return	object or false		        ����������� ������ ��� false � ������ �������
     */
    protected function factory ($name)
    {    	$args = func_get_args();
    	$Registry =& $this->_Registry;
    	return call_user_func_array(array($Registry, 'factory'), $args);
    }

    /**
     * ���������� php-���� � �������� ������
     *
	 * @return	string
     */
    protected function getModulePath ()
    {    	return xbkFunctions::getModulePath($this->_Module->getName());
    }

    /**
     * ���������� web-���� � �������� ������
     *
	 * @return	string
     */
    protected function getModuleWebPath ()
    {
    	return xbkFunctions::getModuleWebPath($this->_Module->getName());
    }

}

?>