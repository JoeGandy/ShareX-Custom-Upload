<?php
declare(strict_types=1);

require_once 'src/functions.php';

$GLOBALS['config'] = include 'src/config.default.php';


use PHPUnit\Framework\TestCase;

final class StorageTest extends TestCase
{
    private $config;

    public function __constructor(): void {
      $this->config = $GLOBALS['config'];
    }

    /** @test */
    public function checkBytesStringB(): void
    {
        $bytes = 30;

        $correct_bytes = '30.00 B';
        $test_bytes = bytes_to_string($bytes);

        $this->assertEquals(
          $test_bytes,
          $correct_bytes
      );
    }

    /** @test */
    public function checkBytesStringKB(): void
    {
        $bytes = 3000; 

        $correct_bytes = '2.93 KB';
        $test_bytes = bytes_to_string($bytes);

        $this->assertEquals(
          $test_bytes,
          $correct_bytes
      );
    }

    /** @test */
    public function checkBytesStringMB(): void
    {
        $bytes = 3000000; 

        $correct_bytes = '2.86 MB';
        $test_bytes = bytes_to_string($bytes);

        $this->assertEquals(
          $test_bytes,
          $correct_bytes
      );
    }

    /** @test */
    public function checkBytesStringGB(): void
    {
        $bytes = 3000000000; 

        $correct_bytes = '2.79 GB';
        $test_bytes = bytes_to_string($bytes);

        $this->assertEquals(
          $test_bytes,
          $correct_bytes
      );
    }

    /** @test */
    public function checkBytesStringTB(): void
    {
        $bytes = 3000000000000; 

        $correct_bytes = '2.73 TB';
        $test_bytes = bytes_to_string($bytes);

        $this->assertEquals(
          $test_bytes,
          $correct_bytes
      );
    }

    /** @test */
    public function checkTotalFreeSpace(): void
    {
        $test_string = bytes_to_string(disk_free_space('/'));

        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\s(B|KB|MB|GB|TB|PB|EB|ZB|YB)/i', $test_string);
    }

    /** @test */
    public function checkTotalSpace(): void
    {
        $test_string = bytes_to_string(disk_total_space('/'));

        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\s(B|KB|MB|GB|TB|PB|EB|ZB|YB)/i', $test_string);
    }
}
