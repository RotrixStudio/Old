<?PHP
class Data extends Mysqli{
	function Init(){
		$this->query("SET NAMES utf-8");
	}
	function InsertInto($tbl, $keys_arr, $vals_arr = null){
		if($keys_arr){
		if(!$vals_arr) $vals_arr = &$_POST;
		$keys_len = count($keys_arr);
		$key = $this->Security($keys_arr[0]);
		$keys .= $key;
		$vals .= "'".$this->Security($vals_arr[$key])."'";
		for($i=1; $i<$keys_len; $i++){
			$key = $this->Security($keys_arr[$i]);
			$keys .= $key.",";
			$vals .= "'".$this->Security($vals_arr[$key])."',";
		}
		}
		else return false;
	}
}
?>