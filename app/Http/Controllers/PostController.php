<?php
// NO FUNCIONA 
namespace App\Http\Controllers;

use App\Models\ProductoCaracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function image(ProductoCaracteristica $post)
    {
        $image = Storage::disk('s3')->get($post->imagen); //Storage::get($post->imagen);
        return response($image)
            ->header('Content-Type', 'image/jpeg');
    }

}
