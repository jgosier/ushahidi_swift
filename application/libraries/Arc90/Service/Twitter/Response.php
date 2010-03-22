<?php

/**
 * LICENSE
 *
 * This source file is subject to the new BSD license bundled with this package
 * in the file, LICENSE. This license is also available through the web at:
 * {@link http://www.opensource.org/licenses/bsd-license.php}. If you did not
 * receive a copy of the license, and are unable to obtain it through the web,
 * please send an email to matt@mattwilliamsnyc.com, and I will send you a copy.
 *
 * @category   Arc90
 * @package    Arc90_Service
 * @subpackage Twitter
 * @author     Matt Williams <matt@mattwilliamsnyc.com>
 * @copyright  Copyright (c) 2008 {@link http://arc90.com Arc90 Inc.}, Matt Williams
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @version    $Id: Response.php 47 2009-02-19 19:52:03Z mattwilliamsnyc $
 */

/**
 * @see Arc90_Service_Twitter_Exception
 */
require_once APPPATH.'libraries/Arc90/Service/Twitter/Exception.php';

/**
 * Arc90_Service_Twitter_Response represents a response to a {@link http://twitter.com Twitter} API call.
 *
 * In addition to the $_data property (accessible via $response->data or $response->getData()), an
 * Arc90_Service_Twitter_Response provides the following read-only attributes related to the original HTTP request:
 *
 * <ul>
 *   <li>url</li>
 *   <li>content_type</li>
 *   <li>http_code</li>
 *   <li>header_size</li>
 *   <li>request_size</li>
 *   <li>filetime</li>
 *   <li>ssl_verify_result</li>
 *   <li>redirect_count</li>
 *   <li>total_time</li>
 *   <li>namelookup_time</li>
 *   <li>connect_time</li>
 *   <li>pretransfer_time</li>
 *   <li>size_upload</li>
 *   <li>size_download</li>
 *   <li>speed_download</li>
 *   <li>speed_upload</li>
 *   <li>download_content_length</li>
 *   <li>upload_content_length</li>
 *   <li>starttransfer_time</li>
 *   <li>redirect_time</li>
 * </ul>
 *
 * @category   Arc90
 * @package    Arc90_Service
 * @subpackage Twitter
 * @author     Matt Williams <matt@mattwilliamsnyc.com>
 * @copyright  Copyright (c) 2008 {@link http://arc90.com Arc90 Inc.}, Matt Williams
 */
class Arc90_Service_Twitter_Response
{
    /**
     * Metadata related to the HTTP response collected by cURL
     *
     * @var array
     */
    protected $_metadata = array();

    /**
     * Response body (if any) returned by Twitter
     *
     * @var string
     */
    protected $_data = '';

    /**
     * Creates a new Arc90_Service_Twitter_Response object.
     *
     * @param array  $meta HTTP {@link http://us3.php.net/curl_getinfo curl_getinfo() metadata}
     * @param string $data Response body (if any) returned by Twitter
     */
    public function __construct($data, array $meta)
    {
        $this->_data     = $data;
        $this->_metadata = $meta;
    }

    /**
     * Overloads retrieval of object properties to allow read-only access.
     *
     * @param  string $name Name of the property to be accessed
     *
     * @return mixed
     */
    public function __get($name)
    {
        if('data' == $name)
        {
            return $this->_data;
        }
        else if(isset($this->_metadata[$name]))
        {
            return $this->_metadata[$name];
        }
        else
        {
            trigger_error(sprintf('Trying to access non-existant property "%s"', $name), E_USER_WARNING);

            return NULL;
        }
    }

    /**
     * Overloads checking for existence of object properties to allow read-only access.
     *
     * @param string $name Name of the property being checked
     *
     * @return boolean
     */
    public function __isset($name)
    {
        if('data' == $name)
        {
            return isset($this->_data);
        }
        else
        {
            return isset($this->_metadata[$name]);
        }
    }

    /**
     * Allows casting of this response object to a string; returns raw response data in native format.
     *
     * @return string
     */
    public function __toString()
    {
        return is_string($this->_data) ? $this->_data : '';
    }

    /**
     * Returns the content body (if any) returned by Twitter.
     *
     * @return string
     */
    public function getData()
    {
        return is_string($this->_data) ? $this->_data : '';
    }

    /**
     * Checks the HTTP status code of the response for 4xx or 5xx class errors.
     *
     * @return boolean
     */
    public function isError()
    {
        return (4 == ($type = floor($this->_metadata['http_code'] / 100)) || 5 == $type);
    }

    /**
     * Returns response data (and metadata) as an associative array.
     *
     * @return array
     */
    public function toArray()
    {
        $array         = $this->_metadata;
        $array['data'] = $this->_data;

        return $array;
    }
}
