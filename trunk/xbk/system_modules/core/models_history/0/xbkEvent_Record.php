<?php

class xbkEvent_Record extends Doctrine_Record
{

    public function setUp()
    {
        $this->hasOne('xbkModule_Record as Module', array('local' => 'module_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'core_events');

        $this->hasColumn('module_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('name', 'string', 50, array('notnull' => true));
        $this->hasColumn('class', 'string', 100, array('notnull' => true));
    }

}

?>