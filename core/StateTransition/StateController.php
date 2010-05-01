<?php
namespace Swiftriver\Core\StateTransition;
class StateController {

    /**
     * the default state of a content item
     * @var int
     */
    public static $defaultState = "new_content";

    /**
     * public list of all states
     * @var array
     */
    private static $states = array(
        "new_content",
        "acurate",
        "chatter"
    );

    /**
     *
     * @param \Swiftriver\Core\ObjectModel\Content $content
     */
    public static function MarkContentAcurate($content) {
        $content->state = "acurate";
        return $content;
    }

    /**
     *
     * @param \Swiftriver\Core\ObjectModel\Content $content
     */
    public static function MarkContentChatter($content) {
        $content->state = "chatter";
        return $content;
    }


    public static function IsValidInacurateReason($reason) {
        if($reason != "falsehood" && $reason != "inaccuracy" && $reason != "biased") {
            return false;
        }
        return true;
    }

}
?>
