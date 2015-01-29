<?php
/**
 * @desc ApiWrapper provides common channel access to both api &
 *       automatically detects which API to use. 
 * @author Aakash Sahai
 * @since Jan 25, 2015
 */
class ApiWrapper {
    static $_api = NULL;        // static API variable
    protected $_username = NULL;
    protected $_password = NULL;

    /**
     * @desc Detects the API from the url, and set _api variable to that API's object.
     *       Set API username password too.
     * @author Aakash Sahai
     * @since Jan 25, 2015
     */
    protected function _detectAndSetApi($url, $ua) {
        $urlData = parse_url($url);
        switch ($urlData['host']) {
            case 'www.github.com' :
            case 'github.com' : {
                    require_once 'GitHubAPI.php';
                    self::$_api = new GitHubAPI();
                    self::$_api->setLoginDetails($this->_username, $this->_password, $ua);
                } break;
            case 'bitbucket.org' :
            case 'www.bitbucket.org' : {
                    require_once 'BitBucketAPI.php';
                    self::$_api = new BitBucketAPI();
                    self::$_api->setLoginDetails($this->_username, $this->_password);
                } break;
            default : {
                    throw new Exception('Sorry, API not present for this service');
                } break;
        }
    }
}
