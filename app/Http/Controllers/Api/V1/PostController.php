<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            // $data = Post::all();
            // return response()->json(['status' => true,'msg' => 'All posts fetched successfully','data'=>$data],200);
            // return $data;
            // $data = PostResource::collection(Post::all());
            // Getting Authenticated User
            $author = Auth::user();
            // $post_data = Post::with('user:id,name,email')->get();
            $post_data = $author->posts()->with('user')->get();
            $data = PostResource::collection($post_data);
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
    public function store(StorePostRequest $request)
    {

        try
        {
        //      $validated = Validator::make(
        //     $request->all(),[
        //         'title' => 'required|string|max:50',
        //         'body' => 'required|string|max:150',

        //     ]);

        // if($validated->fails())
        // {
        //     return response()->json([
        //         'status' => false,
        //         'msg' => "Validation Error Occurred!",
        //         'errors' => $validated->errors()
        //     ],422);
        // }

        // $validated = $validated->validate();
        $validated = $request->validated();
        $validated['author_id'] = Auth::user()->id;

        DB::beginTransaction();

        $new_data = Post::create($validated);
        $new_data = new PostResource($new_data);

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

            $user = Auth::user();
            if($user->id != $post->author_id)
            {
                return response()->json([
                    'status' => false,
                    'msg' => 'Forbidden Access'
                ],403);
            }
            return response()->json([
                'status' => true,
                'msg' => 'post fetched successfully',
                'data' => new PostResource($post)
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
        $user = Auth::user();
            if($user->id != $post->author_id)
            {
                return response()->json([
                    'status' => false,
                    'msg' => 'Forbidden Access'
                ],403);
            }
        try
        {
            DB::transaction(function () use ($post, $request){
                return $post->update($request->all());
            });

            return response()->json([
                'status' => true,
                'msg' => 'Post data updated successfully',
                'data' => new PostResource($post)
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
        $user = Auth::user();
            if($user->id != $post->author_id)
            {
                return response()->json([
                    'status' => false,
                    'msg' => 'Forbidden Access'
                ],403);
            }
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
