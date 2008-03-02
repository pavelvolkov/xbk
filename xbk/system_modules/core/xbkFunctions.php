<?php

/**
 * xbkFunctions
 *
 * Набор вспомогательных функций
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
     * Получает $HTTP_RAW_POST_DATA
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
     * Проверяет, существует ли модуль
     *
	 * @access	public
	 * @param	string	     имя модуля
	 * @return	boolean
     */
    public function moduleExists ($name)
    {    	global $CONFIG;
        if (file_exists($CONFIG['path']['php']['system_modules'].$name)) $exists = true;
        else if (file_exists($CONFIG['path']['php']['user_modules'].$name)) $exists = true;
        else $exists = false;
    	return $exists;    }

    /**
     * Проверяет, существует ли скин модуля
     *
	 * @access	public
	 * @param	string	     имя модуля
	 * @param	string	     имя скина
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
     * Проверяет, существует ли файл интеграции модуля
     *
	 * @access	public
	 * @param	string	     имя модуля
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
     * Возвращает php-путь к каталогу модуля, либо false, если модуль не найден
     *
	 * @access	public
	 * @param	string	     имя модуля
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
     * Возвращает web-путь к каталогу модуля, либо false, если модуль не найден
     *
	 * @access	public
	 * @param	string	     имя модуля
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
     * Возвращает true, если модуль с указанным именем является системным, иначе - false.
     *
	 * @access	public
	 * @param	string	     имя модуля
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
     * Возвращает путь к декларационному файлу модуля, либо false, если файл не найден
     *
	 * @access	public
	 * @param	string	     имя модуля
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
     * Переводит строковый эквивалент в булевое значение
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
     * Делает прописной первую букву
     *
	 * @access	public
	 * @param	string
	 * @return	string
     */
    public function capitalizeFirstLetter ($str)
    {    	return ucfirst($str);
    }

    /**
     * Проверяет соответствие IP-адреса маске
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
     * Рекурсивно удаляет директорию с файлами и поддиректориями
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
     * Получает список всех существующих модулей
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
     * Получает список всех существующих моделей
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
     * Получить секунды с дробью или преобразовать из результата microtime()
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
     * Возвращает максимальное значение поля с приращением на единицу
     *
     * @access  public
	 * @param	string
	 * @feturn	integer или false
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
     * Предотвращает SQL-инъекцию в DQL
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