<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Form;
use App\Models\FormField;

class FormFieldsController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Request $request
     * @param string $code
     * @return array
     */
    public function index(Request $request, string $code): array
    {
        $form = Form::query()
            ->where('code', $code)
            ->firstOrFail();

        $builder = FormField::query();
        $where = [
            ['form_id', $form->id]
        ];

        if ($request->get('active', false)) {
            $where[] = ['active', $request->get('active')];
        }

        return $builder
            ->where($where)
            ->get()
            ->toArray();
    }
}
