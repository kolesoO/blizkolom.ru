<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap as BaseSitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SiteMap extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * @var string
     */
    protected $description = 'Generate the xml sitemap';

    public function handle(): void
    {
        $indexMap = SitemapIndex::create();

        //info pages
        BaseSitemap::create()
            ->add(
                Url::create('/')
                    ->setLastModificationDate(Carbon::now())
                    ->setPriority(1)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            )
            ->add(
                Url::create('/kak-sdat')
                    ->setLastModificationDate(Carbon::now())
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            )
            ->add(
                Url::create('/netochnost')
                    ->setLastModificationDate(Carbon::now())
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            )
            ->writeToFile(
                public_path('info_pages_sitemap.xml')
            );

        $indexMap
            ->add('/info_pages_sitemap.xml');
        //end

        //regions
        $rootProp =
            Property::query()
            ->whereNull('parent_id')
            ->pluck('id');

        Property::query()
            ->where([
                ['urlable', true],
                ['root_url', true]
            ])
            ->get(['id', 'code'])
            ->each(function (Property $item) use ($rootProp, $indexMap) {
                $mapName = $item->code ? 'region_' . $item->code : 'region_russia';
                $map = BaseSitemap::create();

                if ($item->code) {
                    $map->add(
                            Url::create('/' . $item->code)
                                ->setLastModificationDate(Carbon::now())
                                ->setPriority(1)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        );
                }
                Property::query()
                    ->where([
                        ['urlable', true],
                        ['root_url', false]
                    ])
                    ->whereIn('parent_id', $rootProp)
                    ->get(['id', 'code'])
                    ->each(function (Property $sectionItem) use ($map, $item) {
                        $url = $item->code ? '/' . $item->code : '';
                        $map->add(
                                Url::create($url . '/' . $sectionItem->code)
                                    ->setLastModificationDate(Carbon::now())
                                    ->setPriority(1)
                                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                            );
                        Property::query()
                            ->where([
                                ['urlable', true],
                                ['root_url', false],
                                ['parent_id', $sectionItem->id]
                            ])
                            ->get(['id', 'code'])
                            ->each(function (Property $subSectionItem) use ($map, $item, $sectionItem) {
                                $url = $item->code ? '/' . $item->code : '';
                                $map->add(
                                        Url::create($url . '/' . $sectionItem->code . '/' . $subSectionItem->code)
                                            ->setLastModificationDate(Carbon::now())
                                            ->setPriority(1)
                                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                                    );
                            });
                    });
                $map->writeToFile(
                    public_path($mapName . '_sitemap.xml')
                );
                $indexMap
                    ->add('/' . $mapName . '_sitemap.xml');
            });
        //end

        //companies
        $map = BaseSitemap::create();
        Company::query()
            ->where('active', true)
            ->get(['id', 'code', 'updated_at'])
            ->each(function (Company $item) use ($map) {
                $map->add(
                    Url::create('/priem/' . $item->code)
                        ->setLastModificationDate(Carbon::createFromTimeString($item->updated_at))
                        ->setPriority(1)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });
        $map->writeToFile(
            public_path('companies_sitemap.xml')
        );
        $indexMap
            ->add('/companies_sitemap.xml');
        //end

        $indexMap->writeToFile(public_path('sitemap.xml'));
    }
}
