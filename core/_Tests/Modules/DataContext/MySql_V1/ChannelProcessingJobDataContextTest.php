<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class ChannelProcessingJobDataContextTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        include_once(dirname(__FILE__)."/../../../../Setup.php");
        $dirItterator = new \RecursiveDirectoryIterator(dirname(__FILE__)."/../../../../Modules/DataContext/MySql_V1/");
        $iterator = new \RecursiveIteratorIterator($dirItterator, \RecursiveIteratorIterator::SELF_FIRST);
        foreach($iterator as $file) {
            if($file->isFile()) {
                $filePath = $file->getPathname();
                if(strpos($filePath, ".php")) {
                    include_once($filePath);
                }
            }
        }
    }

    public function test() {
        $channel = new ObjectModel\Channel();
        $channel->id = "testId";
        $channel->type = "test";
        $channel->updatePeriod = 5;
        $channel->parameters = array("feedUrl" => "http://something", "something" => "elshdjsh87d7f76&^&*^SHGGT^&");
        Modules\DataContext\MySql_V1\DataContext::SaveChannelProgessingJob($channel);
        $channel = Modules\DataContext\MySql_V1\DataContext::SelectNextDueChannelProcessingJob(time());
        $this->assertEquals(true, isset($channel));
        $channels = Modules\DataContext\MySql_V1\DataContext::ListAllChannelProcessingJobs();
        $found = false;
        foreach($channels as $c) {
            if($c->GetId() == $channel->GetId()) {
                $found = true;
            }
        }
        Modules\DataContext\MySql_V1\DataContext::MarkChannelProcessingJobAsComplete($channel);
        $channels = Modules\DataContext\MySql_V1\DataContext::ListAllChannelProcessingJobs();
        $found = false;
        foreach($channels as $c) {
            if($c->GetId() == $channel->GetId()) {
                $lastsucess = $c->lastSucess;
                $this->assertEquals(true, isset($lastsucess));
                if(isset($lastsucess)) {
                    $this->assertEquals(true, $lastsucess <= time());
                }
                $found = true;
            }
        }
        $this->assertEquals(true, $found);
        Modules\DataContext\MySql_V1\DataContext::RemoveChannelProcessingJob($channel);
        $channels = Modules\DataContext\MySql_V1\DataContext::ListAllChannelProcessingJobs();
        $found = false;
        foreach($channels as $c) {
            if($c->GetId() == $channel->GetId())
                $found = true;
        }
        $this->assertEquals(false, $found);
    }
}
?>
