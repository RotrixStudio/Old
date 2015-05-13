<?PHP
class Auth{
	protected static $data;
	function __construct(){
		global $data;
		self::$data = $data;
	}
	function Status(){
		if($_COOKIE[A_KEY]){
			
		}
	}
	function SignIn($vals_arr = null){
		global $keys;
		$keys_arr = &$keys['signin'];
		$keys_arr_len = count($keys_arr);
		if(!count($vals_arr)) $vals_arr = &$_POST;
		$count = 0;
		for($i=0; $i<$keys_arr_len; $i+=6){
			$key[$count] = self::$data->Security($keys_arr[$i]);
			$val[$count] = self::$data->Security($vals_arr[$key[$i]]);
			
			$count++;
		}
		echo $key[0];
		
	}
	function SignUp(){
		
	}
	function SignOut(){
		return setcookie(A_KEY,"", time()-3600,"/",DOMAIN,false,true);
	}
	function GetKey($user_id){
		return md5(md5($user_id).md5(SALT));
	}
	function IsSetUser(){
		
	}
	function AddToHistory(){
		self::$data->InsertInto(SIGN_TBL);
	}
	function SignHistory(){
		
	}
	function Check(){
		
	}
}
?>