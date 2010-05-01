<?php
namespace Swiftriver\Core\DAL\DataContextInterfaces;
interface IChannelProcessingJobDataContext {

    /**
     * Given the ID of a channel processing job, this method
     * gets it from the underlying data store
     * 
     * @param string $id 
     * @return \Swiftriver\Core\ObjectModel\Channel
     */
    public static function GetChannelProcessingJobById($id);

    /**
     * Adds a new channel processing job to the data store
     *
     * @param \Swiftriver\Core\ObjectModel\Channel $channel
     */
    public static function SaveChannelProgessingJob($channel);

    /**
     * Given a Channel processing job, this method deletes it from the data store
     * @param \Swiftriver\Core\ObjectModel\Channel $channel
     */
    public static function RemoveChannelProcessingJob($channel);
    
    /**
     * Given a date time, this function returns the next due
     * channel processing job.
     *
     * @param DateTime $time
     * @return \Swiftriver\Core\ObjectModel\Channel
     */
    public static function SelectNextDueChannelProcessingJob($time);

    /**
     * Given a Channel processing job, this method upadtes the data store
     * to reflect that the last run was a sucess.
     *
     * @param \Swiftriver\Core\ObjectModel\Channel $channel
     */
    public static function MarkChannelProcessingJobAsComplete($channel);

    /**
     * Lists all the current Channel Processing Jobs in the core
     * @return \Swiftriver\Core\ObjectModel\Channel[]
     */
    public static function ListAllChannelProcessingJobs();
}
?>
