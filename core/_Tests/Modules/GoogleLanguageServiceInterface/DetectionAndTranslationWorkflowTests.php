<?php
namespace Swiftriver\GoogleLanguageServiceInterface;
require_once 'PHPUnit/Framework.php';
class DetectionAndTranslationWorkflowTests extends \PHPUnit_Framework_TestCase {
    public function setup() {
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/LanguageDetectionInterface.php");
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/TranslationInterface.php");
        include_once(dirname(__FILE__)."/../../../Modules/GoogleLanguageServiceInterface/DetectionAndTranslationWorkflow.php");
        include_once("Log.php");
    }
    
    public function testWithSameAsBaseLang() {
        $content = new MockContentObject();
        $content->text = array();
        $lang = new MockLanguageSpecificTextObject();
        $lang->languageCode = null;
        $lang->title = "this is a test title";
        $lang->text = array();
        $lang->text[] = "this is some sample text";
        $content->text[] = $lang;
        $referer = "tests.swiftriver.com";
        $baseLanguageCode = "en";
        $workflow = new DetectionAndTranslationWorkflow(
                $content,
                $referer,
                $baseLanguageCode);
        $logger = new MockLogger();
        $content = $workflow->RunWorkflow($logger);
        $this->assertEquals("en", $content->text[0]->languageCode);
        $this->assertEquals(1, count($content->text));

    }

    public function testWithTranslationRequired() {
        include_once(dirname(__FILE__)."/../../../ObjectModel/LanguageSpecificText.php");
        $content = new MockContentObject();
        $content->text = array();
        $lang = new MockLanguageSpecificTextObject();
        $lang->languageCode = null;
        $lang->title = "À jeune chasseur, il faut un vieux chien";
        $lang->text = array("Souris qui n'a qu'un trou est bientôt prise");
        $content->text[] = $lang;
        $referer = "tests.swiftriver.com";
        $baseLanguageCode = "en";
        $workflow = new DetectionAndTranslationWorkflow(
                $content,
                $referer,
                $baseLanguageCode);
        $logger = new MockLogger();
        $content = $workflow->RunWorkflow($logger);
        $this->assertEquals(2, count($content->text));
        $this->assertEquals("en", $content->text[0]->languageCode);
        $this->assertEquals("fr", $content->text[1]->languageCode);
    }
}

class MockContentObject {
    public $text;
}

class MockLanguageSpecificTextObject {
    public $languageCode;
    public $title;
    public $text;
}

class MockLogger {
    public function Log($message, $level) {}
}

?>
