<?php
namespace Swiftriver\Core\Workflows\ChannelProcessingJobs;
class ChannelProcessingJobBase extends \Swiftriver\Core\Workflows\WorkflowBase {
    /**
     * Parses the json in to a channel object
     *
     * @param string $json
     * @return \Swiftriver\Core\ObjectModel\Channel
     */
    public function ParseJSONToChannel($json) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [Method invoked]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [START: Creating new Channel from the ChannelFactory]", \PEAR_LOG_DEBUG);

        try {
            //Try and get a channel from the factory
            $channel = \Swiftriver\Core\ObjectModel\ObjectFactories\ChannelFactory::CreateChannel($json);
        } catch (\Exception $e) {
            //If exception, get the mesasge
            $message = $e->getMessage();

            //and log it
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [$message]", \PEAR_LOG_ERR);

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [Method finished]", \PEAR_LOG_INFO);

            throw new \InvalidArgumentException("The JSON passed to this method did not contain data required to construct a channel object: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [END: Creating new Channel from the ChannelFactory]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannel [Method finished]", \PEAR_LOG_DEBUG);

        return $channel;
    }

    public function ParseJSONToChannelId($json) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannelId [Method invoked]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannelId [START: Decodeing JSON]", \PEAR_LOG_DEBUG);

        //Call json decode on the json
        $object = json_decode($json);

        //check to see if the object decoded
        if(!$object || $object == null) {
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannelId [Failed to parse the JSON]", \PEAR_LOG_ERR);
            throw new \InvalidArgumentException("The json passed to the method did not decode");
        }

        //get the id from the object
        $id = $object->id;

        //Check that the ID is there
        if(!$id || $id == null || !is_string($id)) {
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannelId [Failed to extract the ID from the JSON]", \PEAR_LOG_ERR);
            throw new \InvalidArgumentException("The JSON did not contain a valid ID string");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseJSONToChannelId [END: Decoding JSON]", \PEAR_LOG_DEBUG);

        return $id;
    }

    /**
     *
     * @param \Swiftriver\Core\ObjectModel\Channel[] $channels
     * @return string
     */
    public function ParseChannelsToJSON($channels) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseChannelsToJSON [Method invoked]", \PEAR_LOG_INFO);

        //$logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseChannelsToJSON [Calling json_decode]", \PEAR_LOG_DEBUG);
        $json = '{"channels":[';
        
        if(isset($channels) && is_array($channels) && count($channels) > 0) {
            foreach($channels as $channel) {
                $json .= json_encode($channel).",";
            }
        }
        
        $json = rtrim($json, ",").']}';

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ChannelProcessingJobBase::ParseChannelsToJSON [Method finsihed]", \PEAR_LOG_INFO);

        return $json;
    }
}
?>
