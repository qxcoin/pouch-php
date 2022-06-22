<?php

namespace QXCoin\Pouch\Networks;

class NetworkManager
{
    /**
     * @var Array<string, NetworkInterface>
     */
    private $networks = [];

    public function __construct()
    {
        $this->register('bitcoin', new BitcoinNetwork());
        $this->register('bitcoin-testnet', new BitcoinNetwork(true));

        $this->register('bitcoin-segwit', new BitcoinSegWitNetwork());
        $this->register('bitcoin-segwit-testnet', new BitcoinSegWitNetwork(true));

        $this->register('tron', new TronNetwork());
    }

    public function register(string $name, NetworkInterface $network): void
    {
        $this->networks[$name] = $network;
    }

    public function get(string $name): ?NetworkInterface
    {
        return $this->networks[$name] ?? null;
    }
}
