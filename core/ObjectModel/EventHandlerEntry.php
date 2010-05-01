<?php
namespace Swiftriver\Core\ObjectModel;
class EventHandlerEntry {
    /**
     * The class name of the event handler
     * @var string
     */
    public $className;

    /**
     * The file path to the event handler relative to the
     * modules directory of the core install
     * @var string
     */
    public $filePath;
    
    public function __construct($className, $filePath) {
        $this->className = $className;
        $this->filePath = $filePath;
    }
}
?>
