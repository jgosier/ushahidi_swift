<?php
namespace Swiftriver\Core\ObjectModel;
class PreProcessingStepEntry {
    /**
     * The class name of the pre processing step
     * @var string
     */
    public $className;

    /**
     * The file path to the pre processing step relative to the
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
