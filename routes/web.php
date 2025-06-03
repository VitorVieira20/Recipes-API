<?php

use App\Http\Controllers\DocumentationController;
use App\Livewire\ApiDocs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});