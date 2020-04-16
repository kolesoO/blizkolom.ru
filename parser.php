<?php

//arg[1] - file name
//arg[2] - region genetiv
//arg[3] - region nominativ

declare(strict_types=1);

use App\Models\Company;
use App\Models\CompanyPrices;
use App\Models\CompanyProperty;
use App\Models\File;
use App\Models\Options;
use App\Models\Property;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Behat\Transliterator\Transliterator;

libxml_use_internal_errors(true);

require __DIR__ . '/bootstrap/autoload.php';

/** @var Application $app */
$app = require_once __DIR__ . '/bootstrap/app.php';

//kernel
/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
//end


$path = __DIR__ . '/storage/remote/' . $argv[1] . '.html';
$server = 'https://xn----7sblvlgns.xn--p1ai';

$staticServerPath = '/var/www/static.blizkolom.ru';
$imgDir = '/' . basename($path, '.html');

$regionGenetiv = $argv[2];
$regionNominativ = $argv[3];

class Parser
{
    protected $doc;

    protected $str;

    public function __construct(string $content)
    {
        $this->str = $content;

        $this->doc = new DOMDocument();
        $this->doc->loadHTML($content);
    }

    public function getName(): string
    {
        $node = $this->doc->getElementsByTagName('h1')->item(0);

        return $node ? trim($node->textContent) : '';
    }

    public function getImage(): string
    {
        global $server;

        if (preg_match('/<div class="field--formatter-responsive-image.+?src="([^"]+)"\s/s', $this->str, $matches)) {
            if (isset($matches[1])) {
                return trim($server . preg_replace('/\?.*$/i', '', trim($matches[1])));
            }
        }

        return '';
    }

    public function getOptions(): array
    {
        if (preg_match('/Опции<\/div>(.+?<\/div>[\s\t]+<\/div>)/s', $this->str, $matches)) {
            if (isset($matches[1])) {
                $return = [];
                if (preg_match_all('/>([^<>\t\n]+)</s', $matches[1], $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        if (!isset($match[1])) continue;
                        $return[] = trim($match[1]);
                    }
                }

                return $return;
            }
        }

        return [];
    }

    public function getApplicationInfo(): array
    {
        if (preg_match('/<script type="application\/ld\+json">(.+?)<\/script>/s', $this->str, $matches)) {
            if (isset($matches[1])) {
                return json_decode($matches[1], true);
            }
        }

        return [];
    }

    public function getTime(): array
    {
        if (preg_match('/class="office-hours__item-label"[^>]*>([^<]+)/s', $this->str, $matches)) {
            $return = [];
            if (isset($matches[1])) {
                $return[] =  trim(str_replace(':', '', $matches[1]));
            }
            if (preg_match('/class="office-hours__item-slots"[^>]*>([^<]+)/s', $this->str, $matches)) {
                if (isset($matches[1])) {
                    $return[] = trim($matches[1]);
                }
            }

            return $return;
        }

        return [];
    }

    public function getDescription(): string
    {
        if (preg_match('/<div class="field--formatter-text-default field field--name-body field--type-text-with-summary field--label-hidden field--item">(.+?)<\/div>/s', $this->str, $matches)) {
            if (isset($matches[1])) {
                return trim($matches[1]);
            }
        }

        return '';
    }

