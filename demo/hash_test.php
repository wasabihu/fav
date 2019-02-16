<?php

$checksum = crc32("The quick brown fox jumped over the lazy dog.");


echo "url: "." $checksum <br>";


printf("%u", $checksum);

$msg = sprintf("%u", $checksum);

echo "<br>url: "." $msg <br>";



echo hash('ripemd160', 'The quick brown fox jumped over the lazy dog.');

echo "<br><br>";

$ctx = hash_init('md5');
hash_update($ctx, 'The quick brown fox ');
hash_update($ctx, 'jumped over the lazy dog.');
echo hash_final($ctx);
