<?php

class xbkUser_Record extends Doctrine_Record
{

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'user');

        $this->hasColumn('login', 'string', 100);
        $this->hasColumn('pass', 'string', 100);
        $this->hasColumn('email', 'string', 100);
        $this->hasColumn('session_id', 'string', 100);
        $this->hasColumn('ip', 'string', 100);
        $this->hasColumn('time', 'timestamp');
        $this->hasColumn('registration_time', 'timestamp');
        $this->hasColumn('active', 'boolean', true);

    }

}

?>