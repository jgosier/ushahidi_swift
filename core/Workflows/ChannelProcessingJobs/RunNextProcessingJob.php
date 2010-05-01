<?php
namespace Swiftriver\Core\Workflows\ChannelProcessingJobs;
class RunNextProcessingJob extends ChannelProcessingJobBase {
    /**
     * Selects the next due processing job and runs it through the core
     *
     * @return string $json
     */
    public function RunWorkflow($key) {
        //Setup the logger
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method invoked]", \PEAR_LOG_INFO);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Setting time out]", \PEAR_LOG_DEBUG);
        
        set_time_limit(300);
        
        $timeout = ini_get('max_execution_time');

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Setting time out to $timeout]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Constructing Repository]", \PEAR_LOG_DEBUG);

        try {
            //Construct a new repository
            $channelRepository = new \Swiftriver\Core\DAL\Repositories\ChannelProcessingJobRepository();
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Constructing Repository]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Fetching next processing Job]", \PEAR_LOG_DEBUG);

        try {
            //Get the next due channel processign job
            $channel = $channelRepository->SelectNextDueChannelProcessingJob(time());
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }


        if($channel == null) {
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [INFO: No processing jobs due]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Fetching next processing Job]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatMessage("OK");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Fetching next processing Job]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Get and parse content]", \PEAR_LOG_DEBUG);

        try {
            $SiSPS = new \Swiftriver\Core\Modules\SiSPS\SwiftriverSourceParsingService();
            $rawContent = $SiSPS->FetchContentFromChannel($channel);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }


        if(isset($rawContent) && is_array($rawContent) && count($rawContent) > 0) {

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Get and parse content]", \PEAR_LOG_DEBUG);

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Running core processing]", \PEAR_LOG_DEBUG);

            try {
                $preProcessor = new \Swiftriver\Core\PreProcessing\PreProcessor();
                $processedContent = $preProcessor->PreProcessContent($rawContent);
            }
            catch (\Exception $e) {
                //get the exception message
                $message = $e->getMessage();
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
                return parent::FormatErrorMessage("An exception was thrown: $message");
            }

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Running core processing]", \PEAR_LOG_DEBUG);

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Save content to the data store]", \PEAR_LOG_DEBUG);

            try {
                $contentRepository = new \Swiftriver\Core\DAL\Repositories\ContentRepository();
                $contentRepository->SaveContent($processedContent);
            }
            catch (\Exception $e) {
                //get the exception message
                $message = $e->getMessage();
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
                $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
                return parent::FormatErrorMessage("An exception was thrown: $message");
            }

            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Save content to the data store]", \PEAR_LOG_DEBUG);
        }
        else {
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Get and parse content]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [No content found.]", \PEAR_LOG_DEBUG);
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [START: Mark channel processing job as complete]", \PEAR_LOG_DEBUG);

        try {
            $channelRepository->MarkChannelProcessingJobAsComplete($channel);
        }
        catch (\Exception $e) {
            //get the exception message
            $message = $e->getMessage();
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [An exception was thrown]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [$message]", \PEAR_LOG_ERR);
            $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return parent::FormatErrorMessage("An exception was thrown: $message");
        }

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [END: Mark channel processing job as complete]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ChannelProcessingJobs::RunNextProcessingJob::RunWorkflow [Method finished]", \PEAR_LOG_INFO);

        return parent::FormatMessage("OK");
    }
}
?>
