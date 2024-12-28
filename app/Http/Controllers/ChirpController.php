<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ChirpController extends Controller
{
    // public function index():Response
    public function index(): View
    {
        // return response('Hello, World!');
        return view('chirps', [
            // 
        ]);
    }
    // public function destroy(Chirp $chirp) {
    //     $chirp->delete
    // }
}
