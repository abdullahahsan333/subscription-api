<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire old user subscriptions';

    public function handle()
    {
        $today = now()->toDateString();
        $expired = UserSubscription::where('status', 'active')
            ->whereDate('end_date', '<', $today)
            ->get();

        foreach ($expired as $sub) {
            $sub->update(['status' => 'expired']);
            $sub->user->update(['subscription_status' => 'expired']);
            \Log::info("Subscription ID {$sub->id} expired.");
        }

        $this->info("Expired {$expired->count()} subscriptions.");
    }
}
