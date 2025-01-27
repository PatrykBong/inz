<?php

namespace MyVendor\MyLibrary;

use Illuminate\Support\ServiceProvider;

class MyLibraryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Rejestracja usług, jeśli potrzebne
    }

    public function boot()
    {
        // Logika inicjalizacyjna
    }
}