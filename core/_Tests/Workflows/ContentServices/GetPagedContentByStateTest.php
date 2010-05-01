<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class GetPagedContentByStateTest extends \PHPUnit_Framework_TestCase  {
    private $object;

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $this->object = new Workflows\ContentServices\GetPagedContentByState();
    }

    public function test() {
        $this->object->RunWorkflow('{"state":10,"pagesize":20,"pagestart":0}', null);
    }
}
?>
