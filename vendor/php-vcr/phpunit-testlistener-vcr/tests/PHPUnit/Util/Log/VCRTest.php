<?php


/**
 * Testing class for PHPUnit_Util_Log_Mongo
 *
 * @package    PHPUnit
 * @subpackage Tests
 * @author     Ben Selby <benmatselby@gmail.com>
 * @copyright  2011-2012 Ben Selby <benmatselby@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link       http://www.phpunit.de/
 */
class Util_Log_VCRTest extends PHPUnit_Framework_TestCase
{

    /**
     * @vcr unittest_annotation_test
     */
    public function testInterceptsWithAnnotations()
    {
        // Content of tests/fixtures/unittest_annotation_test: "This is a annotation test dummy".
        $result = file_get_contents('http://google.com');
        $this->assertEquals('This is a annotation test dummy.', $result, 'Call was not intercepted (using annotations).');
    }
}