<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class TrustLogDataContextTest extends \PHPUnit_Framework_TestCase {

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

    public function testWithOutReason() {
        $markerId = "f78ds9f79sd87f9sd";
        $sourceId = "df9dsf80sdf8ds0fd";
        $change = 1;
        Modules\DataContext\MySql_V1\DataContext::RecordSourceScoreChange($sourceId, $markerId, $change);
    }

    public function testWithReason() {
        $markerId = "f78ds9f79sd87f9sd";
        $sourceId = "df9dsf80sdf8ds0fd";
        $change = -1;
        $reason = "falsehood";
        Modules\DataContext\MySql_V1\DataContext::RecordSourceScoreChange($sourceId, $markerId, $change, $reason);
    }

}
?>
