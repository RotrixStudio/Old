<?PHP

define("DB_HOST","localhost");
define("DB_USER","user");
define("DB_PASS","qwerty");
define("DB_NAME","rcms");

define("ADMIN_USER","test");
define("ADMIN_EMAIL","evasyakin@mail.ru");
define("ADMIN_PASS","1234");


define("CUR_TEMPL","test");

define("SALT","123"); // Соль
define("HTML_CAN_USE","h1,h2,h3,h4,h5,h6,p,a,b,i,span,table,tr,td,th,ul,ol,li");
define("A_KEY","A_KEY"); // 

//
$keys = Array();
$keys['signin'] = Array("user",0,16,"length error","регулярка","error2","pass",0,16,"length error","регулярка","error2");


$keys['blog_categories'] = Array("name");
$keys['blog_articles'] = Array("title","category_id","tags","descr","content");
$keys['blog_articles_select'] = "id,title,tags,descr";
$keys['blog_comments'] = Array("content");

$keys['shop_categories'] = Array("name");
$keys['shop_items'] = Array("title","tags","descr");
$keys['shop_comments'] = Array("content");
//
define("BLOG_Ca","blog_categories");
define("BLOG_A","blog_articles");
define("BLOG_C","blog_comments");
//


// Макс. размеры загружаемых файлов в Мегабайтах
define("MAX_IMAGE_SIZE",4);
define("MAX_VIDEO_SIZE",512);
define("MAX_TXT_SIZE",4);
define("MAX_FILE_SIZE",16);

// Директори для хранения спец. файлов
define("SITEFILES_DIR_NAME","site");
define("IMAGES_DIR_NAME","images");
define("VIDEOS_DIR_NAME","videos");
define("DOCS_DIR_NAME","docs"); // Для текстовых док-тов

?>