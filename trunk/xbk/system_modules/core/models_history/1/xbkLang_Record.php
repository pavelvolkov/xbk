<?php

class xbkLang_Record extends Doctrine_Record
{

    public function setUp()
    {        $this->hasOne('xbkModule_Record as Module', array('local' => 'module_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'core_lang');

        $this->hasColumn('module_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('interface', 'string', 20);
        $this->hasColumn('name', 'string', 50, array('notnull' => true));
        $this->hasColumn('value', 'string', 1000000, array('notnull' => true));

    }

}

?>