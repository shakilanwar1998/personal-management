<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\YearService;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate(['year' => ['required', 'integer', 'min:1970', 'max:2100']]);
        YearService::select((int) $data['year']);

        return back();
    }
}
