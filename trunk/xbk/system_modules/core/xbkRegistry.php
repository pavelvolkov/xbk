<?php

/**
 * xbkRegistry
 *
 * ����������� �������, ����������� ��������, ����������� ���������
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkRegistry
{
   /**
    * ������ Doctrine_Connection
    *
	* @access	public
	* @var  	object Doctrine_Connection
    */
    public $DB;

   /**
    * ���������� ��������� $HTTP_RAW_POST_DATA
    *
    * @access    public
    * @var       string
    */
    public $HTTP_RAW_POST_DATA;

   /**
    * ���������� ��������� $_GET
    *
    * @access    public
    * @var       array
    */
    public $_GET;

   /**
    * ���������� ��������� $_POST
    *
    * @access    public
    * @var       array
    */
    public $_POST;

   /**
    * ���������� ��������� $_COOKIE
	*
	* @access	public
	* @var	    array
	*/
	public $_COOKIE;

   /**
    * ���������� ��������� $_FILES
	*
	* @access	public
	* @var	    array
	*/
	public $_FILES;

   /**
    * ���������� ��������� $_SERVER
	*
	* @access	public
	* @var	    array
	*/
	public $_SERVER;

   /**
    * ��������, ����������� �� ����
    *
	* @access	protected
	* @var  	boolean
    */
    protected $coreInstalled = null;

	/**
    * ������ ����������� �������
	*
	* @access	public
	* @var	    array
	*/
	public $modules = Array();

	/**
    * ������ ��� �������, ������������������ � �������� ����������
	*
	* @access	public
	* @var	    array
	*/
	public $moduleList = Array();

	/**
    * ������ ����������� ����������� ��������
	*
	* @access	public
	* @var	    array
	*/
	public $contextObjects = Array();

	/**
    * ������ ����������� CSS
	*
	* @access	public
	* @var	    array
	*/
	public $css = Array();

	/**
    * ������ ����������� JS
	*
	* @access	public
	* @var	    array
	*/
	public $js = Array();

    /**
     * ����������� ������
     *
	 * @access	public
	 * @param	object Doctrine_Connection
	 * @param	boolean		��������� �� ��������� ����-���������� ���������
     */
    public function __construct ($fill = true)
    {    	global $CONFIG;

        if ($fill) $this->fill();
        else $this->clear();
    }

    /**
     * ���������� ����������
     *
	 * @access	public
	 * @param	object
    */
	public function setConnection (&$DB)
	{
		$this->DB = $DB;
	}

    /**
     * �������� ���������� ����
     *
	 * @access	public
    */
	public function prepare ()
	{		global $CONFIG;

		if (!in_array('core', $this->moduleList))
		{			if (isset($this->_SERVER['REQUEST_URI']))
			{				$UriParser = New xbkUriParser;
				$result = $UriParser->parse($this->_SERVER['REQUEST_URI']);
				if (
    				($result['type'] == 'root' && isset($result['path'])) ?
                    substr($this->_SERVER['REQUEST_URI'], 0, strlen($CONFIG['path']['web']['root'])) == $CONFIG['path']['web']['root']
                    : false
				) {					// ��������� ����� web-���� ��� $CONFIG['path']['web']['root']					$work_path = substr($this->_SERVER['REQUEST_URI'], strlen($CONFIG['path']['web']['root']));					foreach ($CONFIG['required_sections'] as $section)
					{						// ���� ������� ������						if (substr($work_path, 0, strlen($section)) == $section)
						{							// �������� ��������� ���� �� �������� �������							$this->loadModule('core', false);
							return;						}					}				}			}			// �������� ��������� ���� ����������� ���������� �������� ������
			$this->loadModule('core', true);
		}
	}

    /**
     * ������� ��� ����������
     *
	 * @access	public
     */
    public function clear ()
    {
        unset($this->HTTP_RAW_POST_DATA);
        unset($this->_GET);
        unset($this->_POST);
        unset($this->_COOKIE);
        unset($this->_FILES);
        unset($this->_SERVER);
    }

    /**
     * ��������� ���������� �� ������������ ���������
     *
	 * @access	public
     */
    public function fill ()
    {
        $this->HTTP_RAW_POST_DATA = xbkFunctions::get_HTTP_RAW_POST_DATA();
        $this->_GET = $_GET;
        $this->_POST = $_POST;
        $this->_COOKIE = $_COOKIE;
        $this->_FILES = $_FILES;
        $this->_SERVER = $_SERVER;
    }

    /**
     * ������������� ���������� ��� ������ ���������
     *
	 * @access	public
	 * @param	string		��� ���������� ���������
	 * @param	mixed		�������� ���������� ���������
     */
    public function set ($name, $value)
    {
        switch ($name) {
            case 'HTTP_RAW_POST_DATA':
                if (is_string($value)) $this->HTTP_RAW_POST_DATA = $value;
                break;
            case '_GET':
                if (is_array($value)) $this->_GET = $value;
                break;
            case '_POST':
                if (is_array($value)) $this->_POST = $value;
                break;
            case '_COOKIE':
                if (is_array($value)) $this->_COOKIE = $value;
                break;
            case '_FILES':
                if (is_array($value)) $this->_FILES = $value;
                break;
            case '_SERVER':
                if (is_array($value)) $this->_SERVER = $value;
                break;
        }
    }

    /**
     * ���������� ��������� ������ ��������� (_GET, _POST, _COOKIE, _SERVER)
     *
	 * @access	public
	 * @param	string		��� �������
	 * @param	array		��������
     */
    public function merge ($name, $value)
    {
        switch ($name) {
            case '_GET':
                if (is_array($value)) $this->_GET = array_merge_recursive($this->_GET, $value);
                break;
            case '_POST':
                if (is_array($value)) $this->_POST = array_merge_recursive($this->_POST, $value);
                break;
            case '_COOKIE':
                if (is_array($value)) $this->_COOKIE = array_merge_recursive($this->_COOKIE, $value);
                break;
            case '_FILES':
                if (is_array($value)) $this->_FILES = array_merge_recursive($this->_FILES, $value);
                break;
            case '_SERVER':
                if (is_array($value)) $this->_SERVER = array_merge_recursive($this->_SERVER, $value);
                break;
        }
    }

    /**
     * ������ ��������� ������ ������������ ������� � ������������ ��� ����������� ����������
     *
	 * @access	public
	 * @params	string, mixed, mixed...		��� ������������ �������, ����� ������� ������ ����������
	 * @return	object or false		        ����������� ������ ��� false � ������ �������
     */
    public function &factory ($contextObject)
    {    	$args = func_get_args();
    	// ������ ���������� ���������� ������ ���
    	$parameters = Array();
    	if (func_num_args() > 1) for ($i=1; $i<func_num_args(); $i++) array_push($parameters, $args[$i]);

    	$result_false = false;

    	// ��� ������ ��������� ������������ �������    	$moduleName = $this->getModuleNameOfContextObject($contextObject);
        if ($moduleName == false) return $result_false;
        else {
        	// ������ ������ � �������� ��� ���������
            $Class = new ReflectionClass($contextObject);
            if (count($parameters) > 0 && $Class->hasMethod('__construct')) {            	array_push($this->contextObjects, call_user_func_array(array(&$Class, 'newInstance'), $parameters));
            } else {            	array_push($this->contextObjects, New $contextObject);            }
            $index = count($this->contextObjects)-1;

            if ($contextObject != 'xbkModule')
            {                if (!$this->isModuleRegistered($moduleName)) {                	// ���������� ������ ������ ��� ������� ������������ �������
                    $module =& $this->loadModule($moduleName, true);
                } else {
                	// �������� ������ �� ������
                	$module =& $this->getModule($moduleName);
                }
            } else {            	// ��� ������ ������ � �������� ������������ �������            	$module =& $this->contextObjects[$index];            }

            // �������� �������� � ���������������� ��������� ������
            $lang =& $module->getLang();
            $config =& $module->getConfig();

            // ������ ���������� ������������ ���������
            $this->contextObjects[$index]->DB =& $this->DB;
            $this->contextObjects[$index]->HTTP_RAW_POST_DATA =& $this->HTTP_RAW_POST_DATA;
            $this->contextObjects[$index]->_GET =& $this->_GET;
            $this->contextObjects[$index]->_POST =& $this->_POST;
            $this->contextObjects[$index]->_COOKIE =& $this->_COOKIE;
            $this->contextObjects[$index]->_FILES =& $this->_FILES;
            $this->contextObjects[$index]->_SERVER =& $this->_SERVER;
            $this->contextObjects[$index]->_LANG =& $lang;
            $this->contextObjects[$index]->_CONFIG =& $config;
            $this->contextObjects[$index]->_Registry =& $this;
            $this->contextObjects[$index]->_Module =& $module;

            // ��������� __construct2
            if ($Class->hasMethod('__construct2')) {            	call_user_func_array(Array($this->contextObjects[$index], '__construct2'), $parameters);            }
            //echo get_class($this->contextObjects[$index]).' ';
            return $this->contextObjects[$index];
        }
    }

    /**
     * ��������� � ������������ ������
     *
	 * @access	public
	 * @param	string		��� ������
	 * @param	boolean	��������� �� ���� ������
	 * @return	object ��� false � ������ �������
     */
    public function &loadModule ($moduleName, $fromDb)
    {        $module =& $this->factory('xbkModule', $moduleName, $fromDb);

        $this->modules[count($this->modules)] =& $module;
    	array_push($this->moduleList, $moduleName);

        return $module;
    }

    /**
     * ��������� �������� ��������� ������
     *
	 * @access	public
	 * @param	string		��� ������
	 * @return	array ��� false � ������ �������
     */
    public function loadLang ($moduleName)
    {
    	global $CONFIG;
        $file = xbkFunctions::getModulePath($moduleName).$CONFIG['path']['internal']['lang'].$CONFIG['lang'].'.xml';
        if (file_exists($file))
        {        	$lang = Array();        } else return false;
    }

    /**
     * ��������� CSS
     *
	 * @access	public
	 * @param	string     ������ ���� � css
     */
    public function addCss ($css, $order = null)
    {    	if (!in_array($css, $this->css))
    	{        	if ($order == null)
        	{
            	array_push($this->css, $css);
        	} else if (is_int((int)$order)) {        		if (!isset($this->css[$order]))
        		{            		$this->css[$order] = $css;
        		} else {        			array_push($this->css, $css);        		}        	}
    	}
    }

    /**
     * ��������� JS
     *
	 * @access	public
	 * @param	string     ������ ���� � js
     */
    public function addJs ($js, $order = null)
    {    	if (!in_array($js, $this->js))
    	{
        	if ($order == null)
        	{
            	array_push($this->js, $js);
        	} else if (is_int((int)$order)) {
        		if (!isset($this->js[$order]))
        		{
            		$this->js[$order] = $js;
        		} else {
        			array_push($this->js, $js);
        		}
        	}
    	}
    }

    /**
     * ���������, ��������������� �� ������
     *
	 * @access	public
	 * @param	string		��� ������
	 * @return	boolean
     */
    public function isModuleRegistered ($moduleName)
    {    	if (in_array($moduleName, $this->moduleList)) return true;
        else return false;
    }

    /**
     * ���������� ������ �� ������ ������
     *
	 * @access	public
	 * @param	string		��� ������
	 * @return	array
     */
    public function getModule ($moduleName)
    {
    	foreach ($this->modules as $module)
    	{
            if ($module->getName() == $moduleName)
            {
                return $module;
            }
    	}
        return false;
    }

    /**
     * ���������� ��� ������ ������������ �������
     *
	 * @access	public
	 * @param	string		��� ������������ �������
	 * @return	string or false		��� ������ ��� false � ������ �������
     */
    public function getModuleNameOfContextObject ($name)
    {    	global $CONFIG;    	// ������� ���������� � ����� � ���������� ��������    	$modules = scandir($CONFIG['path']['php']['system_modules']);
		foreach ($modules as $module)
		{			$file_class = $CONFIG['path']['php']['system_modules'].$module.'/'.$name.'.php';
            if (file_exists($file_class))
            {            	return $module;
            	break;
            }        }
        // ������� ���������� � ����� � ����������������� ��������
    	$modules = scandir($CONFIG['path']['php']['user_modules']);
		foreach ($modules as $module)
		{
			$file_class = $CONFIG['path']['php']['user_modules'].$module.'/'.$name.'.php';
            if (file_exists($file_class))
            {
            	return $module;
            	break;
            }
        }
        return false;
    }

    /**
     * ���������� ������ �������� ������� �������
     *
	 * @access	public
	 * @return	array
     */
    public function getTemplateModuleInfoList ()
    {
    	global $CONFIG;
    	$return = Array();
    	foreach ($this->modules as $module)
    	{    		$templateModuleInfoList = $module->getTemplateModuleInfoList();    		if ($templateModuleInfoList != null)
    		{        		$return = array_merge($return, $templateModuleInfoList);
    		}    	}
        return $return;
    }

    /**
     * ���������� ������ �������� �����
     *
	 * @access	public
	 * @return	string ��� false
     */
    public function getLangIndex ()
    {
    	global $CONFIG;
    	foreach ($CONFIG['interface'] as $key => $value)
    	{    		if ($key == $CONFIG['lang']) return $value['index'];    	}
    	return false;
    }

    /**
     * ���������� ������� ���������
     *
	 * @access	public
	 * @return	string ��� false
     */
    public function getCharset ()
    {
    	global $CONFIG;
    	return $CONFIG['interface'][$CONFIG['lang']]['charset'];
    }

    /**
     * ���������� CSS
     *
	 * @access	public
	 * @return	Array
     */
    public function getCss ()
    {
    	return $this->css;
    }

    /**
     * ���������� Js
     *
	 * @access	public
	 * @return	Array
     */
    public function getJs ()
    {
    	return $this->js;
    }

    /**
     * ��������, ����������� �� ����
     *
	 * @access	public
	 * @return	Array
     */
    public function isCoreInstalled ()
    {
    	if ($this->coreInstalled == null)
    	{    		try {    			$query = new Doctrine_Query();    			$query->select('m.name')
                      ->from('xbkModule_Record m')
                      ->where('m.name = ?', 'core');
                $result = $query->execute();
                if (count($result) == 0) throw New xbkException;
    			$this->coreInstalled = true;    		} catch (Exception $e) {    			//
    			$this->coreInstalled = false;    		}
    		return $this->coreInstalled;    	} else {    		return $this->coreInstalled;    	}
    }


}

?>