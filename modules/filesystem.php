<?php

class FileSystem{
	
	/*Variable*/
	private $full_access = true;
	private $allowed_dir = array();
	private $allowed_file = array();
	private $dened_dir = array();
	private $denied_file = array();
	/*Returns values*/
	const SUCCESS = 0;
	const FAILURE = 1;
	const UNDEFIND_INPUT = 2;
	const OVERSIZED = 3;
	const ACCESS_DENIED = 4;
	
	/*Functions for File*/
	function LoadFile($input_name,$upload_dir,$dest_file_name=null){
		if(!isset($_FILES[$input_name]))
			return FileSystem::UNDEFIND_INPUT;
		if(!is_uploaded_file($_FILES[$input_name]["tmp_name"]))
			return FileSystem::FAILURE;
		
		$extension = substr(strrchr($_FILES[$input_name]['name'], '.'), 1);
		$extension = mb_strtolower($extension);
		$path = "$upload_dir/$dest_file_name";
		if($dest_file_name==null){
			do {
				$dest_file_name = md5(microtime() . rand(0, 9999));
				$path = "$upload_dir/$dest_file_name.$extension";
			} while (file_exists($path));
		}
		$ftmp_name = $_FILES[$input_name]['tmp_name'];
		if(@move_uploaded_file($ftmp_name,$path))
			return $path;
		return FileSystem::FAILURE;
	}
	function CopyFile($source_file_path, $dest_file_path){/*AllowedAccess,AntiWarning*/
		if(!file_exists($source_file_path) || !is_file($source_file_path))
			return FileSystem::FAILURE;
		if(!$this->AllowedAccess($source_file_path))
			return FileSystem::ACCESS_DENIED;
		if(!$this->AllowedAccess($dest_file_path))
			return FileSystem::ACCESS_DENIED;
		if(!@copy($source_file_path, $dest_file_path))
			return FileSystem::FAILURE;
		return FileSystem::SUCCESS;
	}
	function RenameFile($source_file_path, $dest_file_path){/*AllowedAccess,AntiWarning*/
		if(!file_exists($source_file_path))
			return FALSE;
		if(!$this->AllowedAccess($source_file_path))
			return FileSystem::ACCESS_DENIED;
		if(!$this->AllowedAccess($dest_file_path))
			return FileSystem::ACCESS_DENIED;
		if(!@rename($source_file_path,$dest_file_path))
			return FileSystem::FAILURE;
		return FileSystem::SUCCESS;
	}
	function RemoveFile($file_path){/*AllowedAccess,AntiWarning*/
		if(!file_exists($file_path))
			return FileSystem::SUCCESS;
		if(!is_file($file_path))
			return FileSystem::FAILURE;
		if(!$this->AllowedAccess($file_path))
			return FileSystem::ACCESS_DENIED;
		if(!@unlink($file_path))
			return FileSystem::FAILURE;
		return FileSystem::SUCCESS;
	}	
	function StatFile($file_path){
		if(file_exists($file_path)  && is_file($file_path)){
			$info = @stat($file_path);
			if(!$info)
				return FALSE;
			return array(
				"size" => $info["size"],
				"atime" => $info["atime"],
				"mtime" => $info["mtime"],
				"ctime" => $info["ctime"]					
			);
		}
		return NULL;
	}
	/*Functions for Dir*/
	function CreateDir($dir_path){/*AllowedAccess,AntiWarning*/
		if(file_exists($dir_path) && is_dir($dir_path))
			return FileSystem::SUCCESS;
		if(!$this->AllowedAccess($dir_path.'/'))
			return FileSystem::ACCESS_DENIED;
		$path_parts = split("/",$dir_path);
		$tmp_path = "";
		for($i=0;$i<count($path_parts);$i++){
			$tmp_path.= $path_parts[$i];
			//echo $tmp_path."<br>";
			if(!file_exists($tmp_path) || !is_dir($tmp_path))
				if(!@mkdir($tmp_path))
					return FileSystem::FAILURE;
			$tmp_path.="/";
		}
		RETURN FileSystem::SUCCESS;
	}
	function CopyDir($source_dir_path, $dest_dir_path){/*AllowedAccess*/
		if(!file_exists($source_dir_path))
			return FileSystem::FAILURE;
		if(!is_dir($source_dir_path))
			return FileSystem::FAILURE;
		if(!$this->AllowedAccess($source_dir_path.'/'))
			return FileSystem::ACCESS_DENIED;
		if(!$this->AllowedAccess($dest_dir_path.'/'))
			return FileSystem::ACCESS_DENIED;
		$dirs[] = "";
		$this->CreateDir($dest_dir_path);
		for($i = 0; $i<count($dirs);$i++){
			$current_dir = $dirs[$i];
			$dh = opendir($source_dir_path.'/'.$current_dir);
			//i have to make check
			$this->CreateDir($dest_dir_path.$current_dir);
			while (($file = readdir($dh)) !== false){
				if($file!="." && $file!=".."){
					$path = $current_dir.'/'.$file;
					if(is_dir($source_dir_path.'/'.$path))
						$dirs[] = $path;
					else	
						$this->CopyFile($source_dir_path.'/'.$path,$dest_dir_path.'/'.$path);								
				}
			}
			closedir($dh);
		}
		return FileSystem::SUCCESS;
	}	
	function RenameDir($old_dir_path, $new_dir_path){/*AllowedAccess, AntiWarning*/
		if(!file_exists($old_dir_path))
			return FileSystem::FAILURE;
		if(!is_dir($old_dir_path))
			return FileSystem::FAILURE;
		if(!$this->AllowedAccess($old_dir_path.'/'))
			return FileSystem::ACCESS_DENIED;
		if(!$this->AllowedAccess($new_dir_path.'/'))
			return FileSystem::ACCESS_DENIED;
		if(@rename($old_dir_path,$new_dir_path))
			return FileSystem::SUCCESS;
		return FileSystem::FAILURE; 
	}
	function RemoveDir($dir_path){/*AllowedAccess, AntiWarning*/
		if(!file_exists($dir_path))
			return FileSystem::SUCCESS;
		if(!is_dir($dir_path))
			return FileSystem::FAILURE;
		$dirs[] = $dir_path;
		$ret = FileSystem::SUCCESS;
		for($i = 0; $i<count($dirs);$i++){
			$current_dir = $dirs[$i];
			if(!$this->AllowedAccess($current_dir.'/')){
				 $dirs[$i]="";
				 $ret = FileSystem::ACCESS_DENIED;
				 continue;
			}
			$dh = opendir($current_dir);
			while (($file = readdir($dh)) !== false){
				if($file!="." && $file!=".."){
					$path = ($current_dir).'/'.$file;
					if(is_dir($path))
						$dirs[] = $path;
					else{
						if($r=$this->RemoveFile($path)){
							$ret = $r;
						}
					}						
				}
			}
			closedir($dh);
		}
		for($i=count($dirs)-1;$i>=0;$i--)
			@rmdir($dirs[$i]);
		return $ret;
	}
	function ViewDir($dir_path, $tree=false){
		if(!file_exists($dir_path))
			return FileSystem::SUCCESS;
		if(!is_dir($dir_path))
			return FileSystem::FAILURE;
		$res = array();
		$current_dir = $dir_path;
		$dh = opendir($current_dir);
		while (($file = readdir($dh)) !== false){
			if($file!="." && $file!=".."){
				$path = ($current_dir).'/'.$file;
				$res[]=array($path,is_file($path)?1:0);						
			}
		}
		closedir($dh);
		return $res;
	}
	/*Functions for Access*/
	function SetFullAccess(){
		$this->full_access = true;
	}
	function SetPartialAccess($allowed, $denied, $reset = true){
		$this->full_access = false;
		if($reset){
			unset($this->allowed_dir);
			unset($this->allowed_file);
			unset($this->denied_dir);
			unset($this->denied_file);
		}
		for($i=0;$i<count($allowed);$i++){
			/*BackSlash to Slash*/
			if($allowed[$i][strlen($allowed[$i])-1]=='/')
				$this->allowed_dir[]=mb_strtolower($allowed[$i]);
			else
				$this->allowed_file[]=mb_strtolower($allowed[$i]);
		}
		for($i=0;$i<count($denied);$i++){
			/*BackSlash to Slash*/
			if($denied[$i][strlen($denied[$i])-1]=='/')
				$this->denied_dir[]=mb_strtolower($denied[$i]);
			else
				$this->denied_file[]=mb_strtolower($denied[$i]);
		}
		// var_dump($this->allowed_dir);echo "<br>";
		// var_dump($this->allowed_file);echo "<br>";
		// var_dump($this->denied_dir);echo "<br>";
		// var_dump($this->denied_file);echo "<br>";
		
	}
	function AllowedAccess($path){
		if($this->full_access)
			return TRUE;
		$path = mb_strtolower($path);
		
		/*BackSlash to Slash*/
		/*Анигиляция "/dir/../"->"/" */
		
		$is_file = $allowed[$i][strlen($allowed[$i])-1]!='/';
		$allow = false;
		for($i = 0;$i<count($this->allowed_dir);$i++){
			if(!strncmp($path,$this->allowed_dir[$i],strlen($this->allowed_dir[$i]))){
				$allow = true;
				break;
			}
		}
		if(!$allow && $is_file){
			for($i = 0;$i<count($this->allowed_file);$i++){
				if(!strcmp($path,$this->allowed_file[$i])){
					$allow = true;
					break;
				}
			}
		}
		if(!$allow)
			return FALSE;		
		for($i = 0;$i<count($this->denied_dir);$i++){
			if(!strncmp($path,$this->denied_dir[$i],strlen($this->denied_dir[$i]))){
				return FALSE;
			}
		}
		if($is_file)for($i = 0;$i<count($this->denied_file);$i++){
			if(!strcmp($path,$this->denied_file[$i])){
				return FALSE;
			}
		}
		return TRUE;
	}
}
?>