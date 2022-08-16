<?php

use QXCoin\Pouch\PrivateKey\WIFPrivateKeyParser;

require('../vendor/autoload.php');

$parser = new WIFPrivateKeyParser();

var_dump($parser->parse('L5EZftvrYaSudiozVRzTqLcHLNDoVn7H5HSfM9BAN6tMJX8oTWz6'));
