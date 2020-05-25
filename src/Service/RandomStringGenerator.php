<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

class RandomStringGenerator
{
    public const DEFAULT_KEYSET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const LETTERS_SMALL = 'abcdefghijklmnopqrstuvwxyz';
    public const LETTERS_BIG = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const NUMBERS = '0123456789';

    /**
     * Actually pretty Stack-Overflow-Driven-Development function that generates random string
     *
     * @param int    $length
     * @param string $keyset
     * @return string
     * @throws Exception
     */
    public static function generate(int $length, string $keyset = self::DEFAULT_KEYSET): string
    {
        $pieces = [];
        $max = mb_strlen($keyset, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyset[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
