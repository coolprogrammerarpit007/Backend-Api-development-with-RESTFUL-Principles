<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'msg' => 'All data fetches successfully!',
            'data' => [
                'id' => 1,
                'title' => 'Harry Potter and Philosopher Stone',
                'author' => 'JK Rowling'
            ]
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $data = $request->all(); -> will get all data in request
        $data = $request->only('title','author'); #-> will only get selected data in request
        return response()->json([
            'msg' => 'data created successfully!',
            'title' => $data['title'],
            'author' => $data['author']
        ])->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'message' => 'Post fetched successfully!',
            'data' => [
                'id' => $id,
                'title' => 'New Title',
                'body' => 'Post Body'
            ]
            ])->header('test', 'Arpit')->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:35',
            'author' => ['required','string','min:5']
        ]);


        }

        return response()->json([
            'msg' => 'data updated successfully',
            'id' => $id,
            'title' => $validated['title'],
            'author' => $validated['author'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->noContent();
    }
}
