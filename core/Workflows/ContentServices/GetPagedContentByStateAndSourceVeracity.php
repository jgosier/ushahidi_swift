<?php
namespace Swiftriver\Core\Workflows\ContentServices;
class GetPagedContentByStateAndSourceVeracity extends ContentServicesBase {
    /**
     * Given a JSON string describing the pagination and state
     * required, this method will return a set of content items
     *
     * @param string $json
     * @return string
     */
    public function RunWorkflow($json, $key) {
        //Setup the logger
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [Method invoked]", \PEAR_LOG_INFO);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [START: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        $parameters = parent::ParseJSONToPagedContentByStateAndSourceVeracityParameters($json);

        if(!isset($parameters)) {
            $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [ERROR: Method ParseJSONToPagedContentByStateAndSourceVeracityParameters returned null]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [ERROR: Getting paged content by state]", \PEAR_LOG_INFO);
            parent::FormatErrorMessage("There was an error in the JSON supplied, please consult the API documentation and try again.");
        }

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Parsing the JSON input]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [START: Constructing Content repository]", \PEAR_LOG_DEBUG);

        $repository = new \Swiftriver\Core\DAL\Repositories\ContentRepository();

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Constructing Content repository]", \PEAR_LOG_DEBUG);

        $state = $parameters["state"];
        $pagestart = $parameters["pagestart"];
        $pagesize = $parameters["pagesize"];
        $minVeracity = $parameters["minVeracity"];
        $maxVeracity = $parameters["maxVeracity"];

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [START: Querying repository with supplied parameters - state: $state, pagesize: $pagesize, pagestart: $pagestart]", \PEAR_LOG_DEBUG);

        $results = $repository->GetPagedContentByStateAndSourceVeracity($state, $pagesize, $pagestart, $minVeracity, $maxVeracity);

        if(!isset($results) || !is_array($results) || !isset($results["totalCount"]) || !isset($results["contentItems"]) || !is_numeric($results["totalCount"]) || $results["totalCount"] < 1) {
            $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [No results were returned from the repository]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Querying repository with supplied parameters]", \PEAR_LOG_DEBUG);
            $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [Method finished]", \PEAR_LOG_INFO);
            return '{"totalcount":"0"}';
        }

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Querying repository with supplied parameters]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [START: Parsing content to JSON]", \PEAR_LOG_DEBUG);

        $contentJson = parent::ParseContentToJSON($results["contentItems"]);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Parsing content to JSON]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [START: Constructing return JSON]", \PEAR_LOG_DEBUG);

        $returnJson = '{"totalcount":"'.$results["totalCount"].'","contentitems":'.$contentJson.'}';

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [END: Constructing return JSON]", \PEAR_LOG_DEBUG);

        $logger->log("Core::ServiceAPI::ContentServices::GetPagedContentByStateAndSourceVeracity::RunWorkflow [Method finished]", \PEAR_LOG_INFO);

        return $returnJson;
    }
}
?>
