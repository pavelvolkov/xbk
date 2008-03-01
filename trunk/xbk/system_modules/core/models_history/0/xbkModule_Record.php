<?php

class xbkModule_Record extends Doctrine_Record
{
    public function setUp()
    {
        $this->hasMany('xbkSection_Record as Section', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkTemplateModule_Record as TemplateModule', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkEvent_Record as Event', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkEventHandler_Record as EventHandler', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkLang_Record as Lang', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkPrivilege_Record as Privilege', array('local' => 'id', 'foreign' => 'module_id'));
    }

    public function setTableDefinition()
    {    	global $CONFIG;
    	$this->setTableName($CONFIG['db']['table_prefix'].'core_modules');

        $this->hasColumn('name', 'string', 50, array('notnull' => true, 'unique' => true));
        $this->hasColumn('is_system', 'boolean');
        $this->hasColumn('version', 'string', 20);
        $this->hasColumn('xbk_version', 'string', 20);
        $this->hasColumn('info_class', 'string', 100);
        $this->hasColumn('config', 'string', null);
        $this->hasColumn('migration_required', 'integer', 3);
        $this->hasColumn('migration_current', 'integer', 3);
        $this->hasColumn('active', 'boolean', null, array('default' => false));
    }

}

?>