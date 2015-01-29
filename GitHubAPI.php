<?php
/*
 * @author Aakash Sahai
 * @since Jan 19,2015
 * @desc GitHub API
 */

class GitHubAPI {
    private $_apiUrl = 'https://api.github.com/';
    private $_requestUrl = '';
    private $_requestType = 'GET';
    private $_username = NULL;
    private $_password = NULL;
    private $_userAgent = 'aakashsahai13';
    private $_debugMode = FALSE;
    
    CONST REQUEST_POST = 'POST';
    CONST REQUEST_GET = 'GET';
    CONST REQUEST_DELETE = 'DELETE';
    CONST REQUEST_PUT = 'PUT';
    CONST REQUEST_FILE = 'FILE';
    
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
    public function setLoginDetails($username, $password, $ua = '') {
        $this->_username = $username;
        $this->_password = $password;
        $ua != '' ? $this->_userAgent = $ua : '';
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
        
        // GitHub Issue requires 'body' fo issue description
        $data['body'] = isset($data['issueDescription'])  ? $data['issueDescription'] : '';  
        if(isset($data['issueDescription'])) { unset($data['issueDescription']); }
        $this->requestApi(json_encode($data));
    }

    /**
     * @desc Fires a cURL request as per request type & data passed
     * @since Jan 19, 2015
     * @param array $data holds only the data to be posted
     */
    protected function requestApi($data) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_USERPWD, "$this->_username:$this->_password");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, $this->_userAgent);

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
            $this->_requestUrl = 'repos/' . $data['user'] . '/' . $data['repo'] . '/issues';
            $this->_requestType = self::REQUEST_POST;
        } else throw new APIExceptions(APIExceptions::OWNERREPO, APIExceptions::BADREQUEST);
    }
}
?>
