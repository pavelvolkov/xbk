<?php

/**
 * xbkInstaller
 *
 * ���������� �������
 *
 * @version    1.1   2008-02-08
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 * @togo       ������ Exceptions
 */

class xbkInstaller extends xbkContextObject
{
    /**
     * REGISTER_OK
     * ��� ����������� ���������� ������ ��������� � ��
     */
    const REGISTER_OK = 1;

    /**
     * REGISTER_ERROR
     * ���������� ������ � �� �������� ��� ��������
     */
    const REGISTER_ERROR = 2;

    /**
     * REGISTER_NONE
     * ���������� ������ � �� �� ����������
     */
    const REGISTER_NONE = 3;
    /**
     * ����������� ������
     *
     * @param     string   ��� ������
     * @access    public
     */
    public function __construct2 ($moduleName)
    {

    	$this->moduleName = $moduleName;

    	$this->path_to_models = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];

    	// ����� ��� ������������ ������
    	$this->migrationPath = $CONFIG['path']['php']['tmp'].$moduleName.'_install_migration/';
    /**
     * ������ ��������� ����� ��������
     *
     * @access    public
     * @param     string
	 * @return	  boolean
     */
    public function createMigrations ()
    {
    	global $CONFIG;

    	// �������� ����� �����
    	xbkFunctions::unlinkRecursive($this->migrationPath);
    	if (!mkdir($this->migrationPath)) throw New xbkException("Can't create directory $this->migrationPath.");
		if (!xbkFunctions::moduleExists($this->moduleName)) throw New xbkException("Module $moduleName is not exists.");

        $path_to_models = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];
        $result = Doctrine::generateMigrationsFromModels($this->migrationPath, $path_to_models);
    }

    /**
     * ������ ��������� ���� ������ ��� ��������� ������
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

            // �������� ������ ��
            Doctrine::createTablesFromArray($models);
        }

    }

    /**
     * ������� ��������� ���� ������ ��������� ������
     *
     * @access    public
     */
    public function dropDbStructure ($loop_n = 0)
    {
    	global $CONFIG;

    	// ����� ��������, ����� ������� ��� ���������� ��������
    	$loops = 100;

    	$Export = New Doctrine_Export;

    	//$models = Doctrine::loadModels($this->path_to_models);
    	$models = xbkFunctions::getModelList($this->path_to_models);

    	// ��������� ��������
    	$falls = 0;

    	// �������� ������ ��
        foreach ($models as $model)
        {
        	try {
            	$Export->dropTable($table->getTableName());
        	} catch (Exception $e) {
                try {
                    $record = $table->find(0);
                    $falls++;
                } catch (Exception $e) {
                	// ����������� ����������� �������
                }

        // ��������� �������� �����
        if ($falls > 0 && $loop_n < $loops)
        {

    }

    /**
     * ������� � ������ ���� ������ ���������� ������
     *
     * @access    public
     * @param     string
     * @param     array - ������ ���. ����� ��� ������� ������
     */
    public function register ($moduleName = null, $options = Array())
    {
    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	// ������ ������
    	$Module =& $this->factory('xbkModule', $moduleName, false);

        // ����� ��������� ������
    	$Module_Record = New xbkModule_Record;

    	$this->fillModuleRecord ($Module_Record, $Module);

    	foreach ($options as $key => $value)
    	{

        $Module_Record->save();

    }

    /**
     * ��������� �����������
     *
     * @access    public
     * @param     string
     */
    public function updateRegister ($moduleName = null)
    {

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	// ������ ������
    	$Module =& $this->factory('xbkModule', $moduleName, false);

    	// ����� ����������� �������� ������
		$table = $this->DB->getTable("xbkModule_Record");
    	$found = $table->findByDql("name = '".xbkFunctions::dqlEscapeString($Module->getName())."'");

    	if (count($found) > 0)
    	{

    		$q = new Doctrine_Query();
        	$rows = $q->delete('Module')
              ->from('xbkModule_Record m')
              ->where('m.name = ?', $moduleName)
              ->execute();

            $this->register($moduleName, Array('migration_current' => $migration_current));
        	//$this->fillModuleRecord ($Module_Record, $Module);

            //$Module_Record->save();
        } else {
    }

    /**
     * ��������� ������ xbkModuleRecord ������� �� ��������������� �����
     *
     * @access    protected
     * @param     object xbkModule_Record
     * @param     object xbkModule
     */
    protected function fillModuleRecord (&$Module_Record, &$Module)
    {
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

    	// �������� ���������
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

    	// ����������
    	$privilege_info_list = $Module->getPrivilegeInfoList();
    	$i = 0;
    	foreach ($privilege_info_list as $privilege_info)
    	{
    		$Module_Record->Privilege[$i]->name = $privilege_info['name'];
    		$Module_Record->Privilege[$i]->class = $privilege_info['class'];
    		$i++;
    	}

    	// ������
    	$this->fillSections($Module_Record, $Module->getSections(), $Module_Record);

    	// ������ �������
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
     * ������� ���������� �� �������
     *
     * @access    public
     * @param     string
     */
    public function unregister ($moduleName = null)
    {

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	$q = new Doctrine_Query();
    	$rows = $q->delete('Module')
          ->from('xbkModule_Record m')
          ->where('m.name = ?', $moduleName)
          ->execute();

    }

    /**
     * ������� �������� ��������� � ������ ��
     *
     * @access    public
     * @param     string
     */
    public function langRegister ($moduleName = null)
    {
    	global $CONFIG;

    	if ($moduleName == null) $moduleName = $this->moduleName;

    	$Module =& $this->factory('xbkModule', $moduleName, false);

    	// �������� ���������
    	foreach ($CONFIG['interface'] as $interface_lang => $interface_value)
    	{
    		$lang = $Module->loadLang($interface_lang, false);
        	$i = 0;
        	if (is_array($lang))
        	{
            	foreach ($lang as $key => $value)
            	{
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
     * ������� �������� ��������� �� ������� ��
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
     * ��������� ������ � ������ Module_Record
     *
     * @access    public
     * @param     object   ������ �� ������, � ������� ��������� ������
     * @param     array
     */
    public function fillSections (&$ref, &$sections, &$moduleRef)
    {
    	{
    		{
        		$ref->Section[$i]->type = $section->type;
        		$ref->Section[$i]->class = $section->class;
    		} else if ($ref instanceof xbkSection_Record) {
        		$ref->Subsection[$i]->type = $section->type;
        		$ref->Subsection[$i]->class = $section->class;
    		if ($section->hasSubsections())
    		{
        		{
            		$this->fillSections($ref->Section[$i], $section->getSubsections(), $moduleRef);
        		} else if ($ref instanceof xbkSection_Record) {
    		}
    		$i++;
    }

    /**
     * ������������� ����
     *
     * @access    public
	 * @return	  boolean
     */
    public function install ()
    {
    	global $CONFIG;

        // �������� ������ ��
        $this->createDbStructure($this->moduleName);

        // ����������� ������ � �������
        $this->register($this->moduleName);
    }

    /**
     * ������������� ����
     *
     * @access    public
	 * @return	  boolean
     */
    public function uninstall ()
    {
    	global $CONFIG;

        // �������� ��������������� ����������
        $this->unregister($this->moduleName);

        // �������� ������
        $this->dropDbStructure($this->moduleName);
    }

    /**
     * ���������� ������� ���������� ������ � ����
     *
     * @access    public
     * @param     string
	 * @return	  array
     */
    public function checkRegister ()
    {
        // �������� ��������� ������
		$models_path = xbkFunctions::getModulePath($this->moduleName).$CONFIG['path']['internal']['models'];
		{
			$models = Doctrine::loadModels($models_path);
		}

		$module = New xbkModule_Record;


    }

    /**
     * ����������� ������ �� ������� ������������ ��� ���������
     *
     * @access    public
     * @param     string
	 * @return	  array
     */
    public function check ()
    {
        global $CONFIG;

        // ������������ ���������
        $result = Array();

        // �������� ������� ����������� ������ ��

        $ModelAnalyzer =& $this->factory('xbkModelAnalyzer');
        $result['tables'] = $ModelAnalyzer->checkTables($this->moduleName);

        return $result;

    /**
     * ���������� ID ������ �� �����
     *
     * @access    public
     * @param     string
	 * @return	  int
     */
    public function getModuleId ($moduleName)
    {
    	$rows = $q->select('m.id, m.name')
          ->from('xbkModule_Record m')
          ->where('m.name = ?', $moduleName)
          ->execute();
        if (count($rows) > 0)
        {
    }

}

?>