<?php

class xbkCore_001_Migration extends Doctrine_Migration
{
    public function up()
    {
        global $CONFIG;

        $this->dropTable($CONFIG['db']['table_prefix'].'core_event_handlers');
        $this->dropTable($CONFIG['db']['table_prefix'].'core_events');

    }

    public function down()
    {
        global $CONFIG;


$table = $CONFIG['db']['table_prefix'].'core_events';
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
    'length' => 2147483647,
  ),
  'name' =>
  array (
    'notnull' => true,
    'unique' => true,
    'type' => 'string',
    'length' => 50,
  ),
  'class' =>
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

$table = $CONFIG['db']['table_prefix'].'core_event_handlers';
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
    'length' => 2147483647,
  ),
  'name' =>
  array (
    'notnull' => true,
    'type' => 'string',
    'length' => 50,
  ),
  'class' =>
  array (
    'notnull' => true,
    'type' => 'string',
    'length' => 100,
  ),
  'event' =>
  array (
    'notnull' => true,
    'type' => 'string',
    'length' => 50,
  ),
  'event_module' =>
  array (
    'notnull' => true,
    'type' => 'string',
    'length' => 50,
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


		$this->createForeignKey($CONFIG['db']['table_prefix'].'core_event_handlers', array (
  'local' => 'module_id',
  'foreign' => 'id',
  'foreignTable' => $CONFIG['db']['table_prefix'].'core_modules',
  'onUpdate' => NULL,
  'onDelete' => NULL,
  'name' => $CONFIG['db']['table_prefix'].'core_event_handlers_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id',
));		$this->createForeignKey($CONFIG['db']['table_prefix'].'core_events', array (
  'local' => 'module_id',
  'foreign' => 'id',
  'foreignTable' => $CONFIG['db']['table_prefix'].'core_modules',
  'onUpdate' => NULL,
  'onDelete' => NULL,
  'name' => $CONFIG['db']['table_prefix'].'core_events_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id',
));

}

}


?>