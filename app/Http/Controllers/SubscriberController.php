<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(EmailList $emailList)
    {
        $search = request()->search;
        $showTrash = request()->showTrash;

        //dd($showTrash);

        $subscribers = $emailList
            ->subscribers()
            ->when($showTrash, fn(Builder $query) => $query->withTrashed())
            ->when($search, fn(Builder $query) =>
                $query->where("name", "like", "%$search%")
                ->orWhere("email", "like", "%$search%")
                ->orWhere("id", "=", "$search")
            )

        ->paginate(5);


        return view('subscribers.index', compact('emailList','subscribers','search','showTrash'));
    }


    public function destroy(EmailList $emailList, Subscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()
            ->route('subscribers.index', $emailList)
            ->with('message', __('Subscriber deleted from the list.'));
    }
}
