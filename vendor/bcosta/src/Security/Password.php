<?php

namespace BCosta\Security;

class Password
{
    public static function sha1($plainPassword, $salt, $iteration = 1000)
    {
        $hash = $plainPassword;
        for ($i = 0; $i < $iteration; ++$i) {
            $hash = sha1($hash . $salt);
        }
        return $hash;
    }
}