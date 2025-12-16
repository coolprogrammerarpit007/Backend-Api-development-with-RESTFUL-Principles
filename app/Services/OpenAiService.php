<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use OpenAI\Factory;

class OpenAiService
{
    public function generatePromptForImage(UploadedFile $image)
    {
        $imageData = base64_encode(file_get_contents($image->getPathName()));
        $mimeType = $image->getMimeType();

        $client = (new Factory())->withApiKey(config('services.openai.key'))->make();


    }
}
