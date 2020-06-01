<?php

declare(strict_types=1);

namespace App\Resources;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read int $width
 * @property-read int $height
 * @property-read int $size
 * @property-read string $content_type
 * @property-read string $path
 *
 * @see File
 */
class FileResource extends JsonResource
{
    /** @var File */
    public $resource;

    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            ['path' => $this->resource->getPath()]
        );
    }
}
