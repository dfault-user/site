<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Item;
use App\Jobs\RenderJob;
use App\Http\Cdn\Render;
use App\Http\Cdn\Thumbnail;
use Illuminate\Support\Facades\File;

use Log;

class CdnController extends Controller
{
    public function render(Request $request)
    {
        if (!$request->has('type') || !$request->has('id'))
        {
            return response()->api(['success' => false]);
        }

        $threeDee = $request->has('3d');        
        $resolved;

        try
        {
            $resolved = Render::resolve($request->input('type'), $request->input('id'), $threeDee);
        }
        catch (Exception)
        {
            return response()->api(['success' => false]);
        }

        return response()->api($resolved);
    }

    public function thumbnail(Request $request)
    {        
        if (!$request->has('type') || !$request->has('id'))
        {
            return response()->api([ 'status' => 0 ]);
        }

        $threeDee = (bool) $request->has('3d');
        $admin = $request->has('admin');
        
        return response()->api(Thumbnail::resolve($request->input('type'), $request->input('id'), $threeDee, $admin));
    }

public function file(Request $request, $file)
{
    /**
     * Three operations:
     * - Is the file name secure? (i.e. sanitize it before passing it to actual file operations)
     * - Does the file exist?
     * - Return the file.
     */

    if (!ctype_alnum($file))
    {
        return abort(404);
    }
    $user = false;
    if ($request->has("type")) {
	if ($request->query('type') == "bodyshot") {
		$user = true;
		$filePath = storage_path('app/renders/users/' . $file . '.png');
	}
	if ($request->query("type") == "headshot") {
		$user = true;
		$filePath = storage_path('app/renders/users/headshots/' . $file . '.png');
	}
    } else {     $filePath = storage_path('app/renders/items/' . $file . '.png'); }
    if ($request->query("games")) {
		$filePath = storage_path('app/renders/places/' . $file . '.png');
        $user = true; // im horribly lazy
	}

    $item = Item::find($file);

    if (File::exists(storage_path("cdn/". $file))) {
        $headers = explode(';', Storage::disk('cdn')->get($file . '.mime'));

        return response(Storage::disk('cdn')->get($file))
            ->header('Content-Type', 'application/octet-stream')
            ->header('Cache-Control', 'no-store')
            ->header('Content-Encoding', 'gzip')
            ->header('Access-Control-Allow-Origin', '*');
    }
    if ($item == null) {
        return redirect(asset('images/thumbnail/blank.png'));
    }

    if ($item && $item->thumbnail_url != NULL && $user == false) {
        return redirect($item->thumbnail_url);
    }
    if ($item && $user == false && $item->approved == 2) {
	        return redirect(asset('images/thumbnail/disapproved.png'));
    }
    if (!File::exists($filePath)) {
        if ($user && $request->query("games")) {
            return redirect(asset('images/thumbnail/blank_place.png'));
        }
        return redirect(asset('images/thumbnail/blank.png'));
    }

    $fileContents = File::get($filePath);

    return response($fileContents)
        ->header('Content-Type', 'image/png')
        ->header('Cache-Control', 'no-store')
        ->header('Content-Encoding', 'gzip')
        ->header('Access-Control-Allow-Origin', '*');
}
}
