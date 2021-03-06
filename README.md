# shellxerox

Single API for posting issue to GitHub and BitBucket.

This API has currently one function createIssue which expects few inputs for e.g. username, password, url-with-owner-and-repo[most important], issue title & issue description(optional). Username/Password required for authentication, whereas url is most important as it decides which API[github/bitbucket] connection to use for further processing. Issue title is mandatory. Lets jump into the code.

To post an issue, use:

``$ shell.php createIssue username password "url/to/repository/with/owner/and/reponame" "issue title" "issue description"``

#### Syntax & Example [createIssue]:

``$ shell.php createIssue username password "https://github.com/testowner/testrepo/" "issue title" "issue description"``


``$ shell.php createIssue username password "https://bitbucket.org/testowner/testrepo/" "issue title" "issue description"``

Here shell.php potrays usage of CreateIssueAPI. CreateIssueAPI requires username/password when instantiated. After which ``raiseIssue($data)`` post the issue.


## ``CreateIssueAPI->raiseIssue()`` : 
This method expects an array with `title`,`user`,`repo`,`issueDescription<optional>` as keys for rasing an issue in respective REPO SERVICE.

APIWrapper smartly detects which API to use from the URL passed. Ordering of parameters as shown above is very much important. User can extend CreateIssueAPI, or add new functions to it and calling them straight forwardly, in very similar manner shown above.
