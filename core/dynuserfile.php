<?php

    $StoragePath = dirname(__FILE__);
	$StorageDefaultDir = "uploads"; 
	
	function File_PrepareFullname($Dir, $Name, $AutoCreateFolder = true)
	{			
		$Fullpath = rtrim( $Dir, '/');
	
		if ( $AutoCreateFolder )
		{
			if (! is_dir($Fullpath))
				if (! mkdir($Fullpath, 0777, true))
					return null;
		}
		
		return $Fullpath . "/" . $Name;
	}

	function Get_UploadedFiles($UploadName, $AddInfo = true)
	{
		$Res = array();
		if ( isset( $_FILES[$UploadName] ) )
		{
			$FInfo = $_FILES[$UploadName];
			if (empty($FInfo))
				return null;
			if (is_array($FInfo['name']))
			{			
				foreach ($FInfo['name'] as $Key => $Name) 
				{					
					if (($FInfo['error'][$Key] == UPLOAD_ERR_OK)  &&
						($FInfo['name'][$Key] != ''))
					{
						$Res[] = array(
							"name" => $FInfo['name'][$Key],
							"key" => $Key,
							"size" => $FInfo['size'][$Key],
							"error" => $FInfo['error'][$Key],
                            "src" => $FInfo['tmp_name'][$Key],
							"tmp_name" => $FInfo['tmp_name'][$Key] 
							);
							
					}
				}
			}
			else
			{
				if ($FInfo['error'] == UPLOAD_ERR_OK)
					$Res = array( $FInfo );
					
			}
		}
		
		if (count($Res) > 0)
		{
			foreach($Res as &$R)
			{
				$R['filename'] = (strpos($R['name'], '.') !== false) ? strstr( $R['name'], '.', true ) : $R['name'];
				$R['ext'] = pathinfo($R['name'], PATHINFO_EXTENSION  );	
				
				if ( $AddInfo )
				{
					$FInfo = @getimagesize($R['tmp_name']);
					if ($FInfo)
					{	
						$R['image'] = true;
						$R['mime'] = $FInfo['mime'];
						$R['width'] = $FInfo[0];
						$R['height'] = $FInfo[1];
						$R['imagetype'] = $FInfo[2];
					}
				}
			}
		}
		
		return $Res;
	}
	
	function Save_UploadedFiles($Files, $BaseName = null, $AddExt = true)
	{  	global $StorageDefaultDir;
        
		$res = array();
		if ( is_array($Files) )
		{
            $index = 1;
			foreach ($Files as $FInfo)
			{
				 if ($FInfo['error'] == UPLOAD_ERR_OK) 
				 {	                    
                    if ($BaseName != null) 
                    {
                        if ($index == 1)
                            $Name = "{$BaseName}.{$FInfo['ext']}";
                        else
                            $Name = "{$BaseName}-{$index}.{$FInfo['ext']}";
                    }
                    else
                        $Name = $FInfo['name'];
                     
					$Fullname = File_PrepareFullname( $StorageDefaultDir , $Name);
					if ($Fullname != null)
					{
						if ( move_uploaded_file($FInfo['tmp_name'] , $Fullname) );
							$res[] = $Fullname;
					}
                    $index++;
				}
			}
		}
		return $res;
	}
	

?>