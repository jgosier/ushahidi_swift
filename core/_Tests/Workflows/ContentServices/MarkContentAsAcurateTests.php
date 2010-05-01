<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class MarkContentAsAcurateTest extends \PHPUnit_Framework_TestCase  {
    public function test() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $workflow = new Workflows\ContentServices\MarkContentAsAcurate();
        $workflow->RunWorkflow('{"id":"42e87d503076ad3f4edf3c74ea5d4c4e","markerId":"someotherid"}', "somekey");
    }
}
?>
