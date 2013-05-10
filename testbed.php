<?PHP
require_once("./databasemanager.php");


$dbmngr = new DatabaseManager("localhost", "php_journal", "root", "braindrain");

$legalUser = $dbmngr->CheckUserExistence("Tom", "password");
$legalUserString = $legalUser ? 'true' : 'false';

print "<h3>Is it a legal user?: $legalUserString</h3>";


?>