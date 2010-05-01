<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class ContentDataContextTest extends \PHPUnit_Framework_TestCase {

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

    public function testRedbean() {
        $time = time();
        $c1 = new ObjectModel\Content();
        $c1->id = "testid1";
        $c1->link = "testlink";
        $c1->state = StateTransition\StateController::$defaultState;
        $c1->date = $time;
        $c1->text = array(
            new ObjectModel\LanguageSpecificText(
                "en",
                "testtitle1",
                array("id1text1", "id1text2")));
        $c1->tags = array(new ObjectModel\Tag("id1tag1", "who"), new ObjectModel\Tag("id1tag2", "what"));
        $dif1 = new ObjectModel\DuplicationIdentificationField("unique_tweet_id", "d87f8d7fdsg7dfgdfgfd89g7as");
        $dif2 = new ObjectModel\DuplicationIdentificationField("tweet_text", "jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ");
        $c1->difs = array(new ObjectModel\DuplicationIdentificationFieldCollection("collection1", array($dif1, $dif2)));
        $s = ObjectModel\ObjectFactories\SourceFactory::CreateSourceFromID("thisisatestidforatestsource");
        $s->score = 10;
        $c1->source = $s;
        Modules\DataContext\MySql_V1\DataContext::SaveContent(array($c1));

        $cOutArray = Modules\DataContext\MySql_V1\DataContext::GetContent(array($c1->id));
        $this->assertEquals(true, isset($cOutArray));
        $this->assertEquals(true, is_array($cOutArray));
        $this->assertEquals(1, count($cOutArray));

        $content = $cOutArray[0];
        $this->assertEquals("testid1", $content->id);
        $this->assertEquals("testlink", $content->link);
        $this->assertEquals($time, $content->date);
        $text = reset($content->text); //get the first element
        $this->assertEquals("testtitle1", $text->title);
        $this->assertEquals("en", $text->languageCode);
        $this->assertEquals("id1text1", $text->text[0]);
        $this->assertEquals("id1text2", $text->text[1]);
        $tags = $content->tags;
        $this->assertEquals(true, isset($tags));
        $this->assertEquals(true, is_array($tags));
        $this->assertEquals(2, count($tags));
        $tag1 = $tags[0];
        $this->assertEquals("id1tag1", $tag1->text);
        $this->assertEquals("who", $tag1->type);
        $tag2 = $tags[1];
        $this->assertEquals("id1tag2", $tag2->text);
        $this->assertEquals("what", $tag2->type);
        $difCollections = $content->difs;
        $this->assertEquals(true, isset($difCollections));
        $this->assertEquals(true, is_array($difCollections));
        $this->assertEquals(1, count($difCollections));
        $difCollection = $difCollections[0];
        $this->assertEquals(true, isset($difCollection));
        $this->assertEquals("collection1", $difCollection->name);
        $difs = $difCollection->difs;
        $this->assertEquals(true, isset($difs));
        $this->assertEquals(true, is_array($difs));
        $this->assertEquals(2, count($difs));
        $d1 = $difs[0];
        $this->assertEquals("unique_tweet_id", $d1->type);
        $this->assertEquals("d87f8d7fdsg7dfgdfgfd89g7as", $d1->value);
        $d2 = $difs[1];
        $this->assertEquals("tweet_text", $d2->type);
        $this->assertEquals("jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ", $d2->value);
        $source = $content->source;
        $this->assertEquals(true, isset($source));
        $this->assertEquals(10, $source->score);
        $sId = $source->id;
        $this->assertEquals(true, isset($sId));

        $state = StateTransition\StateController::$defaultState;
        $array = Modules\DataContext\MySql_V1\DataContext::GetPagedContentByState($state, 10, 0);
        $this->assertEquals(true, is_array($array));
        $totalCout = $array["totalCount"];
        $this->assertEquals(true, $totalCout > 0);
        $contentArray = $array["contentItems"];
        $this->assertEquals(true, isset($contentArray));
        $this->assertEquals(true, is_array($contentArray));
        foreach($contentArray as $c) {
            if($c->id == "testid1") {
                $content = $c;
            }
        }
        $this->assertEquals("testid1", $content->id);
        $this->assertEquals("testlink", $content->link);
        $text = reset($content->text); //get the first element
        $this->assertEquals("testtitle1", $text->title);
        $this->assertEquals("en", $text->languageCode);
        $this->assertEquals("id1text1", $text->text[0]);
        $this->assertEquals("id1text2", $text->text[1]);
        $tags = $content->tags;
        $this->assertEquals(true, isset($tags));
        $this->assertEquals(true, is_array($tags));
        $this->assertEquals(2, count($tags));
        $tag1 = $tags[0];
        $this->assertEquals("id1tag1", $tag1->text);
        $this->assertEquals("who", $tag1->type);
        $tag2 = $tags[1];
        $this->assertEquals("id1tag2", $tag2->text);
        $this->assertEquals("what", $tag2->type);
        $difCollections = $content->difs;
        $this->assertEquals(true, isset($difCollections));
        $this->assertEquals(true, is_array($difCollections));
        $this->assertEquals(1, count($difCollections));
        $difCollection = $difCollections[0];
        $this->assertEquals(true, isset($difCollection));
        $this->assertEquals("collection1", $difCollection->name);
        $difs = $difCollection->difs;
        $this->assertEquals(true, isset($difs));
        $this->assertEquals(true, is_array($difs));
        $this->assertEquals(2, count($difs));
        $d1 = $difs[0];
        $this->assertEquals("unique_tweet_id", $d1->type);
        $this->assertEquals("d87f8d7fdsg7dfgdfgfd89g7as", $d1->value);
        $d2 = $difs[1];
        $this->assertEquals("tweet_text", $d2->type);
        $this->assertEquals("jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ", $d2->value);
        $source = $content->source;
        $this->assertEquals(true, isset($source));
        $this->assertEquals(10, $source->score);
        $sId = $source->id;
        $this->assertEquals(true, isset($sId));

        Modules\DataContext\MySql_V1\DataContext::DeleteContent(array($content));
        $contentArray = Modules\DataContext\MySql_V1\DataContext::GetContent(array($content->id));
        $this->assertEquals(true, isset($contentArray));
        $this->assertEquals(0, count($contentArray));

    }
}

?>
