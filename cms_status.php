<?PHP
include "license.php";
$db_const_status = defined("DB_HOST") && defined("DB_USER") && defined("DB_PASS") && defined("DB_NAME");
$admin_const_status = defined("ADMIN_USER") && defined("ADMIN_EMAIL") && defined("ADMIN_PASS");
$cr_const_status = defined("PROJECT_NAME") && defined("PROJECT_VERSION") && defined("PROJECT_DEVELOPERS") &&  defined("PROJECT_SUP") && defined("PROJECT_RELEASE") && defined("PROJECT_SITE") && defined("CONTACT_EMAIL") && defined("COPYRIGHT");

$all_const_status = $db_const_status && $admin_const_status && $cr_const_status;

if(!$all_const_status){
	if($url_arr[0] != "install.php")
		header("Location: ".CMS_DIR."install.php");
}
else{
	$db_const_status2 = strlen(DB_HOST) > 0 && strlen(DB_USER) > 0 && strlen(DB_PASS) > 0 && strlen(DB_NAME) > 0;
	$admin_const_status2 = strlen(ADMIN_USER) > 3 && strlen(ADMIN_PASS) > 3;
	$cr_const_status2 = strlen(PROJECT_NAME) == 10 && strlen(PROJECT_VERSION) > 0 && strlen(PROJECT_DEVELOPERS) > 11 && strlen(PROJECT_SUP) > 0 && strlen(PROJECT_RELEASE) > 7 && strlen(PROJECT_SITE) == 16 && strlen(CONTACT_EMAIL) == 14 && strlen(CR) > 0;
	if(!$db_const_status2 || !$admin_const_status2 || !$cr_const_status2)
		if($url_arr[0] != "install.php")
			header("Location: ".CMS_DIR."install.php");
	
}

?>