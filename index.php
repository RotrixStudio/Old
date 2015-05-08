<?PHP
header("Content-Type: text/html; charset=utf-8");
include "conf.php";
include "dir.php";
include "cms_status.php";

include "modules/data.php";include "modules/actions.php";

include "templates/".CUR_TEMPL."/index.php";

?>