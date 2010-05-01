<?php
namespace Swiftriver\Core;
require_once 'PHPUnit/Framework.php';
class WorkflowBaseTests extends \PHPUnit_Framework_TestCase {
    public function testCheckAPIKey() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $object = new Workflows\WorkflowBase();
        $return = $object->CheckKey("test");
        $this->assertEquals(true, isset($return));
    }
}
?>
