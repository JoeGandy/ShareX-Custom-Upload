<?php
declare(strict_types=1);

require_once 'src/functions.php';

$GLOBALS['config'] = include 'src/config.default.php';


use PHPUnit\Framework\TestCase;

final class ShareXVersionTest extends TestCase
{
    private $config;

    public function __constructor(): void {
      $this->config = $GLOBALS['config'];
    }

    /** @test */
    public function canGetVersion(): void
    {
        $version = get_latest_sharex_version();

        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\.[0-9]+$/i', $version);

    }
}
