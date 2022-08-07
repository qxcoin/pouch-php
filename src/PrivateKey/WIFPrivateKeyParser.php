<?php

namespace QXCoin\Pouch\PrivateKey;

use InvalidArgumentException;
use Tuupola\Base58;

final class WIFPrivateKeyParser
{
    private Base58 $base58;

    public function __construct()
    {
        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
        ]);
    }

    /**
     * @return array{prefix: int, testnet: bool, private_key: string, compressed: bool, checksum: string}
     */
    public function parse(string $wifPrivateKey): array
    {
        $decoded = $this->base58->decode($wifPrivateKey);

        $hex = bin2hex($decoded);

        if (!in_array(strlen($hex), [74, 76])) {
            throw new InvalidArgumentException('Invalid WIF Private Key provided.');
        }

        $prefix = hexdec(substr($hex, 0, 2));
        $privateKey = substr($hex, 2, 64);
        $checksum = substr($hex, -8);

        if ($prefix === 0xEF) $testnet = true;
        else $testnet = false;

        if (strlen($hex) === 76) $compressed = true;
        else $compressed = false;

        $data = [];
        $data['prefix'] = $prefix;
        $data['testnet'] = $testnet;
        $data['private_key'] = $privateKey;
        $data['compressed'] = $compressed;
        $data['checksum'] = $checksum;
        return $data;
    }
}
