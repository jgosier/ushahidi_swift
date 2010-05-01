<?php
namespace Swiftriver\Core;
class Setup {
    private static $configuration;
    private static $dalConfiguration;
    private static $preProcessingStepsConfiguration;
    private static $eventDistributionConfiguration;

    public static function GetLogger() {
        $logger = &\Log::singleton('file', Setup::Configuration()->CachingDirectory."/log.log" , '   ');
        return $logger;
    }

    /**
     * @return Configuration\ConfigurationHandlers\CoreConfigurationHandler
     */
    public static function Configuration() {
        if(isset(self::$configuration))
            return self::$configuration;
        self::$configuration = new Configuration\ConfigurationHandlers\CoreConfigurationHandler(dirname(__FILE__)."/Configuration/ConfigurationFiles/CoreConfiguration.xml");
        return self::$configuration;
    }

    /**
     * @return Configuration\ConfigurationHandlers\DALConfigurationHandler
     */
    public static function DALConfiguration() {
        if(isset(self::$dalConfiguration))
            return self::$dalConfiguration;
        self::$dalConfiguration = new Configuration\ConfigurationHandlers\DALConfigurationHandler(dirname(__FILE__)."/Configuration/ConfigurationFiles/DALConfiguration.xml");
        return self::$dalConfiguration;
    }

    /**
     * @return Configuration\ConfigurationHandlers\PreProcessingStepsConfigurationHandler
     */
    public static function PreProcessingStepsConfiguration() {
        if(isset(self::$preProcessingStepsConfiguration))
            return self::$preProcessingStepsConfiguration;
        self::$preProcessingStepsConfiguration = new Configuration\ConfigurationHandlers\PreProcessingStepsConfigurationHandler(dirname(__FILE__)."/Configuration/ConfigurationFiles/PreProcessingStepsConfiguration.xml");
        return self::$preProcessingStepsConfiguration;
    }

    /**
     * @return Configuration\ConfigurationHandlers\EventDistributionConfigurationHandler
     */
    public static function EventDistributionConfiguration() {
        if(isset(self::$eventDistributionConfiguration))
            return self::$eventDistributionConfiguration;
        self::$eventDistributionConfiguration = new Configuration\ConfigurationHandlers\EventDistributionConfigurationHandler(dirname(__FILE__)."/Configuration/ConfigurationFiles/EventDistributionConfiguration.xml");
        return self::$eventDistributionConfiguration;
    }
}
//include the Loging Framework
include_once("Log.php");

//Include the config framework
include_once(dirname(__FILE__)."/Configuration/ConfigurationHandlers/BaseConfigurationHandler.php");
$dirItterator = new \RecursiveDirectoryIterator(dirname(__FILE__)."/Configuration/ConfigurationHandlers/");
$iterator = new \RecursiveIteratorIterator($dirItterator, \RecursiveIteratorIterator::SELF_FIRST);
foreach($iterator as $file) {
    if($file->isFile()) {
        $filePath = $file->getPathname();
        if(strpos($filePath, ".php")) {
            include_once($filePath);
        }
    }
}


//Include some specific files
include_once(dirname(__FILE__)."/Workflows/WorkflowBase.php");
include_once(dirname(__FILE__)."/Workflows/ChannelProcessingJobs/ChannelProcessingJobBase.php");
include_once(dirname(__FILE__)."/Workflows/ContentServices/ContentServicesBase.php");

//include everything else
$directories = array(
    dirname(__FILE__)."/ObjectModel/",
    dirname(__FILE__)."/DAL/",
    dirname(__FILE__)."/StateTransition/",
    dirname(__FILE__)."/PreProcessing/",
    dirname(__FILE__)."/Workflows/",
    dirname(__FILE__)."/EventDistribution/",
    Setup::Configuration()->ModulesDirectory."/SiSW/",
    Setup::Configuration()->ModulesDirectory."/SiSPS/",
);
foreach($directories as $dir) {
    $dirItterator = new \RecursiveDirectoryIterator($dir);
    $iterator = new \RecursiveIteratorIterator($dirItterator, \RecursiveIteratorIterator::SELF_FIRST);
    foreach($iterator as $file) {
        if($file->isFile()) {
            $filePath = $file->getPathname();
            if(strpos($filePath, ".php")) {
                include_once($filePath);
            }
        }
    }
}

//Include the DAL Data Context Setup file
$relativeDir = Setup::DALConfiguration()->DataContextDirectory;
if(isset($relativeDir) && $relativeDir != "") {
    $directory = Setup::Configuration()->ModulesDirectory.$relativeDir;
    if(file_exists($directory)) {
        //include the setup file - if there is one
        $setupfile = $directory."/Setup.php";
        if(file_exists($setupfile)) {
            include_once($setupfile);
        }
    }
}
?>
