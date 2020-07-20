<?php

declare(strict_types=1);

namespace App\Service\Managers;

use App\Contracts\Service\Statistic\DatesInterface;
use App\DTO\Statistic;
use App\Models\Client;
use App\Models\Company;
use App\Models\Statistic as StatisticModel;
use App\Repositories\CompanyRepository;
use App\Repositories\StatisticRepository;
use App\Service\Statistic\Dates\Factory;
use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use ReflectionException;

class StatisticManager
{
    /** @var StatisticRepository */
    private $statisticRepository;

    /** @var CompanyRepository */
    private $companyRepository;

    /** @var DatesInterface|null  */
    private $statisticDates;

    /**
     * StatisticManager constructor.
     * @param StatisticRepository $statisticRepository
     * @param CompanyRepository $companyRepository
     * @throws ReflectionException
     */
    public function __construct(StatisticRepository $statisticRepository, CompanyRepository $companyRepository)
    {
        $this->statisticRepository = $statisticRepository;
        $this->companyRepository = $companyRepository;
        $this->statisticDates = Factory::create('week');
    }

    /**
     * @param Client $client
     * @return SupportCollection|Statistic\StatisticList[]
     */
    public function allByClient(Client $client): SupportCollection
    {
        $companies = $this->companyRepository->getByClient($client);

        if ($companies->count() == 0) {
            throw (new ModelNotFoundException())
                ->setModel(StatisticRepository::getModelClass());
        }

        $statistic = $this->statisticRepository->getByCompany(
            $companies->pluck('id')
        )
            ->groupBy('company_id');

        if ($statistic->count() == 0) {
            throw (new ModelNotFoundException())
                ->setModel(StatisticRepository::getModelClass());
        }

        $result = SupportCollection::make([]);

        /**
         * @var int $companyId
         * @var Collection $companyStatistic
         */
        foreach ($statistic as $companyId => $companyStatistic) {
            /** @var Company $company */
            $company = $companies->find($companyId);

            /** @var SupportCollection|StatisticModel[] $statisticByType */
            $statisticByType = $companyStatistic->groupBy('type');

            $statisticList = (new Statistic\StatisticList())
                ->setCompany($company);

            foreach ($statisticByType as $type => $statisticByTypeItem) {
                $dates = $this->statisticDates->groupFromList(
                    $statisticByTypeItem->pluck('created_at')
                );
                $statisticList->addStatistic(
                    (new Statistic\SingleStatistic())
                        ->setLabel(
                            trans('statistic.title.' . $type)
                        )
                        ->setDates(
                            $this->statisticDates->getFormatted($dates)
                        )
                        ->setCompany($company)
                        ->setType($type)
                        ->setValues(
                            $this->getValuesByDates($dates, $statisticByTypeItem)
                        )
                );
            }

            $firstStatistic = $statisticList->getFirstStatistic();

            if (!is_null($firstStatistic)) {
                $statisticList->setDates(
                    $firstStatistic->getDates()
                );
            }

            $result->push($statisticList);
        }

        return $result;
    }

    /**
     * @param int $companyId
     * @param string $type
     * @return bool
     */
    public function create(int $companyId, string $type): bool
    {
        $company = $this->companyRepository->find($companyId);

        if (is_null($company)) {
            throw (new ModelNotFoundException())
                ->setModel(CompanyRepository::getModelClass());
        }

        $entity = $this->statisticRepository->create(['type' => $type]);
        $entity->company()->associate($company);

        return $this->statisticRepository->save($entity);
    }

    /**
     * @param SupportCollection|Carbon[] $dates
     * @param SupportCollection|StatisticModel[] $statistic
     * @return array
     */
    private function getValuesByDates(SupportCollection $dates, SupportCollection $statistic): array
    {
        $result = [];

        for ($counter = 0; $counter < $dates->count(); $counter ++) {
            $result[] = $statistic
                ->filter(
                    function (StatisticModel $item) use ($counter, $dates) {
                        $condition = $item->created_at->timestamp >= $dates[$counter]->timestamp;

                        if (isset($dates[$counter + 1]) && $dates[$counter]->timestamp != $dates[$counter + 1]->timestamp) {
                            $condition = $condition && $item->created_at->timestamp < $dates[$counter + 1]->timestamp;
                        }

                        return $condition;
                    })
                ->count();
        }

        return $result;
    }
}
