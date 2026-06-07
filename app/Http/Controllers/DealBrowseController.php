<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;

class DealBrowseController extends Controller
{
    public function index()
    {
        $deals = Deal::active()
            ->current()
            ->orderBy('sort_order')
            ->get();

        return view('deals.index', compact('deals'));
    }

    public function show(Deal $deal)
    {
        if (!$deal->is_active || $deal->is_expired || $deal->is_upcoming) {
            abort(404, 'Deal not available');
        }

        return view('deals.show', compact('deal'));
    }
}
