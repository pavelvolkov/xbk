<?php

/**
 * xbkMigrationManager
 *
 * ����������� ������ Doctrine_Migration
 *
 * @version    1.0   2008-02-04
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkMigration extends Doctrine_Migration
{
    /**
     * ������� ������
     *
     * @access    public
     * @var       integer ��� null
     */
    public $currentVersion = null;
    /**
     * construct
     *
     * Specify the path to the directory with the migration classes.
     * The classes will be loaded and the migration table will be created if it does not already exist
     *
     * @param string $directory
     * @return void
     */
    public function __construct($directory = null)
    {
        if ($directory != null) {
            $this->_migrationClassesDirectory = $directory;

            $this->loadMigrationClasses();
        }
    }

    /**
     * createMigrationTable
     *
     * Creates the migration table used to store the current version
     *
     * @return void
     */
    protected function createMigrationTable()
    {
    }

    /**
     * ������������� ������� ������
     *
     * @return void
     */
    public function setCurrentVersion($version = 0)
    {
        $this->currentVersion = $version;
    }

    /**
     * ���������� ������� ������
     *
     * @return void
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * ���������� ������ ������� ��������
     *
     * @return void
     */
    public function getMigrationNumbers()
    {
        return array_keys($this->_migrationClasses);
    }
}

?>