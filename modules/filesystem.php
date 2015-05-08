<?php
	class FileSystem{	
		function Load($upload_dir,$dest_file_path,$input_name){
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
		function CreateDir($dir_path){
			if(file_exists($dir_path))
				return TRUE;
			$path_parts = split("/",$dir_path);
			$tmp_path = ".";
			for($i=0;$i<count($path_parts);$i++){
				$tmp_path.= "/".$path_parts[$i];
				if(!file_exists($tmp_path) || !is_dir($tmp_path))
					mkdir($tmp_path);
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
?>