<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailList;
use App\Models\Template;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\ShowCampaignRequest;
use Illuminate\Support\Traits\Conditionable;
use App\Jobs\SendEmailsCampaign;
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
                ->orWhere("id", "=", "$search")
            )
            ->paginate(5)
            ->appends(compact('search', 'showTrash'));

        return view('campaigns.index', compact('campaigns','search', 'showTrash'));
    }

    public function create(?string $tab = null)
    {
        $data = session()->get('campaigns::create', [
            'name' => null,
            'subject' => null,
            'email_list_id' => null,
            'template_id' => null,
            'body' => null,
            'track_click' => null,
            'track_open' => null,
            'send_at' => null,
            'send_when' => 'now',
        ]);

        return view('campaigns.create',
            array_merge(
                $this->when(blank($tab), fn() => [
                    'emailLists' => EmailList::query()->select(['id', 'title'])->orderBy('title')->get(),
                    'templates' => Template::query()->select(['id', 'name'])->orderBy('name')->get(),
                ], fn() => []),
                $this->when($tab == 'schedule', fn() => [
                    'countEmails' => EmailList::find($data['email_list_id'])->subscribers()->count() ?? 0,
                    'template' => Template::find($data['template_id'])->first()
                ],  fn() => []),
                [
                    'tab' => $tab,
                    'view' => match ($tab) {
                        'template' => 'template',
                        'schedule' => 'schedule',
                        default => 'config'
                    },
                    'data' => $data
                ]
            )
        );
    }


    public function store(StoreCampaignRequest $request, ?string $tab = null)
    {
        $data = $request->getData();
        $toRoute = $request->getToRoute();

        if ($tab == 'schedule') {
            $campaign = Campaign::create($data);

            SendEmailsCampaign::dispatchAfterResponse($campaign);
        }

        return response()->redirectTo($toRoute)->with('message',   ('Campaign successfully created.'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('message',   ('Campaign successfully deleted.'));
    }

     /**
     * Restore the specified deleted resource from storage.
     */
    public function restore(Campaign $campaign)
    {
        $campaign->restore();

        return redirect()->route('campaigns.index')->with('message',   ('Campaign successfully restored.'));
    }

    public function show(ShowCampaignRequest $request, Campaign $campaign, ?string $what = null)
    {
        if ($redirect = $request->checkWhat()) {
            return $redirect;
        }

        $search = request()->search;

        $query = $campaign
            ->emails()
            ->when($what == 'statistics', fn (Builder $query) => $query->statistics())
            ->when($what == 'open', fn (Builder $query) => $query->openings($search))
            ->when($what == 'clicked', fn (Builder $query) => $query->clicks($search))
            ->simplePaginate(5)
            ->withQueryString();

        if ($what == 'statistics') {
            $query = $query->first()->toArray();
        }

        return view('campaigns.show', compact('campaign', 'what', 'search','query'));
    }
}
