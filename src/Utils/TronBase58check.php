<?php

namespace QXCoin\Pouch\Utils;

use Tuupola\Base58;

class TronBase58check extends Base58
{
    const VERSION = 0x41;

    public function __construct()
    {
        parent::__construct([
            'characters' => Base58::BITCOIN,
            'check' => true,
            'version' => self::VERSION,
        ]);
    }

    public function encode(string $data): string
    {
        if (str_starts_with($data, chr(self::VERSION)) and 21 === strlen($data)) $data = substr($data, 1);

        return parent::encode($data);
    }

    public function decode(string $data): string
    {
        $decoded = parent::decode($data);

        return chr(self::VERSION) . $decoded;
    }
}
