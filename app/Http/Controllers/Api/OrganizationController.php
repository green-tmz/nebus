<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['building', 'phones', 'activities'])->paginate();
        return OrganizationResource::collection($organizations);
    }

    public function show(Organization $organization): Organization
    {
        return $organization->load(['building', 'phones', 'activities']);
    }

    public function searchByName(Request $request)
    {
        $name = $request->query('name');

        return Organization::where('name', 'like', "%{$name}%")
            ->with(['building', 'phones', 'activities'])
            ->paginate();
    }

    public function byBuilding(Building $building): LengthAwarePaginator
    {
        return $building->organizations()->with(['phones', 'activities'])->paginate();
    }

    public function byActivity(Activity $activity)
    {
        $activityIds = $activity->getDescendantsAndSelf()->pluck('id');

        return Organization::whereHas('activities', function($query) use ($activityIds) {
            $query->whereIn('business_activity_id', $activityIds);
        })->with(['building', 'phones', 'activities'])->paginate();
    }

    public function byGeoLocation(Request $request)
    {
        $latitude = $request->query('lat');
        $longitude = $request->query('lng');
        $radius = $request->query('radius', 10);

        $buildings = Building::selectRaw('*,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))
            AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->pluck('id');

        return Organization::whereIn('building_id', $buildings)
            ->with(['building', 'phones', 'activities'])
            ->paginate();
    }
}
