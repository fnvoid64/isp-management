<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        return view('areas.list', ['request' => $request]);
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();
            $areas = $user
                ->areas()
                ->select(['id', 'name']);


            if ($request->filled('searchQuery') && $request->searchQuery != '0') {
                $request->searchQuery = ltrim($request->searchQuery, '0');
                $areas->where('name', 'ilike', '%' . $request->searchQuery . '%');
            }


            $areas = $areas
                ->latest()
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            $areas->data = $areas->each(function ($c) {
                $c->customer_count = $c->customers()->count();
                $c->cp_count = $c->connection_points()->count();
                return $c;
            });

            return $areas;
        }

        return false;
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $area = auth()->user()->areas()->create([
            'name' => $request->name
        ]);

        return redirect(route('areas'))->with('message', "Area $area->name created!");
    }


    public function edit(Area $area)
    {
        $user = auth()->user();

        if ($area->user_id != $user->id) {
            abort(404);
        }

        return view('areas.edit', ['area' => $area]);
    }


    public function update(Request $request, Area $area)
    {
        $user = auth()->user();

        if ($area->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $area->name = $request->name;
        $area->save();

        return redirect()
            ->back()
            ->with('message', "Area successfully updated!");
    }

    public function destroy(Request $request, Area $area)
    {
        $user = auth()->user();

        if ($area->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'pin' => ['required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        if ($area->customers()->count() != 0) {
            return redirect()->back()->withErrors(['errors' => 'Area has customers!']);
        }

        if ($area->connection_points()->count() != 0) {
            return redirect()->back()->withErrors(['errors' => 'Area has connection points!']);
        }

        if ($area->delete()) {
            return redirect(route('areas'))->with('message', 'Area successfully deleted!');
        }

        return redirect()->back()->withErrors(['errors' => 'Delete ERROR!']);
    }
}
