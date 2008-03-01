<?php

/**
 * xbkModule
 *
 * ����� ������
 *
 * @version       1.1   2008-03-01
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkModule extends xbkContextObject
{
    /**
     * MODULE_CLASS_IS_NOT_LOADED
     * ����� ���������� ������ �� ��� ��������
     */
    const MODULE_CLASS_IS_NOT_LOADED = 1;

    /**
     * ������ �������, ���������� � ����������� ��������������� �����
     *
     * @access    protected
     * @var       array
     */
	protected $xbkDeclarationVersions = Array('0.1', '0.3');
    /**
     * ��� ������
     *
     * @access    protected
     * @var       string
     */
	protected $name;

    /**
     * ������ ������ ����-���������� ������, ���� �����
     *
     * @access    protected
     * @var       object ��� null
     */
	protected $Info = null;

    /**
     * ���������, ��������� ������ ��� ����������������
     *
     * @access    protected
     * @var       boolean
     */
	protected $isSystem;

    /**
     * ���� � ����� ������
     *
     * @access    protected
     * @var       string
     */
	protected $modulePath;

    /**
     * ������ ������� ������
     *
     * @access    protected
     * @var       array
     */
	protected $models;
	/**
     * �����
     *
     * @access    protected
     * @var       object
     */
	protected $blockList;

	/**
     * ����������
     *
     * @access    protected
     * @var       array
     */
	protected $privileges = Array();

	/**
     * ������
     *
     * @access    protected
     * @var       array
     */
	protected $sections = Array();

	/**
     * ������ �������
     *
     * @access    protected
     * @var       array
     */
	protected $templateModules = Array();

	/**
     * ������������ ����������
     *
     * @access    protected
     * @var       array
     */
	protected $abstract = Array();

	/**
     * �������� ���������
     *
     * @access    protected
     * @var       array
     */
	protected $lang = Array();

	/**
     * ������������
     *
     * @access    protected
     * @var       array
     */
	protected $config = Array();

	/**
     * DOM-������ ����� ����������
     *
     * @access    protected
     * @var       object DOMDocument
     */
	protected $moduleDOM;

    /**
     * ����������� ������
     *
     * @access	  public
     * @param	  string	type (html ��� tex)
     */
    public function __construct2 ($moduleName, $fromDb = false)
    {
    	global $CONFIG;

    	//$this->DB = $DB;

    	// �������� ����� ������
    	$this->modulePath = xbkFunctions::getModulePath($moduleName);

    	// ����������, ��������� �� ������
    	$this->isSystem = xbkFunctions::isSystemModule($moduleName);

    	if (xbkFunctions::moduleExists($moduleName)) $this->name = $moduleName;

        if ($fromDb)
        {        	$result = $this->loadFromDb($moduleName);
        	// ��� ��������� �������� �� ���� ��������� �� �����
        	if (!$result) {        		$this->loadDeclarationFile($moduleName);
        	}        } else {
        	$this->loadDeclarationFile($moduleName);
    	}

    }

    /**
     * ���������� ������ ��������� ������, ���� false, ���� ������ �� ������
     *
     * @access	public
     * @param	string	type (html ��� tex)
     * @param	boolean	��������� �� ���� ������
     */
    public function getInstance ($moduleName, $fromDb, &$DB = null)
    {
    	global $CONFIG;
    	if (xbkFunctions::moduleExists($moduleName)) return new xbkModule($moduleName, $fromDb, $DB);
    	else return false;
    }

    /**
     * ��������� ���������� �� ���� ������
     *
	 * @access	private
	 * @param	string     ��� ������
     */
    protected function loadFromDb ($moduleName)
    {    	global $CONFIG;

        $models_dir = $this->modulePath.$CONFIG['path']['internal']['models'];
        if (file_exists($models_dir))
        {
            $this->models = Doctrine::loadModels($models_dir);
        }

        try {        	$table = $this->DB->getTable("xbkModule_Record");
        	$found = $table->findByDql("name = '".xbkFunctions::dqlEscapeString($moduleName)."'");
    	} catch (Exception $e) {    		// ����������� �������    		return false;    	}

        // ���������, ���� ������ ������ �� ��������� �����
    	if (count($found) > 0)
    	{
    	    try {
        		// �������� ���������� �� ����        		$Module_Record = $found[0];

        		// ����� ����������
        		$this->abstract['project'] = $Module_Record->project;
        		$this->abstract['version'] = $Module_Record->version;
        		$this->abstract['xbk_version'] = $Module_Record->xbk_version;
        		$this->abstract['migration'] = $Module_Record->migration_required;
        		$this->abstract['class'] = $Module_Record->class;
        		$this->abstract['dependencies'] = $Module_Record->dependencies;

        		// �������� ����������
        		foreach ($Module_Record->Lang as $Lang)
        		{        			// �������� �������� ���������� ���������� �� ���������        			if ($Lang->interface == $CONFIG['lang'])
        			{            			$this->lang[$Lang->name] = $Lang->value;
        			}
        			// �������� �������� ���������� �������� ����������
        			//
        		}

        		// ����������
        		foreach ($Module_Record->Privilege as $Privilege)
        		{
        			array_push($this->privileges, Array('name' => $Privilege->name, 'class' => $Privilege->class));
        		}

        		// ������
                $this->sections = xbkModuleSection::getSectionsFromModel($Module_Record);

        		// ������ �������
                foreach ($Module_Record->TemplateModule as $TemplateModule)
        		{
        			array_push($this->templateModules,
            			Array(
                			'type' => $TemplateModule->type,
                			'name' => $TemplateModule->name,
                			'class' => $TemplateModule->class
            			)
        			);
        		}

                return true;

            } catch (Exception $e) {            	// ����������� ������            	return false;            }
    	} else {    		// ��������� ��������    		return false;
    	}    }

    /**
     * �������� ����� ������� ����. ����� � ������������ � ������� �������
     *
	 * @access	protected
	 * @param	string     ��� ������
     */
    protected function getDeclarationFileParserNumber ($xbkVersion)
    {    	$last = (count($this->xbkDeclarationVersions)-1);    	for ($i = $last; isset($this->xbkDeclarationVersions[$i]); $i--) {    		if (version_compare($xbkVersion, $this->xbkDeclarationVersions[$i]) >= 0) {
    			return $i;
    		}    	}    	return $last;
    }

    /**
     * ��������� �������������� ���� ������
     *
	 * @access	protected
	 * @param	string     ��� ������
     */
    protected function loadDeclarationFile ($moduleName)
    {    	global $CONFIG;

    	if (xbkFunctions::moduleFileExists($moduleName)) {

            $this->moduleDOM = new DOMDocument();
            $this->moduleDOM->load(xbkFunctions::getModuleFilePath($moduleName));
            $declarationDOM = $this->moduleDOM->getElementsByTagName('declaration')->item(0);

            // ��������� ������ �������
            if ($declarationDOM->hasAttribute('xbkVersion')) {
                $parser_n = $this->getDeclarationFileParserNumber($declarationDOM->getAttribute('xbkVersion'));
            } else $parser_n = count($this->xbkDeclarationVersions)-1;

            // ����� ����-����������
            if ($parser_n == 1) {
                $metaDOM = $declarationDOM->getElementsByTagName('meta');
                if ($metaDOM->item(0)->getElementsByTagName('project')->length > 0) {
                    $this->abstract['project'] = $metaDOM->item(0)->getElementsByTagName('project')->item(0)->nodeValue;
                } else $this->abstract['project'] = null;
                if ($metaDOM->item(0)->getElementsByTagName('version')->length > 0) {
                    $this->abstract['version'] = $metaDOM->item(0)->getElementsByTagName('version')->item(0)->nodeValue;
                } else $this->abstract['version'] = null;
                if ($metaDOM->item(0)->getElementsByTagName('xbkVersion')->length > 0) {
                    $this->abstract['xbk_version'] = $metaDOM->item(0)->getElementsByTagName('xbkVersion')->item(0)->nodeValue;
                } else $this->abstract['xbk_version'] = null;
                if ($metaDOM->item(0)->getElementsByTagName('migration')->length > 0) {
                    $this->abstract['migration'] = $metaDOM->item(0)->getElementsByTagName('migration')->item(0)->nodeValue;
                }
                if ($metaDOM->item(0)->getElementsByTagName('class')->length > 0) {
                    $this->abstract['class'] = $metaDOM->item(0)->getElementsByTagName('class')->item(0)->nodeValue;
                } else $this->abstract['class'] = null;
                if ($metaDOM->item(0)->getElementsByTagName('dependencies')->length > 0) {
                    $this->abstract['dependencies'] = $metaDOM->item(0)->getElementsByTagName('dependencies')->item(0)->nodeValue;
                } else $this->abstract['info_class'] = null;

            } else if ($parser_n == 0) {
                $abstractDOM = $declarationDOM->getElementsByTagName('abstract');
                if ($abstractDOM->item(0)->getElementsByTagName('name')->length > 0)
                {
                    $this->abstract['project'] = $abstractDOM->item(0)->getElementsByTagName('name')->item(0)->nodeValue;
                } else $this->abstract['name'] = null;
                if ($abstractDOM->item(0)->getElementsByTagName('version')->length > 0)
                {
                    $this->abstract['version'] = $abstractDOM->item(0)->getElementsByTagName('version')->item(0)->nodeValue;
                } else $this->abstract['version'] = null;
                if ($abstractDOM->item(0)->getElementsByTagName('xbkVersion')->length > 0)
                {
                    $this->abstract['xbk_version'] = $abstractDOM->item(0)->getElementsByTagName('xbkVersion')->item(0)->nodeValue;
                } else $this->abstract['xbk_version'] = null;
                if ($abstractDOM->item(0)->getElementsByTagName('migration')->length > 0)
                {
                    $this->abstract['migration'] = $abstractDOM->item(0)->getElementsByTagName('migration')->item(0)->nodeValue;
                }
                if ($abstractDOM->item(0)->getElementsByTagName('infoClass')->length > 0)
                {
                    $this->abstract['class'] = $abstractDOM->item(0)->getElementsByTagName('infoClass')->item(0)->nodeValue;
                } else $this->abstract['class'] = null;

            }

            // �������� �������� ���������� ������
            $this->lang = $this->loadLang();

            // ����� ����������
            $privilegesGroupDOM = $declarationDOM->getElementsByTagName('privileges');
            if ($privilegesGroupDOM->length > 0)
            {
                $privilegesDOM = $privilegesGroupDOM->item(0)->getElementsByTagName('privilege');
                for ($e=0; $e<$privilegesDOM->length; $e++)
                {
                    $privilegeDOM = $privilegesDOM->item($e);
                    $privilege = Array();
                    if ($privilegeDOM->hasAttribute('name'))
                    {
                        $privilege['name'] = $privilegeDOM->getAttribute('name');
                    }
                    if ($privilegeDOM->hasAttribute('class'))
                    {
                        $privilege['class'] = $privilegeDOM->getAttribute('class');
                    }
                    array_push($this->privileges, $privilege);
        		}
    		}

            // ����� ������
            $xpath = new DOMXPath($this->moduleDOM);
            $sectionsDOM = $declarationDOM->getElementsByTagName('sections')->item(0);
            if ($sectionsDOM != null)
            {
                $src = $xpath->query("section", $declarationDOM->getElementsByTagName('sections')->item(0));
                $this->sections = $this->readSections($src);
            }

            // ����� ������� �������
            $templateModulesGroupDOM = $declarationDOM->getElementsByTagName('templateModules');
            if ($templateModulesGroupDOM->length > 0)
            {
                $templateModulesDOM = $templateModulesGroupDOM->item(0)->getElementsByTagName('templateModule');
                for ($t=0; $t<$templateModulesDOM->length; $t++)
                {
                    $templateModuleDOM = $templateModulesDOM->item($t);
                    $templateModule = Array();
                    if ($templateModuleDOM->hasAttribute('type'))
                    {
                        $templateModule['type'] = xbkFunctions::capitalizeFirstLetter($templateModuleDOM->getAttribute('type'));
                    }
                    if ($templateModuleDOM->hasAttribute('name'))
                    {
                        $templateModule['name'] = $templateModuleDOM->getAttribute('name');
                    }
                    if ($templateModuleDOM->hasAttribute('class'))
                    {
                        $templateModule['class'] = $templateModuleDOM->getAttribute('class');
                    }
                    array_push($this->templateModules, $templateModule);
                }
            }

    		// �������� ��������� ������
    		$models_path = xbkFunctions::getModulePath($moduleName).$CONFIG['path']['internal']['models'];
    		if (file_exists($models_path))
    		{
    			$models = Doctrine::loadModels($models_path);
    		}

        } else {
        	// �������������� ���� ������ �� ������
        	throw New xbkException('Module declaration file "'.$moduleName.'" not found');
        }
    }

    /**
     * ������ ������ � ������ �� ������� DOMNodeList
     *
	 * @access	protected
	 * @param	object		DOMNodeList
	 * @return	array ��� null � ������ �������
     */
    protected function readSections ($sectionDOMNodeList)
    {    	$sectionList = xbkModuleSection::getSections($this->moduleDOM, $sectionDOMNodeList);
    	return $sectionList;    }

    /**
     * ��������� �������� ��������� ������
     *
	 * @access	public
	 * @param	string       ������ ����������
	 * @return	array ��� false � ������ �������
     */
    public function loadLang ($lang = null, $default = true)
    {
    	global $CONFIG;
    	$path_to_module = $this->modulePath;
    	if ($lang != null)
    	{
            $file = $path_to_module.$CONFIG['path']['internal']['lang'].$lang.'.xml';
        } else $file = $path_to_module.$CONFIG['path']['internal']['lang'].$CONFIG['lang'].'.xml';
        if (!file_exists($file) && $default)
        {        	$file = $path_to_module.$CONFIG['path']['internal']['lang'].$CONFIG['lang'].'.xml';        }
        if (file_exists($file))
        {
        	$lang = Array();
        	$langDOM = new DOMDocument();
            $langDOM->load($file);
            $varsDOM = $langDOM->getElementsByTagName('collection')->item(0)->getElementsByTagName('var');
            for ($i=0; $i<$varsDOM->length; $i++)
            {            	$varDOM = $varsDOM->item($i);
               	if ($varDOM->hasAttribute('name') && $varDOM->hasAttribute('value'))
            	{            		$lang[$varDOM->getAttribute('name')] = $varDOM->getAttribute('value');            	} else if ($varDOM->hasAttribute('name'))
            	{            		$lang[$varDOM->getAttribute('name')] = $varDOM->nodeValue;            	}
            }
            return $lang;
        } else return false;
    }

    /**
     * ��������� �������������� �����, ���� �� ��������
     *
     * @access	public
     * @boolean
	 */
    public function prepareInfo ()
    {
    	if ($this->Info == null)
    	{
            $this->Info =& $this->factory($this->abstract['info_class']);
        }
    }

    /**
     * ���������� ��� ������
     *
     * @access	public
	 * @return  string
     */
    public function getName ()
    {    	return $this->name;
    }

    /**
     * �������� �� ������� ���������
     *
     * @access	public
	 * @return  boolean
     */
    public function isSystem ()
    {
    	return $this->isSystem;
    }

    /**
     * ���������� ������������ ����������
     *
     * @access	public
	 * @return  array
     */
    public function getAbstract ()
    {
    	return $this->abstract;
    }

    /**
     * ���������� �������� ���������
     *
     * @access	public
	 * @return  array
     */
    public function &getLang ()
    {
    	return $this->lang;
    }

    /**
     * ���������� ������������
     *
     * @access	public
	 * @return  array
     */
    public function &getConfig ()
    {
    	return $this->config;
    }

    /**
     * ���������� ������ �������� ������
     *
     * @access	public
	 * @return  array
     */
    public function &getSections ()
    {
    	return $this->sections;
    }

    /**
     * ���������� ������ �� ��������� �����
     *
     * @access	public
     * @param	string
	 * @return  object xbkModuleSection ��� null
     */
    public function &getSection ($sectionName)
    {    	foreach ($this->sections as $section)
    	{    		if ($section->name == $sectionName)
    		{    			return $section;    		}    	}
    	return null;
    }

    /**
     * ���������� ������ �������
     *
     * @access	public
	 * @return  array
     */
    public function getTemplateModuleInfoList ()
    {
    	return $this->templateModules;
    }

    /**
     * ���������� ����������
     *
     * @access	public
	 * @return  array
     */
    public function getPrivilegeInfoList ()
    {
    	return $this->privileges;
    }

    /**
     * ���������� �������� (���������) ������
     *
     * @access	public
	 * @return  string
     */
    public function getTitle ()
    {    	$this->prepareInfo();    	if ($this->Info != null)
    	{
        	return $this->Info->getTitle();
    	} else {    		throw New xbkException(self::MODULE_CLASS_IS_NOT_LOADED);    		return null;    	}
    }

    /**
     * ���������� �������� ������
     *
     * @access	public
	 * @return  string
     */
    public function getDescription ()
    {    	$this->prepareInfo();
    	if ($this->Info != null)
    	{
        	return $this->Info->getDescription();
    	} else {
    		throw New xbkException(self::MODULE_CLASS_IS_NOT_LOADED);
    		return null;
    	}
    }

    /**
     * ���������� ������ ������
     *
     * @access	public
	 * @return  string
     */
    public function getAuthor ()
    {    	$this->prepareInfo();
    	if ($this->Info != null)
    	{
        	return $this->Info->getAuthor();
    	} else {
    		throw New xbkException(self::MODULE_CLASS_IS_NOT_LOADED);
    		return null;
    	}
    }

    /**
     * ���������� ��������
     *
     * @access	public
	 * @return  string
     */
    public function getLicense ()
    {    	$this->prepareInfo();
    	if ($this->Info != null)
    	{
        	return $this->Info->getLicense();
    	} else {
    		throw New xbkException(self::MODULE_CLASS_IS_NOT_LOADED);
    		return null;
    	}
    }

    /**
     * ���������� ����
     *
     * @access	public
	 * @return  array
     */
    public function getMenu ()
    {    	$this->prepareInfo();
    	if ($this->Info != null)
    	{
        	return $this->Info->getMenu();
    	} else {
    		throw New xbkException(self::MODULE_CLASS_IS_NOT_LOADED);
    		return null;
    	}
    }

    /**
     * ���������� ��� �������������� ����������
     *
     * @access	public
	 * @return  array
     */
    public function getInfo ()
    {    	$info = Array();
    	if ($this->abstract['info_class'] != null)
    	{
        	$info['title'] = $this->getTitle();
        	$info['description'] = $this->getDescription();
        	$info['author'] = $this->getAuthor();
        	$info['license'] = $this->getLicense();
        	$info['menu'] = $this->getMenu();
        } else {        	$info['title'] = $this->getName();
        	$info['description'] = null;
        	$info['author'] = null;
        	$info['license'] = null;
        	$info['menu'] = null;        }
    	return $info;
    }

    /**
     * ���������� ������� ������ ���� ������ (��������)
     *
     * @access	public
	 * @return  integer ��� null
     */
    public function getCurrentMigrationVersion ()
    {    	if ($this->name != null)
    	{        	$q = new Doctrine_Query();
        	$rows = $q->select('m.migration_current')
              ->from('xbkModule_Record m')
              ->where('m.name = ?', $this->name)
              ->execute();
            if (count($rows) > 0)
            {            	return $rows[0]->migration_current;            } else return null;
        } else return null;
    }

    /**
     * ���������� ��������� ������ ���� ������ (��������)
     *
     * @access	public
	 * @return  integer ��� null
     */
    public function getRequiredMigrationVersion ()
    {    	if (isset($this->abstract['migration'])) return (int)$this->abstract['migration'];
    	else return null;
    }

}

?>