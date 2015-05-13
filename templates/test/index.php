<?PHP
include "modules/data.php";
include "modules/actions.php";
include "modules/auth.php";


$data = new Data(DB_HOST,DB_USER,DB_PASS);
$data->Init(DB_NAME);
$actions = new Actions();
$auth = new Auth();
$auth->SignIn();

if($page)include $page;
?>