<?php

namespace App\Http\Controllers;

use App\Models\ConnectionPoint;
use Illuminate\Http\Request;

class ConnectionPointController extends Controller
{
    public function index(Request $request)
    {
        return view('connection_points.list', ['request' => $request]);
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();
            $connectionPoints = $user
                ->connection_points()
                ->select(['id', 'name', 'area_id']);

            if ($request->filled('area')) {
                $area = $user->areas()->findOrFail($request->area);
                $connectionPoints = $area->connection_points();
            }

            if ($request->filled('searchQuery') && $request->searchQuery != '0') {
                $request->searchQuery = ltrim($request->searchQuery, '0');
                $connectionPoints->where('name', 'like', '%' . $request->searchQuery . '%');
            }

            $connectionPoints = $connectionPoints
                ->orderBy('id', 'DESC')
                ->with('area:id,name')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            $connectionPoints->data = $connectionPoints->each(function ($c) {
                $c->customer_count = $c->customers()->count();
                return $c;
            });

            return $connectionPoints;
        }

        return false;
    }

    public function create()
    {
        return view('connection_points.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255']
        ]);
        $user = auth()->user();
        $area = $user->areas()->findOrFail($request->area);

        $connectionPoint = $area->connection_points()->create([
            'user_id' => $user->id,
            'name' => $request->name
        ]);

        return redirect(route('connection_points'))->with('message', "Connection Point $connectionPoint->name created!");
    }

    public function edit(ConnectionPoint $connectionPoint)
    {
        $user = auth()->user();

        if ($connectionPoint->user_id != $user->id) {
            abort(404);
        }

        return view('connection_points.edit', ['connectionPoint' => $connectionPoint]);
    }

    public function update(Request $request, ConnectionPoint $connectionPoint)
    {
        $user = auth()->user();

        if ($connectionPoint->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $connectionPoint->name = $request->name;
        $connectionPoint->save();

        return redirect()
            ->back()
            ->with('message', "Connection Point successfully updated!");
    }

    public function destroy(Request $request, ConnectionPoint $connectionPoint)
    {
        $user = auth()->user();

        if ($connectionPoint->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'pin' => ['required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        if ($connectionPoint->customers()->count() != 0) {
            return redirect()->back()->withErrors(['errors' => 'Connection Point has customers!']);
        }

        if ($connectionPoint->delete()) {
            return redirect(route('connection_points'))->with('message', 'Connection Point successfully deleted!');
        }

        return redirect()->back()->withErrors(['errors' => 'Delete ERROR!']);
    }
}
