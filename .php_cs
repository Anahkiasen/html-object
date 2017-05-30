<?php
require 'vendor/autoload.php';

return Madewithlove\PhpCsFixer\Config::fromFolders(['src', 'examples', 'tests'])->mergeRules([
    'array_syntax' => ['syntax' => 'long'],
]);
