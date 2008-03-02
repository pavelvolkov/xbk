<?php

class xbkSection_Record extends Doctrine_Record
{
    public function setUp()
    {    	$this->hasOne('xbkModule_Record as Module', array('local' => 'module_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasOne('xbkSection_Record as Parent', array('local' => 'parent_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasMany('xbkSection_Record as Subsection', array('local' => 'id', 'foreign' => 'parent_id'));
    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'core_sections');

        $this->hasColumn('module_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('parent_id', 'integer');
        $this->hasColumn('name', 'string', 50, array('notnull' => true));
        $this->hasColumn('type', 'string', 20, array('values' => Array('content', 'page', 'document', 'image', 'file', 'clean'), 'default' => 'content', 'notnull' => true));
        $this->hasColumn('enforce_ssl', 'boolean');
        $this->hasColumn('enforce_non_ssl', 'boolean');
        $this->hasColumn('class', 'string', 100, array('notnull' => true));
    }

}

?>