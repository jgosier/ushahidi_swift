<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class RemoveProcessingJobTest extends \PHPUnit_Framework_TestCase  {
    public function test() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        
        $json = '{"id":"test1dforRemoveProcessingJobTest","type":"RSS","updatePeriod":1,"parameters":{"feedUrl":"http://feeds.feedburner.com/Appfrica?format=xml"}}';
        $service = new Workflows\ChannelProcessingJobs\RegisterNewProcessingJob();
        $message = $service->RunWorkflow($json, $key);
        $this->assertEquals(true, strpos($message, "OK") != 0);

        $json = '{"id":"test1dforRemoveProcessingJobTest"}';
        $service = new Workflows\ChannelProcessingJobs\RemoveChannelProcessingJob();
        $message = $service->RunWorkflow($json, $key);
        $this->assertEquals(true, strpos($message, "OK") != 0);
    }
}
?>
