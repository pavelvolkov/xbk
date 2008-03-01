<?php

/**
 * xbkModuleInfo
 *
 * Абстрактный класс дополнительной информации модуля,
 * требующей динамического формирования
 *
 * @version    1.0   2008-01-27
 * @package    xBk
 * @author     Pavel Bakanov
 * @license	   LGPL
 * @link	   http://bakanov.info
 */

abstract class xbkModuleInfo extends xbkContextObject
{
    /**
     * Заголовок
     *
     * @access	  public
     * @return	  string или null
     */
    public function getTitle ()
    {    	return null;    }

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
     * Автор
     *
     * @access	  public
     * @return	  string или null
     */
    public function getAuthor ()
    {
    	return null;
    }

    /**
     * Лицензия
     *
     * @access	  public
     * @return	  string или null
     */
    public function getLicense ()
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