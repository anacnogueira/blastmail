<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailList;
use App\Models\Template;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreCampaignRequest;
use Illuminate\Support\Traits\Conditionable;

class CampaignController extends Controller
{
    use Conditionable;

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
       return view('campaigns.create',
            array_merge(
                $this->when(blank($tab), fn() => [
                    'emailLists' => EmailList::query()->select(['id', 'title'])->orderBy('title')->get(),
                    'templates' => Template::query()->select(['id', 'name'])->orderBy('name')->get(),
                ], fn() => []),
                [
                    'tab' => $tab,
                    'form' => match ($tab) {
                        'template' => '_template',
                        'schedule' => '_schedule',
                        default => '_config'
                    },
                    'data' => session()->get('campaigns::create', [
                        'name' =>  null,
                        'subject' =>  null,
                        'email_list_id' => null,
                        'template_id' => null,
                        'body' => null,
                        'track_click' => null,
                        'track_open' => null,
                        'send_at' => null,
                        'send_when' => 'now',
                    ])
                ]
            )
        );
    }


    public function store(StoreCampaignRequest $request, ?string $tab = null)
    {
        $data = $request->getData();
        $toRoute = $request->getToRoute();

        if ($tab == 'schedule') {
            Campaign::create($data);
        }

        return response()->redirectTo($toRoute)->with('message', __('Campaign successfully created.'));

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
