<?php

namespace QXCoin\Pouch\Utils;

class Bitcoin
{
    public static function hash160(string $data, bool $binary = false): string
    {
        return hash('ripemd160', hash('sha256', $data, true), $binary);
    }

    public static function hash256(string $data, bool $binary = false): string
    {
        return hash('sha256', hash('sha256', $data, true), $binary);
    }

    public static function strToLittleEndian(string $data): string
    {
        return join('', array_reverse(str_split($data, 2)));
    }
}
