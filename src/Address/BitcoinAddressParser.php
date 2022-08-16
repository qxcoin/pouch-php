<?php

namespace QXCoin\Pouch\Address;

use Exception;
use InvalidArgumentException;
use QXCoin\Pouch\Utils\Bitcoin;
use Tuupola\Base58;

final class BitcoinAddressParser
{
    private Base58 $base58;

    public function __construct()
    {
        $this->base58 = new Base58([
            'characters' => Base58::BITCOIN,
        ]);
    }

    /**
     * @return array{type: string, data: string, testnet: bool}
     */
    public function parse(string $address): array
    {
        if (str_starts_with($address, 'bc') or str_starts_with($address, 'tb')) {
            return $this->parseBetch32($address);
        } else {
            return $this->parseBase58($address);
        }
    }

    private function parseBetch32(string $address): array
    {
        $version = 0;
        $hrp = '';
        $decoded = segwit_addr_decode($address, $data, $version, $hrp, $enc);
        if (!$decoded) {
            throw new InvalidArgumentException('Invalid address, unable to decode.');
        }

        // Version 0 means it is either P2WPKH or P2WSH
        // because hash160 of public key is 20 bytes length
        // if data is also 20 bytes length it is P2WPKH otherwise P2WSH
        if (0 === $version) {
            $type = 20 === strlen($data) ? 'P2WPKH' : 'P2WSH';
        } elseif (1 === $version) {
            $type = 'P2TR';
        } else {
            throw new InvalidArgumentException('Invalid address, unsupported witness version.');
        }

        $result = [];
        $result['type'] = $type;
        $result['data'] = bin2hex($data);
        $result['testnet'] = ('tb' === $hrp);
        return $result;
    }

    private function parseBase58(string $address): array
    {
        $decoded = $this->base58->decode($address);

        if (25 !== strlen($decoded)) {
            throw new InvalidArgumentException('Invalid address, wrong length.');
        }

        $prefix = $decoded[0];
        $checksum = substr($decoded, -4);
        $data = substr($decoded, 1, -4);

        $expectedChecksum = substr(Bitcoin::hash256($prefix . $data, true), 0, 4);
        if ($expectedChecksum !== $checksum) {
            throw new InvalidArgumentException('Invalid address, wrong checksum.');
        }

        $result = [];
        $result['data'] = bin2hex($data);

        switch (hexdec(bin2hex($prefix))) {
            case 0x00:
                return [...$result, 'type' => 'P2PKH', 'testnet' => false];
            case 0x6F:
                return [...$result, 'type' => 'P2PKH', 'testnet' => true];
            case 0x05:
                return [...$result, 'type' => 'P2SH', 'testnet' => false];
            case 0xC4:
                return [...$result, 'type' => 'P2SH', 'testnet' => true];
            default:
                throw new InvalidArgumentException('Invalid address, unsupported prefix.');
        }
    }
}
