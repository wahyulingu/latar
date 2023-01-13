<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Dashboard\Content Routes
|--------------------------------------------------------------------------
|
| Here is where you can register content routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "content" middleware group. Now create something great!
|
*/

/*
/--------------------------------------------------------------------------
/ Saya sengaja tidak menggunakan method shallow() pada route karena
/ hasilnya tidak seperti yang diharapkan.
/--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render(
        component: 'Welcome',
        props: [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]
    );
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::name('dashboard.')->prefix('dashboard')->group(function () {
        Route::name('profile.')->prefix('profile')->group(function () {
            $profileResources = [
                'author' => Dashboard\Profile\AuthorController::class,
                'customer' => Dashboard\Profile\CustomerController::class,
                'driver' => Dashboard\Profile\DriverController::class,
                'owner' => Dashboard\Profile\OwnerController::class,
            ];

            $profileParameters = [
                'author' => 'user:username',
                'customer' => 'user:username',
                'driver' => 'user:username',
                'owner' => 'user:username',
            ];

            Route::resources(
                resources: $profileResources,
                options: [
                    'parameters' => $profileParameters,
                    'only' => ['index', 'show'],
                ]
            );

            Route::resources(
                resources: $profileResources,
                options: [
                    'middleware' => 'can:update,user',
                    'parameters' => $profileParameters,
                    'only' => ['edit', 'update'],
                ]
            );

            foreach ($profileResources as $name => $controller) {
                Route::middleware('can:update,user')->group(
                    function () use ($name, $controller) {
                        Route::get(sprintf('%s/{user:username}/create', $name), [$controller, 'create'])->name(sprintf('%s.create', $name));
                        Route::post(sprintf('%s/{user:username}', $name), [$controller, 'store'])->name(sprintf('%s.store', $name));
                    });
            }
        });

        Route::name('product.')->prefix('product')->group(function () {
            Route::resource('category', Dashboard\Product\CategoryController::class);

            Route::resources(
                resources: [
                    'photo' => Dashboard\Product\PhotoController::class,
                    'video' => Dashboard\Product\VideoController::class,
                    'specification' => Dashboard\Product\SpecificationController::class,
                    'variant' => Dashboard\Product\VariantController::class,
                ],
                options: [
                    'except' => ['index', 'create', 'store'],
                ],
            );

            Route::resources(
                resources: [
                    'variant.photo' => Dashboard\Product\Variant\PhotoController::class,
                    'variant.video' => Dashboard\Product\Variant\VideoController::class,
                    'variant.specification' => Dashboard\Product\Variant\SpecificationController::class,
                ],
                options: [
                    'middleware' => 'can:update,variant',
                    'only' => ['index', 'create', 'store'],
                ],
            );
        });

        Route::name('content.')->prefix('content')->group(function () {
            Route::resource('article', Dashboard\Content\ArticleController::class);
            Route::resource('page', Dashboard\Content\PageController::class);
            Route::resource('category', Dashboard\Content\CategoryController::class);

            Route::resources(
                resources: [
                    'photo' => Dashboard\Content\PhotoController::class,
                    'video' => Dashboard\Content\VideoController::class,
                ],
                options: [
                    'except' => ['index', 'create', 'store'],
                ],
            );

            Route::resources(
                resources: [
                    'article.photo' => Dashboard\Content\Article\PhotoController::class,
                    'article.video' => Dashboard\Content\Article\VideoController::class,
                ],
                options: [
                    'middleware' => 'can:update,article',
                    'only' => ['index', 'create', 'store'],
                ],
            );

            Route::resources(
                resources: [
                    'page.photo' => Dashboard\Content\Page\PhotoController::class,
                    'page.video' => Dashboard\Content\Page\VideoController::class,
                ],
                options: [
                    'middleware' => 'can:update,page',
                    'only' => ['index', 'create', 'store'],
                ],
            );
        });

        Route::resources(
            resources: [
                'product.photo' => Dashboard\Product\Master\PhotoController::class,
                'product.video' => Dashboard\Product\Master\VideoController::class,
                'product.specification' => Dashboard\Product\Master\SpecificationController::class,
                'product.variant' => Dashboard\Product\Master\VariantController::class,
            ],
            options: [
                'middleware' => 'can:update,product',
                'only' => ['index', 'create', 'store'],
            ],
        );

        Route::resource('product', Dashboard\Product\MasterController::class);
    });
});
