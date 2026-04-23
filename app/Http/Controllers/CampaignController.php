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
            ->paginate(5)
            ->appends(compact('search', 'showTrash'));

        return view('campaigns.index', compact('campaigns','search', 'showTrash'));
    }

    public function create(?string $tab = null)
    {
        return view('campaigns.create', compact('tab'));
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('message', __('Campaign successfully deleted.'));
    }

     /**
     * Restore the specified deleted resource from storage.
     */
    public function restore(Campaign $campaign)
    {
        $campaign->restore();

        return redirect()->route('campaigns.index')->with('message', __('Campaign successfully restored.'));
    }


}
