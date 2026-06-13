<?php

namespace App\Observers;

use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrganizer;

class CampaignOrganizerObserver
{
    public function saved(CampaignOrganizer $organizer): void
    {
        if (empty($organizer->organizer_slug)) {
            return;
        }

        if (!$organizer->wasRecentlyCreated && !$organizer->wasChanged('organizer_slug')) {
            return;
        }

        Campaign::where('organizer_id', $organizer->id)
            ->where(function ($query) use ($organizer) {
                $query->whereNull('customer_organization_slug')
                    ->orWhere('customer_organization_slug', '!=', $organizer->organizer_slug);
            })
            ->update([
                'customer_organization_slug' => $organizer->organizer_slug,
                'updated_at' => now(),
            ]);
    }
}
