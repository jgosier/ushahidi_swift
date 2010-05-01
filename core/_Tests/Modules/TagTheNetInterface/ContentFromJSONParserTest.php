<?php
namespace Swiftriver\TagTheNetInterface;
require_once 'PHPUnit/Framework.php';

class ContentFromJSONParserTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Modules/TagTheNetInterface/Setup.php");
        $config = Setup::Configuration();
        include_once($config["SwiftriverCoreDirectory"]."/ObjectModel/Content.php");
        include_once($config["SwiftriverCoreDirectory"]."/ObjectModel/Tag.php");
        include_once(dirname(__FILE__)."/../../../Modules/TagTheNetInterface/ContentFromJSONParser.php");
    }

    public function testWithNullContentItems() {
        $parser = new ContentFromJSONParser(null, "");
        $contentItems = $parser->GetTaggedContent();
        $this->assertEquals(null, $contentItems);
    }

    public function testWithNullJSON() {
        $content = new \Swiftriver\Core\ObjectModel\Content();
        $content->id = "testId";
        $parser = new ContentFromJSONParser($content, null);
        $contentItem = $parser->GetTaggedContent();
        $this->assertEquals(true, isset($contentItem));
        $this->assertEquals("testId", $contentItem->id);
    }

    public function testWithEmptyJSON() {
        $content = new \Swiftriver\Core\ObjectModel\Content();
        $content->id = "testId";
        $parser = new ContentFromJSONParser($content, "");
        $contentItem = $parser->GetTaggedContent();
        $this->assertEquals(true, isset($contentItem));
        $this->assertEquals("testId", $contentItem->id);
    }

    public function testWithBADJSON() {
        $content = new \Swiftriver\Core\ObjectModel\Content();
        $content->id = "testId";
        $parser = new ContentFromJSONParser($content, '[{"this is":bad json, well i think it is]}]');
        $contentItem = $parser->GetTaggedContent();
        $this->assertEquals(true, isset($contentItem));
        $this->assertEquals("testId", $contentItem->id);
    }

    public function testWithFullGoodJSON() {
        $content = new \Swiftriver\Core\ObjectModel\Content();
        $content->id = "testId";
        $json = '{"memes":[{"source":"http://www.knallgrau.at/en","updated":"Mon May 15 13:38:14 CEST 2006","dimensions":{"title":["knallgrau | Company"],"topic":["knallgrau","twoday","platform","Contact","content","software","management","blog","business","company"],"person":["Dieter Rappold"],"size":["7047"],"content-type":["text/html"],"location":["Vienna","Austria"],"language":["english"],"author":["Ronald Malis"]}}]}';
        $parser = new ContentFromJSONParser($content, $json);
        $item = $parser->GetTaggedContent();
        $this->assertEquals(13, count($item->tags));
        $tags = $item->tags;
        $firsttag = $tags[0];
        $this->assertEquals("what", $firsttag->type);
        $this->assertEquals("knallgrau", $firsttag->text);
        $lasttag = $tags[12];
        $this->assertEquals("who", $lasttag->type);
        $this->assertEquals("Dieter Rappold", $lasttag->text);
    }

    public function testWithGoodButSmallJSON() {
        $content = new \Swiftriver\Core\ObjectModel\Content();
        $content->id = "testId";
        $json = '{"memes":[{"source":"http://www.knallgrau.at/en","updated":"Mon May 15 13:38:14 CEST 2006","dimensions":{"topic":["knallgrau","twoday","platform","Contact","content","software","management","blog","business","company"]}}]}';
        $parser = new ContentFromJSONParser($content, $json);
        $item = $parser->GetTaggedContent();
        $this->assertEquals(10, count($item->tags));
        $tags = $item->tags;
        $firsttag = $tags[0];
        $this->assertEquals("what", $firsttag->type);
        $this->assertEquals("knallgrau", $firsttag->text);
        $lasttag = $tags[9];
        $this->assertEquals("what", $lasttag->type);
        $this->assertEquals("company", $lasttag->text);
    }
}
?>
