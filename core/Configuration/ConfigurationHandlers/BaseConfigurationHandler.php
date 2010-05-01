<?php
namespace Swiftriver\Core\Configuration\ConfigurationHandlers;
class BaseConfigurationHandler {
    /**
     * Given a SimpleXMLElement from a Swiftriver config document,
     * this method extract the core data and returns a keyed array
     * of the data.
     * @param \SimpleXMLElement $xml
     * @return string[]
     */
    public function ExtractConfigurationPropertyValues($xml) {
        $config = array (
            "name" => $xml["name"],
            "displayName" => $xml["displayName"],
            "type" => $xml["type"],
            "value" => $xml["value"]
        );
        return $config;
    }

    public function ExtractAllConfigurationProperties($xml) {

    }
}
?>
