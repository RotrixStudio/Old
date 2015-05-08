<?PHP
$data = new Data(DB_HOST,DB_USER,DB_PASS);
$data->Init(DB_NAME);
// $data->InsertInto(BLOG_A,$keys[BLOG_A]);
// $data->Update(BLOG_A,1,$keys[BLOG_A]);
// $data->Delete(BLOG_A,1);
// $data->SelectOne(BLOG_A,1);
// $data->SelectList("title,descr",BLOG_A,Array("id"=>1),0,20,"id",1);

$actions = new Actions();
$actions->Create(BLOG_A);
$a = $actions->ViewOne(BLOG_A,1);

var_dump($res[BLOG_A]);
echo "<br>";
var_dump($a);
echo "<br>";
if($page)include $page;
?>