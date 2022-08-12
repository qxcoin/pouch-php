<?php

use QXCoin\Pouch\Address\BitcoinAddressParser;

require('../vendor/autoload.php');

$parser = new BitcoinAddressParser();

var_dump($parser->parse('13Q64rgq4DbdFujfyi5dfNCCe7Juy8GZEH'));
var_dump($parser->parse('bc1qhg7j3xz78ya98mqr3u5ul2h70jaj8ffccqhl7v'));
