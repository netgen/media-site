<?php

// Re-enables PHAR support for CLI which is disabled in eZ Publish Legacy autoload.php
// Required to be able to run PHAR tools like PHPStan, Psalm and PHPUnit

if (PHP_SAPI === 'cli' && !in_array('phar', stream_get_wrappers(), true)) {
    stream_wrapper_restore('phar');
}
