<?php
/**
 * The TestParser class is used to facilitate the unit tests
 * in the ParserFactoryTests class
 */
namespace Swiftriver\Core\Modules\SiSPS\Parsers;
class TestParser implements IParser {
    public function GetAndParse($parameters, $lastsucess) {
        $item = new \Swiftriver\Core\ObjectModel\Content();
        $item->id = "testId";
        return array($item);
    }
}
?>
