<?php
namespace Swiftriver\Core\EventDistribution;
class EventDistributor {
    private $eventHandlers;
    
    public function __construct() {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::EventDistribution::EventDistributor::__construct [Method invoked]", \PEAR_LOG_DEBUG);
        
        $logger->log("Core::EventDistribution::EventDistributor::__construct [START: Adding configured event handlers]", \PEAR_LOG_DEBUG);
        
        $this->eventHandlers = \Swiftriver\Core\Setup::EventDistributionConfiguration()->EventHandlers;
        
        $logger->log("Core::EventDistribution::EventDistributor::__construct [END: Adding configured event handlers]", \PEAR_LOG_DEBUG);

        $logger->log("Core::EventDistribution::EventDistributor::__construct [Method finished]", \PEAR_LOG_DEBUG);
    }

    public function RaiseAndDistributeEvent($event) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::EventDistribution::EventDistributor [Method invoked]", \PEAR_LOG_DEBUG);

        $modulesDirectory = \Swiftriver\Core\Setup::Configuration()->ModulesDirectory;
        $configuration = \Swiftriver\Core\Setup::Configuration();

        if(isset($this->eventHandlers) && count($this->eventHandlers) > 0) {
            foreach($this->eventHandlers as $eventHandler) {
                //Get the class name from config
                $className = $eventHandler->className;

                //get the file path from config
                $filePath = $modulesDirectory . $eventHandler->filePath;

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [START: Including event handler: $filePath]", \PEAR_LOG_DEBUG);

                //Include the file
                include_once($filePath);

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [END: Including event handler: $filePath]", \PEAR_LOG_DEBUG);

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [START: Instanciating event handler: $className]", \PEAR_LOG_DEBUG);

                try {
                    //Instanciate the event handlerr
                    $handler = new $className();
                }
                catch (\Exception $e) {
                    $message = $e->getMessage();
                    $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [$message]", \PEAR_LOG_ERR);
                    $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [Unable to run event distribution for event handler: $className]", \PEAR_LOG_ERR);
                }

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [END: Instanciating event handler: $className]", \PEAR_LOG_DEBUG);

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [START: Run event distribution for $className]", \PEAR_LOG_DEBUG);

                try {
                    //Run the handle event method
                    $handler->HandleEvent($event, $configuration, $logger);
                }
                catch (\Exception $e) {
                    $message = $e->getMessage();
                    $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [$message]", \PEAR_LOG_ERR);
                    $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [Unable to run event distribution for event handler: $className]", \PEAR_LOG_ERR);
                }

                $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [END: Run event distribution for $className]", \PEAR_LOG_DEBUG);
            }
        } else {

            $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [No event handlers found to run]", \PEAR_LOG_DEBUG);

        }

        $logger->log("Core::EventDistribution::EventDistributor::RaiseAndDistributeEvent [Method finished]", \PEAR_LOG_DEBUG);
    }
}
?>