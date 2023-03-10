<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'min:3', 'max: 50'],
            'message' => ['required'],
        ]);

        Contact::create($validatedData);

        return response()->json(
            [
                'message' => 'Your application has been successfully sent! We will get back to you as soon as possible.'
            ]
        );
    }
}
