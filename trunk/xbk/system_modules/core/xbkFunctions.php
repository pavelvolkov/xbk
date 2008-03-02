<?php

/**
 * xbkFunctions
 *
 * ����� ��������������� �������
 *
 * @version       1.2   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */
class xbkFunctions
{
    /**
     * �������� $HTTP_RAW_POST_DATA
     *
	 * @access	public
	 * @return	string
     */
    public function get_HTTP_RAW_POST_DATA ()
    {
    	global $HTTP_RAW_POST_DATA;
    	if (!isset($HTTP_RAW_POST_DATA)) {
    	    return file_get_contents("php://input");
    	} else return $HTTP_RAW_POST_DATA;
    }

    /**
     * ���������, ���������� �� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	boolean
     */
    public function moduleExists ($name)
    {    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$name)) $exists = true;
        else if (file_exists($CONFIG['path']['php']['user_modules'].$name)) $exists = true;
        else $exists = false;
    	return $exists;    }

    /**
     * ���������, ���������� �� ���� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @param	string	     ��� �����
	 * @return	boolean
     */
    public function moduleSkinExists ($moduleName, $skinName)
    {
    	global $CONFIG;
    	$exists = false;
        if (xbkFunctions::moduleExists($moduleName))
        {        	 if (file_exists($CONFIG['path']['php']['skins'].$moduleName.'/'.$skinName)) $exists = true;        }

    	return $exists;
    }

    /**
     * ���������, ���������� �� ���� ���������� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	boolean
     */
    public function moduleFileExists ($moduleName)
    {
    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$moduleName.'/'.$CONFIG['file']['module'])) $exists = true;
        else if (file_exists($CONFIG['path']['php']['user_modules'].$moduleName.'/'.$CONFIG['file']['module'])) $exists = true;
        else $exists = false;
    	return $exists;
    }

    /**
     * ���������� php-���� � �������� ������, ���� false, ���� ������ �� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	string
     */
    public function getModulePath ($moduleName)
    {
    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$moduleName))
        {
        	return $CONFIG['path']['php']['system_modules'].$moduleName.'/';
        } else if (file_exists($CONFIG['path']['php']['user_modules'].$moduleName))
        {
        	return $CONFIG['path']['php']['user_modules'].$moduleName.'/';
        } else return false;
    }

    /**
     * ���������� web-���� � �������� ������, ���� false, ���� ������ �� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	string
     */
    public function getModuleWebPath ($moduleName)
    {
    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$moduleName))
        {
        	return $CONFIG['path']['web']['system_modules'].$moduleName.'/';
        } else if (file_exists($CONFIG['path']['php']['user_modules'].$moduleName))
        {
        	return $CONFIG['path']['web']['user_modules'].$moduleName.'/';
        } else return false;
    }

    /**
     * ���������� true, ���� ������ � ��������� ������ �������� ���������, ����� - false.
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	boolean
     */
    public function isSystemModule ($moduleName)
    {
    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$moduleName))
        {
        	return true;
        } else return false;
    }

    /**
     * ���������� ���� � ��������������� ����� ������, ���� false, ���� ���� �� ������
     *
	 * @access	public
	 * @param	string	     ��� ������
	 * @return	string
     */
    public function getModuleFilePath ($moduleName)
    {
    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$moduleName.'/'.$CONFIG['file']['module']))
        {        	return $CONFIG['path']['php']['system_modules'].$moduleName.'/'.$CONFIG['file']['module'];
        } else if (file_exists($CONFIG['path']['php']['user_modules'].$moduleName.'/'.$CONFIG['file']['module']))
        {        	return $CONFIG['path']['php']['user_modules'].$moduleName.'/'.$CONFIG['file']['module'];        } else return false;
    }

    /**
     * ��������� ��������� ���������� � ������� ��������
     *
     * 'true', 'yes', 'on' - true
     * 'false', 'no', 'off' - false
     *
	 * @access	public
	 * @param	string
	 * @return	boolean
     */
    public function str2bool ($str)
    {
    	if (in_array($str, Array('true', 'yes', 'on'))) return true;
    	else return false;
    }

    /**
     * ������ ��������� ������ �����
     *
	 * @access	public
	 * @param	string
	 * @return	string
     */
    public function capitalizeFirstLetter ($str)
    {    	return ucfirst($str);
    }

    /**
     * ��������� ������������ IP-������ �����
     *
     * @access  public
	 * @param	string
	 * @param	string
     */
    public function matchIp ($mask, $ip)
    {    	$pattern = "/^".str_replace(Array("*", "?", "."), Array("[0-9]{0,3}", "[0-9]+", "\."), $mask)."$/";
        if (preg_match($pattern, $ip) > 0) return true;
        else return false;
    }

    /**
     * ���������� ������� ���������� � ������� � ���������������
     *
     * @access  public
	 * @param	string
     */
    public function unlinkRecursive ($input)
    {    	if (is_file($input))
    	{
        	unlink($input);
    	} else if (is_dir($input))
    	{    		$files = scandir($input);
    		foreach ($files as $sub_file)
    		{    			if (!in_array($sub_file, Array('.', '..')))
    			{
        			self::unlinkRecursive($input.'/'.$sub_file);
    			}    		}
    		@rmdir($input);    	}
    }

    /**
     * �������� ������ ���� ������������ �������
     *
     * @access  public
	 * @param	string
     */
    public function getExistsModuleList ()
    {    	global $CONFIG;
    	$system_modules_dir_content = scandir($CONFIG['path']['php']['system_modules']);
    	$user_modules_dir_content = scandir($CONFIG['path']['php']['user_modules']);
    	$module_list = Array();
    	foreach ($system_modules_dir_content as $value)
    	{    		if (
        		is_dir($CONFIG['path']['php']['system_modules'].$value) &&
        		$value != '.' &&
        		$value != '..' &&
        		self::moduleFileExists($value)
    		)
    		{        		array_push($module_list, $value);
    		}    	}
    	foreach ($user_modules_dir_content as $value)
    	{
    		if (
        		is_dir($CONFIG['path']['php']['user_modules'].$value) &&
        		$value != '.' &&
        		$value != '..' &&
        		!in_array($value, $module_list) &&
        		self::moduleFileExists($value)
    		)
    		{
        		array_push($module_list, $value);
    		}
    	}
    	return $module_list;    }

    /**
     * �������� ������ ���� ������������ �������
     *
     * @access  public
	 * @param	string
     */
    public function getModelList ($path)
    {    	if (file_exists($path))
    	{        	$files = scandir($path);
        	$models = Array();
        	foreach ($files as $file)
        	{        		if (!in_array($file, Array('.', '..')))
        		{        			array_push($models, substr($file, 0, (strlen($file)-4)));        		}        	}
        	return $models;
    	} else return Array();
    }

    /**
     * �������� ������� � ������ ��� ������������� �� ���������� microtime()
     *
     * @access  public
	 * @param	string
     */
    public function microtimeToTime($mct = false)
    {
        if ($mct == false) list($usec, $sec) = explode(" ", microtime());
        else list($usec, $sec) = explode(" ", $mct);
        return ((float)$usec + (float)$sec);
    }

    /**
     * ���������� ������������ �������� ���� � ����������� �� �������
     *
     * @access  public
	 * @param	string
	 * @feturn	integer ��� false
     */
    public function incrementValue($modelName, $fieldName)
    {
        $query = new Doctrine_Query();
        $query->select('m.'.$fieldName)
              ->from($modelName.' m')
              ->orderby('m.'.$fieldName.' DESC')
              ->limit(1);

        $records = $query->execute();
        if (count($records) > 0) return ($records[0]->get($fieldName)+1);
        else return 1;
    }

    /**
     * ������������� SQL-�������� � DQL
     *
     * @access  public
	 * @param	string
	 * @feturn	string
     */
    public function dqlEscapeString($string)
    {
        return str_replace("'", "''", $string);
    }

}

?>