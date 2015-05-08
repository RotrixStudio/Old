<?php
	class FileSystem
	{
		

		function DeleteFile($file_path){
			if(file_exists($file_name)  && is_file($dir_path))
			{
				chmod($file_name, 0755);
				return unlink($file_name);
			}
			return TRUE;
			
		}
		
		function CreateDir($dir_path){
			$path_parts = split("/",$dir_path);
			$tmp_path = "";
			for($i=0;$i<count($path_parts);$i++){
				$tmp_path.= "/".$path_parts[$i];
				if(!file_exists($tmp_path))
					mkdir($tmp_path);
			}
		}
		function DeleteDir($dir_path){
			if(file_exists($dir_path)  && is_dir($dir_path)){
				$dirs[] = $dir_path;
				for($i = 0; $i<count($dirs);$i++){
					$current_dir = $dirs[$i];
					$dh = opendir($current_dir);
					while (($file = readdir($dh)) !== false){
						if($file!="." && $file!=".."){
							$path = ($current_dir).'/'.$file;
							chmod($path, 0755);
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
			return TRUE;
			
				
		}
	}
	
	$fs = new FileSystem();
	echo $fs->CreateDir("test_dir/qwe/fds");
?>