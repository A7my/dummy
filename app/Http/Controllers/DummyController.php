<?php

namespace App\Http\Controllers;

use App\Models\Dummy;
use Illuminate\Http\Request;

class DummyController extends Controller
{

    public function dummy(Request $request)
    {
        $categories = $request->input('categories', []);
        $brands = $request->input('brands', []);
        $search = $request->input('search');

        $items = Dummy::query();

        if (!empty($categories)) {
            $items->whereIn('category', $categories);
        }
        if (!empty($search)) {
            $items->where('product', 'LIKE', '%' . $search . '%');
        }
        if (!empty($brands)) {
            $items->whereIn('brand', $brands);
        }
        $items = $items->paginate(6);

        // $items = $items->get();
        if ($request->ajax()) {
            $view = view('data', compact('items'))->render();
            return response()->json(['html' => $view]);
        }

        return view('dummy', compact('items'));
    }

}
