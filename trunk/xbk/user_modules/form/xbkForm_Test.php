<?php

/**
 * xbkForm_Test
 *
 * Секция тестирования класса xbkForm
 *
 * @version    1.0   2008-02-04
 * @package    xBk
 * @author
 * @license    LGPL
 * @link
 */

class xbkForm_Test extends xbkSection
{
    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2()
    {
        $Form = $this->factory('xbkForm');

        $this->setContent('');
    }
}

?>