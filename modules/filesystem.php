<?php
class FileSystem{
	/*Return's values, may be for future*/
	const SUCCESS = 0;
	const FAILURE = 1;
	const UNDEFIND_INPUT = 1;
	
	
	function __construct(){
		//
	}
	/*Functions for File*/
	function LoadFile($input_name,$upload_dir,$dest_file_name=null){
		if(!isset($_FILES[$input_name]))
			return self::UNDEFIND_INPUT;
		if(!is_uploaded_file($_FILES[$input_name]["tmp_name"]))
			return FALSE;
		
		$extension = substr(strrchr($_FILES[$input_name]['name'], '.'), 1);
		$extension = mb_strtolower($extension);
		$path = "$upload_dir/$dest_file_name";
		if($dest_file_name==null){
			do {
				$dest_file_name = md5(microtime() . rand(0, 9999));
				$path = "$upload_dir/$dest_file_name.$extension";
			} while (file_exists($path));
		}
		else{
			//
		}
		
		$ftmp_name = $_FILES[$input_name]['tmp_name'];
		echo "$ftmp_name<br>";
	
		if(move_uploaded_file($ftmp_name,$path)){
			return $path;
		}
		else
		{
			return "Печаль<br>";
		}
		return FALSE;
	}
	function CopyFile($source_file_path, $dest_file_path){
		if(!file_exists($source_file_path) || !is_file($source_file_path))
			return FALSE;
		return copy($source_file_path, $dest_file_path);
	}
	function RenameFile($source_file_path, $dest_file_path){
		if(!file_exists($source_file_path))
			return FALSE;
		return rename($source_file_path,$dest_file_path); 
	}
	function RemoveFile($file_path){
		if(!file_exists($file_path))
			return TRUE;
		if(!is_file($file_path))
			return FALSE;
		return unlink($file_path);
	}	
	function StatFile($file_path){
		if(file_exists($file_path)  && is_file($file_path)){
			$info = stat($file_path);
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
	function CreateDir($dir_path){
		if(file_exists($dir_path) && is_dir($dir_path))
			return 2;
		$path_parts = split("/",$dir_path);
		$tmp_path = "";
		for($i=0;$i<count($path_parts);$i++){
			$tmp_path.= $path_parts[$i];
			echo $tmp_path."<br>";
			if(!file_exists($tmp_path) || !is_dir($tmp_path))
				mkdir($tmp_path);
			$tmp_path.="/";
		}
		RETURN TRUE;
	}
	function CopyDir($source_dir_path, $dest_dir_path){
		if(!file_exists($source_dir_path))
			return FALSE;
		if(!is_dir($source_dir_path))
			return FALSE;
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
		return TRUE;
	}	
	function RenameDir($old_dir_path, $new_dir_path){
		if(!file_exists($source_dir_path))
			return FALSE;
		if(!is_dir($source_dir_path))
			return FALSE;
		return rename($old_dir_path,$new_dir_path); 
	}
	function RemoveDir($dir_path){
		if(!file_exists($dir_path))
			return TRUE;
		if(!is_dir($dir_path))
			return FALSE;
		$dirs[] = $dir_path;
		for($i = 0; $i<count($dirs);$i++){
			$current_dir = $dirs[$i];
			$dh = opendir($current_dir);
			while (($file = readdir($dh)) !== false){
				if($file!="." && $file!=".."){
					$path = ($current_dir).'/'.$file;
					//chmod($path, 0755);
					if(is_dir($path))
						$dirs[] = $path;
					else
						unlink($path);						
				}
			}
			closedir($dh);
		}
		for($i = count($dirs)-1; $i>=0;$i--)
			rmdir($dirs[$i]);
		return TRUE;
	}
}

define("CMS_DIR","C:\AppServ\www");

//echo $fs->RemoveDir(CMS_DIR."upload")."<br>";
//echo $fs->CreateDir(CMS_DIR."/upload")."<br>";

$fs = new FileSystem();
switch($fs->LoadFile("upload_file",CMS_DIR."/upload/"))!=FileSystem::SUCCESS)
{
	FileSystem::UNDEFIND_INPUT:
		break;
	FileSystem::BIG_SIZE:
		break;
}
//echo FileSystem::SUCCESS;
?>