    public function getPrices(): array
    {
        if (preg_match_all('/<h4 class="panel-title">[\s\n\t]*<a[^>]+>([^<]+)/s', $this->str, $matches, PREG_SET_ORDER)) {
            $result = [];
            foreach ($matches as $match) {
                if (isset($match[1])) {
                    $result[] = trim($match[1]);
                }
            }
            if (preg_match_all('/<table class="price-table[^>]*>[\t\n\s]*<tbody>[\t\n\s]*(.+?)[\t\n\s]*<\/tbody>[\t\n\s]*<\/table>/s', $this->str, $matches, PREG_SET_ORDER)) {
                $newItems = [];
                foreach ($matches as $key => $match) {
                    if (!isset($match[1])) continue;

                    $items = [];

                    if (preg_match_all('/<tr[^>]*>[\t\n\s]*<td.*?<span[\t\n\s]*data-toggle[^>]*>(.+?)<\/span>.+?<td>(.+?)<\/td>[\t\n\s]*<\/tr>/s', $match[1], $matches1, PREG_SET_ORDER)) {
                        foreach ($matches1 as $match1) {
                            $items[] = [
                                'name' => trim($match1[1]),
                                'price' => $match1[2],
                            ];
                        }
                    }

                    if (preg_match_all('/<tr[^>]*>[\t\n\s]*<td>([^<]+)<\/td>.+?<td>(.+?)<\/td>[\t\n\s]*<\/tr>/s', $match[1], $matches1, PREG_SET_ORDER)) {
                        foreach ($matches1 as $match1) {
                            $items[] = [
                                'name' => trim($match1[1]),
                                'price' => $match1[2],
                            ];
                        }
                    }

                    if (count($items) == 0) continue;

                    $newItems[] = [
                        'name' => $result[$key],
                        'prices' => $items,
                    ];
                }

                return $newItems;
            }
        }

        return [];
    }

    public function getUrl(): string
    {
        if (preg_match('/<a href="([^"]+)" rel="nofollow noopener noreferrer"/i', $this->str, $matches)) {
            if (isset($matches[1])) {
                return trim($matches[1]);
            }
        }

        return '';
    }
}

function getPropName(string $name)
{
    $list = [
        'черный' => 'Черные металлы',
        'медь' => [
            'name' => 'Медь',
            'parent_name' => 'Цветные металлы',
        ],
        'нержавейка' => [
            'name' => 'Нержавейка',
            'parent_name' => 'Цветные металлы',
        ],
        'латунь' => [
            'name' => 'Латунь',
            'parent_name' => 'Цветные металлы',
        ],
        'свинец' => [
            'name' => 'Свинец',
            'parent_name' => 'Цветные металлы',
        ],
        'магний' => [
            'name' => 'Магний',
            'parent_name' => 'Цветные металлы',
        ],
        'титан' => [
            'name' => 'Титан',
            'parent_name' => 'Цветные металлы',
        ],
        'алюминий' => [
            'name' => 'Алюминий',
            'parent_name' => 'Цветные металлы',
        ],
        'бронза' => null,
        'золото' => null,
        'платина' => null,
        'серебро' => null,
        //'бытовой лом' => null,
    ];

    return $list[mb_strtolower($name)] ?? $name;
}

//root regions
$russia = Property::query()->where('title', 'Россия')->first();
$region = Property::query()
    ->firstOrCreate(
        ['title' => $regionNominativ],
        [
            'urlable' => true,
            'root_url' => true,
            'code' => Transliterator::transliterate($regionNominativ),
            'genetiv' => $regionGenetiv,
            'nominativ' => $regionNominativ,
            'parent_id' => $russia->parent_id,
        ]
    );

$regionPropId = [$russia->id, $region->id];
//end

