<?php

declare(strict_types=1);

namespace App\Service\Persistence\File;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class ServerPersistence
{
    /** @var string */
    private $documentRoot = '/Users/alekseykolesnichenko/Sites/blizkolom.local/public';

    /** @var string */
    private $directoryPath = '/img';

    /** @var string */
    private static $hostAddress = 'http://blizkolom.local';

    /**
     * @param SymfonyFile $file
     * @return string
     */
    public function getRelativePath(SymfonyFile $file): string
    {
        return str_replace(
            $this->documentRoot,
            '',
            $file->getRealPath()
        );
    }

    /**
     * @param string $path
     * @return string
     */
    public static function getHostPath(string $path): string
    {
        return self::$hostAddress . $path;
    }

    /**
     * @param UploadedFile $file
     * @return SymfonyFile
     */
    public function persist(UploadedFile $file): SymfonyFile
    {
        return $file->move(
            $this->documentRoot . $this->directoryPath,
            $file->getClientOriginalName()
        );
    }

    /**
     * @param string $path
     * @return SymfonyFile
     */
    public function retrieve(string $path): SymfonyFile
    {
        return new SymfonyFile($this->documentRoot . $path);
    }

    /**
     * @param SymfonyFile $file
     * @return bool
     */
    public function delete(SymfonyFile $file): bool
    {
        return unlink($file->getRealPath());
    }
}
