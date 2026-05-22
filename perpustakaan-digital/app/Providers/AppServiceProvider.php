<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // Default values to prevent undefined variable errors
            $pendingApprovalsCount = 0;
            $unpaidFinesCount = 0;
            $totalUnpaidFinesCount = 0;
            $pendingLoansCount = 0;

            // 1. Pending Approvals (Admin only should see this ideally, but safe to global)
            $pendingApprovalsCount = \App\Models\User::where('role', 'member')
                ->where('is_approved', false)
                ->count();

            // 2. Pending Loans
            $pendingLoansCount = \App\Models\Loan::where('status', 'menunggu')->count();

            // 3. Auth-based counts
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $now = now();

                // Unpaid fines for current user (Member)
                $unpaidFinesCount = \App\Models\Loan::where('user_id', $user->id)
                    ->where('is_paid', false)
                    ->where(function($query) use ($now) {
                        $query->where('fine_amount', '>', 0)
                              ->orWhere(function($q) use ($now) {
                                  $q->whereNull('return_date')->where('due_date', '<', $now);
                              });
                    })->count();

                // Global unpaid fines for Admin
                if ($user->role === 'admin') {
                    $totalUnpaidFinesCount = \App\Models\Loan::where('is_paid', false)
                        ->where(function($query) use ($now) {
                            $query->where('fine_amount', '>', 0)
                                  ->orWhere(function($q) use ($now) {
                                      $q->whereNull('return_date')->where('due_date', '<', $now);
                                  });
                        })->count();
                }
            }

            $view->with([
                'pendingApprovalsCount' => $pendingApprovalsCount,
                'pendingLoansCount' => $pendingLoansCount,
                'unpaidFinesCount' => $unpaidFinesCount,
                'totalUnpaidFinesCount' => $totalUnpaidFinesCount,
            ]);
        });
    }
}
