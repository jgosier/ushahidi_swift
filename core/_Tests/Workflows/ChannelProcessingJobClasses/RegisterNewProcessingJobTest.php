<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class RegisterNewProcessingJobTest extends \PHPUnit_Framework_TestCase  {
    private $object;

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $this->object = new Workflows\ChannelProcessingJobs\RegisterNewProcessingJob();
    }

    public function testRunWorkflowWithBadJSON() {
        $json = 'this is bad json and will not pass the parser';
        $message = $this->object->RunWorkflow($json, $key);
        $this->assertEquals(true, strpos($message, "OK") == 0);
    }

    public function testRunWorkflowWithGoodJSON() {
        $json = '{"type":"RSS","updatePeriod":1,"parameters":{"feedUrl":"http://feeds.feedburner.com/Appfrica?format=xml"}}';
        $message = $this->object->RunWorkflow($json, $key);
        $this->assertEquals(true, strpos($message, "OK") != 0);
    }
}
?>
