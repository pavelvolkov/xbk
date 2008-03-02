<?php

class xbkModule_Record extends Doctrine_Record
{
    public function setUp()
    {
        $this->hasMany('xbkSection_Record as Section', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkTemplateModule_Record as TemplateModule', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkLang_Record as Lang', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkPrivilege_Record as Privilege', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkExtensionPoint_Record as ExtensionPoint', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkExtension_Record as Extension', array('local' => 'id', 'foreign' => 'module_id'));
    }

    public function setTableDefinition()
    {    	global $CONFIG;
    	$this->setTableName($CONFIG['db']['table_prefix'].'core_modules');

        $this->hasColumn('project', 'string', 50, array('notnull' => true, 'unique' => true));
        $this->hasColumn('system', 'boolean');
        $this->hasColumn('version', 'string', 20);
        $this->hasColumn('xbk_version', 'string', 20);
        $this->hasColumn('dependencies', 'string', 1000);
        $this->hasColumn('class', 'string', 100);
        $this->hasColumn('config', 'string', null);
        $this->hasColumn('migration_required', 'integer', 3);
        $this->hasColumn('migration_current', 'integer', 3);
        $this->hasColumn('active', 'boolean', null, array('default' => false));
    }

}

?>