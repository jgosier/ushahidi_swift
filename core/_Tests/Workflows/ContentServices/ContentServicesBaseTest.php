<?php
namespace Swiftriver\Core;

require_once 'PHPUnit/Framework.php';

class ContentServicesBaseTest extends \PHPUnit_Framework_TestCase  {
    private $object;

    protected function setUp() {
        include_once(dirname(__FILE__)."/../../../Setup.php");
        $this->object = new Workflows\ContentServices\ContentServicesBase();
    }

   public function testParseContentToJSONWithOneContentItem() {
        $c1 = new ObjectModel\Content();
        $c1->id = "testid1";
        $c1->title = "testtitle1";
        $c1->link = "testlink";
        $c1->state = StateTransition\StateController::$defaultState;
        $c1->text = array("id1text1", "id1text2");
        $c1->tags = array(new ObjectModel\Tag("id1tag1", "who"), new ObjectModel\Tag("id1tag2", "what"));
        $dif1 = new ObjectModel\DuplicationIdentificationField("unique_tweet_id", "d87f8d7fdsg7dfgdfgfd89g7as");
        $dif2 = new ObjectModel\DuplicationIdentificationField("tweet_text", "jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ");
        $c1->difs = array(new ObjectModel\DuplicationIdentificationFieldCollection("collection1", array($dif1, $dif2)));
        $s = new ObjectModel\Source("thisisatestidforatestsource");
        $s->score = 10;
        $c1->source = $s;
        $json = $this->object->ParseContentToJSON(array($c1));
        $this->assertEquals("[".json_encode($c1)."]", $json);
   }

   public function testParseContentToJSONWithTwoContentItems() {
        $c1 = new ObjectModel\Content();
        $c1->id = "testid1";
        $c1->title = "testtitle1";
        $c1->link = "testlink";
        $c1->state = StateTransition\StateController::$defaultState;
        $c1->text = array("id1text1", "id1text2");
        $c1->tags = array(new ObjectModel\Tag("id1tag1", "who"), new ObjectModel\Tag("id1tag2", "what"));
        $dif1 = new ObjectModel\DuplicationIdentificationField("unique_tweet_id", "d87f8d7fdsg7dfgdfgfd89g7as");
        $dif2 = new ObjectModel\DuplicationIdentificationField("tweet_text", "jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ");
        $c1->difs = array(new ObjectModel\DuplicationIdentificationFieldCollection("collection1", array($dif1, $dif2)));
        $s = new ObjectModel\Source("thisisatestidforatestsource");
        $s->score = 10;
        $c1->source = $s;
        $c2 = new ObjectModel\Content();
        $c2->id = "testid2";
        $c2->title = "testtitle2";
        $c2->link = "testlink";
        $c2->state = StateTransition\StateController::$defaultState;
        $c2->text = array("id2text2", "id2text2");
        $c2->tags = array(new ObjectModel\Tag("id2tag2", "who"), new ObjectModel\Tag("id2tag2", "what"));
        $dif2 = new ObjectModel\DuplicationIdentificationField("unique_tweet_id", "d87f8d7fdsg7dfgdfgfd89g7as");
        $dif2 = new ObjectModel\DuplicationIdentificationField("tweet_text", "jdhjsdfy jhfjdsf ksjhf kdjf ksdjfhsd ");
        $c2->difs = array(new ObjectModel\DuplicationIdentificationFieldCollection("collection2", array($dif2, $dif2)));
        $s = new ObjectModel\Source("thisisatestidforatestsource");
        $s->score = 20;
        $c2->source = $s;
        $json = $this->object->ParseContentToJSON(array($c1, $c2));
        $this->assertEquals("[".json_encode($c1).",".json_encode($c2)."]", $json);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToContentIDWithBadJSON() {
        $this->object->ParseJSONToContentID("some bad json");
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToContentIDWithMissingID() {
       $this->object->ParseJSONToContentID('{"good":"json","this":"is"}');
   }

   public function testParseJSONToContentIdWithGoodJSON() {
       $idString = "fdf67gv6df7g86df8g6dfg";
       $id = $this->object->ParseJSONToContentID('{"id":"'.$idString.'"}');
       $this->assertEquals($idString, $id);
   }


      /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToMarkerIDWithBadJSON() {
        $this->object->ParseJSONToContentID("some bad json");
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToMarkerIDDWithMissingID() {
       $this->object->ParseJSONToContentID('{"good":"json","this":"is"}');
   }

   public function testParseJSONToMarkerIDWithGoodJSON() {
       $idString = "fdf67gv6df7g86df8g6dfg";
       $id = $this->object->ParseJSONToMarkerID('{"markerId":"'.$idString.'"}');
       $this->assertEquals($idString, $id);
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToInacurateReasonWithBadJSON() {
        $this->object->ParseJSONToInacurateReason("some bad json");
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToInacurateReasonWithMissingReason() {
       $this->object->ParseJSONToInacurateReason('{"good":"json","this":"is"}');
   }

   /**
    * @expectedException InvalidArgumentException
    */
   public function testParseJSONToInacurateReasonWithBadReason() {
       $this->object->ParseJSONToInacurateReason('{"reason":"youSmell"}');
   }

   public function testParseJSONToInacurateReasonWithGoodJSON() {
       $reason = $this->object->ParseJSONToInacurateReason('{"reason":"falsehood"}');
       $this->assertEquals("falsehood", $reason);
   }
}
?>
