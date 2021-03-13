<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class TestController extends Controller {
	public function auth() {
        dd(auth()->id());
    }
}