<?php

declare(strict_types=1);

namespace App\DTO\Statistic;

use App\Models\Company;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class StatisticList implements Arrayable
{
    /** @var Collection */
    private $dates;

    /** @var Company|null */
    private $company;

    /** @var Collection|SingleStatistic[] */
    private $statistic;

    public function __construct()
    {
        $this->dates = Collection::make([]);
        $this->statistic = Collection::make([]);
    }

    /**
     * @return Collection
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @return Collection|SingleStatistic[]
     */
    public function getStatistic(): Collection
    {
        return $this->statistic;
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
     * @param Company $company
     * @return $this
     */
    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @param SingleStatistic $statistic
     * @return $this
     */
    public function addStatistic(SingleStatistic $statistic): self
    {
        $this->statistic->push($statistic);

        return $this;
    }

    /**
     * @return SingleStatistic|null
     */
    public function getFirstStatistic(): ?SingleStatistic
    {
        /** @var SingleStatistic|null $result */
        $result = $this->statistic->first();

        return $result;
    }

    /** @inheritDoc */
    public function toArray()
    {
        $company = !is_null($this->company)
            ? ['id' => $this->company->id, 'name' => $this->company->name]
            : null;

        return [
            'dates' => $this->dates->toArray(),
            'statistic' => $this->statistic->keyBy('type'),
            'company' => $company,
        ];
    }
}
