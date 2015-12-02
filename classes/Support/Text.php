<?php

namespace Acraviz\Support;

class Text
{

    public static function is()
    {
        foreach (func_get_args() as $arg) {
            if (!is_string($arg) || trim($arg) == '') {
                return false;
            }
        }
        return true;
    }

}
