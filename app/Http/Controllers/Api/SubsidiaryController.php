<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subsidiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubsidiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subsidiaries = Subsidiary::all();
        return response()->json($subsidiaries, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:80',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $subsidiary = Subsidiary::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);
        return response()->json($subsidiary, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subsidiary = Subsidiary::find($id);

        if (!$subsidiary) {
            return response()->json(['message' => 'Subsidiary not found'], 404);
        }

        return response()->json($subsidiary, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subsidiary = Subsidiary::find($id);

        if (!$subsidiary) {
            return response()->json(['message' => 'Subsidiary not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:80',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subsidiary->update($request->all());
        return response()->json($subsidiary, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subsidiary = Subsidiary::find($id);

        if (!$subsidiary) {
            return response()->json(['message' => 'Subsidiary not found'], 404);
        }

        $subsidiary->delete();
        return response()->json(['message' => 'Subsidiary deleted successfully'], 200);
    }


    public function areas($identifier)
    {
        $subsidiary = Subsidiary::where('id', $identifier)->orWhere('slug', $identifier)->first();

        if (!$subsidiary) {
            return response()->json(['message' => 'Subsidiary not found'], 404);
        }

        return response()->json($subsidiary->areas, 200);
    }

}
