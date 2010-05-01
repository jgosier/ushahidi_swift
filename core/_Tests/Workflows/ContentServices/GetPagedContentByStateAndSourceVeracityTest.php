<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class GetPagedContentByStateAndSourceVeracityTest extends \PHPUnit_Framework_TestCase  {
    private $object;

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $this->object = new Workflows\ContentServices\GetPagedContentByStateAndSourceVeracity();
    }

    public function test() {
        $this->object->RunWorkflow('{"state":"new_content","pagesize":20,"pagestart":0,"minVeracity":"0","maxVeracity":"100"}', null);
    }
}
?>
