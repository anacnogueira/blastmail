<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Builder;

class CampaignController extends Controller
{
    public function index()
    {
        $search = request()->search;
        $showTrash = request()->showTrash;
        $campaigns = Campaign::when($showTrash, fn(Builder $query) => $query->withTrashed())
            ->when($search, fn(Builder $query) =>
                $query->where("name", "like", "%$search%")
                ->where("subject", "like", "%$search%")
                ->orWhere("id", "=", "$search")
            )
            ->paginate(5);

        return view('campaigns.index', compact('campaigns','search', 'showTrash'));
    }
}
