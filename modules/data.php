<?PHP
class Data extends Mysqli{
	function Init($db_name){
		$db_name = $this->Security($db_name);
		$this->query("USE `$db_name`");
		$this->query("SET NAMES utf-8");
	}
	function InsertInto($tbl, $keys_arr, $vals_arr = null){
		$tbl = $this->Security($tbl);
		$keys_len = count($keys_arr);
		if(!$vals_arr) $vals_arr = &$_POST;
		$key = $this->Security($keys_arr[0]);
		$keys .= $key;
		$vals .= "'".$this->Security($vals_arr[$key])."'";
		for($i=1; $i<$keys_len; $i++){
			$key = $this->Security($keys_arr[$i]);
			$keys .= ",".$key;
			$vals .= ",'".$this->Security($vals_arr[$key])."'";
		}
		// echo "INSERT INTO `$tbl` ($keys) VALUES ($vals)";
		return $this->query("INSERT INTO `$tbl` ($keys) VALUES ($vals)");
	}
	function Update($tbl, $id, $keys_arr, $vals_arr = null){
		$tbl = $this->Security($tbl);
		$id = $this->Security($id);
		$keys_len = count($keys_arr);
		if(!$vals_arr) $vals_arr = &$_POST;
		$key = $this->Security($keys_arr[0]);
		$update .= $key."='".$this->Security($vals_arr[$key])."'";
		for($i=1; $i<$keys_len; $i++){
			$key = $this->Security($keys_arr[$i]);
			$update .= ",".$key."='".$this->Security($vals_arr[$key])."'";
		}
		// echo "UPDATE `$tbl` SET $update WHERE id='$id'";
		return $this->query("UPDATE `$tbl` SET $update WHERE id='$id'");
	}
	function Delete($tbl, $id){
		$tbl = $this->Security($tbl);
		$id = $this->Security($id);
		// echo "DELETE FROM `$tbl` WHERE id='$id'";
		return $this->query("DELETE FROM `$tbl` WHERE id='$id'");
	}
	function SelectOne($tbl, $id){
		$tbl = $this->Security($tbl);
		$id = $this->Security($id);
		// echo "SELECT * FROM `$tbl` WHERE id='$id'";
		$this->query("SELECT * FROM `$tbl` WHERE id='$id'");
	}
	function SelectList($select, $tbl, $where_arr = null, $limit0 = null, $limit1 = null, $order0 = null, $order1 = null){
		$select = $this->Security($select);
		$tbl = $this->Security($tbl);
		if($where_arr && count($where_arr)){
			$where = "WHERE ";
			foreach($where_arr as $key=>$val){
				if($count > 0)
					$where .= " AND ";
				$where .= $this->Security($key)."='".$this->Security($val)."'";
				$count++;
			}
		}
		if($limit0 !== null){
			$limit = "LIMIT ".$this->Security($limit0);
			if(isset($limit1)) $limit .= ", ".$this->Security($limit1);
		}
		if($order0){
			$order = "ORDER BY ".$this->Security($order0);
			if($order1) $order .= " DESC";
		}
		// echo "SELECT $select FROM `$tbl` $where $limit $order";
		return $this->query("SELECT $select FROM `$tbl` $where $limit $order");
	}
	function Security($val){
		$val = rtrim($val);
		$val = strip_tags($val,HTML_CAN_USE);
		$val = stripslashes($val);
		return $this->real_escape_string($val);
	}
}
?>