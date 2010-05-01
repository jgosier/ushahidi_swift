<?php
namespace Swiftriver\Core;
require_once 'PHPUnit/Framework.php';
class PreProcessingStepsConfigurationHandlerTest extends \PHPUnit_Framework_TestCase {
    public function testWithMockConfig() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $config = new Configuration\ConfigurationHandlers\PreProcessingStepsConfigurationHandler(dirname(__FILE__)."/MockPreProcessingStepsConfiguration.xml");
        $this->assertEquals(2, count($config->PreProcessingSteps));
    }
}
?>
