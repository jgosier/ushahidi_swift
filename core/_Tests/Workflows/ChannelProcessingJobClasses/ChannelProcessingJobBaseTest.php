<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class ChannelProcessingJobBaseTest extends \PHPUnit_Framework_TestCase  {
    private $object;

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $this->object = new Workflows\ChannelProcessingJobs\ChannelProcessingJobBase();

    }

    public function testParseJSONToChannel() {
        $json = '{"type":"Test","updatePeriod":5,"parameters":{"test":"test"},"active":1}';
        $channel = $this->object->ParseJSONToChannel($json);
        $this->assertEquals(true, isset($channel));
        $this->assertEquals("Test", $channel->type);
        $this->assertEquals(5, $channel->updatePeriod);
        $params = $channel->parameters;
        $this->assertEquals(true, is_array($params));
        $this->assertEquals("test", $params["test"]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testParseJSONToChannelWithBadJSON() {
        $json = 'this is bad json and will not pass the parser';
        $channel = $this->object->ParseJSONToChannel($json);
    }

    public function testParseChannelsToJSON() {
        $channel = new ObjectModel\Channel();
        $channel->type = "Test";
        $channel->updatePeriod = 5;
        $channel->parameters = array("one_k" => "one_v", "two_k" => "two_v");
        $json = $this->object->ParseChannelsToJSON(array($channel));
    }
}
?>
