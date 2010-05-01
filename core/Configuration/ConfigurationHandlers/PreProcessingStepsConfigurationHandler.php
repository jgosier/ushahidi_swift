<?php
namespace Swiftriver\Core\Configuration\ConfigurationHandlers;
class PreProcessingStepsConfigurationHandler extends BaseConfigurationHandler {

    /**
     * The ordered collection of pre preocessing steps
     * @var \Swiftriver\Core\ObjectModel\PreProcessingStepEntry[]
     */
    public $PreProcessingSteps;

    public function __construct($configurationFilePath) {
        $xml = simplexml_load_file($configurationFilePath);
        $this->PreProcessingSteps = array();
        foreach($xml->preProcessingSteps->step as $step) {
            $this->PreProcessingSteps[] =
                    new \Swiftriver\Core\ObjectModel\PreProcessingStepEntry(
                        (string) $step["className"],
                        (string) $step["filePath"]);
        }
    }
}
?>
