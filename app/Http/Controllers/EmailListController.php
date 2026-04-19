<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailListRequest;
use App\Models\EmailList;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class EmailListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $emailLists = EmailList::withCount('subscribers')
            ->when($search,
            fn(Builder $query) => $query
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('id', '=', $search)
            )
        ->paginate(5)
        ->appends('search', $search);


        return view('email-lists.index',compact('emailLists', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('email-liss.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailListRequest $request)
    {
        $emails = $this->getEmailsFromCsvFile($request->file('file'));

        DB::transaction(function () use ($request, $emails) {

            $emailList = EmailList::create(['title' => $request->title]);

            $emailList->subscribers()->createMany($emails);
        });

        return redirect()->route('email-list.index');
    }

    private function getEmailsFromCsvFile(UploadedFile $file): array
    {
        $fileHandler = fopen($file->getRealPath(), 'r');
        $items = [];

        while(($row = fgetcsv($fileHandler, null, ',')) !== false) {
            if ($row[0] == 'Name' && $row[1] == 'Email') {
                continue;
            }
            $items[] = [
                'name' => $row[0],
                'email' => $row[1],
            ];
        }

        fclose($fileHandler);

        return $items;
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailList $list)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailList $list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailList $list)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailList $list)
    {
        //
    }
}
