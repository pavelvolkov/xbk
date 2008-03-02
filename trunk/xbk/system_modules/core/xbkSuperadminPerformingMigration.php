<?php

/**
 * xbkSuperadminModules
 *
 * Панель суперадмина - модули системы
 *
 * @version    1.0   2008-02-24
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminPerformingMigration extends xbkSection
{
    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2 ()
    {    	global $CONFIG;
        if (isset($this->_GET['module'], $this->_GET['from'], $this->_GET['to']))
        {        	/*        	$Module =& $this->factory('xbkModule', $this->_GET['module']);

            // Менеджер миграций
    		$MigrationManager = $this->factory('xbkMigrationManager', $Module);

    		// Текущая версия
    		$currentVersion = $Module->getCurrentMigrationVersion();

    		// Требуемая версия
            $requiredVersion = $Module->getRequiredMigrationVersion();

            // Доступные номера версий структуры данных
    		$numbers = $MigrationManager->getMigrationNumbers();

    		if (!in_array(0, $numbers)) {
    			// Добавляем нулевую миграцию
    			$numbers = array_merge(Array(0), $numbers);
    		}

    		$exceptionMessage = '';

    		// echo $this->_GET['from'].' '.$this->_GET['to'];

    		$MigrationManager->setCurrentVersion($this->_GET['from']);
        	$result = $MigrationManager->migrate($this->_GET['to'], $exceptionMessage);

        	$this->setContent($exceptionMessage);
        	*/

        	$this->DB->export->dropForeignKey(
            	$CONFIG['db']['table_prefix'].'core_extension_points',
            	$CONFIG['db']['table_prefix'].'core_extension_points_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id'
        	);

        	$this->DB->export->dropForeignKey(
            	$CONFIG['db']['table_prefix'].'core_extensions',
            	$CONFIG['db']['table_prefix'].'core_extensions_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id'
        	);

            // Класс Doctrine_Migration
        	//$migration_path = xbkFunctions::getModulePath($this->_GET['module']).$CONFIG['path']['internal']['migrations'];
        	//$Migration = New xbkMigration($migration_path);

        	/*
        	$this->DB->export->createForeignKey($CONFIG['db']['table_prefix'].'core_extension_points', array (
  'local' => 'module_id',
  'foreign' => 'id',
  'foreignTable' => $CONFIG['db']['table_prefix'].'core_modules',
  'onUpdate' => NULL,
  'onDelete' => 'CASCADE',
  'name' => $CONFIG['db']['table_prefix'].'core_extension_points_'.$CONFIG['db']['table_prefix'].'core_modules_module_id_id',
));
*/

        	$Migration->setCurrentVersion($this->_GET['from']);
        	$Migration->migrate($this->_GET['to']);
        }
    }
}

?>