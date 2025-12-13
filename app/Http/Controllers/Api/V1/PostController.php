<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Post;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $data = Post::all();
            return response()->json(['status' => true,'msg' => 'All posts fetched successfully','data'=>$data],200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'msg' => 'error in fetching data',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try
        {
             $validated = Validator::make(
            $request->all(),[
                'title' => 'required|string|max:50',
                'body' => 'required|string|max:150',

            ]);

        if($validated->fails())
        {
            return response()->json([
                'status' => false,
                'msg' => "Validation Error Occurred!",
                'errors' => $validated->errors()
            ],422);
        }

        $validated = $validated->validate();
        $validated['author_id'] = 1;

        DB::beginTransaction();

        $new_data = Post::create($validated);

        DB::commit();

        return response()->json([
            'status' => true,
            "msg" => "Post created successfully!",
            "data" => $new_data
        ],201);

        }

        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'msg' => 'error happen on post creation',
                'errors' => $e->getMessage(),
            ]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try
        {
            return response()->json([
                'status' => true,
                'msg' => 'post fetched successfully',
                'data' => $post
            ]);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'msg' => 'post details are failed to fetched',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        try
        {
            DB::transaction(function () use ($post, $request){
                return $post->update($request->all());
            });

            return response()->json([
                'status' => true,
                'msg' => 'Post data updated successfully',
                'data' => $post
            ]);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'msg' => 'post details are failed to updated',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try
        {
            $post->delete();
            return response()->noContent();
        }

        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'msg' => 'post details are failed to be deleted!',
                'errors' => $e->getMessage()
            ]);
        }
    }
}
