<?php
/*
 * @author Aakash Sahai
 * @since Jan 19,2015
 * @desc Main class for issue posting
 */

function debug($mode, $data, $halt = FALSE) {
    if ($mode) {
        var_dump($data);
        if ($halt)
            die;
    }
}

require_once 'ApiWrapper.php';
require_once 'APIExceptions.php';

class CreateIssueAPI extends ApiWrapper {

    public function __construct($username, $password, $url, $ua = '') {
        $this->_username = $username;
        $this->_password = $password;
        $this->_detectAndSetApi($url, $ua);
    }

    /**
     * @desc raise issue
     * @params array params this array should have issue title, description, repo & owner
     */
    public function raiseIssue(array $params, $debug = FALSE, $debugHalt = FALSE) {
        $this::$_api->setDebugMode($debug, $debugHalt);        
        $this::$_api->createAnIssue($params);
    }
}
?>