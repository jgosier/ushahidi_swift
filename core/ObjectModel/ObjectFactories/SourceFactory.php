<?php
namespace Swiftriver\Core\ObjectModel\ObjectFactories;
class SourceFactory {
    public static function CreateSourceFromJSON($json) {
        //decode the json
        $object = json_decode($json);

        //If there is an error in the JSON
        if(!$object || $object == null) {
            //throw an exception
            throw new \Exception("There was an error in the JSON passed in to the SourceFactory.");
        }

        //create a new source
        $source = new \Swiftriver\Core\ObjectModel\Source();

        //set the basic properties
        $source->id = $object->id;
        $source->score = $object->score;
        $source->uniqueIdString = $object->uniqueIdString;
        $source->name = $object->name;

        //return the source
        return $source;
    }

    public static function CreateSourceFromID($uniqueId) {
        //create a new source
        $source = new \Swiftriver\Core\ObjectModel\Source();

        //use the uniqueId to generate a new id
        $source->id = hash("md5", $uniqueId);

        //TEMP: use the url as the name
        $source->name = $uniqueId;

        //return the source
        return $source;
    }
}
?>
