<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Swiftriver\Core\Modules\SiSPS\Parsers;
interface IParser{
    /**
     * Given a set of parameters, this method should
     * fetch content from a channel and parse each
     * content into the Swiftriver object model :
     * Content Item. The $lastsucess datetime is passed
     * to the function to ensure that content that has
     * already been parsed is not duplicated.
     *
     * @param array $parameters
     * @param datetime $lastscuess
     * @return Swiftriver\Core\ObjectModel\Content[] contentItems
     */
    public function GetAndParse($parameters, $lastsucess);
}
?>
