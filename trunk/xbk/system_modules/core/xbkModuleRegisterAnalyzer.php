<?php

/**
 * xbkModuleRegisterAnalyzer
 *
 * ���������� ��������������� ���������� ������, ������� � ������������
 *
 * @version    1.0   2008-01-28
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkModuleRegisterAnalyzer extends xbkContextObject
{
    /**
     * CONTAINS_ALL
     * ������ ����� ����������� ����������
     */
    const CONTAINS_ALL = 1;

    /**
     * CONTAINS_PART
     * �������� ����� ����������� ����������
     */
    const CONTAINS_PART = 2;

    /**
     * CONTAINS_NONE
     * ���������� �����������
     */
    const CONTAINS_NONE = 3;

    /**
     * CORE_NOT_INSTALLED
     * ���� �� �����������, ������� �����������
     */
    const CORE_NOT_INSTALLED = 4;

    /**
     * ����������� ������
     *
     * @access	  public
     */
    public function __construct2 ($moduleName = null)
    {    	if ($moduleName != null) $this->moduleName = $moduleName;
    }

    /**
     * ���������� ������� ��������������� ���������� ������
     *
	 * @access	public
	 * @param	string
	 * @return  integer ��� false, ���� ������ ����� �������
    */
	public function check ($moduleName = null)
	{		if ($moduleName == null) $moduleName = $this->moduleName;
        //$ModelAnalyzer = $this->factory('xbkModelAnalyzer');
        //$result = $ModelAnalyzer->checkTables('core');
        $result = xbkModelAnalyzer::checkTables('core');

        if ($result == xbkModelAnalyzer::CONTAINS_ALL)
        {        	$query = new Doctrine_Query();
            $query->select('m.*')
                  ->from('xbkModule_Record m')
                  ->where('m.name = ?', $moduleName);
            $modules = $query->execute();
            if (count($modules) > 0) return self::CONTAINS_ALL;
            else return self::CONTAINS_NONE;        } else {        	return self::CORE_NOT_INSTALLED;
        }
	}
}

?>