<?php

namespace App\DB;

interface DB
{
    public function read();

    public function write($data);

    public function printData();
}
