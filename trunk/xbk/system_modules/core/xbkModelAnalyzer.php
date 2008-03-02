<?php

/**
 * xbkModelAnalyzer
 *
 * Анализатор моделей данных
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
     * Полный необходимый набор таблиц БД
     */
    const CONTAINS_ALL = 1;

    /**
     * CONTAINS_PART
     * Неполный необходимый набор таблиц БД
     */
    const CONTAINS_PART = 2;

    /**
     * CONTAINS_NONE
     * Необходимые таблицы БД отсутствуют
     */
    const CONTAINS_NONE = 3;

    /**
     * NOT_ALLOWED
     * Таблицы не требуются
     */
    const NOT_ALLOWED = 4;

    /**
     * Определяет наличие необходимых таблиц
     *
	 * @access	public
	 * @param	string
	 * @return  integer или false, если модуль задан неверно
    */
	public function checkTables ($moduleName)
	{    	global $CONFIG;
		if (xbkFunctions::moduleExists($moduleName))
		{
    		// Загрузка структуры данных
    		$models_path = xbkFunctions::getModulePath($moduleName).$CONFIG['path']['internal']['models'];
    		if (file_exists($models_path))
    		{
    			//$models = Doctrine::loadModels($models_path);
    			$models = xbkFunctions::getModelList($models_path);
    			$tables_exists = 0;
    			foreach ($models as $model)
    			{                    $table = $this->DB->getTable($model);
                    try {
                        $record = $table->find(0);
                        $tables_exists++;
                    } catch (Exception $e) {                    	// Отсутствует необходимая таблица                    }    			}
    			if (count($models) > 0)
    			{
        			if ($tables_exists == 0)
        			{        				return self::CONTAINS_NONE;
        			} else if ($tables_exists > 0 && $tables_exists < count ($models))
        			{        				return self::CONTAINS_PART;
        			} else {        				return self::CONTAINS_ALL;
        			}
    			} else {
        			return self::NOT_ALLOWED;
        		}
    		} else {    			return self::NOT_ALLOWED;    		}
		} else {			return false;
		}
	}
}

?>