<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class EventDistributerTests extends \PHPUnit_Framework_TestCase  {
    /**
     * This test simply proves that the interplay between the 
     * Event Distributer and the configuration file is working, it
     * does not test the actual preprocessing steps.
     */
    public function test() {
        include_once(dirname(__FILE__)."/../../Setup.php");
        $eventDistributer = new EventDistribution\EventDistributor();
        $eventDistributer->RaiseAndDistributeEvent(null);
    }
}
?>
