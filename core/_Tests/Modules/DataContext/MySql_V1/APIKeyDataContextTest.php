<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class APIKeyDataContextTest extends \PHPUnit_Framework_TestCase {

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
    
    public function testIsRegisteredCoreAPIKeyWithNoneExistantAPIKey(){
        $result = Modules\DataContext\MySql_V1\DataContext::IsRegisterdCoreAPIKey("none-existant-key");
        $this->assertEquals(false, $result);
    }
    
    public function testAllAPIKeyFunctions() {
        $result = Modules\DataContext\MySql_V1\DataContext::AddRegisteredCoreAPIKey("testkey");
        $this->assertEquals(true, $result);
        $this->assertEquals(true, Modules\DataContext\MySql_V1\DataContext::IsRegisterdCoreAPIKey("testkey"));
        $result = Modules\DataContext\MySql_V1\DataContext::RemoveRegisteredCoreAPIKey("testkey");
        $this->assertEquals(true, $result);
        $this->assertEquals(false, Modules\DataContext\MySql_V1\DataContext::IsRegisterdCoreAPIKey("testkey"));
    }
}
?>
