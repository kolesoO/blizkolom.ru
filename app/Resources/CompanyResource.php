<?php

declare(strict_types=1);

namespace App\Resources;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property string $name
 * @property string $code
 * @property bool $active
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $h1
 * @property string $preview_text
 * @property string $detail_text
 * @property int $preview_picture
 * @property int $detail_picture
 * @property string $contacts
 * @property string $url
 * @property string $phone
 * @property string $email
 * @property string $map_coords
 * @property Carbon $open_from
 * @property Carbon $open_to
 * @property int $ranging
 * @property-read int $client_id
 *
 * @see Company
 */
class CompanyResource extends JsonResource
{
    /** @var Company */
    public $resource;

    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'preview_picture' => FileResource::make(
                    $this->resource->preview_picture()->first()
                ),
                'detail_picture' => FileResource::make(
                    $this->resource->detail_picture()->first()
                ),
            ]
        );
    }
}
