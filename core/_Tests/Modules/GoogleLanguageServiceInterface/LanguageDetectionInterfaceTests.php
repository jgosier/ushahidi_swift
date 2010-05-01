<?php
namespace Swiftriver\GoogleLanguageServiceInterface\Tests;
require_once 'PHPUnit/Framework.php';

class LanguageDetectionInterfaceTests extends \PHPUnit_Framework_TestCase {

    public function testGetLanguageCodeFromEnglish() {
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/LanguageDetectionInterface.php");
        $referer = "tests.swiftriver.com";
        $text = "In 1972, a crack commando unit was sent to prison by a military court for a crime they didn't commit. They promptly escaped from a maximum security stockade to the Los Angeles underground.";
        $interface = new \Swiftriver\GoogleLanguageServiceInterface\LanguageDetectionInterface($text, $referer);
        $code = $interface->GetLanguageCode();
        $this->assertEquals("en", $code);
    }

    public function testGetLanguageCodeFromFrench() {
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/LanguageDetectionInterface.php");
        $referer = "tests.swiftriver.com";
        $text = "Le plus grand faible des hommes, c'est l'amour qu'ils ont de la vie";
        $interface = new \Swiftriver\GoogleLanguageServiceInterface\LanguageDetectionInterface($text, $referer);
        $code = $interface->GetLanguageCode();
        $this->assertEquals("fr", $code);
    }

    public function testGetLanguageCodeWithJiberish() {
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/LanguageDetectionInterface.php");
        $referer = "tests.swiftriver.com";
        $text = "kjhfdsdksjhf fh dsfskjdfh kjhfk ds hfkjshfkshf owqrpowreirakd;lkd;aslkd dpokwjad lasjdklas";
        $interface = new \Swiftriver\GoogleLanguageServiceInterface\LanguageDetectionInterface($text, $referer);
        $code = $interface->GetLanguageCode();
        $this->assertEquals(null, $code);
    }
}
?>
