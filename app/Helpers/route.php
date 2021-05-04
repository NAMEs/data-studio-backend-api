<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (!function_exists('crudRoutes')) {
    function crudRoutes(string $name, string $controller) {
        $snakeCaseName = Str::snake(
            Str::camel($name)
        );
        Route::get($name, [$controller, 'getMany'])->name("{$name}.get-many");
        Route::get("$name/{{$snakeCaseName}}", [$controller, 'getOne'])->name("{$name}.get-one");
        Route::post("$name", [$controller, 'insertOne'])->name("{$name}.insert-one");
        Route::patch("$name/{{$snakeCaseName}}", [$controller, 'updateOne'])->name("{$name}.update-one");
        Route::delete("$name/{{$snakeCaseName}}", [$controller, 'deleteOne'])->name("{$name}.delete-one");
    }
}
