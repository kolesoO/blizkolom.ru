<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormResult;

class FormResultsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request $request
     * @param string $code
     * @return array
     */
    public function store(Request $request, string $code): array
    {
        $form = Form::query()
            ->where('code', $code)
            ->firstOrFail();

        FormResult::query()
            ->insert([
                'content' => json_encode($request->all()),
                'form_id' => $form->id
            ]);

        return [];
    }
}
