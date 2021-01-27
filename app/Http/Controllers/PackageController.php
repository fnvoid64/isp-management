<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    public function index(Request $request)
    {
        return view('packages.list', compact('request'));
    }

    public function indexData(Request $request)
    {
        if ($request->expectsJson()) {
            $user = auth()->user();
            $packages = $user
                ->packages()
                ->select(['id', 'name', 'type', 'buying_price', 'sale_price']);


            if ($request->filled('searchQuery') && $request->searchQuery != '0') {
                $request->searchQuery = ltrim($request->searchQuery, '0');
                $packages->where('name', 'ilike', '%' . $request->searchQuery . '%');
            }

            if ($request->filled('type')) {
                $packages = $packages->where('type', $request->type);
            }


            $packages = $packages
                ->orderBy('id', 'DESC')
                ->paginate(20, ['*'], 'page', $request->page ?? 1);

            $packages->data = $packages->each(function ($c) {
                $c->customer_count = $c->customers()->count();
                return $c;
            });

            return $packages;
        }

        return false;
    }


    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'numeric', 'in:1,2'],
            'buying_price' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],
            'on_site' => ['nullable'],
        ]);

        $package = auth()->user()->packages()->create([
            'name' => $request->name,
            'type' => $request->type,
            'buying_price' => $request->buying_price,
            'sale_price' => $request->sale_price,
            'on_site' => $request->filled('on_site')
        ]);

        return redirect(route('packages'))->with('message', "Package $package->name successfully created!");
    }

    public function show(Package $package)
    {
        $user = auth()->user();

        if ($package->user_id != $user->id) {
            abort(404);
        }

        return view('packages.show', ['package' => $package]);
    }


    public function edit(Package $package)
    {
        $user = auth()->user();

        if ($package->user_id != $user->id) {
            abort(404);
        }

        return view('packages.edit', ['package' => $package]);
    }

    public function update(Request $request, Package $package)
    {
        $user = auth()->user();

        if ($package->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'numeric', 'in:1,2'],
            'buying_price' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],
            'on_site' => ['nullable'],
        ]);

        $package->name = $request->name;
        $package->type = $request->type;
        $package->buying_price = $request->buying_price;
        $package->sale_price = $request->sale_price;
        $package->on_site = $request->filled('on_site');
        $package->save();

        return redirect()->back()->with('message', 'Package successfully edited!');
    }

    public function destroy(Request $request, Package $package)
    {
        $user = auth()->user();

        if ($package->user_id != $user->id) {
            abort(404);
        }

        $request->validate([
            'pin' => ['required', 'numeric']
        ]);

        if ($user->pin != $request->pin) {
            return redirect()->back()->withErrors(['errors' => 'Wrong PIN!']);
        }

        if ($package->customers()->count() != 0) {
            return redirect()->back()->withErrors(['errors' => 'Package has customers!']);
        }

        if ($package->delete()) {
            return redirect(route('packages'))->with('message', 'Package successfully deleted!');
        }

        return redirect()->back()->withErrors(['errors' => 'Delete ERROR!']);
    }
}
