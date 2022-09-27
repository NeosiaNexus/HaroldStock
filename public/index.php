<?php

declare(strict_types=1);

use Service\Exception\SessionException;
use Service\Session;

try {
    Session::start();
} catch (SessionException $e) {
    echo $e->getMessage();
}

