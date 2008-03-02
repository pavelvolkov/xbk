<?php

class xbkPrivilege_Record extends Doctrine_Record
{

    public function setUp()
    {
        $this->hasOne('xbkModule_Record as Module', array('local' => 'module_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'core_privileges');

        $this->hasColumn('module_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('name', 'string', 100, array('notnull' => true, 'unique' => true));
        $this->hasColumn('class', 'string', 100, array('notnull' => true, 'unique' => true));

    }

}

?>