<?php
namespace Swiftriver\Core\ObjectModel\ObjectFactories;
class ChannelFactory {
    public static function CreateChannel($json) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Method invoked]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Calling json_decode]", \PEAR_LOG_DEBUG);

        $data = json_decode($json);

        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Extracting data from the JSON objects]", \PEAR_LOG_DEBUG);

        if(!isset($data) || !$data) {
            throw new \InvalidArgumentException("There was an error in the JSON. No Channel can be constructed.");
        }

        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Extracting values from the data]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Constructing Channel object]", \PEAR_LOG_DEBUG);

        $channel = new \Swiftriver\Core\ObjectModel\Channel();
        $channel->id = isset($data->id) ? $data->id : null;
        $channel->type = $data->type;
        $channel->updatePeriod = $data->updatePeriod;
        $channel->active = isset($data->active) ? $data->active : true;
        $channel->lastSucess = isset($data->lastSucess) ? $data->lastSucess : null;
        $channel->inprocess = isset($data->inprocess) ? $data->inprocess : false;

        $params = array();
        foreach($data->parameters as $key => $value) {
            $params[$key] = $value;
        }

        $channel->parameters = $params;

        //set key values if they have not been set
        $channel = ChannelFactory::SetValuesIfNotSet(
                $channel, 
                array(
                    "id" => md5(uniqid(rand(), true)),
                    "active" => true,
                    "nextrun" => time() + ($channel->updatePeriod * 60)
                ));

        $logger->log("Core::ObjectModel::ObjectFactories::ChannelFactory::CreateChannel [Method finished]", \PEAR_LOG_DEBUG);

        return $channel;
    }

    public static function SetValuesIfNotSet($channel, $propertiesAndValues) {
        foreach($propertiesAndValues as $key => $value) {
            $property = $channel->$key;
            if(!isset($property) || $property == null) {
                $channel->$key = $value;
            }
        }
        return $channel;
    }
}
?>