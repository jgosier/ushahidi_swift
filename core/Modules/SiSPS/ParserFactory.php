<?php
/**
 * ParserFactory is responciable for returning 
 * an instance of an object that implements the
 * IParser interface.
 */
namespace Swiftriver\Core\Modules\SiSPS;
class ParserFactory{
    /**
     * Expects a string reprosenting the class
     * name of an object that implements the
     * SiSPS\IParser interface. The param $type
     * must not include the word 'Parser'. For
     * example, supplying the $type Email will
     * return an instance of the EmailParser
     * object.
     *
     * @param string $type
     * @return SiSPS\Parsers\IParser $parser
     */
    public static function GetParser($type) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::Modules::SiSPS::ParserFactory::GetParser [Method invoked]", \PEAR_LOG_DEBUG);

        //Append the word Parser to the type
        $type = $type."Parser";

        //If the class is not defined, return null
        $type = "\\Swiftriver\\Core\\Modules\\SiSPS\\Parsers\\".$type;
        if(!class_exists($type)) {
            $logger->log("Core::Modules::SiSPS::ParserFactory::GetParser [Class $type not found. Returning null]", \PEAR_LOG_DEBUG);
            $logger->log("Core::Modules::SiSPS::ParserFactory::GetParser [Method finished]", \PEAR_LOG_DEBUG);
            return null;
        }

        $logger->log("Core::Modules::SiSPS::ParserFactory::GetParser [Method finished]", \PEAR_LOG_DEBUG);

        //Finally, return a new Parser
        return new $type();
    }
}
?>
