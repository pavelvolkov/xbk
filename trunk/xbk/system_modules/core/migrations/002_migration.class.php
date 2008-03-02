<?php

class xbkCore_002_Migration extends Doctrine_Migration
{

    public function up()
    {
        global $CONFIG;

        // Переименование колонок
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'name', 'project');
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'is_system', 'system');
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'info_class', 'class');
        // Добавление колонки
        $this->addColumn($CONFIG['db']['table_prefix'].'core_modules', 'dependencies', 'string', Array('length' => 1000));

        $table = $CONFIG['db']['table_prefix'].'core_extension_points';
        $this->createTable($table, array (
          'id' =>
          array (
            'type' => 'integer',
            'length' => 20,
            'autoincrement' => true,
            'primary' => true,
          ),
          'module_id' =>
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
          'interface' =>
          array (
            'notnull' => true,
            'unique' => true,
            'type' => 'string',
            'length' => 200,
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

        $table = $CONFIG['db']['table_prefix'].'core_extensions';
        $this->createTable($table, array (
          'id' =>
          array (
            'type' => 'integer',
            'length' => 20,
            'autoincrement' => true,
            'primary' => true,
          ),
          'module_id' =>
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
          'class' =>
          array (
            'notnull' => true,
            'unique' => true,
            'type' => 'string',
            'length' => 200,
          ),
          'extension_point' =>
          array (
            'notnull' => true,
            'type' => 'string',
            'length' => 200,
          ),
          'extension_point_module' =>
          array (
            'notnull' => true,
            'type' => 'string',
            'length' => 200,
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

        $this->createForeignKey($CONFIG['db']['table_prefix'].'core_extension_points', array (
          'local' => 'module_id',
          'foreign' => 'id',
          'foreignTable' => $CONFIG['db']['table_prefix'].'core_modules',
          'onUpdate' => NULL,
          'onDelete' => 'CASCADE',
          'name' => $CONFIG['db']['table_prefix'].'core_extension_points_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id',
        ));

        $this->createForeignKey($CONFIG['db']['table_prefix'].'core_extensions', array (
          'local' => 'module_id',
          'foreign' => 'id',
          'foreignTable' => $CONFIG['db']['table_prefix'].'core_modules',
          'onUpdate' => NULL,
          'onDelete' => 'CASCADE',
          'name' => $CONFIG['db']['table_prefix'].'core_extensions_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id',
        ));

}

    public function down()
    {
        global $CONFIG;

    	$this->dropForeignKey(
        	$CONFIG['db']['table_prefix'].'core_extension_points',
        	$CONFIG['db']['table_prefix'].'core_extension_points_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id'
    	);

    	$this->dropForeignKey(
        	$CONFIG['db']['table_prefix'].'core_extensions',
        	$CONFIG['db']['table_prefix'].'core_extensions_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id'
    	);

        $this->dropTable($CONFIG['db']['table_prefix'].'core_extensions');
        $this->dropTable($CONFIG['db']['table_prefix'].'core_extension_points');

        // Переименование колонок обратно
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'project', 'name');
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'system', 'is_system');
        $this->renameColumn($CONFIG['db']['table_prefix'].'core_modules', 'class', 'info_class');
        // Удаление
        $this->removeColumn($CONFIG['db']['table_prefix'].'core_modules', 'dependencies');

    }

}


?>