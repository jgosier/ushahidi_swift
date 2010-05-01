<?php
namespace Swiftriver\Core\Workflows\ChannelProcessingJobs;
class SaveChannelProcessingJob extends ChannelProcessingJobBase {
    /**
     * Adds the pre processing job to the DAL
     *
     * @param string $json
     * @return string $json
     */
    public function RunWorkflow($json, $key) {
        //Setup the logger
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [Method invoked]", \PEAR_LOG_INFO);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [START: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        try {
            //Parse the JSON input
            $channel = parent::ParseJSONToChannel($json);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        if(!isset($channel)) {
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [ERROR: Method ParseIncommingJSON returned null]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [ERROR: Registering new processing job with Core]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("There were errors in you JSON. Please review the API documentation and try again.");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [END: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [START: Constructing Repository]", \PEAR_LOG_DEBUG);

        try {
            //Construct a new repository
            $repository = new \Swiftriver\Core\DAL\Repositories\ChannelProcessingJobRepository();
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [END: Constructing Repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [START: Saving Processing Job]", \PEAR_LOG_DEBUG);

        try {
            //Add the channel processign job to the repository
            $repository->SaveChannelProgessingJob($channel);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [END: Saving Processing Job]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::SaveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);

        //return an OK messagae
        return parent::FormatMessage("OK");
    }
}
?>
