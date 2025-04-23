<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:areas,name',
            'subsidiary_id' => 'required|exists:subsidiaries,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $area = Area::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'subsidiary_id' => $request->subsidiary_id
        ]);

        return response()->json([
            'message' => 'Area created successfully',
            'area' => $area
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $area = $this->findItem($id);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        return response()->json($area, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $area = $this->findItem($id);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:80',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $area->update($request->all());
        return response()->json($area, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $area = $this->findItem($id);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        $area->delete();
        return response()->json(['message' => 'Area deleted successfully'], 200);
    }


    private function findItem(string $identifier)
    {
        $item = Area::where('id', $identifier)->orWhere('slug', $identifier)->first();

        return $item;
    }

}
