<?php
namespace Swiftriver\Core\Workflows\ChannelProcessingJobs;
class RemoveChannelProcessingJob extends ChannelProcessingJobBase {
    /**
     * Removes a channel processing job from the DAL
     * 
     * @param string $json
     * @return string 
     */
    public function RunWorkflow($json, $key) {
        //Setup the logger
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method invoked]", \PEAR_LOG_INFO);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [START: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        //try to parse the id from the JSON
        try {
            //get the ID from the JSON
            $id = parent::ParseJSONToChannelId($json);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [END: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [START: Constructing Repository]", \PEAR_LOG_DEBUG);

        try {
            //Construct a new repository
            $repository = new \Swiftriver\Core\DAL\Repositories\ChannelProcessingJobRepository();
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [END: Constructing Repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [START: Getting the channel from the repository]", \PEAR_LOG_DEBUG);

        try {
            //Get the channel from the repo
            $channel = $repository->GetChannelProcessingJobById($id);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [END: Getting the channel from the repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [START: Marking channel processing job as inactive and saving to the repository]", \PEAR_LOG_DEBUG);

        try {
            //Delete the channel from the data store
            $repository->RemoveChannelProcessingJob($channel);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [END: Marking channel processing job as inactive and saving to the repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RemoveChannelProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);

        //return an OK messagae
        return parent::FormatMessage("OK");
    }
}
?>
