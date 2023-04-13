<?php

test('command "create" is correctly loaded', function () {
    $app = getMinicli();
    $app->runCommand(['minicli', 'create']);
})->expectOutputRegex("/librarian create/");

test('command "create content" throws error if stencil_dir is not defined', function () {
    $app = getMinicli();
    $app->runCommand(['minicli', 'create', 'content']);
})->expectOutputRegex("/You must define a stencil_dir config option/");
