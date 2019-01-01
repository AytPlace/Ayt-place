<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/12/18
 * Time: 16:07
 */

namespace App\Service;


class RandomStringGenerator
{
    public function generate(int $length = 10) : string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';

        for ($i = 0; $i < $length; ++$i) {
            $string .= $chars[mt_rand(0, 61)];
        }

        return $string;
    }
}