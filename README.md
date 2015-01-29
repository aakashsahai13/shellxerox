# shellxerox

This is a PHP API for issue posting to GitHub/BitBucket.

This API requires basic inputs for e.g. username, password, url-with-owner-and-repo[most important], issue title & issue description(optional). Username/Password required for authentication, whereas url is most important as it decides which API[github/bitbucket] connection to use. Issue title is mandatory. Lets jump into the code.

To directly post issue, use:

$ shell.php createIssue username password "url/to/repository/with/owner/and/reponame" "issue title" "issue description"

# for e.g. 

$ shell.php createIssue username password "https://github.com/aakashsahai13/shellxerox/" "issue title" "issue description"
$ shell.php createIssue username password "https://bitbucket.org/aakashsahai13/shellxerox/" "issue title" "issue description"

Here shell.php potrays usage of CreateIssueAPI. CreateIssueAPI requires username/password while 
