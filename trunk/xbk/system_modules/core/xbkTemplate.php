<?php

/**
 * xbkTeplate
 *
 * ����������� ����� �� patTemplate
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkTemplate extends patTemplate
{
    /**
     * ������ ���������� �������������� �������, ��������� ��������
     *
     * @access    private
     * @var       array
     */
    private $_additionalModuleInfoList = Array();

    /**
     * Web-���� � ����� ����� �������� ����� ��������
     *
     * @access    private
     * @var       string
     */
    private $_pathToSkinWeb = null;

    /**
     * Php-���� � ����� ����� �������� ����� ��������
     *
     * @access    private
     * @var       string
     */
    private $_pathToSkinPhp = null;
    /**
     * ����������� ������
     *
     * ������ �������� ���������� ����� ��������� ��������� �����:
     * - 'module' - ��� ������ ��� ������ �� ������ ������
     * - 'skin' - ��� �����
     * - 'type' - 'html' ��� 'tex'
     *
     * @access    public
     * @param     array
     */
    public function __construct2 ($options)
    {
        global $CONFIG;

        // ��������� �������������� �����
        if (isset($options['module']) ? xbkFunctions::moduleExists($options['module']) : false)
        {
            $this->_options['module'] = $options['module'];
        } else {        	$this->_options['module'] = $this->_Module->getName();        }
        if (isset($options['skin']) ? xbkFunctions::moduleSkinExists($options['skin']) : false)
        {
            $this->_options['skin'] = $options['skin'];
        } else {
        	$this->_options['skin'] = null;
        }
        if (isset($options['type']) ? (in_array($options['type'], Array('html', 'tex'))) : false)
        {
            $this->_options['type'] = $options['type'];
        } else {
        	$this->_options['type'] = 'html';
        }

        //$this->_options['defaultFunction'] = true;

        // ����������� ����������� ������
        $this->patTemplate($this->_options['type']);

        // ������� ������������ ���
        $this->setNamespace('xbk');

        // �������� ������������
        $this->applyInputFilter('ShortModifiers');

        // �������������� ������
        $this->_additionalModuleInfoList = $this->_Registry->getTemplateModuleInfoList();

    }

    /**
     * ���������� ��� ������ ��������������� ������
     *
     * - 'moduleType' - ��� ��������������� ������
     * - 'moduleName' - ��� ��������������� ������
     *
     * @access    public
     * @param     array ��� false
     */
    public function getAdditionalModuleClassName ($moduleType, $moduleName)
    {    	foreach ($this->_additionalModuleInfoList as $moduleInfo)
    	{    		if ($moduleName == $moduleInfo['name'] && $moduleType == $moduleInfo['type'])
    		{    			return $moduleInfo['class'];
    			break;    		}    	}
    	return false;
    }

    /**
     * ������������� �������� ����� ��� ����������� �������,
     * ������ �� ��������� ����� � �������/���������� ������������ �����.
     * ���� ���� ������� �����������, ����� �� ����� ����� �� ���������.
	 *
	 * @access	private
	 * @param	string	��� �������
	 */
    private function setRootForTpl ($input = null)
    {
    	global $CONFIG;
    	// ����� � ��������� ������ ��� ��������� ������
    	$root_for_tpl = $CONFIG['path']['php']['skins'].
    	$this->_options['module'].'/'.
    	$this->_options['skin'].'/'.
    	$CONFIG['path']['internal']['html'];
    	// ����� �� ������ �� ��������� ��� ��������� �������� ������
     	$root_for_tpl_default1 = $CONFIG['path']['php']['system_modules'].
    	$this->_options['module'].'/'.
    	$CONFIG['path']['internal']['skin'].
    	$CONFIG['path']['internal']['html'];
    	// ����� �� ������ �� ��������� ��� ��������� ����������������� ������
     	$root_for_tpl_default2 = $CONFIG['path']['php']['user_modules'].
    	$this->_options['module'].'/'.
    	$CONFIG['path']['internal']['skin'].
    	$CONFIG['path']['internal']['html'];
    	// �������� �� ������� ������ ��������
    	if (file_exists($root_for_tpl.$input.'.'.$CONFIG['tpl']['ext']) && $this->_options['skin'] != null)
    	{
    		$this->setRoot($root_for_tpl);
    		$this->_pathToSkinWeb = $CONFIG['path']['web']['skins'].
        	$this->_options['module'].'/'.
        	$this->_options['skin'].'/';
        	$this->_pathToSkinPhp = $CONFIG['path']['php']['skins'].
        	$this->_options['module'].'/'.
        	$this->_options['skin'].'/';
    	} else if (file_exists($root_for_tpl_default1.$input.'.'.$CONFIG['tpl']['ext']))
    	{
    		$this->setRoot($root_for_tpl_default1);
    		$this->_pathToSkinWeb = $CONFIG['path']['web']['system_modules'].
        	$this->_options['module'].'/'.
        	$CONFIG['path']['internal']['skin'];
        	$this->_pathToSkinPhp = $CONFIG['path']['php']['system_modules'].
        	$this->_options['module'].'/'.
        	$CONFIG['path']['internal']['skin'];
    	} else if (file_exists($root_for_tpl_default2.$input.'.'.$CONFIG['tpl']['ext']))
    	{
    		$this->setRoot($root_for_tpl_default2);
    		$this->_pathToSkinWeb = $CONFIG['path']['web']['user_modules'].
        	$this->_options['module'].'/'.
        	$CONFIG['path']['internal']['skin'];
        	$this->_pathToSkinPhp = $CONFIG['path']['php']['user_modules'].
        	$this->_options['module'].'/'.
        	$CONFIG['path']['internal']['skin'];
    	} else {
    		// ������: ���� ������� �� ����������
    	}
    }

    /**
     * ���������� web-���� � ����� �������� �������
	 *
	 * @access	private
	 * @return	string
	 */
    public function getpathToSkin ($mode = 'web')
    {    	if ($mode == 'web') return $this->_pathToSkinWeb;
    	else if ($mode == 'php') return $this->_pathToSkinPhp;
    	else return $this->_pathToSkinWeb;    }

   /**
    * ������� loadModule �������� ����������� ������������ �������
    * � �������� � ���� �������������� �������
    *
    * loads a patTemplate module
    *
    * Modules are located in the patTemplate folder and include:
    * - Readers
    * - Caches
    * - Variable Modifiers
    * - Filters
    * - Functions
    * - Stats
    *
    * @access    public
    * @param     string    moduleType (Reader|TemplateCache|Modifier|OutputFilter|InputFilter)
    * @param     string    moduleName
    * @param     array    parameters for the module
    * @return    object
    */
    function &loadModule( $moduleType, $moduleName, $params = array(), $new = false )
    {
        if (!isset($this->_modules[$moduleType])) {
            $this->_modules[$moduleType] = array();
        }

        $sig = md5($moduleName . serialize($params));

        // already has been loaded before
        if (isset($this->_modules[$moduleType][$sig] ) && $new === false) {
            return $this->_modules[$moduleType][$sig];
        }

        // base class for all modules must be loaded
        if (!class_exists('patTemplate_Module')) {
            $file = sprintf('%s/Module.php', $this->getIncludePath() );
            if (!@include_once $file) {
                return    patErrorManager::raiseError(PATTEMPLATE_ERROR_BASECLASS_NOT_FOUND, 'Could not load module base class.');
			}
		}

		// base class of the module type
		$baseClass = 'patTemplate_' . $moduleType;
		if (!class_exists($baseClass)) {
			$baseFile = sprintf('%s/%s.php', $this->getIncludePath(), $moduleType);
			if (!@include_once $baseFile) {
                return patErrorManager::raiseError(PATTEMPLATE_ERROR_BASECLASS_NOT_FOUND, "Could not load base class for $moduleType ($baseFile).");
			}
		}

        // -- ������ ������� � ���� ������������ ������� --
		// ������� N1 - �������� � ���-�� ������������ ������� xBk
		$moduleClass = $this->getAdditionalModuleClassName($moduleType, $moduleName);
		if($moduleClass != false)
		{
			if (!class_exists($moduleClass)) {
    			return	patErrorManager::raiseError(PATTEMPLATE_ERROR_Module_NOT_FOUND, "Module file $moduleFile does not contain class $moduleClass.");
    		}

    		$this->_modules[$moduleType][$sig] = &$this->_Registry->factory($moduleClass);
    		if (method_exists( $this->_modules[$moduleType][$sig], 'setTemplateReference')) {
    			$this->_modules[$moduleType][$sig]->setTemplateReference( $this );
    		}
    		$this->_modules[$moduleType][$sig]->setParams($params);
    		return $this->_modules[$moduleType][$sig];
		}
		// -- ����� ������� --

		// ������� N2 - �������� ������������ ������� ������������ ������
		$moduleClass = 'patTemplate_' . $moduleType . '_' .$moduleName;
		if(!class_exists( $moduleClass, false )) {
			if (isset($this->_ModuleDirs[$moduleType])) {
				$dirs = $this->_ModuleDirs[$moduleType];
			} else {
				$dirs = array();
			}
			array_push($dirs, $this->getIncludePath() .'/'. $moduleType);

			$found = false;
			foreach ($dirs as $dir) {
				$moduleFile	= sprintf('%s/%s.php', $dir, str_replace( '_', '/', $moduleName));
				if (@include_once $moduleFile) {
					$found = true;
					break;
				}
			}

			if (!$found) {
				return patErrorManager::raiseError( PATTEMPLATE_ERROR_Module_NOT_FOUND, "Could not load module $moduleClass ($moduleFile)." );
			}
		}

		if (!class_exists($moduleClass)) {
			return	patErrorManager::raiseError(PATTEMPLATE_ERROR_Module_NOT_FOUND, "Module file $moduleFile does not contain class $moduleClass.");
		}

		$this->_modules[$moduleType][$sig] = &new $moduleClass;
		if (method_exists( $this->_modules[$moduleType][$sig], 'setTemplateReference')) {
			$this->_modules[$moduleType][$sig]->setTemplateReference( $this );
		}

		$this->_modules[$moduleType][$sig]->setParams($params);
		return $this->_modules[$moduleType][$sig];
	}

   /**
	* ��������� ����������� ���� � ��������� xbkTemplate ����
	*
	* @access	public
	* @param	string	��� ����� (��� ����� (��� ����������), shm segment, etc.)
	* @param	string	�������, ������������ ��� ������. ����� ����� ���������� ������ ��������
	* @param	array	�������������� ����� ������ ��� ������� �������
	* @param	string	��� ���������� �������, ������������� � ���-�� ����������
	* @return	boolean	true � ������ ������, false � ������ �������
	*/
	function readTemplatesFromInput($input, $reader = 'xbkFile', $options = null, $parseInto = null)
	{
		if ($reader == 'xbkFile') $this->setRootForTpl($input);
		return parent::readTemplatesFromInput($input, $reader, $options, $parseInto);
    }

   /**
	* ��������� � ��������� ����������� ���� � ������
	*
	* @access	public
	* @param	string	��� ����� (filename, shm segment, etc.)
	* @param	string	������� ������������ ��� ������
	* @param	string	��� �������, ������������� � ���-�� ����������,
	* @return	boolean	true, ���� ������ ��� ��������, ����� false
	*/
	function loadTemplateFromInput($input, $reader = 'xbkFile', $options = null, $parseInto = false)
	{
		if ($reader == 'xbkFile') $this->setRootForTpl($input);
        return parent::loadTemplateFromInput($input, $reader, $options, $parseInto);
	}

    /**
     * ��������� CSS
     *
	 * @access	public
	 * @param	string     ��� CSS ����� ��� ����������
     */
    public function addCss ($css, $order = null)
    {
    	global $CONFIG;
        $this->_Registry->addCss($this->_pathToSkinWeb.$CONFIG['path']['internal']['css'].$css.'.css', $order);
    }

    /**
     * ��������� JS
     *
	 * @access	public
	 * @param	string     ��� JS ����� ��� ����������
     */
    public function addJs ($js, $order = null)
    {
    	global $CONFIG;
        $this->_Registry->addJs($this->_pathToSkinWeb.$CONFIG['path']['internal']['js'].$js.'.js', $order);
    }
}

?>