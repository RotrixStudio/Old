<?PHP
class Actions{
	protected static $data, $res;
	function __construct(){
		global $data, $res;
		self::$data = $data;
		self::$res = &$res;
	}
	function Create($tbl){
		$this->GetPage("_create",$tbl);
	}
	function FirstSaveOne($tbl, $keys_arr){
		$this->GetPage("_firstsaveone",$tbl);
		if(self::$data->InsertInto($tbl,$keys_arr))
			return self::$data->insert_id; // last id
		else return false;
	}
	function EditOne($tbl, $id){
		$this->GetPage("_editone",$tbl);
		return self::$res[$tbl] = self::$data->SelectOne($tbl,$id);
	}
	function EditList($tbl, $limit0, $limit1, $order0, $order1){
		$this->GetPage("_editlist",$tbl);
		return self::$res[$tbl] = self::$data->SelectList("*",$tbl,null,$limit0,$limit1,$order0,$order1);
	}
	function SaveOne($tbl, $id, $keys_arr){
		$this->GetPage("_saveone",$tbl);
		return self::$data->Update($tbl,$id,$keys_arr);
	}
	function Remove($tbl, $id){
		$this->GetPage("_remove",$tbl);
		return self::$data->Delete($tbl,$id);
	}
	function ViewOne($tbl, $id){
		$this->GetPage("_viewone",$tbl);
		return self::$res[$tbl] = self::$data->SelectOne($tbl,$id);
	}
	function ViewList($tbl, $limit0, $limit1, $order0, $order1){
		$this->GetPage("_viewlist",$tbl);
		return self::$res[$tbl] = self::$data->SelectList($select,$tbl,null, $limit0,$limit1,$order0,$order1);
	}
	function Search($tbl, $where_arr, $limit0, $limit1, $order0, $order1){
		$this->GetPage("_search",$tbl);
		return self::$res[$tbl] = self::$data->SelectList($select,$tbl,$where_arr, $limit0,$limit1,$order0,$order1);
	}
	function Relations(){ // Для отношений (вложений)
		
	}
	function GetPage($page_name, $tbl = null){
		global $page, $res;
		$page_tmp = PAGES_DIR.$tbl.$page_name.".php";
		if(file_exists($page_tmp)) $page = $page_tmp;
	}
}
?>