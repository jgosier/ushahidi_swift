<?php
namespace Swiftriver\Core;
require_once 'PHPUnit/Framework.php';
class CoreConfigurationHandlerTest extends \PHPUnit_Framework_TestCase {
    public function testWithMockConfig() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $config = new Configuration\ConfigurationHandlers\CoreConfigurationHandler(dirname(__FILE__)."/MockCoreConfiguration.xml");
        $this->assertEquals(true, strpos($config->ModulesDirectory, "/Modules") != 0);
        $this->assertEquals(true, strpos($config->CachingDirectory, "/Cache") != 0);
    }
}
?>
