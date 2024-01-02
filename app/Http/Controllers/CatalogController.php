<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $catalogs = Catalog::all();

            $response = response()->json(['data' => ['catalogs' => $catalogs]]);
        } catch (\Exception $ex) {

            $message = $ex->getMessage();

            $response = response()->json(['error' => $message, 'e' => $ex], 403);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $newCatalog = DB::transaction(function () use ($request) {
                $catalog = new Catalog();
                $catalog->name = $request->name;
                $catalog->save();

                return $catalog;
            });

            $response = response()->json(['data' => $newCatalog], 201);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => $ex->getMessage()], 500);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $catalog = Catalog::findOrFail($id);

            $response = response()->json(['data' => $catalog]);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => $ex->getMessage()], 404);
        }

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $catalog = DB::transaction(function () use ($request, $id) {
                $catalog = Catalog::findOrFail($id);
                $catalog->name = $request->name;
                $catalog->save();

                return $catalog;
            });

            $response = response()->json(['data' => $catalog]);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => $ex->getMessage()], 500);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $catalog = Catalog::findOrFail($id);
                $catalog->delete();
            });

            $response = response()->json(['message' => 'Catalog deleted successfully']);
        } catch (\Exception $ex) {
            $response = response()->json(['error' => $ex->getMessage()], 500);
        }

        return $response;
    }
}
