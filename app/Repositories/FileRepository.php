<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Contracts\File\Persistence;
use Illuminate\Http\UploadedFile;
use Throwable;

class FileRepository
{
    private $persistence;

    public function __construct(Persistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param array $attributes
     * @return File
     */
    public function createModel(UploadedFile $file): File
    {
        return new File([
            'width' => '',
            'height' => '',
            'size' => $file->getSize(),
            'content_type' => $file->getMimeType(),
            'path' => $file->path(),
        ]);
    }

    /**
     * @param int $id
     * @return File|null
     */
    public function find(int $id): ?File
    {
        /** @var File|null $result */
        $result = File::query()->find($id);

        return $result;
    }

    /**
     * @param UploadedFile $file
     * @param array $options
     * @return bool
     */
    public function save(UploadedFile $file, array $options = []): File
    {
        return new File();
    }

    /**
     * @param File $entity
     * @return bool
     */
    public function delete(File $entity): bool
    {
        try {
            return $entity->delete();
        } catch (Throwable $exception) {
            return false;
        }
    }
}
