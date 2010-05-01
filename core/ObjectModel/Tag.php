<?php
namespace Swiftriver\Core\ObjectModel;
class Tag {
    /**
     * The type of the tag. eg: Location, Person etc.
     * @var string
     */
    public $type = "General";

    /**
     * The text of the tag.
     * @var string
     */
    public $text;

    public function __construct($text, $type = "General") {
        $this->type = $type;
        $this->text = $text;
    }
}
?>