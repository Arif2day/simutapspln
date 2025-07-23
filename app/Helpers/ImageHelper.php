<?php

namespace App\Helpers;


use Sentinel;
use Storage;
use Image;

class ImageHelper {

	public static function uploadImage($imageData){
		$img = $imageData->image;
			if($img != "") {
				$path = 'uploads/'.$imageData->path;

				$generateName = uniqid($imageData->uniqid);
				$storageDisk = $imageData->uniqid;
				$destinationPath = public_path().$path;

				$ext = self::getMIMETYPE($img);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);

				$data = base64_decode($img);
				$fileName = $generateName.'.'.$ext;
				$imgdata = Storage::disk($storageDisk)->put($fileName, $data);
				// $originalName = $generateName.'.'.$ext;
		        // $thumbName = $generateName.'thumb'.'.'.$ext;				
		        // $upload1 = self::saveImageThumb(file_get_contents($destinationPath.'/'.$fileName), $thumbName, $path);
		        if($imgdata){
		        	// return $path.$thumbName;
		        	return $path.$fileName;
		        }
			}
		return 0;
	}

	public static function uploadPDF($pdfData){
		$pdf = $pdfData->pdf;
			if($pdf != "") {
				$path = 'uploads/'.$pdfData->path;

				$generateName = uniqid($pdfData->uniqid);
				$storageDisk = $pdfData->uniqid;
				$destinationPath = public_path().$path;

				if (str_starts_with($pdf, 'data:application/pdf')) {
					$base64Data = explode(',', $pdf)[1];
					$decodedData = base64_decode($base64Data);
				}

				$fileName = $generateName.'.'.'pdf';
				$pdfdata = Storage::disk($storageDisk)->put($fileName, $decodedData);
				// $originalName = $generateName.'.'.$ext;
		        // $thumbName = $generateName.'thumb'.'.'.$ext;				
		        // $upload1 = self::saveImageThumb(file_get_contents($destinationPath.'/'.$fileName), $thumbName, $path);
		        if($pdfdata){
		        	// return $path.$thumbName;
		        	return $path.$fileName;
		        }
			}
		return 0;
	}


	static function getMIMETYPE($uri){
	    $img = explode(',', $uri);
		$ini =substr($img[0], 11);
		$type = explode(';', $ini);
		return $type[0]; // result png
	}

	static function saveImage($photo,$filename, $pt){
        $path = public_path().'/uploads/'.$pt.'/';
        $image = Image::make($photo)->save($path.$filename);
        return $image;
    }

    static function saveImageThumb($photo,$filename,$pt){
        $path = public_path().'/uploads/'.$pt.'/';
        $image = Image::make($photo)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
            })
        ->save($path. $filename);
        return $image;
    }
 
    static function getBytesFromHexString($hexdata)
	{
	  for($count = 0; $count < strlen($hexdata); $count+=2)
	    $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

	  return implode($bytes);
	}

	static function getImageMimeType($imagedata)
	{
	  $imagemimetypes = array(
	    "jpeg" => "FFD8",
	    "png" => "89504E470D0A1A0A",
	    "gif" => "474946",
	    "bmp" => "424D",
	    "tiff" => "4949",
	    "tiff" => "4D4D"
	  );

	  foreach ($imagemimetypes as $mime => $hexbytes)
	  {
	    $bytes = getBytesFromHexString($hexbytes);
	    if (substr($imagedata, 0, strlen($bytes)) == $bytes)
	      return $mime;
	  }

	  return NULL;
	}

}