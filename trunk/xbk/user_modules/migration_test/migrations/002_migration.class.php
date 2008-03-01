<?php

class MigrationTest_002_Migration extends Doctrine_Migration
{
    public function up()
    {
        global $CONFIG;
        $this->addColumn($CONFIG['db']['table_prefix'].'migration_test', 'test2', 'string');
    }

    public function down()
    {
        global $CONFIG;
        $this->removeColumn($CONFIG['db']['table_prefix'].'migration_test', 'test2');
    }
}

?>