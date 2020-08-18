<?php
declare(strict_types=1);

require_once 'src/functions.php';

$GLOBALS['config'] = include 'src/config.php';

$GLOBALS['config']['default_naming_scheme'] = 'random';


use PHPUnit\Framework\TestCase;

final class FileNameTest extends TestCase
{
    private $config;

    public function __constructor(): void {
      $this->config = $GLOBALS['config'];
    }

    /** @test */
    public function fileNameHasRightExtensionRandomName(): void
    {
        $original_file_name = "TestPngFile.png";

        $correct = $original_file_name;
        $test = get_file_target($original_file_name, '');

        $this->assertEquals(
            pathinfo($correct, PATHINFO_EXTENSION),
            pathinfo($test, PATHINFO_EXTENSION)
        );
    }

    /** @test */
    public function fileNameHasRightExtensionNoRandomName(): void
    {
        $original_file_name = "TestPngFile.png";
        $post_name = "07.53.17-08.11.19";

        $correct = $original_file_name;
        $test = get_file_target($original_file_name, $post_name);

        $this->assertEquals(
            pathinfo($correct, PATHINFO_EXTENSION),
            pathinfo($test, PATHINFO_EXTENSION)
        );
    }

    /** @test */
    public function fileNameHasRightNameNoRandomName(): void
    {
        $original_file_name = "TestPngFile.png";
        $post_name = "testname";

        $correct = $post_name.'.png';
        $test = get_file_target($original_file_name, $post_name);

        $this->assertEquals(
            $correct,
            basename($test)
        );
    }

    /** @test */
    public function fileNameRandomTestLength(): void
    {
        $correct_length = 15;
        
        $testLength = strlen(generate_random_name('png', $correct_length - 4));

        $this->assertEquals(
            $testLength,
            $correct_length
        );
    }

    /** @test */
    public function joinPathsNoUrl(): void
    {
        $segment1 = '/real/paths/go/here/';
        $segment2 = 'little/slash///mishap';
        $segment3 = '/free/me/from/the/robot/overlords.png';

        $expected = '/real/paths/go/here/little/slash/mishap/free/me/from/the/robot/overlords.png';
        
        $result = join_paths($segment1, $segment2, $segment3);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    /** @test */
    public function joinPathsUrl(): void
    {
        $segment1 = 'https://robotfactory.com/';
        $segment2 = '/test/professional';
        $segment3 = 'woweetxt';

        $expected = 'https://robotfactory.com/test/professional/woweetxt';
        
        $result = join_paths($segment1, $segment2, $segment3);

        $this->assertEquals(
            $expected,
            $result
        );
    }
}
