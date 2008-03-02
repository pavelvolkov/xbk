<?php

/**
 * xbkException
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-09
 */

class xbkException extends Exception
{

    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

?>