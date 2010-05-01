<?php
namespace Swiftriver\Core\DAL\Repositories;
class ContentRepository {
    /**
     * The fully qualified type of the IContentDataContext implemting
     * data context for this repository
     * @var \Swiftriver\Core\DAL\DataContextInterfaces\IDataContext
     */
    private $dataContext;

    /**
     * The constructor for this repository
     * Accepts the fully qulaified type of the IContentDataContext implemting
     * data context for this repository
     *
     * @param string $dataContext
     */
    public function __construct($dataContext = null) {
        if(!isset($dataContext))
            $dataContext = \Swiftriver\Core\Setup::DALConfiguration()->DataContextType;
        $classType = (string) $dataContext;
        $this->dataContext = new $classType();
    }

    /**
     * Given a set of content items, this method will persist
     * them to the data store, if they already exists then this
     * method should update the values in the data store.
     *
     * @param \Swiftriver\Core\ObjectModel\Content[] $content
     */
    public function SaveContent($content) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::DAL::Repositories::ContentRepository::SaveContent [Method invoked]", \PEAR_LOG_DEBUG);
        $dc = new $this->dataContext();
        $dc::SaveContent($content);
        $logger->log("Core::DAL::Repositories::ContentRepository::SaveContent [Method Finished]", \PEAR_LOG_DEBUG);
    }

    /**
     * Given an array of content is's, this function will
     * fetch the content objects from the data store.
     *
     * @param string[] $ids
     * @return \Swiftriver\Core\ObjectModel\Content[]
     */
    public function GetContent($ids) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::DAL::Repositories::ContentRepository::GetContent [Method invoked]", \PEAR_LOG_DEBUG);
        $dc = new $this->dataContext();
        $content = $dc::GetContent($ids);
        $logger->log("Core::DAL::Repositories::ContentRepository::GetContent [Method finished]", \PEAR_LOG_DEBUG);
        return $content;
    }

    /**
     * Given an array of content items, this method removes them
     * from the data store.
     * @param \Swiftriver\Core\ObjectModel\Content[] $content
     */
    public function DeleteContent($content) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::DAL::Repositories::ContentRepository::DeleteContent [Method invoked]", \PEAR_LOG_DEBUG);
        $dc = new $this->dataContext();
        $dc::DeleteContent($content);
        $logger->log("Core::DAL::Repositories::ContentRepository::DeleteContent [Method finshed]", \PEAR_LOG_DEBUG);
    }

    /**
     * Given a status, pagesize, page start index and possibly
     * an order by calse, this method will return a page of content.
     *
     * @param int $state
     * @param int $pagesize
     * @param int $pagestart
     * @param string $orderby
     * @return array("totalCount" => int, "contentItems" => Content[])
     */
    public function GetPagedContentByState($state, $pagesize, $pagestart, $orderby = null) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::DAL::Repositories::ContentRepository::GetPagedContentByState [Method invoked]", \PEAR_LOG_DEBUG);
        $dc = new $this->dataContext();
        $content = $dc::GetPagedContentByState($state, $pagesize, $pagestart, $orderby);
        $logger->log("Core::DAL::Repositories::ContentRepository::GetPagedContentByState [Method finished]", \PEAR_LOG_DEBUG);
        return $content;
    }

    /**
     * Given the correct parameters, this method will reatun a page of content
     * in the correct state for whome the source of that content has a veracity
     * score in between the $minVeracity and $maxVeracity supplied.
     *
     * @param int $state
     * @param int $pagesize
     * @param int $pagestart
     * @param int $minVeracity 0 - 100
     * @param int $maxVeracity 0 - 100
     * @param string $orderby
     * @return array("totalCount" => int, "contentItems" => Content[])
     */
    public function GetPagedContentByStateAndSourceVeracity($state, $pagesize, $pagestart, $minVeracity, $maxVeracity, $orderby = null) {
        $logger = \Swiftriver\Core\Setup::GetLogger();
        $logger->log("Core::DAL::Repositories::ContentRepository::GetPagedContentByStateAndSourceVeracity [Method invoked]", \PEAR_LOG_DEBUG);
        $dc = new $this->dataContext();
        $content = $dc::GetPagedContentByStateAndSourceVeracity($state, $pagesize, $pagestart, $minVeracity, $maxVeracity, $orderby);
        $logger->log("Core::DAL::Repositories::ContentRepository::GetPagedContentByStateAndSourceVeracity [Method finished]", \PEAR_LOG_DEBUG);
        return $content;
    }
}
?>
