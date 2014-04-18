<?php
/**
 * A TestListener that integrates with PHP-VCR.
 *
 * Here is an example XML configuration for activating this listener:
 *
 * <code>
 * <listeners>
 *  <listener class="PHPUnit_Util_Log_VCR" file="PHPUnit/Util/Log/VCR.php" />
 * </listeners>
 * </code>
 *
 * @package    PHPUnit
 * @subpackage Util_Log
 * @author     Adrian Philipp <mail@adrian-philipp.com>
 * @copyright  2011-2012 Adrian Philipp <mail@adrian-philipp.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.0.0
 */
class PHPUnit_Util_Log_VCR implements PHPUnit_Framework_TestListener
{
    /**
     * @var array
     */
    protected $runs = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var integer
     */
    protected $suites = 0;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
    }

    /**
     * An error occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * A failure occurred.
     *
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    /**
     * Incomplete test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * Skipped test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    /**
     * Risky test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {

    }

    /**
     * A test started.
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $class      = get_class($test);
        $method     = $test->getName();

        if (!method_exists($class, $method)) {
            return;
        }

        $reflection = new ReflectionMethod($class, $method);
        $doc_block  = $reflection->getDocComment();

        // Use regex to parse the doc_block for a specific annotation
        $cassetteName = self::parseDocBlock($doc_block, '@vcr');

        if (empty($cassetteName)) {
            return true;
        }

        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette(array_pop($cassetteName));
    }

    private static function parseDocBlock($doc_block, $tag)
    {
        $matches = array();

        if (empty($doc_block))
        return $matches;

        $regex = "/{$tag} (.*)(\\r\\n|\\r|\\n)/U";
        preg_match_all($regex, $doc_block, $matches);

        if (empty($matches[1])) {
            return array();
        }

        // Removed extra index
        $matches = $matches[1];

        // Trim the results, array item by array item
        foreach ($matches as $ix => $match)
        $matches[$ix] = trim( $match );

        return $matches;
    }
    /**
     * A test ended.
     *
     * @param PHPUnit_Framework_Test $test
     * @param float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        \VCR\VCR::turnOff();
    }

    /**
     * A test suite started.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    /**
     * A test suite ended.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {

    }
}