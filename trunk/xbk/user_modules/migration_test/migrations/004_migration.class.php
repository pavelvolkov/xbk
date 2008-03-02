<?php

class MigrationTest_004_Migration extends Doctrine_Migration
{
    public function up()
    {
        global $CONFIG;

        $table = $CONFIG['db']['table_prefix'].'migration_test2';
        $this->createTable($table, array (
          'id' =>
          array (
            'type' => 'integer',
            'length' => 20,
            'autoincrement' => true,
            'primary' => true,
          ),
          'cat_id' =>
          array (
            'notnull' => true,
            'type' => 'integer',
            'length' => 20,
          ),
          'name' =>
          array (
            'notnull' => true,
            'type' => 'string',
            'length' => 100,
          ),
        ), array (
          'indexes' =>
          array (
          ),
          'primary' =>
          array (
            0 => 'id',
          ),
        ));

        $this->createForeignKey($CONFIG['db']['table_prefix'].'migration_test2', array (
          'local' => 'cat_id',
          'foreign' => 'id',
          'foreignTable' => $CONFIG['db']['table_prefix'].'migration_test',
          'onUpdate' => NULL,
          'onDelete' => 'CASCADE',
          'name' => 'cat_id_idx',
        ));


    }

    public function down()
    {
        global $CONFIG;

        $this->dropForeignKey($CONFIG['db']['table_prefix'].'migration_test2', 'cat_id_idx');

        $this->dropTable($CONFIG['db']['table_prefix'].'migration_test2');
    }
}

?>