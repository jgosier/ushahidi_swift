<?php
namespace Swiftriver\Core\ObjectModel;
class Content {
    /**
     * The unique Id of the content
     * @var sytring
     */
    public $id;

    /**
     * The current state of the content
     * @var string
     */
    public $state;

    /**
     * A none-assisative array of language specific
     * text associated with the content. Each element
     * of the array is an instance of the
     * Core\ObjectModel\LanguageSpecificText class
     * @var LanguageSpecificText[]
     */
    public $text = array();

    /**
     * The hyperlink to the original content
     * @var string
     */
    public $link;

    /**
     * the publish date of the content
     * @var timestamp
     */
    public $date;

    /**
     * An array of tags for the content
     * @var \Swiftriver\Core\ObjectModel\Tag[]
     */
    public $tags = array();

    /**
     * The source of the content
     * @var \Swiftriver\Core\ObjectModel\Source
     */
    public $source;

    /**
     * The array of DIFs
     * @var \Swiftriver\Core\ObjectModel\DuplicationIdentificationFieldCollection[]
     */
    public $difs = array();
}
?>
