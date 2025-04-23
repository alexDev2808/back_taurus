<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subareas = Subarea::all();
        return response()->json($subareas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:80|unique:subareas,name',
            'description' => 'nullable|string|max:255',
            'area_id' => 'required|exists:areas,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $subarea = Subarea::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
            'area_id' => $request->area_id
        ]);

        return response()->json([
            'message' => 'Subarea created successfully',
            'subarea' => $subarea
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subarea = $this->findItem($id);

        if (!$subarea) {
            return response()->json(['message' => 'Subarea not found'], 404);
        }

        return response()->json($subarea, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subarea = $this->findItem($id);

        if (!$subarea) {
            return response()->json(['message' => 'Subarea not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:80|unique:subareas,name,' . $subarea->id,
            'description' => 'nullable|string|max:255',
            'area_id' => 'sometimes|required|exists:areas,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        if ($request->has('name')) {
            $data['slug'] = Str::slug($request->name, '-');
        }
        
        $subarea->update($data);

        return response()->json([
            'message' => 'Subarea updated successfully',
            'subarea' => $subarea
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subarea = $this->findItem($id);

        if (!$subarea) {
            return response()->json(['message' => 'Subarea not found'], 404);
        }

        $subarea->delete();

        return response()->json(['message' => 'Subarea deleted successfully'], 200);
    }


    private function findItem(string $identifier)
    {
        $item = Subarea::where('id', $identifier)->orWhere('slug', $identifier)->first();

        return $item;
    }
}
