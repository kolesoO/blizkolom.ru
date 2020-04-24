<?php

namespace App\Http\Controllers\Api\V1;

use App\Jobs\CallBackEmail;
use App\Resources\CallBack;
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

        $data = $request->all();

        if (FormResult::query()
            ->insert([
                'content' => json_encode($data),
                'form_id' => $form->id,
            ])) {
            $this->dispatch(
                new CallBackEmail(CallBack::make($data))
            );
        }

        return [];
    }
}
