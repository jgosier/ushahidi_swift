<?php
namespace Swiftriver\Core\PreProcessing;
class PreProcessor {
    private $preProcessingSteps;

    public function __construct($modulesDirectory = null) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::PreProcessing::PreProcessor::__construct [Method invoked]", \PEAR_LOG_DEBUG);
        
        $logger->log("Core::PreProcessing::PreProcessor::__construct [START: Adding configured pre processors]", \PEAR_LOG_DEBUG);
        
        $this->preProcessingSteps = \Swiftriver\Core\Setup::PreProcessingStepsConfiguration()->PreProcessingSteps;
        
        $logger->log("Core::PreProcessing::PreProcessor::__construct [END: Adding configured pre processors]", \PEAR_LOG_DEBUG);

        $logger->log("Core::PreProcessing::PreProcessor::__construct [Method finished]", \PEAR_LOG_DEBUG);
    }

    public function PreProcessContent($content) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [Method invoked]", \PEAR_LOG_DEBUG);

        $modulesDirectory = \Swiftriver\Core\Setup::Configuration()->ModulesDirectory;
        $configuration = \Swiftriver\Core\Setup::Configuration();

        if(isset($this->preProcessingSteps) && count($this->preProcessingSteps) > 0) {
            foreach($this->preProcessingSteps as $preProcessingStep) {
                //Get the class name from config
                $className = $preProcessingStep->className;

                //get the file path from config
                $filePath = $modulesDirectory . $preProcessingStep->filePath;

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [START: Including pre processor: $filePath]", \PEAR_LOG_DEBUG);

                //Include the file
                include_once($filePath);

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [END: Including pre processor: $filePath]", \PEAR_LOG_DEBUG);

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [START: Instanciating pre processor: $className]", \PEAR_LOG_DEBUG);

                try {
                    //Instanciate the pre processor
                    $preProcessor = new $className();
                }
                catch (\Exception $e) {
                    $message = $e->getMessage();
                    $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [$message]", \PEAR_LOG_ERR);
                    $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [Unable to run PreProcessing for preprocessor $className]", \PEAR_LOG_ERR);
                }

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [END: Instanciating pre processor: $className]", \PEAR_LOG_DEBUG);

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [START: Run PreProcessing for $className]", \PEAR_LOG_DEBUG);

                try {
                    //Run the preocess method on the pre processor
                    $content = $preProcessor->Process($content, $configuration, $logger);
                }
                catch (\Exception $e) {
                    $message = $e->getMessage();
                    $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [$message]", \PEAR_LOG_ERR);
                    $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [Unable to run PreProcessing for preprocessor $className]", \PEAR_LOG_ERR);
                }

                $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [END: Run PreProcessing for $className]", \PEAR_LOG_DEBUG);
            }
        } else {

            $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [No PreProcessing Steps found to run]", \PEAR_LOG_DEBUG);

        }

        $logger->log("Core::PreProcessing::PreProcessor::PreProcessContent [Method finished]", \PEAR_LOG_DEBUG);
        
        //Return the content
        return $content;
    }
}
?>