<?php

/**
 * xbkMigrationManager
 *
 * �������� �������� ������
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
     * ������ ������, � ������� ��������
     *
     * @access    public
     * @var       object   xbkModule
     */
    public $Module;

    /**
     * ������ Doctrine_Migration
     *
     * @access    public
     * @var       object   xbkModule
     */
    public $Migration;

    /**
     * ������� ������
     *
     * @access    public
     * @var       integer ��� null
     */
    public $currentVersion = null;

    /**
     * ��������� ������
     *
     * @access    public
     * @var       integer ��� null
     */
    public $requiredVersion = null;
    /**
     * ����������� ������
     *
     * @access      public
     * @param       object   xbkModule
     */
    public function __construct2 (&$Module)
    {    	global $CONFIG;    	$this->Module = $Module;

    	// ����� Doctrine_Migration
    	$migration_path = xbkFunctions::getModulePath($Module->getName()).$CONFIG['path']['internal']['migrations'];
    	$this->Migration = New xbkMigration($migration_path);

    	$this->currentVersion = $Module->getCurrentMigrationVersion();
    	$this->requiredVersion = $Module->getRequiredMigrationVersion();

    	// ������������� ������� ������
        $this->Migration->setCurrentVersion($this->currentVersion);
    }

    /**
     * ������������� ������� ������
     *
     * @return void
     */
    public function setCurrentVersion($version = 0)
    {
        $this->Migration->setCurrentVersion($version);
    }

    /**
     * �������� ������� �������� ������
     *
     * @access      public
     * @return      Array
     */
    public function getMigrationNumbers ()
    {
    	return $this->Migration->getMigrationNumbers();
    }

    /**
     * ��������
     * ���� ������ �� �������, ��������� �� ������� ������
     *
     * @access      public
     * @param       object   xbkModule
     * @return      boolean
     */
    public function migrate ($version = null, &$exceptionMessage = null)
    {    	if ($version === null) $version = $this->requiredVersion;
        try {
        	$this->Migration->migrate($version);

        	// ������ ������ � ���� ������
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