<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index($userId)
    {
        $category = category::where('id_user', $userId)->get();

        try {

            return ResponseFormatter::success([
                $category,
            ]);
        } catch (\Exception $e) {
            ResponseFormatter::error($e);
        }
    }
    public function create(Request $request, $userId)
    {
        $uploadFolder = 'category';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );
        $category = category::create([
            'id_user'       => $userId,
            'nameCategory'  => $request->nameCategory,
            'iconCategory'  => $uploadedImageResponse['image_url'],
        ]);
        return ResponseFormatter::success($category);
    }
}
