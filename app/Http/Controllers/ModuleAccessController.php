<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\ModuleAccessControl;

class ModuleAccessController extends Controller
{
    /**
     * Return JSON of module access states.
     */
    public function index(Request $request)
    {
        $keys = ['module-1','module-2','module-3','module-4'];
        $states = ModuleAccessControl::whereIn('module_key', $keys)->get()->keyBy('module_key');

        $result = [];
        foreach ($keys as $k) {
            $item = $states->get($k);
            $result[$k] = [
                'is_unlocked' => (bool) ($item?->is_unlocked),
                'updated_at' => $item?->updated_at?->toDateTimeString(),
            ];
        }

        return Response::json(['modules' => $result]);
    }
}
