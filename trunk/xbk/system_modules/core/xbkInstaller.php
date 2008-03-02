<?php

/**
 * xbkInstaller
 *
 * Установщик модулей
 *
 * @version    1.1   2008-02-08
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 * @togo       Выброс Exceptions
 */

class xbkInstaller extends xbkContextObject
{
    /**
     * REGISTER_OK
     * Вся необходимая информация модуля находится в БД
     */
    const REGISTER_OK = 1;

    /**
     * REGISTER_ERROR
     * Информация модуля в БД неверная или неполная
     */
    const REGISTER_ERROR = 2;

    /**
     * REGISTER_NONE
     * Информация модуля в БД не обнаружена
     */
    const REGISTER_NONE = 3;
    /**
     * Конструктор класса
     *
     * @param     string   имя модуля
     * @access    public
     */
    public function __construct2 ($moduleName)
    {    	global $CONFIG;

    	$this->moduleName = $moduleName;

    	$this->path_to_models = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];

    	// Папка для миграционных файлов
    	$this->migrationPath = $CONFIG['path']['php']['tmp'].$moduleName.'_install_migration/';    }
    /**
     * Создаёт временные файлы миграции
     *
     * @access    public
     * @param     string
	 * @return	  boolean
     */
    public function createMigrations ()
    {
    	global $CONFIG;

    	// Создание новой папки
    	xbkFunctions::unlinkRecursive($this->migrationPath);
    	if (!mkdir($this->migrationPath)) throw New xbkException("Can't create directory $this->migrationPath.");
		if (!xbkFunctions::moduleExists($this->moduleName)) throw New xbkException("Module $moduleName is not exists.");
        // Генерация миграций из моделей
        $path_to_models = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];
        $result = Doctrine::generateMigrationsFromModels($this->migrationPath, $path_to_models);
    }

    /**
     * Создаёт структуру базы данных для заданного модуля
     *
     * @access    public
     * @param     string
	 * @return	  boolean
     */
    public function createDbStructure ()
    {
    	global $CONFIG;

    	if (file_exists($this->path_to_models))
    	{
        	Doctrine::loadModels($this->path_to_models);
        	$models = xbkFunctions::getModelList($this->path_to_models);

            // Создание таблиц БД
            Doctrine::createTablesFromArray($models);
        }

    }

    /**
     * Удаляет структуру базы данных заданного модуля
     *
     * @access    public
     */
    public function dropDbStructure ($loop_n = 0)
    {
    	global $CONFIG;

    	// Число повторов, после которых идёт прерывание операции
    	$loops = 100;

    	$Export = New Doctrine_Export;

    	//$models = Doctrine::loadModels($this->path_to_models);
    	$models = xbkFunctions::getModelList($this->path_to_models);

    	// Неудачные удаления
    	$falls = 0;

    	// Удаление таблиц БД
        foreach ($models as $model)
        {        	$table = $this->DB->getTable($model);
        	try {
            	$Export->dropTable($table->getTableName());
        	} catch (Exception $e) {            	// Существует ли таблица
                try {
                    $record = $table->find(0);
                    $falls++;
                } catch (Exception $e) {
                	// Отсутствует необходимая таблица
                }        	}        }

        // Запустить удаление снова
        if ($falls > 0 && $loop_n < $loops)
        {        	$loop_n++;        	$this->dropDbStructure($loop_n);        }

    }

    /**
     * Заносит в реестр базы данных информацию модуля
     *
     * @access    public
     * @param     string
     * @param     array - массив доп. опций для таблицы модуля
     */
    public function register ($moduleName = null, $options = Array())
    {
    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	// Объект модуля
    	$Module =& $this->factory('xbkModule', $moduleName, false);

        // Общие настройки модуля
    	$Module_Record = New xbkModule_Record;

    	$this->fillModuleRecord ($Module_Record, $Module);

    	foreach ($options as $key => $value)
    	{    		$Module_Record->set($key, $value);    	}

        $Module_Record->save();

    }

    /**
     * Обновляет регистрацию
     *
     * @access    public
     * @param     string
     */
    public function updateRegister ($moduleName = null)
    {    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	// Объект модуля
    	$Module =& $this->factory('xbkModule', $moduleName, false);

    	// Поиск регистрации текущего модуля
		$table = $this->DB->getTable("xbkModule_Record");
    	$found = $table->findByDql("name = '".xbkFunctions::dqlEscapeString($Module->getName())."'");

    	if (count($found) > 0)
    	{    		$migration_current = $found[0]->migration_current;

    		$q = new Doctrine_Query();
        	$rows = $q->delete('Module')
              ->from('xbkModule_Record m')
              ->where('m.name = ?', $moduleName)
              ->execute();

            $this->register($moduleName, Array('migration_current' => $migration_current));    		//$Module_Record = $found[0];
        	//$this->fillModuleRecord ($Module_Record, $Module);

            //$Module_Record->save();
        } else {        	throw New xbkException('Module "'.$Module->getName().'" not found!');        }
    }

    /**
     * Наполняет объект xbkModuleRecord данными из декларационного файла
     *
     * @access    protected
     * @param     object xbkModule_Record
     * @param     object xbkModule
     */
    protected function fillModuleRecord (&$Module_Record, &$Module)
    {    	global $CONFIG;
        $abstract = $Module->getAbstract();

    	if (isset($abstract['name'])) {
        	$Module_Record->name = $abstract['name'];
    	}
    	if (isset($abstract['version'])) {
        	$Module_Record->version = $abstract['version'];
    	}
    	if (isset($abstract['xbk_version'])) {
        	$Module_Record->xbk_version = $abstract['xbk_version'];
    	}
    	if (isset($abstract['info_class'])) {
        	$Module_Record->info_class = $abstract['info_class'];
    	}
    	if (isset($abstract['migration'])) {
        	$Module_Record->migration_required = $abstract['migration'];
        	$Module_Record->migration_current = $abstract['migration'];
    	}
    	$Module_Record->active = true;

    	$Module_Record->is_system = xbkFunctions::isSystemModule($moduleName);

    	// Языковые настройки
    	foreach ($CONFIG['interface'] as $interface_lang => $interface_value)
    	{
    		$lang = $Module->loadLang($interface_lang, false);
        	$i = 0;
        	if (is_array($lang))
        	{
            	foreach ($lang as $key => $value)
            	{
            		$Module_Record->Lang[$i]->name = $key;
                	$Module_Record->Lang[$i]->value = $value;
                	$Module_Record->Lang[$i]->interface = $interface_lang;
                	$i++;
            	}
        	}
    	}

    	// Привилегии
    	$privilege_info_list = $Module->getPrivilegeInfoList();
    	$i = 0;
    	foreach ($privilege_info_list as $privilege_info)
    	{
    		$Module_Record->Privilege[$i]->name = $privilege_info['name'];
    		$Module_Record->Privilege[$i]->class = $privilege_info['class'];
    		$i++;
    	}

    	// Секции
    	$this->fillSections($Module_Record, $Module->getSections(), $Module_Record);

    	// Модули шаблона
    	$template_module_info_list = $Module->getTemplateModuleInfoList();
    	$i = 0;
    	foreach ($template_module_info_list as $template_module_info)
    	{
    		$Module_Record->TemplateModule[$i]->type = $template_module_info['type'];
    		$Module_Record->TemplateModule[$i]->name = $template_module_info['name'];
    		$Module_Record->TemplateModule[$i]->class = $template_module_info['class'];
    		$i++;
    	}
    }

    /**
     * Удаляет информацию из реестра
     *
     * @access    public
     * @param     string
     */
    public function unregister ($moduleName = null)
    {    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	$q = new Doctrine_Query();
    	$rows = $q->delete('Module')
          ->from('xbkModule_Record m')
          ->where('m.name = ?', $moduleName)
          ->execute();

    }

    /**
     * Заносит языковые настройки в реестр БД
     *
     * @access    public
     * @param     string
     */
    public function langRegister ($moduleName = null)
    {
    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	$Module =& $this->factory('xbkModule', $moduleName, false);

    	// Языковые настройки
    	foreach ($CONFIG['interface'] as $interface_lang => $interface_value)
    	{
    		$lang = $Module->loadLang($interface_lang, false);
        	$i = 0;
        	if (is_array($lang))
        	{
            	foreach ($lang as $key => $value)
            	{            		$Lang = New xbkLang_Record;
            		$Lang->module_id = $this->getModuleId($moduleName);
            		$Lang->name = $key;
                	$Lang->value = $value;
                	$Lang->interface = $interface_lang;
                	$Lang->save();
                	$i++;
            	}
        	}
    	}

    }

    /**
     * Удаляет языковые настройки из реестра БД
     *
     * @access    public
     * @param     string
     */
    public function langUnregister ($moduleName = null)
    {
    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	$q = new Doctrine_Query();
    	$rows = $q->select('m.id, m.name')
          ->from('xbkModule_Record m')
          ->where('m.name = ?', $moduleName)
          ->execute();
        if (count($rows) > 0)
        {

        	$q = new Doctrine_Query();
        	$deleted = $q->delete('xbkLang_Record')
              ->from('xbkLang_Record l')
              ->where('l.module_id = ?', $rows[0]->id)
              ->execute();
        }

    }

    /**
     * Заполняет секции в объект Module_Record
     *
     * @access    public
     * @param     object   ссылка на объект, в который заполняем секции
     * @param     array
     */
    public function fillSections (&$ref, &$sections, &$moduleRef)
    {    	$i = 0;    	foreach ($sections as $section)
    	{    		if ($ref instanceof xbkModule_Record)
    		{    			//echo 'Module ';        		$ref->Section[$i]->name = $section->name;
        		$ref->Section[$i]->type = $section->type;
        		$ref->Section[$i]->class = $section->class;
    		} else if ($ref instanceof xbkSection_Record) {    			$ref->Subsection[$i]->Module = $moduleRef;    			$ref->Subsection[$i]->name = $section->name;
        		$ref->Subsection[$i]->type = $section->type;
        		$ref->Subsection[$i]->class = $section->class;    		}
    		if ($section->hasSubsections())
    		{    			if ($ref instanceof xbkModule_Record)
        		{        			//echo 'ModuleSection ';
            		$this->fillSections($ref->Section[$i], $section->getSubsections(), $moduleRef);
        		} else if ($ref instanceof xbkSection_Record) {        			$this->fillSections($ref->Subsection[$i], $section->getSubsections(), $moduleRef);        		}
    		}
    		$i++;    	}
    }

    /**
     * Устанавливает ядро
     *
     * @access    public
	 * @return	  boolean
     */
    public function install ()
    {
    	global $CONFIG;

        // Создание таблиц БД
        $this->createDbStructure($this->moduleName);

        // Регистрация модуля в реестре
        $this->register($this->moduleName);
    }

    /**
     * Устанавливает ядро
     *
     * @access    public
	 * @return	  boolean
     */
    public function uninstall ()
    {
    	global $CONFIG;

        // Удаление регистрационной информации
        $this->unregister($this->moduleName);

        // Удаление таблиц
        $this->dropDbStructure($this->moduleName);
    }

    /**
     * Определяет наличие информации модуля в базе
     *
     * @access    public
     * @param     string
	 * @return	  array
     */
    public function checkRegister ()
    {
        // Загрузка структуры данных
		$models_path = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];        if (file_exists($models_path))
		{
			$models = Doctrine::loadModels($models_path);
		}

		$module = New xbkModule_Record;


    }

    /**
     * Анализирует модуль на предмет корректности его установки
     *
     * @access    public
     * @param     string
	 * @return	  array
     */
    public function check ()
    {
        global $CONFIG;

        // Возвращаемый результат
        $result = Array();

        // Проверка наличия необходимых таблиц БД

        $ModelAnalyzer =& $this->factory('xbkModelAnalyzer');
        $result['tables'] = $ModelAnalyzer->checkTables($this->moduleName);

        return $result;    }

    /**
     * Возвращает ID модуля по имени
     *
     * @access    public
     * @param     string
	 * @return	  int
     */
    public function getModuleId ($moduleName)
    {    	$q = new Doctrine_Query();
    	$rows = $q->select('m.id, m.name')
          ->from('xbkModule_Record m')
          ->where('m.name = ?', $moduleName)
          ->execute();
        if (count($rows) > 0)
        {            return $rows[0]->id;        } else return false;
    }

}

?>