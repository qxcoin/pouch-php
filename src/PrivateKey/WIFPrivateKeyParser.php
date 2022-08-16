<?php

namespace QXCoin\Pouch\PrivateKey;

use InvalidArgumentException;
use QXCoin\Pouch\Utils\Bitcoin;
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
    public function parse(string $wif): array
    {
        $decoded = $this->base58->decode($wif);

        if (!in_array(strlen($decoded), [37, 38])) {
            throw new InvalidArgumentException('Invalid WIF Private Key provided.');
        }

        $prefix = $decoded[0];
        $privateKey = substr($decoded, 1, 32);
        $checksum = substr($decoded, -4);

        if (strlen($decoded) === 38) $compressionByte = substr($decoded, 33, 1);
        else $compressionByte = null;

        $expectedChecksum = substr(Bitcoin::hash256($prefix . $privateKey . $compressionByte, true), 0, 4);
        if ($expectedChecksum !== $checksum) {
            throw new InvalidArgumentException('Invalid WIF, wrong checksum.');
        }

        $result = [];
        $result['prefix'] = hexdec(bin2hex($prefix));
        $result['testnet'] = (hexdec(bin2hex($prefix)) === 0xEF);
        $result['private_key'] = bin2hex($privateKey);
        $result['compressed'] = ($compressionByte === 0x01);
        $result['checksum'] = bin2hex($checksum);
        return $result;
    }
}
