<?php

declare(strict_types=1);

namespace App\Service\Managers;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;

class CompanyManager
{
    /** @var CompanyRepository */
    private $companyRepository;

    /** @var FileRepository */
    private $fileRepository;

    /**
     * @param CompanyRepository $companyRepository
     * @param FileRepository $fileRepository
     */
    public function __construct(CompanyRepository $companyRepository, FileRepository $fileRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Company $entity
     * @return bool
     */
    public function save(Company $entity): bool
    {
        //TODO: добавить rollback
        if ($entity->preview_picture instanceof UploadedFile) {
            $file = $this->fileRepository->createFromUploadedFile($entity->preview_picture);
            $file->setUploadedFile($entity->preview_picture);

            if ($this->fileRepository->save($file)) {
                $entity->preview_picture()->associate($file);
                $this->deleteOriginalFile($entity, 'preview_picture');
            }
        }

        if ($entity->detail_picture instanceof UploadedFile) {
            $file = $this->fileRepository->createFromUploadedFile($entity->detail_picture);
            $file->setUploadedFile($entity->detail_picture);

            if ($this->fileRepository->save($file)) {
                $entity->detail_picture = $file->id;
                $this->deleteOriginalFile($entity, 'detail_picture');
            }
        }

        return $this->companyRepository->save($entity);
    }

    /**
     * @param Company $entity
     * @return bool
     */
    public function delete(Company $entity): bool
    {
        if ($this->companyRepository->delete($entity)) {
            $this->deleteOriginalFile($entity, 'preview_picture');
            $this->deleteOriginalFile($entity, 'detail_picture');

            return true;
        }

        return false;
    }

    /**
     * @param Company $entity
     * @param string $key
     * @return bool
     */
    private function deleteOriginalFile(Company $entity, string $key): bool
    {
        $file = $this->fileRepository->find(
            (int) $entity->getOriginal($key)
        );

        if ($file) {
            return $this->fileRepository->delete($file);
        }

        return false;
    }
}