preg_match_all('/<a\shref="([^"]+)"[^>]+>Подробнее<\/a>/s', file_get_contents($path), $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    if (isset($match[1]) && strpos($match[1], 'office') !== false) {
        echo 'Load content from ' . $server . $match[1] . PHP_EOL;
        $parser = new Parser(
            file_get_contents($server . $match[1])
        );

        //main info
        echo 'Init main company info' . PHP_EOL;
        $applicationInfo = $parser->getApplicationInfo();

        $applicationInfo['@graph'][0]['telephone'] = $applicationInfo['@graph'][0]['telephone'] ?? '';
        $applicationInfo['@graph'][0]['address']['addressLocality'] = $applicationInfo['@graph'][0]['address']['addressLocality'] ?? '';
        $applicationInfo['@graph'][0]['address']['streetAddress'] = $applicationInfo['@graph'][0]['address']['streetAddress'] ?? '';
        $applicationInfo['@graph'][0]['geo']['latitude'] = $applicationInfo['@graph'][0]['geo']['latitude'] ?? '';
        $applicationInfo['@graph'][0]['geo']['longitude'] = $applicationInfo['@graph'][0]['geo']['longitude'] ?? '';
        $applicationInfo['@graph'][0]['contactPoint']['email'] = $applicationInfo['@graph'][0]['contactPoint']['email'] ?? '';

        $company = [
            'name' => $applicationInfo['@graph'][0]['name'],
            'code' => Transliterator::transliterate(
                mb_strtolower($applicationInfo['@graph'][0]['name'])
            ),
            'phone' => $applicationInfo['@graph'][0]['telephone'] ?? '',
            'map_coords' => $applicationInfo['@graph'][0]['geo']['latitude'] . ',' . $applicationInfo['@graph'][0]['geo']['longitude'],
            'email' => $applicationInfo['@graph'][0]['contactPoint']['email'],
            'title' => 'Компания – «' . $applicationInfo['@graph'][0]['name'] . '» в ' . $regionGenetiv,
            'description' => 'Информация о пункте приёма металлолома «' . $applicationInfo['@graph'][0]['name'] . '», расположенном в ' . $regionGenetiv,
            'keywords' => 'компания ' . mb_strtolower($applicationInfo['@graph'][0]['name']) . ' пункт приёма ' . mb_strtolower($regionNominativ),
            'h1' => $applicationInfo['@graph'][0]['name'],
            'url' => $parser->getUrl(),
            'active' => true,
        ];

        if (
            strlen($applicationInfo['@graph'][0]['address']['addressLocality']) > 0
            && strlen($applicationInfo['@graph'][0]['address']['streetAddress']) > 0
        ) {
            $company['contacts'] = $applicationInfo['@graph'][0]['address']['addressLocality'] . ', ' . $applicationInfo['@graph'][0]['address']['streetAddress'];
        }
        //end

        //images
        echo 'Load company logo' . PHP_EOL;
        $filePath = $parser->getImage();
        if (strlen($filePath) > 0) {
            try {
                $logoContent = file_get_contents($filePath);
            } catch (Throwable $exception) {
                $logoContent = false;
            }
            if ($logoContent = file_get_contents($filePath)) {
                $localPath = '/img' . $imgDir. '/' . basename($filePath);
                echo 'Save company logo' . PHP_EOL;
                if (!is_dir($staticServerPath . '/img' . $imgDir)) {
                    mkdir($staticServerPath . '/img' . $imgDir);
                }
                file_put_contents($staticServerPath . $localPath, $logoContent);
                $fileInstance = File::query()
                    ->firstOrCreate([
                        'path' => $localPath,
                    ]);

                $company['preview_picture'] = $fileInstance->id;
                $company['detail_picture'] = $fileInstance->id;
            }
        }
        //end

        //time
        echo 'Load open/close time' . PHP_EOL;
        $time = $parser->getTime();
        if (isset($time[1])) {
            $arValues = explode('-', $time[1]);
            if (count($arValues) == 2) {
                $company['open_from'] = trim($arValues[0]);
                $company['open_to'] = trim($arValues[1]);
            }
        }
        //end

        //description
        echo 'Load description' . PHP_EOL;
        $company['detail_text'] = $parser->getDescription();
        //end

        //save company
        if ($companyInstance = Company::query()
            ->where([
                ['name', $company['name']],
                ['contacts', $company['contacts']],
                ['phone', $company['phone']],
            ])
            ->first()
        ) {
            echo 'Update company ' . $company['name'] . PHP_EOL;
            $companyInstance
                ->fill($company)
                ->save();
        } else {
            echo 'Save new company ' . $company['name'] . PHP_EOL;
            $companyInstance = new Company();
            $companyInstance
                ->fill($company)
                ->save();
        }
        //end

        //options
        echo 'Load options' . PHP_EOL;
        $options = $parser->getOptions();
        foreach ($options as $option) {
            $companyInstance->options()
                ->firstOrCreate([
                    'value' => $option,
                ]);
        }
        //end

        $companyPropIds = $regionPropId;

        //prices
        echo 'Load prices' . PHP_EOL;
        $priceList = $parser->getPrices();

        foreach ($priceList as $priceInfo) {
            $realName = getPropName($priceInfo['name']);

            if (is_null($realName)) continue;

            if (is_array($realName)) {
                $parentName = $realName['parent_name'];
                $realName = $realName['name'];
            } else {
                $parentName = $realName;
            }

            $parentProperty = Property::query()
                ->where('title', $realName)
                ->first();

            if (is_null($parentProperty)) {
                $newPropParams = [
                    'title' => $realName,
                    'filtered' => true,
                    'urlable' => true,
                    'code' => Transliterator::transliterate($realName),
                ];
                if ($realName != $parentName) {
                    $rootProperty = Property::query()
                        ->where('title', $parentName)
                        ->first();
                    if (is_null($rootProperty)) {
                        $rootProperty = new Property([
                            'title' => $parentName,
                            'filtered' => true,
                            'urlable' => true,
                            'code' => Transliterator::transliterate($parentName),
                        ]);
                        $rootProperty->save();
                        echo 'Create property ' . $rootProperty->title . PHP_EOL;
                    }
                    $newPropParams['parent_id'] = $rootProperty->id;
                    $companyPropIds[] = $rootProperty->id;
                }

                $parentProperty = new Property($newPropParams);
                $parentProperty->save();
                echo 'Create property ' . $parentProperty->title . PHP_EOL;
            } else {
                $companyPropIds[] = $parentProperty->parent_id;
            }

            $companyPropIds[] = $parentProperty->id;

            foreach ($priceInfo['prices'] as $price) {
                $realName = getPropName($price['name']);
                if (is_null($realName)) continue;

                $property = Property::query()
                    ->where([
                        ['title', $realName],
                        ['parent_id', $parentProperty->id]
                    ])
                    ->first();

                if (is_null($property)) {
                    $property = new Property([
                        'title' => $realName,
                        'filtered' => true,
                        'urlable' => true,
                        'code' => $parentProperty->code . '-' . Transliterator::transliterate($realName),
                        'parent_id' => $parentProperty->id,
                    ]);
                    $property->save();
                    echo 'Create property ' . $property->title . PHP_EOL;
                }

                echo 'Create company price for ' . $property->title . PHP_EOL;
                $companyPriceInstance = CompanyPrices::query()
                    ->where([
                        ['company_id', $companyInstance->id],
                        ['property_id', $property->id],
                    ])
                    ->first();
                if (is_null($companyPriceInstance)) {
                    (new CompanyPrices([
                        'company_id' => $companyInstance->id,
                        'property_id' => $property->id,
                        'value' => $price['price']
                    ]))
                        ->save();
                } else {
                    $companyPriceInstance->update([
                        'value' => $price['price']
                    ]);
                }

                $companyPropIds[] = $property->id;
            }

            //avg price for root property
            $forAvgPrices = CompanyPrices::query()
                ->whereIn(
                    'property_id',
                    Property::query()
                        ->where('parent_id', $parentProperty->id)
                        ->pluck('id')
                        ->toArray()
                )
                ->pluck('value')
                ->toArray();
            $avgValue = round((float) array_sum($forAvgPrices) / count($forAvgPrices), 0, PHP_ROUND_HALF_UP);

            /** @var CompanyPrices $avgPriceInstance */
            $avgPriceInstance = CompanyPrices::query()
                ->where([
                    ['company_id', $companyInstance->id],
                    ['property_id', $parentProperty->id],
                ])
                ->first(['id']);

            if (!is_null($avgPriceInstance)) {
                echo 'Update avg price for ' . $parentProperty->title . PHP_EOL;
                $avgPriceInstance->update([
                    'value' => $avgValue
                ]);
            } else {
                echo 'Create avg price for ' . $parentProperty->title . PHP_EOL;
                (new CompanyPrices([
                    'company_id' => $companyInstance->id,
                    'property_id' => $parentProperty->id,
                    'value' => $avgValue,
                ]))
                    ->save();
            }
            //end
        }

        foreach ($companyPropIds as $propId) {
            CompanyProperty::query()
                ->firstOrCreate([
                    'company_id' => $companyInstance->id,
                    'property_id' => $propId,
                ]);
        }
        //end

        echo '----------' . PHP_EOL;
    }
}
