<?PHP
require_once("./databasemanager.php");


$dbmngr = new DatabaseManager("localhost", "php_journal", "root", "braindrain");

// $legalUser = $dbmngr->CheckUserExistence("Tom", "password");
// $legalUserString = $legalUser ? 'true' : 'false';

// print "<h3>Is it a legal user?: $legalUserString</h3>";


echo date('n-d-o g:i:sA');

$result = $dbmngr->RegisterUser('augustus', 'password');
$success = $result->GetSuccessFlag() ? 'true' : 'false';
$reason = $result->GetReason();

print "<h3>Registed correctly: $success - $reason</h3>";

$result2 = $dbmngr->CheckUserExistence('bill');
$success2 = $result2->GetSuccessFlag() ? 'true' : 'false';
$reason2 = $result2->GetReason();

print "<h3>bill already exists: $success2 - $reason2</h3>";


?>