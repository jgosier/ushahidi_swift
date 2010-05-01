<?php
namespace Swiftriver\Core\EventDistribution;
class GenericEvent {
    /**
     * The name of the event, event handlers should be listening
     * to raised events and filtering only the ones they want
     * to handle based on this attribute.
     * @var string
     */
    public $name;

    /**
     * The event arguments, these can be anything held in an
     * associative aray, Event handlers can interpret the data
     * in this array to act on the raising of this event
     * @var Associative Array
     */
    public $arguments;

    /**
     * The constructor for a generic event
     * @param string $name
     * @param array() $arguments
     */
    public function __construct($name, $arguments) {
        $this->name = $name;
        $this->arguments = $arguments;
    }
}
?>
