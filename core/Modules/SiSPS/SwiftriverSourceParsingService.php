<?php
/* 
 * SiSPS\SwiftriverSourceParsingService is the main
 * interface class to the Source Parsing Componet if
 * Swiftriver. This class is designed to take in
 * instructions regarding a channel and return all content
 * items that can be selected from that channel.
 */
namespace Swiftriver\Core\Modules\SiSPS;
class SwiftriverSourceParsingService {
    /**
     * This method will take the information prvided in the
     * instance of a Swiftriver\Core\ObjectModel\Channel object
     * and will make a call to the channel to fetch and content
     * that can be fetched and then parse the content into an array
     * of Swiftriver\Core\ObjectModel\Content items
     *
     * @param Swiftriver\Core\ObjectModel\Channel $channel
     * @return Swiftriver\Core\ObjectModel\Content[] $contentItems
     */
    public function FetchContentFromChannel($channel) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [Method invoked]", \PEAR_LOG_DEBUG);

        if(!isset($channel) || $channel == null) {
            $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [The channel object param is null]", \PEAR_LOG_DEBUG);
            $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [Method finished]", \PEAR_LOG_DEBUG);
            return;
        }

        //get the type of the channel
        $channelType = $channel->type;

        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [Channel type is $channelType]", \PEAR_LOG_DEBUG);

        //Get a Parser from the ParserFactory based on the channel type
        $parser = ParserFactory::GetParser($channelType);

        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [Constructed parser from factory]", \PEAR_LOG_DEBUG);

        //Extract the parameters from the channel object
        $parameters = $channel->parameters;

        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [START: parser->GetAndParse]", \PEAR_LOG_DEBUG);

        //Get and parse all avaliable content items from the parser
        $contentItems = $parser->GetAndParse($parameters, $channel->lastSucess);

        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [END: parser->GetAndParse]", \PEAR_LOG_DEBUG);

        $logger->log("Core::Modules::SiSPS::SwiftriverSourceParsingService::FetchContentFromChannel [Method finished]", \PEAR_LOG_DEBUG);

        //Return the content items
        return $contentItems;
    }
}
?>
