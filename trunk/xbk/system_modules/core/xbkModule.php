<?php

/**
 * xbkModule
 *
 * Класс модуля
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
     * Класс информации модуля не был загружен
     */
    const MODULE_CLASS_IS_NOT_LOADED = 1;

    /**
     * Версии системы, сопряжённые с синтаксисом декларационного файла
     *
     * @access    protected
     * @var       array
     */
	protected $xbkDeclarationVersions = Array('0.1', '0.3');
    /**
     * Имя модуля
     *
     * @access    protected
     * @var       string
     */
	protected $name;

    /**
     * Объект класса мета-информации модуля, если задан
     *
     * @access    protected
     * @var       object или null
     */
	protected $Info = null;

    /**
     * Указывает, системный модуль или пользовательский
     *
     * @access    protected
     * @var       boolean
     */
	protected $isSystem;

    /**
     * Путь к папке модуля
     *
     * @access    protected
     * @var       string
     */
	protected $modulePath;

    /**
     * Массив моделей данных
     *
     * @access    protected
     * @var       array
     */
	protected $models;
	/**
     * Блоки
     *
     * @access    protected
     * @var       object
     */
	protected $blockList;

	/**
     * Привилегии
     *
     * @access    protected
     * @var       array
     */
	protected $privileges = Array();

	/**
     * Секции
     *
     * @access    protected
     * @var       array
     */
	protected $sections = Array();

	/**
     * Модули шаблона
     *
     * @access    protected
     * @var       array
     */
	protected $templateModules = Array();

	/**
     * Описательная информация
     *
     * @access    protected
     * @var       array
     */
	protected $abstract = Array();

	/**
     * Языковые настройки
     *
     * @access    protected
     * @var       array
     */
	protected $lang = Array();

	/**
     * Конфигурации
     *
     * @access    protected
     * @var       array
     */
	protected $config = Array();

	/**
     * DOM-объект файла интеграции
     *
     * @access    protected
     * @var       object DOMDocument
     */
	protected $moduleDOM;

    /**
     * Конструктор класса
     *
     * @access	  public
     * @param	  string	type (html или tex)
     */
    public function __construct2 ($moduleName, $fromDb = false)
    {
    	global $CONFIG;

    	//$this->DB = $DB;

    	// Получаем папку модуля
    	$this->modulePath = xbkFunctions::getModulePath($moduleName);

    	// Определяем, системный ли модуль
    	$this->isSystem = xbkFunctions::isSystemModule($moduleName);

    	if (xbkFunctions::moduleExists($moduleName)) $this->name = $moduleName;

        if ($fromDb)
        {        	$result = $this->loadFromDb($moduleName);
        	// При неудачной загрузке из базы загружаем из файла
        	if (!$result) {        		$this->loadDeclarationFile($moduleName);
        	}        } else {
        	$this->loadDeclarationFile($moduleName);
    	}

    }

    /**
     * Возвращает объект заданного модуля, либо false, если модуль не найден
     *
     * @access	public
     * @param	string	type (html или tex)
     * @param	boolean	загружать из базы данных
     */
    public function getInstance ($moduleName, $fromDb, &$DB = null)
    {
    	global $CONFIG;
    	if (xbkFunctions::moduleExists($moduleName)) return new xbkModule($moduleName, $fromDb, $DB);
    	else return false;
    }

    /**
     * Загружает информацию из базы данных
     *
	 * @access	private
	 * @param	string     имя модуля
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
    	} catch (Exception $e) {    		// Отсутствует таблица    		return false;    	}

        // Проверяем, если модуль найден по заданному имени
    	if (count($found) > 0)
    	{
    	    try {
        		// Получаем информацию из базы        		$Module_Record = $found[0];

        		// Общая информация
        		$this->abstract['project'] = $Module_Record->project;
        		$this->abstract['version'] = $Module_Record->version;
        		$this->abstract['xbk_version'] = $Module_Record->xbk_version;
        		$this->abstract['migration'] = $Module_Record->migration_required;
        		$this->abstract['class'] = $Module_Record->class;
        		$this->abstract['dependencies'] = $Module_Record->dependencies;

        		// Языковые переменные
        		foreach ($Module_Record->Lang as $Lang)
        		{        			// Загрузка языковых переменных интерфейса по умолчанию        			if ($Lang->interface == $CONFIG['lang'])
        			{            			$this->lang[$Lang->name] = $Lang->value;
        			}
        			// Загрузка языковых переменных текущего интерфейса
        			//
        		}

        		// Привилегии
        		foreach ($Module_Record->Privilege as $Privilege)
        		{
        			array_push($this->privileges, Array('name' => $Privilege->name, 'class' => $Privilege->class));
        		}

        		// Секции
                $this->sections = xbkModuleSection::getSectionsFromModel($Module_Record);

        		// Модули шаблона
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

            } catch (Exception $e) {            	// Неизвестная ошибка            	return false;            }
    	} else {    		// Неудачная загрузка    		return false;
    	}    }

    /**
     * Получает номер парсера декл. файла в соответствии с версией системы
     *
	 * @access	protected
	 * @param	string     имя модуля
     */
    protected function getDeclarationFileParserNumber ($xbkVersion)
    {    	$last = (count($this->xbkDeclarationVersions)-1);    	for ($i = $last; isset($this->xbkDeclarationVersions[$i]); $i--) {    		if (version_compare($xbkVersion, $this->xbkDeclarationVersions[$i]) >= 0) {
    			return $i;
    		}    	}    	return $last;
    }

    /**
     * Загружает декларационный файл модуля
     *
	 * @access	protected
	 * @param	string     имя модуля
     */
    protected function loadDeclarationFile ($moduleName)
    {    	global $CONFIG;

    	if (xbkFunctions::moduleFileExists($moduleName)) {

            $this->moduleDOM = new DOMDocument();
            $this->moduleDOM->load(xbkFunctions::getModuleFilePath($moduleName));
            $declarationDOM = $this->moduleDOM->getElementsByTagName('declaration')->item(0);

            // Получение номера парсера
            if ($declarationDOM->hasAttribute('xbkVersion')) {
                $parser_n = $this->getDeclarationFileParserNumber($declarationDOM->getAttribute('xbkVersion'));
            } else $parser_n = count($this->xbkDeclarationVersions)-1;

            // Рабор мета-информации
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

            // Загрузка языковых переменных модуля
            $this->lang = $this->loadLang();

            // Рабор привилегий
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

            // Рабор секций
            $xpath = new DOMXPath($this->moduleDOM);
            $sectionsDOM = $declarationDOM->getElementsByTagName('sections')->item(0);
            if ($sectionsDOM != null)
            {
                $src = $xpath->query("section", $declarationDOM->getElementsByTagName('sections')->item(0));
                $this->sections = $this->readSections($src);
            }

            // Рабор модулей шаблона
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

    		// Загрузка структуры данных
    		$models_path = xbkFunctions::getModulePath($moduleName).$CONFIG['path']['internal']['models'];
    		if (file_exists($models_path))
    		{
    			$models = Doctrine::loadModels($models_path);
    		}

        } else {
        	// Интеграционный файл модуля не найден
        	throw New xbkException('Module declaration file "'.$moduleName.'" not found');
        }
    }

    /**
     * Читает секции в массив из объекта DOMNodeList
     *
	 * @access	protected
	 * @param	object		DOMNodeList
	 * @return	array или null в случае неудачи
     */
    protected function readSections ($sectionDOMNodeList)
    {    	$sectionList = xbkModuleSection::getSections($this->moduleDOM, $sectionDOMNodeList);
    	return $sectionList;    }

    /**
     * Загружает языковые настройки модуля
     *
	 * @access	public
	 * @param	string       индекс интерфейса
	 * @return	array или false в случае неудачи
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
     * Загружает информационный класс, если не загружен
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
     * Возвращает имя модуля
     *
     * @access	public
	 * @return  string
     */
    public function getName ()
    {    	return $this->name;
    }

    /**
     * Является ли модуляь системным
     *
     * @access	public
	 * @return  boolean
     */
    public function isSystem ()
    {
    	return $this->isSystem;
    }

    /**
     * Возвращает описательную информацию
     *
     * @access	public
	 * @return  array
     */
    public function getAbstract ()
    {
    	return $this->abstract;
    }

    /**
     * Возвращает языковые настройки
     *
     * @access	public
	 * @return  array
     */
    public function &getLang ()
    {
    	return $this->lang;
    }

    /**
     * Возвращает конфигурации
     *
     * @access	public
	 * @return  array
     */
    public function &getConfig ()
    {
    	return $this->config;
    }

    /**
     * Возвращает массив объектов секций
     *
     * @access	public
	 * @return  array
     */
    public function &getSections ()
    {
    	return $this->sections;
    }

    /**
     * Возвращает секцию по заданному имени
     *
     * @access	public
     * @param	string
	 * @return  object xbkModuleSection или null
     */
    public function &getSection ($sectionName)
    {    	foreach ($this->sections as $section)
    	{    		if ($section->name == $sectionName)
    		{    			return $section;    		}    	}
    	return null;
    }

    /**
     * Возвращает модули шаблона
     *
     * @access	public
	 * @return  array
     */
    public function getTemplateModuleInfoList ()
    {
    	return $this->templateModules;
    }

    /**
     * Возвращает привилегии
     *
     * @access	public
	 * @return  array
     */
    public function getPrivilegeInfoList ()
    {
    	return $this->privileges;
    }

    /**
     * Возвращает название (заголовок) модуля
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
     * Возвращает описание модуля
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
     * Возвращает автора модуля
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
     * Возвращает лицензию
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
     * Возвращает меню
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
     * Возвращает всю дополнительную информацию
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
     * Возвращает текущую версию базы данных (миграция)
     *
     * @access	public
	 * @return  integer или null
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
     * Возвращает требуемую версию базы данных (миграция)
     *
     * @access	public
	 * @return  integer или null
     */
    public function getRequiredMigrationVersion ()
    {    	if (isset($this->abstract['migration'])) return (int)$this->abstract['migration'];
    	else return null;
    }

}

?>