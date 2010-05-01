<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Content Curation Controller
 *
 * PHP version 5.3
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author      Ushahidi Team <team@ushahidi.com>
 * @package     Ushahidi - http://source.ushahididev.com
 * @module	Feed Controller
 * @copyright   Ushahidi - http://www.ushahidi.com
 * @license     http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 *
 */
class ContentCuration_Controller extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Communicates with the Swiftriver_Core API to mark the
     * source of a piece of content as acurate.
     * 
     * @param string $contentId
     * @param string $markerId 
     */
    public function markasaccurate($contentId, $markerId) {
        $coreFolder = DOCROOT . "/../Core/";
        $coreSetupFile = $coreFolder."Setup.php";
        include_once($coreSetupFile);
        $workflowData = json_encode(array("id" => $contentId, "markerId" => $markerId));
        $workflow = new Swiftriver\Core\Workflows\ContentServices\MarkContentAsAcurate();
        $workflow->RunWorkflow($workflowData, "swiftriver_apala");
        
        //MG TODO: Change this to persist category and other nav choices
        //MG It'll do for now though :-)
        url::redirect(url::base());
    }

    /**
     * Communicates with the Swiftriver_Core API to mark the
     * source of a piece of content as inacurate - providing a
     * reason.
     *
     * @param string $contentId
     * @param string (falsehood|inaccuracy|biased) $reason
     * @param string $markerId
     */
    public function markasinaccurate($contentId, $reason, $markerId) {
        $coreFolder = DOCROOT . "/../Core/";
        $coreSetupFile = $coreFolder."Setup.php";
        include_once($coreSetupFile);
        $workflowData = json_encode(array("id" => $contentId, "reason" => $reason, "markerId" => $markerId));
        $workflow = new \Swiftriver\Core\Workflows\ContentServices\MarkContentAsInacurate();
        $workflow->RunWorkflow($workflowData, "swiftriver_apala");

        //MG TODO: Change this to persist category and other nav choices
        //MG It'll do for now though :-)
        url::redirect(url::base());
    }

    /**
     * Communicates with the Swiftriver_Core API to mark the
     * source of a piece of content as cross talk / chatter.
     *
     * @param string $contentId
     * @param string $markerId
     */
    public function markascrosstalk($contentId, $markerId) {
        $coreFolder = DOCROOT . "/../Core/";
        $coreSetupFile = $coreFolder."Setup.php";
        include_once($coreSetupFile);
        $workflowData = json_encode(array("id" => $contentId, "markerId" => $markerId));
        $workflow = new Swiftriver\Core\Workflows\ContentServices\MarkContentAsChatter();
        $workflow->RunWorkflow($workflowData, "swiftriver_apala");

        //MG TODO: Change this to persist category and other nav choices
        //MG It'll do for now though :-)
        url::redirect(url::base());
    }
}