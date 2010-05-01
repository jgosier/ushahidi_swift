<?php
namespace Swiftriver\Core\DAL\DataContextInterfaces;
include_once(dirname(__FILE__)."/IAPIKeyDataContext.php");
include_once(dirname(__FILE__)."/IChannelProcessingJobDataContext.php");
include_once(dirname(__FILE__)."/IContentDataContext.php");
include_once(dirname(__FILE__)."/ITrustLogDataContext.php");
/**
 * This interfaces pulls together all the components of the
 * DAL into one IDataContext interface that can then be
 * implemented by any type of data store and passed to any
 * of the repositories.
 */
interface IDataContext extends 
    IAPIKeyDataContext,
    IChannelProcessingJobDataContext,
    IContentDataContext,
    ITrustLogDataContext {

}
?>
