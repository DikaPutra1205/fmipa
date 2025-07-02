<?php
// File: app/Providers/MenuServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Pastikan kita hanya menjalankan ini jika ada user yang login
            if (Auth::check()) {
                $user = Auth::user();

                $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);

                // Filter menu berdasarkan hak akses
                $filteredMenu = array_filter($verticalMenuData->menu, function ($menuItem) use ($user) {
                    // Jika item tidak memiliki kunci 'roles', tampilkan untuk semua orang
                    if (!isset($menuItem->roles)) {
                        return true;
                    }
                    // Jika ada kunci 'roles', cek apakah role user ada di dalam array tersebut
                    return in_array($user->role, $menuItem->roles);
                });

                // Buat ulang objek menu dengan data yang sudah difilter
                $filteredVerticalMenuData = new \stdClass();
                $filteredVerticalMenuData->menu = array_values($filteredMenu); // array_values untuk reset index

                // Untuk horizontalMenu, lakukan hal yang sama jika perlu
                $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
                $horizontalMenuData = json_decode($horizontalMenuJson);

                // Bagikan data menu yang SUDAH DIFILTER ke semua view
                $view->with('menuData', [$filteredVerticalMenuData, $horizontalMenuData]);
            }
        });
    }
}