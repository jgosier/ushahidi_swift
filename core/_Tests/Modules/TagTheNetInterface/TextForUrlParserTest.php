<?php
namespace Swiftriver\TagTheNetInterface;
require_once 'PHPUnit/Framework.php';

class TextForUrlParserTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Modules/TagTheNetInterface/Setup.php");
        $config = Setup::Configuration();
        include_once($config["SwiftriverCoreDirectory"]."/ObjectModel/Content.php");
        include_once($config["SwiftriverCoreDirectory"]."/ObjectModel/LanguageSpecificText.php");
        include_once(dirname(__FILE__)."/../../../Modules/TagTheNetInterface/TextForUrlParser.php");
    }

    public function testWithNullArgs() {
        $parser = new TextForUrlParser(null);
        $this->assertEquals(null, $parser->GetUrlText());
    }

    public function testWithNoTextButTitle() {
        $item = new \Swiftriver\Core\ObjectModel\Content();
        $title = "this is a test title";
        $item->text = array(new \Swiftriver\Core\ObjectModel\LanguageSpecificText(null, $title, array()));
        $parser = new TextForUrlParser($item);
        $this->assertEquals(urlencode($title), $parser->GetUrlText());
    }

    public function testWithTextAndTitle() {
        $item = new \Swiftriver\Core\ObjectModel\Content();
        $title = "this is a test title";
        $item->text = array(
            new \Swiftriver\Core\ObjectModel\LanguageSpecificText(
                    null, 
                    $title, 
                    array("one line of text", "a second line of text")
            ));
        $parser = new TextForUrlParser($item);
        $formattedText = $title . " one line of text" . " a second line of text";
        $this->assertEquals(urlencode($formattedText), $parser->GetUrlText());
    }

    public function testCroppingOfText() {
        $item = new \Swiftriver\Core\ObjectModel\Content();
        $title = "x";
        for($i = 0; $i<2500; $i++) {
            $title .= " x";
        }
        $item->text = array(new \Swiftriver\Core\ObjectModel\LanguageSpecificText(null, $title, array()));
        $parser = new TextForUrlParser($item);
        $this->assertEquals(2000, strlen($parser->GetUrlText()));
    }
}
?>
