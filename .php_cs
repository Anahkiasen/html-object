<?php
require 'vendor/autoload.php';

return Madewithlove\PhpCsFixer\Config::fromFolders(['src', 'examples', 'tests'])->mergeRules([
    'array_syntax' => ['syntax' => 'long'],
    'pow_to_exponentiation' => false,
    'ternary_to_null_coalescing' => false,
]);
