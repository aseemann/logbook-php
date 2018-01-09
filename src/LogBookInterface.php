<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 09.01.18
 * Time: 23:28
 */

namespace AxelKummer\LogBook;


interface LogBookInterface
{
    public function getLogger($loggerName);
}