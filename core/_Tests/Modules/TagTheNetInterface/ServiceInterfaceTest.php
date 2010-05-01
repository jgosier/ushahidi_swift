<?php
namespace Swiftriver\TagTheNetInterface;
require_once 'PHPUnit/Framework.php';

class ServiceInterfaceTest extends \PHPUnit_Framework_TestCase {
    public function test() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        include_once(dirname(__FILE__)."/../../../Modules/TagTheNetInterface/Setup.php");
        include_once(dirname(__FILE__).'/../../../Modules/TagTheNetInterface/ServiceInterface.php');
        $service = new ServiceInterface();
        $uri = "http://tagthe.net/api/";
        $text = urlencode("In 1972, a crack commando unit was sent to prison by a military court for a crime they didn't commit. They promptly escaped from a maximum security stockade to the Los Angeles underground. Today, still wanted by the government, they survive as soldiers of fortune. If you have a problem, if no-one else can help, and if you can find them, maybe you can hire the A-Team.");
        $config = \Swiftriver\Core\Setup::COnfiguration();
        $json = $service->InterafceWithService($uri, $text, $config);
        $this->assertEquals(
                true,
                0 != strpos($json, '"dimensions":{"topic":["unit","prison","crack","crime","maximum","stockade","court","commando","security","underground"],"location":["Los Angeles"],"language":["english"]}}]}')
        );
    }
}
?>
