<?php
namespace Swiftriver\Core\ObjectModel;
class Source {
    /**
     * The genuine unique ID of this source
     * @var string
     */
    public $id;

    /**
     * The trust score for this source
     * @var int
     */
    public $score;

    /**
     * A string that can be used to uniquly identify
     * this source - such as the feed URL of an RSS
     * @var string
     */
    public $uniqueIdString;

    /**
     * The friendly name of this source
     * @var string
     */
    public $name;
}
?>
