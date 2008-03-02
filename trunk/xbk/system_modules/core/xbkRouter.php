<?php

/**
 * xbkRouter
 *
 * Маршрутизатор по секциям
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkRouter extends xbkContextObject
{
    /**
     * Массив, содержащий имена всех надстоящих секций, заканчивая искомой
     *
     * @access    private
     * @var       array
     */
    private $sectionHierarchyList;
    /**
     * Конструктор класса
     *
     * @access	  public
     * @param	  object xbkUri или null
     */
    public function __construct2 ($Uri = null)
    {    	$this->prepare($Uri);
    }

    /**
     * Подготовка объекта класса
     *
     * @access	  public
     * @param	  object xbkUri или null
     */
    public function prepare ($Uri = null)
    {
        if ($Uri == null) $Uri =& $this->factory('xbkUri');
    	$this->sectionHierarchyList = $Uri->getPath(false);
    	// Если ядро установлено, ищем нужную секцию в базе данных
    	if ($this->_Registry->isCoreInstalled())
    	{
            $this->loadModuleSection();
    	}
    }

    /**
     * Возвращает объект секции модуля
     *
     * @access	  public
     * @param	  object xbkModuleSection
     * @param 	  int
     * @return	  object xbkModuleSection или null
     */
    public function getModuleSection (&$foundSection = null, $pos = null)
    {    	if (count($this->sectionHierarchyList) > 0 && $foundSection == null)
    	{    		// Ищем верхнюю секцию в наборе секций каждого модуля    		foreach ($this->_Registry->modules as $module)
        	{        		$sections =& $module->getSections();
        		for ($s=0; isset($sections[$s]); $s++)
        		{        			if ($this->sectionHierarchyList[0] == $sections[$s]->name)
        			{        				// Секция обнаружена
        				$found = true;        				return $this->getModuleSection($sections[$s], 0);
        				break;        			}        		}
        	}
            // Секция не обнаружена - возвращаем 404 секцию
            return $this->_Registry->getModule('core')->getSection('404');
        } else if (count($this->sectionHierarchyList) > 0) {
    		// Ищем следующую подсекцию
    		if (isset($this->sectionHierarchyList[$pos+1]))
    		{
        		if ($foundSection->hasSubsections())
        		{    				$subsection =& $foundSection->getSubsection($this->sectionHierarchyList[$pos+1]);                    if ($subsection != false) return $this->getModuleSection($subsection, ($pos+1));
                    else return $foundSection;        		} else return $foundSection;
    		} else return $foundSection;
    	} else {    		// Вызываем стартовую (корневую) секцию
    		return $this->getStartSection();    	}
    }

    /**
     * Ищет секцию в реестре БД и регистрирует модуль секции
     *
     * @access	  public
     * @param	  object xbkModuleSection
     * @param 	  int
     */
    public function loadModuleSection ($foundSection_Record = null, $pos = 0)
    {
        if (count($this->sectionHierarchyList) > 0) {
            $parent_id = null;
            if ($foundSection_Record != null)
            {            	$parent_id = $foundSection_Record->id;            }        	// Ищем следующую подсекцию
            if ($parent_id == null)
            {
                $result = $this->DB->query(
                    "FROM xbkSection_Record
                    WHERE xbkSection_Record.name = :name
                    AND xbkSection_Record.parent_id IS :parent_id",
                    array(':name' => $this->sectionHierarchyList[$pos], ':parent_id' => $parent_id)
                );
            } else {            	$result = $this->DB->query(
                    "FROM xbkSection_Record
                    WHERE xbkSection_Record.name = :name
                    AND xbkSection_Record.parent_id = :parent_id",
                    array(':name' => $this->sectionHierarchyList[$pos], ':parent_id' => $parent_id)
                );            }

            if (count($result) == 0)
            {            	// Возвращаем последнюю найденную секцию
            	if ($foundSection_Record != null)
            	{
                    $module_name = $foundSection_Record->Module->name;
                    $this->_Registry->loadModule($module_name, true);
                }            } else {            	if (isset($this->sectionHierarchyList[($pos+1)]))
            	{                	// Ищем дальше
                	$this->loadModuleSection($result[0], ($pos+1));
            	} else {            		// Возвращаем текущую найденную секцию
            		$module_name = $result[0]->Module->name;
                    $this->_Registry->loadModule($module_name, true);            	}            }

    	} else {
    		// Вызываем модуль стартовой секции
    	}
    }

    /**
     * Возвращает объект стартововой секции
     *
     * @access	  public
     * @return	  object xbkModuleSection
     */
    public function getStartSection ()
    {    	global $CONFIG;
    	$Uri = $this->factory('xbkUri');
    	$Uri->goto($CONFIG['index']['section']);
    	$this->prepare($Uri);
    	$this->_SERVER['REQUEST_URI'] = $Uri->build();
    	return $this->getModuleSection();    }
}

?>