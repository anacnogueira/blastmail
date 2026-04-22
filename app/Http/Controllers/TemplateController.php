<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Http\Requests\StoreTemplateRequest;
use Illuminate\Database\Eloquent\Builder;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $showTrash = request()->showTrash;
        $templates = Template::when($showTrash, fn(Builder $query) => $query->withTrashed())
            ->when($search, fn(Builder $query) =>
                $query->where("name", "like", "%$search%")
                ->orWhere("id", "=", "$search")
            )
            ->paginate(5)
            ->appends(compact('search', 'showTrash'));

        return view('templates.index', compact('templates','search', 'showTrash'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateRequest $request)
    {
        $data = $request->validated();
        Template::create($data);

        return redirect()->route('templates.index')->with('message', __('Template successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        return view('templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTemplateRequest $request, Template $template)
    {
        $data = $request->validated();
        $template->fill($data);
        $template->save();

        return redirect()->route('templates.index')->with('message', __('Template successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('templates.index')->with('message', __('Template successfully deleted.'));
    }
}
