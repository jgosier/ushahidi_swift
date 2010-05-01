<?php
namespace Swiftriver\Core\Configuration\ConfigurationHandlers;
class EventDistributionConfigurationHandler extends BaseConfigurationHandler {

    /**
     * The ordered collection of pre preocessing steps
     * @var \Swiftriver\Core\ObjectModel\PreProcessingStepEntry[]
     */
    public $EventHandlers;

    public function __construct($configurationFilePath) {
        $xml = simplexml_load_file($configurationFilePath);
        $this->EventHandlers = array();
        foreach($xml->eventHandlers->handler as $handler) {
            $this->EventHandlers[] =
                    new \Swiftriver\Core\ObjectModel\EventHandlerEntry(
                        (string) $handler["className"],
                        (string) $handler["filePath"]);
        }
    }
}
?>
