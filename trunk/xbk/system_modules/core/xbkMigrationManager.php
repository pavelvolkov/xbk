<?php

/**
 * xbkMigrationManager
 *
 * Мэнеджер миграции данных
 *
 * @version    1.0   2008-02-10
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkMigrationManager extends xbkContextObject
{
    /**
     * Объект модуля, с которым работаем
     *
     * @access    public
     * @var       object   xbkModule
     */
    public $Module;

    /**
     * Объект Doctrine_Migration
     *
     * @access    public
     * @var       object   xbkModule
     */
    public $Migration;

    /**
     * Текущая версия
     *
     * @access    public
     * @var       integer или null
     */
    public $currentVersion = null;

    /**
     * Требуемая версия
     *
     * @access    public
     * @var       integer или null
     */
    public $requiredVersion = null;
    /**
     * Конструктор класса
     *
     * @access      public
     * @param       object   xbkModule
     */
    public function __construct2 (&$Module)
    {    	global $CONFIG;    	$this->Module = $Module;

    	// Класс Doctrine_Migration
    	$migration_path = xbkFunctions::getModulePath($Module->getName()).$CONFIG['path']['internal']['migrations'];
    	$this->Migration = New xbkMigration($migration_path);

    	$this->currentVersion = $Module->getCurrentMigrationVersion();
    	$this->requiredVersion = $Module->getRequiredMigrationVersion();

    	// Устанавливаем текущую версию
        $this->Migration->setCurrentVersion($this->currentVersion);
    }

    /**
     * Устанавливает текущую версию
     *
     * @return void
     */
    public function setCurrentVersion($version = 0)
    {
        $this->Migration->setCurrentVersion($version);
    }

    /**
     * Перечень номеров миграций модуля
     *
     * @access      public
     * @return      Array
     */
    public function getMigrationNumbers ()
    {
    	return $this->Migration->getMigrationNumbers();
    }

    /**
     * Миграция
     * Если версия не указана, мигрирует до текущей версии
     *
     * @access      public
     * @param       object   xbkModule
     * @return      boolean
     */
    public function migrate ($version = null, &$exceptionMessage = null)
    {    	if ($version === null) $version = $this->requiredVersion;
        try {
        	$this->Migration->migrate($version);

        	// Запись версии в базу данных
            $q = new Doctrine_Query();
        	$rows = $q->select('m.migration_current')
              ->from('xbkModule_Record m')
              ->where('m.name = ?', $this->Module->getName())
              ->execute();
            if (count($rows) > 0)
            {            	$rows[0]->migration_current = $version;
            	$rows[0]->save();
            }
        	return true;
    	} catch (Exception $e) {    		$exceptionMessage = $e->getMessage();    		return false;    	}
    }
}

?>