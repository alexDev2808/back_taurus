<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departaments = Departament::all();
        return response()->json($departaments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:80|unique:departaments,name',
            'description' => 'nullable|string|max:255',
            'subarea_id' => 'required|exists:subareas,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $departament = Departament::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
            'subarea_id' => $request->subarea_id
        ]);

        return response()->json([
            'message' => 'Departament created successfully',
            'departament' => $departament
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $departament = $this->findItem($id);

        if (!$departament) {
            return response()->json(['message' => 'Departament not found'], 404);
        }

        return response()->json($departament, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $departament = $this->findItem($id);

        if (!$departament) {
            return response()->json(['message' => 'Departament not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:80|unique:departaments,name,' . $departament->id,
            'description' => 'nullable|string|max:255',
            'subarea_id' => 'sometimes|required|exists:subareas,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        if ($request->has('name')) {
            $data['slug'] = Str::slug($request->name, '-');
        }

        $departament->update($data);

        return response()->json([
            'message' => 'Departament updated successfully',
            'departament' => $departament
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $departament = $this->findItem($id);

        if (!$departament) {
            return response()->json(['message' => 'Departament not found'], 404);
        }

        $departament->delete();

        return response()->json(['message' => 'Departament deleted successfully'], 200);
    }

    /**
     * Helper method to find a Departament by ID or slug.
     */
    private function findItem(string $identifier)
    {
        $item = Departament::where('id', $identifier)->orWhere('slug', $identifier)->first();

        return $item;
    }
}
