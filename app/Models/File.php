<?php

namespace App\Models;

use Carbon\Traits\Date;
use Illuminate\Http\UploadedFile;

/**
 * Class File
 *
 * @property    string  $id
 * @property    Date  $created_at
 * @property    Date  $updated_at
 * @property    int  $width
 * @property    int  $height
 * @property    int  $size
 * @property    string  $content_type
 * @property    string  $path
 * @package App\Models
 */
class File extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'created_at', 'updated_at', 'width', 'height', 'size', 'content_type', 'path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected static $available = [
        'id', 'created_at', 'updated_at', 'width', 'height', 'size', 'content_type', 'path'
    ];

    /**
     * @var string
     */
    protected $table = "files";

    /** @var UploadedFile */
    protected $uploadedFile;

    /**
     * @var string
     */
    protected static $remoteDomain = "https://static.blizkolom.ru";

    /**
     * @param string $value
     * @return string
     */
    public static function withRemoteDomain(string $value): string
    {
        return self::$remoteDomain.$value;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setUploadedFile(UploadedFile $file): self
    {
        $this->uploadedFile = $file;

        return $this;
    }
}
