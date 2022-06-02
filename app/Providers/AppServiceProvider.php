<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::replacer('required_with', function ($message, $attribute, $rule, $parameters) {
            // if attribute match with proper signature
            // dd($attribute);
           if(\Str::startsWith($attribute, 'staff_id.')) {
               $attributeArr = explode('.', $attribute);
               $staffId = $attributeArr[1];
               $field_staff=User::select('name')->find($staffId);
               if ($attributeArr[2]=='entity_visit_count') {
                    $newMessage = "The No. of Entity Visit is required for selected $field_staff->name field staff's row";
               
               }
               return $newMessage;
           }
        });
    }
}
