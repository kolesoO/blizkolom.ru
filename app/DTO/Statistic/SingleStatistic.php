<?php

declare(strict_types=1);

namespace App\DTO\Statistic;

use App\Models\Company;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class SingleStatistic implements Arrayable
{
    /** @var Collection */
    private $dates;

    /** @var string|null */
    private $label;

    /** @var array|null */
    private $values;

    /** @var Company|null */
    private $company;

    /** @var string|null */
    public $type;

    public function __construct()
    {
        $this->dates = Collection::make([]);
    }

    /**
     * @return Collection
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return array|null
     */
    public function getValues(): ?array
    {
        return $this->values;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param Collection $dates
     * @return $this
     */
    public function setDates(Collection $dates): self
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param Company $company
     * @return $this
     */
    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /** @inheritDoc */
    public function toArray()
    {
        $company = !is_null($this->company)
            ? ['id' => $this->company->id, 'name' => $this->company->name]
            : null;

        return [
            'type' => $this->type,
            'label' => $this->label,
            'dates' => $this->dates->toArray(),
            'values' => $this->values,
            'company' => $company,
        ];
    }
}
