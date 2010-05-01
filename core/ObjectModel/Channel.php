<?php
namespace Swiftriver\Core\ObjectModel;
class Channel {
    
    /**
     * The unique ID of this channel processing job
     * @var string
     */
    public $id;

    /**
     * The type of the Channel
     * @var string 
     */
    public $type;

    /**
     * Parameters used to update the channel with new content;
     * For example, parameters may be:
     *  array (
     *      "type" -> "email",
     *      "connectionString" -> "someConnectionString"
     *  );
     * @var array(string)
     */
    public $parameters = array();

    /**
     * The period in minutes that the channel should be updated
     * @return int
     */
    public $updatePeriod;


    public $nextrun;

    public $lastrun;

    public $inprocess;

    public $timesrun = 0;

    /**
     * If this job is currently active or not
     * @var bool
     */
    public $active = true;

    /**
     * The last time this processing job was sucessfully run
     * @var datetime
     */
    public $lastSucess;

    /**
     * Gets the unique data store Id for this channel processing job, this
     * is made up of the type and parameters written to a string
     * @return string
     */
    public function GetId() { 
        $id = $this->type;
        foreach(array_keys($this->parameters) as $key) {
            $id .= $key.$this->parameters[$key];
        }
        return $id;
    }
}
?>
