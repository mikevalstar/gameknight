<?PHP

class img{

    static function resize_upload($bucket, $category, $name_md5, $source, $sizes, $del_on_complete = true){
        $images = array();
        
        // special case upload original
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $images['orig'] = img::upload($bucket, $source, $category . '/orig/' . $name_md5 . '.' . $ext);
        
        foreach($sizes as $s){
            $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
            $path = strtolower(pathinfo($source, PATHINFO_DIRNAME));
            $dest = $path . '/' . $name_md5 . $s['width'] . $s['height'] . '.' . $ext;
            img::resize($source, $dest, $s['width'], $s['height']);
            
            $images[$s['width'] . 'x' . $s['height']] = img::upload($bucket, $dest, $category . '/' . $s['width'] . 'x' . $s['height'] . '/' . $name_md5 . '.' . $ext);
            unlink($dest);
        }
        
        unlink($source);
        
        return $images;
    }

    static function resize($source, $dest, $size_x, $size_y){
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        list($width, $height) = getimagesize($source);
		$thumb = imagecreatetruecolor($size_x, $size_y);

		switch($ext){
			case 'png':
				$source = imagecreatefrompng($source);
				break;
			case 'jpg':
			case 'jpeg':
				$source = imagecreatefromjpeg($source);
				break;
			case 'gif':
				$source = imagecreatefromgif($source);
				break;
		}
						
		if($width / $size_x > $height / $size_y){
			// center on width
			$src_width = round(($height / $size_y) * $size_x);
			$src_height = $height;
			
			$src_x = round(($width - $src_width)/ 2);
			$src_y = 0;
		}else{
			// center on height
			$src_width = $width;
			$src_height = round(($width / $size_x) * $size_y);
			
			$src_x = 0;
			$src_y = round(($height - $src_height)/ 2);
		}
		
		
		imagecopyresampled ( $thumb,
							$source ,
							0,
							0,
							$src_x ,
							$src_y ,
							$size_x ,
							$size_y ,
							$src_width ,
							$src_height );

		switch($ext){
			case 'png':
				imagepng($thumb, $dest);
				break;
			case 'jpg':
			case 'jpeg':
				imagejpeg($thumb, $dest);
				break;
			case 'gif':
				imagegif($thumb, $dest);
				break;
		}
    }

    static function upload($bucket, $source, $dest, $del_on_complete = true){
        $s3 = new AmazonS3();
        $response = $s3->create_object($bucket, $dest, array( 
            'fileUpload' => $source,
            'acl' => AmazonS3::ACL_PUBLIC,
            'storage' => AmazonS3::STORAGE_REDUCED
        ));
        
        //if(isset($_SESSION['user'])) $_SESSION['user']->msg('warning', 'uploaded: ' . $source . ' to: ' . $dest);
        
        if($edata = $response->isOK()){
            //if(isset($_SESSION['user'])) $_SESSION['user']->msg('success', 'Amazon responded with success. ' . $response->header['x-aws-request-url']);
			return $response->header['x-aws-request-url'];
		}else{
    		if(isset($_SESSION['user'])) $_SESSION['user']->msg('error', 'Amazon responded with error. ' . print_r($response, true));
		}
    }
}