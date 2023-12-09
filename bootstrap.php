<?php 

// bootstrap.php

xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_INCLUDE,
    [
        __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR, // Include your source directory
        __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR, // Include your test directory
        // Add other directories or files you want to include for code coverage
    ]
);
