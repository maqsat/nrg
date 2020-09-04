<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Auth gates for: AdminController
        Gate::define('admin_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('not_cash_bonuses_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('not_cash_bonuses_travel_bonus', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('not_cash_bonuses_status_no_cash_bonus', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('offices_bonus_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_reviews_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_reviews_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_reviews_add', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_reviews_status', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_comments_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_comments_status', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_notifications_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_progress_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_programs_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_orders_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_overview_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: UserController
        Gate::define('admin_user_activation', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_deactivation', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_success_basket', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_cancel_basket', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_upgrade', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_deactivation_upgrade', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_transfer', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_program', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_processing', function ($user) {
            return $user->admin && in_array($user->role_id, [0,2]);
        });

        Gate::define('admin_user_add_bonus', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0,1,2]);
        });

        Gate::define('admin_user_upgrade', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]) || !$user->admin && in_array($user->role_id, [1]);
        });

        Gate::define('admin_user_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_user_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: PackageController
        Gate::define('admin_package_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_package_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_package_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_package_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: RoleController
        Gate::define('admin_role_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_role_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_role_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_role_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: OfficeController
        Gate::define('admin_office_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_office_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_office_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_office_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: CityController
        Gate::define('admin_city_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_city_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_city_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_city_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: CountryController
        Gate::define('admin_country_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_country_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_country_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_country_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: ProductController
        Gate::define('admin_product_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_product_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_product_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_product_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: PageController
        Gate::define('admin_page_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_page_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_page_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_page_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: NewsController
        Gate::define('admin_news_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_news_create', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_news_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_news_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: ProcessingController
        Gate::define('admin_processing_view', function ($user) {
            return $user->admin && in_array($user->role_id, [0,1]);
        });

        Gate::define('admin_processing_status_request', function ($user) {
            return $user->admin && in_array($user->role_id, [0,1]);
        });

        Gate::define('admin_processing_status_out', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_status_cancel', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_status_in', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_status_transfered_in', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_status_step', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_status_update', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_created', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_edit', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_processing_destroy', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: Admin menu
        Gate::define('admin_menu_item_users', function ($user) {
            return $user->admin && in_array($user->role_id, [0,1,2]);
        });

        Gate::define('admin_menu_item_settings', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_income', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_processing', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_shop', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_news', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_additional', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_menu_item_profile', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: Settings
        Gate::define('admin_statuses_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_types_of_bonuses_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_faq_access', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        // Auth gates for: Actions on users page
        Gate::define('admin_actions_user', function ($user) {
            return $user->admin && in_array($user->role_id, [0,2]);
        });

        Gate::define('admin_transfer_user', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_review_add_user', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_go_under_user', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_activation_user', function ($user) {
            return $user->admin && in_array($user->role_id, [0]);
        });

        Gate::define('admin_column_pv', function ($user) {
            return $user->admin && in_array($user->role_id, [0,1]);
        });
    }
}
