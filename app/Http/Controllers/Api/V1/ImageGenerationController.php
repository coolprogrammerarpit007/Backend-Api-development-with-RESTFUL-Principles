<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePromptRequest;
use App\Http\Resources\ImageGenerationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\OpenAiService;


class ImageGenerationController extends Controller
{

     public function __construct(private OpenAiService $openAiService)
    {}
    public function index(Request $request)
    {
        $user = $request->user();
        $query = $user->imageGenerations();

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('generated_prompt', 'LIKE', '%' . $request->search . '%');
        }

        // Apply sorting
        $allowedSortFields = ['created_at', 'generated_prompt', 'original_filename', 'file_size'];
        $sortField = 'created_at';
        $sortDirection = 'desc';

        if ($request->has('sort') && !empty($request->sort)) {
            $sort = $request->sort;
            if (str_starts_with($sort, '-')) {
                $sortField = substr($sort, 1);
                $sortDirection = 'desc';
            } else {
                $sortField = $sort;
                $sortDirection = 'asc';
            }
        }

        // Validate sort field
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $imageGenerations = $query->paginate($request->get('per_page'));
        return ImageGenerationResource::collection($imageGenerations);
    }




    public function store(GeneratePromptRequest $request)
    {
        try
        {
            $user = $request->user();
            $image = $request->file('image');
            $originalImageName = $image->getClientOriginalName();

            // Sanitize Image File Name
            $sanitized_name = preg_replace('/[^a-z0-9A-Z._-]/','_',pathinfo($originalImageName,PATHINFO_FILENAME));

            $extention = $image->getClientOriginalExtension();
            $safe_file_name = $sanitized_name . '_' . Str::random(32) . $extention;

            // Storing Image into
            $image_path = $image->storeAs('uploads/images',$safe_file_name,'public');

            $generated_prompt = $this->openAiService->generatePromptForImage($image);

            $image_generation = $user->imageGenerations()->create([
                'image_path' => $image_path,
                'generated_prompt' => $generated_prompt,
                'original_file_name' => $originalImageName,
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ]);

            return new ImageGenerationResource($image_generation);

        }

        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'some error occur! please try again later',
                'errors' => $e->getMessage()
            ],500);
        }
    }
}
