<?PHP

define("PHP_SELF",$_SERVER['PHP_SELF']);
$last_slash_pos = strrpos(PHP_SELF,"/");
define("CMS_DIR",substr(PHP_SELF,0,$last_slash_pos+1));

$url_arr0 = explode("?", $_SERVER['REQUEST_URI']);
$url = str_replace(CMS_DIR, "", $url_arr0[0]);
$url_arr = explode("/", $url);

define("TEMPL_DIR","templates/".CUR_TEMPL."/");
define("TEMPL_PATH",CMS_DIR.TEMPL_DIR);
define("FILES_DIR","files/".CUR_TEMPL."/");
define("FILES_PATH",CMS_DIR.FILES_DIR);
define("MODULES_DIR","modules/");
define("LIBS_DIR","libs");

// Спец. директории
define("PAGES_DIR",TEMPL_DIR."pages/");
define("PAGES",CMS_DIR.PAGES_DIR);

// Спец. директории для файлов
define("SITEFILES_DIR",FILES_DIR.SITEFILES_DIR_NAME."/");
define("IMAGES_DIR",FILES_DIR.IMAGES_DIR_NAME."/");
define("VIDEOS_DIR",FILES_DIR.VIDEOS_DIR_NAME."/");

define("SITEFILES",CMS_DIR.SITEFILES_DIR);
define("IMAGES",CMS_DIR.IMAGES_DIR);
define("VIDEOS",CMS_DIR.VIDEOS_DIR);

?>