<?php

namespace App\Http\Controllers;

use App\Jobs\SendFirebaseNoti;
use App\Models\Firebase;
use App\Models\RecipeRequest;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function index()
    {
        return view('firebase');
    }

    public function sendFirebase(Request $request): \Illuminate\Http\JsonResponse
    {
        $title = $request->title;
        $body = $request->body;
        $action = new SendFirebaseNoti($title, $body);
        dispatch($action);
        return response()->json(['message' => 'success'], 200);
    }

    public function showRequest(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $recipes = RecipeRequest::all();
        return view('request', compact('recipes'));
    }
}
