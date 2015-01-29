<?php
/*
 * @author Aakash Sahai
 * @since Jan 19,2015
 * @desc BitBucket API
 */

class BitBucketAPI {
    private $_apiUrl = 'https://bitbucket.org/api/1.0/';
    private $_requestUrl = '';
    private $_requestType = 'GET';
    private $_username = NULL;
    private $_password = NULL;
    private $_debugMode = FALSE;
    
    CONST REQUEST_POST = 'POST';
    CONST REQUEST_GET = 'GET';
    CONST REQUEST_DELETE = 'DELETE';
    CONST REQUEST_PUT = 'PUT';
 
    /**
     * @desc set debug mode
     * @since Jan 19, 2015
     * @params <debug mode> BOOL, <debug halt> BOOL
     */
    public function setDebugMode($debugMode = FALSE, $debugHalt = FALSE) {
        $this->_debugMode = $debugMode;
        $this->_debugHalt = $debugHalt;
    }
    
    /**
     * @desc set username/password at API level
     * @since Jan 19, 2015
     * @params string $username, string $password
     */
    public function setLoginDetails($username, $password) {
        $this->_username = $username;
        $this->_password = $password;
    }
    
    /**
     * @desc Create issue
     * @since Jan 19, 2015
     * @param array $data Array holds all issue related data for being generated
     */   
    public function createAnIssue($data) {
        $this->validateLogin();
        $this->validateIssueAndGenerateIssueUrl($data);
        if (isset($data['user']) && isset($data['repo'])) { unset($data['user'], $data['repo']); }
        
        // Bitbucket issue requires 'content' fo issue description
        $data['content'] = isset($data['issueDescription']) ? $data['issueDescription'] : '';  
        if(isset($data['issueDescription'])) { unset($data['issueDescription']); }
        $this->requestApi(http_build_query($data));
    }

    /**
     * @desc Fires a cURL request as per request type & data passed
     * @since Jan 19, 2015
     * @param array $data holds only the data to be posted
     */
    protected function requestApi($data) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "$this->_username:$this->_password");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HEADER, true);

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_bool($value)) $data[$key] = $value ? 'true' : 'false';
            }
        }
        switch ($this->_requestType) {
            case self::REQUEST_POST: {
                curl_setopt($c, CURLOPT_POST, true);
                if (count($data)) curl_setopt($c, CURLOPT_POSTFIELDS, $data);
            } break;
        }
        curl_setopt($c, CURLOPT_URL, $this->_apiUrl . $this->_requestUrl);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($c);
        curl_close($c);

        debug($this->_debugMode, $response, $this->_debugHalt);
    }
    
    /**
     * @desc Validates whether username/password set or not
     */
    protected function validateLogin() {
        if (!($this->_username && $this->_password))
            throw new APIExceptions(APIExceptions::USNPASS, APIExceptions::UNAUTHORISED);
    }
    
    /**
     * @desc Sets request url if valid issue title/owner/repo found. Any of them 
     * missing  leads to exception
     * @param array $data issue details 
     */
    protected function validateIssueAndGenerateIssueUrl($data) {
        if (isset($data['title']) && trim($data['title']) == '') {
            throw new APIExceptions(APIExceptions::TITLE, APIExceptions::BADREQUEST);
        }
        
        if (isset($data['user']) && isset($data['repo'])) { 
            $this->_requestUrl = 'repositories/' . $data['user'] . '/' . $data['repo'] . '/issues/';
            $this->_requestType = self::REQUEST_POST;
        } else throw new APIExceptions(APIExceptions::OWNERREPO, APIExceptions::BADREQUEST);
    }
}
?>
