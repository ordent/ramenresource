<?php

namespace Ordent\RamenResource\FileUpload;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic as Image;

class ResizeImage
{
    protected $width;
    protected $height;

    public function __construct(){

        $this->width = config('fileUpload.image.width');
        $this->height = config('fileUpload.image.height');
    }

    //set width
    public function setWidth(string $width){
        $this->width = $width;
        return $this;
    }

    //set height
    public function setHeight(string $height){
        $this->height = $height;
        return $this;
    }

    //resize $image
    public function __invoke(UploadedFile $file){

        //create image object from $file, resize, save, and return it
        return Image::make($file)
            ->resize($this->width, $this->height)
            ->save();
    }
}