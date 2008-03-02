<?php

/**
 * xbkPrivilege
 *
 * Абстрактный класс привилегии
 *
 * @version    1.0   2008-01-27
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

abstract class xbkModuleInfo extends xbkContextObject
{
    /**
     * Название
     *
     * @access	  public
     * @return	  string или null
     */
    public function getTitle ()
    {
    	return null;
    }

    /**
     * Описание
     *
     * @access	  public
     * @return	  string или null
     */
    public function getDescription ()
    {
    	return null;
    }

    /**
     * Автоматическое меню в виде массива
     * Элементы массива - массивы со следующими ключами:
     * - text string текст раздела меню
     * - link string ссылка
     * - sub  array массив подразделов (опционально)
     *
     * @access	  public
     * @return	  string или null
     */
    public function getMenu ()
    {
    	return null;
    }
}

?>