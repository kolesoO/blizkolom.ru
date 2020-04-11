<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;

class PropertyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request): array
    {
        $builder = Property::query();

        if ($request->get('filtered', false)) {
            $builder->where('filtered', 1);
        }
        if ($request->get('urlable', false)) {
            $builder->where('urlable', 1);
        }
        if ($request->get('root_url', false)) {
            $builder->where('root_url', 1);
        }
        if ($request->get('popular', false)) {
            $builder->where('popular', 1);
        }
        if ($request->get('title', false)) {
            $builder->where('title', 'like', '%' . $request->get('title') . '%');
        }

        return $builder
            ->get()
            ->each(function($item) use ($request) {
                if ($request->get('with_url', false)) {
                    $item->url = '/' . $item->code;
                }
                return $item;
            })
            ->toArray();
    }
}
