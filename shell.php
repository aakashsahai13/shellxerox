<?php 
require_once('createIssueAPI.php');
function createIssue($params) {
    $chk = FALSE;
    $username = $password = $url = NULL;
    $paramsAPI = array();
    if (isset($params[2]) && strlen($params[2])) {
        $chk = TRUE;
        $username = $params[2];
    } else throw new APIExceptions(APIExceptions::USNPASS, APIExceptions::UNAUTHORISED);
    if (isset($params['3']) && strlen($params['3'])) {
        $chk = TRUE;
        $password = $params[3];
    } else throw new APIExceptions(APIExceptions::USNPASS, APIExceptions::UNAUTHORISED);
    if (isset($params['4']) && strlen($params['4'])) {
        $chk = TRUE;
        $url = $params[4];
    } else throw new APIExceptions(APIExceptions::URL, APIExceptions::FORBIDDEN);
    // proceed only if above details exist
    if ($chk) {
        if (isset($params['5']) && strlen($params['5'])) {
            $paramsAPI['title'] = $params[5];
            $paramsAPI['issueDescription'] = $params[6];
        } else throw new APIExceptions(APIExceptions::TITLE, APIExceptions::BADREQUEST);
    } else {
        throw new APIExceptions(APIExceptions::INVALIDREQUEST, APIExceptions::BADREQUEST);
    }
    
    // extracting username & repo name from url
    if (strlen($url)) {
        $urlData = parse_url($url);
        if(isset($urlData['path'])) {
            $pathData = explode('/', $urlData['path']);
            // 0 index will be empty as path always starts from '/'
            if (isset($pathData[1])) {  // owner/user found
                if (isset($pathData[2])) { // check whether repo name exist
                    $paramsAPI['user'] = $pathData[1];
                    $paramsAPI['repo'] = $pathData[2];
                } else throw new APIExceptions(APIExceptions::OWNERREPO, APIExceptions::BADREQUEST);
            }
        } else throw new APIExceptions(APIExceptions::OWNERREPO, APIExceptions::BADREQUEST);
    }
    if ($username && $password && $url) {
        $createIssueObj = new createIssueAPI($username, $password, $url);
    }
    $createIssueObj->raiseIssue($paramsAPI);
}


$argv[1]($argv);
// create-issue -u jdoe -p secret https://github.com/example/test "The title of my issue" "Here's what I did to reproduce the problem"
?>
