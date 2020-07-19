<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\File;
use App\Service\Persistence\File\ServerPersistence;
use Illuminate\Http\UploadedFile;
use Throwable;

class FileRepository
{
    /** @var ServerPersistence */
    private $persistence;

    public function __construct(ServerPersistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param UploadedFile $file
     * @return File
     */
    public function createFromUploadedFile(UploadedFile $file): File
    {
        return new File([
            'width' => null,
            'height' => null,
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
     * @param File $file
     * @param array $options
     * @return bool
     */
    public function save(File $file, array $options = []): bool
    {
        $uploadedFile = $this->persistence->persist(
            $file->getUploadedFile()
        );
        $file->path = $this->persistence->getRelativePath($uploadedFile);

        if (!$file->save($options)) {
            $this->persistence->delete($uploadedFile);

            return false;
        }

        return true;
    }

    /**
     * @param File $entity
     * @return bool
     */
    public function delete(File $entity): bool
    {
        try {
            $file = $this->persistence->retrieve($entity->path);

            if ($entity->delete()) {
                $this->persistence->delete($file);

                return true;
            }

            return false;
        } catch (Throwable $exception) {
            return false;
        }
    }
}
