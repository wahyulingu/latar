<?php

namespace App\Providers;

use App\Models\Content\ContentArticle;
use App\Models\Content\ContentCategory;
use App\Models\Content\ContentPage;
use App\Models\Content\ContentPhoto;
use App\Models\Content\ContentVideo;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductPhoto;
use App\Models\Product\ProductSpecification;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductVideo;
use App\Models\Profile\ProfileOwner;
use App\Models\Team;
use App\Models\User;
use App\Policies\Content;
use App\Policies\Product;
use App\Policies\Profile;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        User::class => UserPolicy::class,

        ProductCategory::class => Product\CategoryPolicy::class,
        ProductMaster::class => Product\MasterPolicy::class,
        ProductVariant::class => Product\VariantPolicy::class,
        ProductSpecification::class => Product\SpecificationPolicy::class,
        ProductPhoto::class => Product\PhotoPolicy::class,
        ProductVideo::class => Product\VideoPolicy::class,

        ContentCategory::class => Content\CategoryPolicy::class,
        ContentArticle::class => Content\ArticlePolicy::class,
        ContentPage::class => Content\PagePolicy::class,
        ContentPhoto::class => Content\PhotoPolicy::class,
        ContentVideo::class => Content\VideoPolicy::class,

        ProfileOwner::class => Profile\OwnerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
