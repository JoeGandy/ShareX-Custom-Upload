<?php
declare(strict_types=1);

require_once 'src/u/functions.php';

$GLOBALS['config'] = include 'src/u/config.php';


use PHPUnit\Framework\TestCase;

final class FileNameTest extends TestCase
{
    private $config;

    public function __constructor(): void {
      $this->config = $GLOBALS['config'];
    }

    /** @test */
    public function fileNameHasRightExtensionRandomNameRandom(): void
    {
        $orginal_file_name = "TestPngFile.png";
        $post_name = "07.53.17-08.11.19";

        $correct = $orginal_file_name;
        $test = get_file_target(['enable_random_name' => true], $orginal_file_name, $post_name);

        $this->assertEquals(
            pathinfo($correct)['extension'],
            pathinfo($test)['extension']
        );
    }

    /** @test */
    public function fileNameHasRightExtensionShareXNameNoRandom(): void
    {
        $orginal_file_name = "TestPngFile.png";
        $post_name = "07.53.17-08.11.19";

        $correct = $orginal_file_name;
        $test = get_file_target(['enable_random_name' => false], $orginal_file_name, $post_name);

        $this->assertEquals(
            pathinfo($correct)['extension'],
            pathinfo($test)['extension']
        );
    }

    /** @test */
    public function fileNameRandomTestLength(): void
    {
        $correct_length = 15;
        
        $testLength = strlen(generateRandomName('png', $correct_length - 4));

        $this->assertEquals(
            $testLength,
            $correct_length
        );
    }
}





