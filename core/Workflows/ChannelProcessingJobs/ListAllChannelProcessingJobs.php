<?php
namespace Swiftriver\Core\Workflows\ChannelProcessingJobs;
class ListAllChannelProcessingJobs extends ChannelProcessingJobBase {
    /**
     * List all Channel Processing Jobs in the Data Store
     *
     * @param string $json
     * @return string $json
     */
    public function RunWorkflow($key) {
        //Setup the logger
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [Method invoked]", \PEAR_LOG_INFO);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [START: Constructing Repository]", \PEAR_LOG_DEBUG);

        try {
            //Construct a new repository
            $repository = new \Swiftriver\Core\DAL\Repositories\ChannelProcessingJobRepository();
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [END: Constructing Repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [START: Listing all processing jobs]", \PEAR_LOG_DEBUG);

        try {
            //Get all the channel processing jobs
            $channels = $repository->ListAllChannelProcessingJobs();
        }
        catch (\Exception $e) {
            //get the exception message 
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [END: Listing all processing jobs]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [START: Parsing channel processing jobs to JSON]", \PEAR_LOG_DEBUG);

        try {
            //Parse the JSON input
            $json = parent::ParseChannelsToJSON($channels);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [END: Parsing channel processing jobs to JSON]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::ListAllChannelProcessingJobs::RunWorkflow [Method finished]", \PEAR_LOG_INFO);

        //return the channels as JSON
        return $json;
    }
}
?>
