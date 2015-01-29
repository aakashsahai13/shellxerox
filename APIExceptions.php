<?php 
class APIExceptions extends Exception {
    
    public function __construct($message, $code, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }
    
    const BADREQUEST = 400;
    const FORBIDDEN = 403;
    const UNAUTHORISED = 401;
    const NOTFOUND = 404;
    
    const USNPASS = 'USERNAME/PASSWORD NOT FOUND';
    const URL = 'URL NOT FOUND';
    const TITLE = 'TITLE NOT FOUND';
    const OWNERREPO = 'OWNER/REPO NOT FOUND';
    const INVALIDREQUEST = 'REQUEST NOT FOUND';
}
?>