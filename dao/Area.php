<?php

namespace DAO;

require_once 'Database.php';

use DAO\Database;

class Area extends Database
{

    const TABLE = 'area';
    protected static $oInstance;
}
