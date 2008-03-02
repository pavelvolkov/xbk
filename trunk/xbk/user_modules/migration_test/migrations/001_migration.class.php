<?php

class MigrationTest_001_Migration extends Doctrine_Migration
{
    public function up()
    {
        $this->addColumn($CONFIG['db']['table_prefix'].'migration_test', 'test', 'string');
    }

    public function down()
    {
        $this->removeColumn($CONFIG['db']['table_prefix'].'migration_test', 'test');
    }
}

?>