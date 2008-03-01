<?php

class xbkMigrationTest_Record extends Doctrine_Record
{

    public function setUp()
    {

    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'migration_test');

        $this->hasColumn('order_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('test', 'string', 200, array('notnull' => true));
    }

}

?>