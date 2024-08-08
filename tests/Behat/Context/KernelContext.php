<?php

declare(strict_types=1);

namespace Fulll\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Hook\BeforeFeature;

class KernelContext implements Context
{
    #[BeforeFeature]
    public static function bootstrap(): void
    {
        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test';
        $_SERVER['KERNEL_CLASS'] = 'Fulll\Kernel';
        include_once __DIR__.'/../../bootstrap.php';
    }
}
