<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $types = Type::query()
            ->when($search, function ($query, $search) {
                $query->where('tipe', 'like', "%{$search}%");
            })
            ->orderBy('tipe')
            ->paginate(5)
            ->withQueryString();

        return view('types', [
            'types' => $types,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipe' => ['required', 'string', 'max:50'],
        ]);

        Type::create($data);

        return redirect()
            ->route('types')
            ->with('status', 'Type created.');
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $data = $request->validate([
            'tipe' => ['required', 'string', 'max:50'],
        ]);

        $type->update($data);

        return redirect()
            ->route('types')
            ->with('status', 'Type updated.');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return redirect()
            ->route('types')
            ->with('status', 'Type deleted.');
    }
}
