<?php

class MigrationTest_003_Migration extends Doctrine_Migration
{
    public function up()
    {
        global $CONFIG;
        $this->addColumn($CONFIG['db']['table_prefix'].'migration_test', 'test3', 'string');
    }

    public function down()
    {
        global $CONFIG;
        $this->removeColumn($CONFIG['db']['table_prefix'].'migration_test', 'test3');
    }
}

?>