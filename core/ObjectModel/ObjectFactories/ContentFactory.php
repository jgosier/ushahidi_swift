<?php
namespace Swiftriver\Core\ObjectModel\ObjectFactories;
class ContentFactory {
    /**
     * Given a source and optional content JSON, this factory method
     * creates a new content item associated with the given source.
     * If JSON is passed in then this is used to build the content, if
     * not, then source is set and a new unique ID is generated.
     * 
     * @param \Swiftriver\Core\ObjectModel\Source $source
     * @param string $json
     * @return \Swiftriver\Core\ObjectModel\Content
     */
    public static function CreateContent($source, $json = null) {
        //If no JSON data passed in then create a new content item
        if($json == null) {
            //Create a new Id
            $id = md5(uniqid(rand(), true));

            //Create the content item
            $content = new \Swiftriver\Core\ObjectModel\Content();

            //Set the id
            $content->id = $id;

            //set the source
            $content->source = $source;

            //Set the state of the content
            $content->state = \Swiftriver\Core\StateTransition\StateController::$defaultState;

            //return the content
            return $content;
        }

        //Else, decode the JSON
        $object = json_decode($json);

        //If there is an error in the josn
        if(!$object || $object == null) {
            //trow an exception
            throw new \Exception("There was an error in the JSON passed to the ContentFactory");
        }

        //Create the content item
        $content = new \Swiftriver\Core\ObjectModel\Content();

        //Set the basic properties
        $content->id = $object->id;
        $content->state = $object->state;
        $content->link = $object->link;
        $content->date = $object->date;

        //Sort out the language specific text
        $languages = $object->text;
        if(isset($languages) && is_array($languages)) {
            foreach($languages as $lang) {
                $content->text[] = new \Swiftriver\Core\ObjectModel\LanguageSpecificText(
                        $lang->languageCode, 
                        $lang->title, 
                        $lang->text);
            }
        }

        //Set the source
        $content->source = $source;

        //Sort out the tags
        $tags = $object->tags;
        if($tags != null && is_array($tags)) {
            foreach($tags as $tag){
                $content->tags[] = new \Swiftriver\Core\ObjectModel\Tag($tag->text, $tag->type);
            }
        }

        //Sort out the difs
        $difCollections = $object->difs;
        if($difCollections != null && is_array($difCollections)) {
            $difCollectionsArray = array();
            foreach($difCollections as $difCollection) {
                $difs = $difCollection->difs;
                if($difs != null && is_array($difs)) {
                    $difArray = array();
                    foreach($difs as $dif) {
                        $difArray[] = new \Swiftriver\Core\ObjectModel\DuplicationIdentificationField($dif->type, $dif->value);
                    }
                    $difCollectionsArray[] = new \Swiftriver\Core\ObjectModel\DuplicationIdentificationFieldCollection($difCollection->name, $difArray);
                }
            }
            $content->difs = $difCollectionsArray;
        }

        //return the contetn
        return $content;
    }
}
?>
