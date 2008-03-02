<?php

/**
 * xbkModelAnalyzer
 *
 * ���������� ������� ������
 *
 * @version    1.1   2008-02-08
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkModelAnalyzer extends xbkContextObject
{
    /**
     * CONTAINS_ALL
     * ������ ����������� ����� ������ ��
     */
    const CONTAINS_ALL = 1;

    /**
     * CONTAINS_PART
     * �������� ����������� ����� ������ ��
     */
    const CONTAINS_PART = 2;

    /**
     * CONTAINS_NONE
     * ����������� ������� �� �����������
     */
    const CONTAINS_NONE = 3;

    /**
     * NOT_ALLOWED
     * ������� �� ���������
     */
    const NOT_ALLOWED = 4;

    /**
     * ���������� ������� ����������� ������
     *
	 * @access	public
	 * @param	string
	 * @return  integer ��� false, ���� ������ ����� �������
    */
	public function checkTables ($moduleName)
	{

		{
    		// �������� ��������� ������
    		$models_path = xbkFunctions::getModulePath($moduleName).$CONFIG['path']['internal']['models'];
    		if (file_exists($models_path))
    		{
    			//$models = Doctrine::loadModels($models_path);
    			$models = xbkFunctions::getModelList($models_path);
    			$tables_exists = 0;
    			foreach ($models as $model)
    			{
                    try {
                        $record = $table->find(0);
                        $tables_exists++;
                    } catch (Exception $e) {
    			if (count($models) > 0)
    			{
        			if ($tables_exists == 0)
        			{
        			} else if ($tables_exists > 0 && $tables_exists < count ($models))
        			{
        			} else {
        			}
    			} else {
        			return self::NOT_ALLOWED;
        		}
    		} else {
		} else {
		}
	}
}

?>