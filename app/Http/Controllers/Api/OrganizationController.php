<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchByNameRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\OrganizationsResource;
use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationController extends Controller
{
    public function index(): OrganizationsResource
    {
        $organizations = Organization::with(['building', 'phones', 'activities'])->paginate();
        return new OrganizationsResource($organizations);
    }

    public function show(Organization $organization): OrganizationResource
    {
        return new OrganizationResource(
            $organization->load(['building', 'phones', 'activities'])
        );
    }

    public function searchByName(SearchByNameRequest $request): OrganizationsResource
    {
        $validData = $request->validated();

        $organization = Organization::where('name', 'like', "%{$validData['name']}%")
            ->with(['building', 'phones', 'activities'])
            ->paginate();

        return new OrganizationsResource($organization);
    }

    public function searchByBuilding(Building $building): LengthAwarePaginator
    {
        return $building->organizations()->with(['phones', 'activities'])->paginate();
    }

    public function searchByActivity(Activity $activity)
    {
        $activityIds = $activity->getDescendantsAndSelf()->pluck('id');

        return Organization::whereHas('activities', function($query) use ($activityIds) {
            $query->whereIn('business_activity_id', $activityIds);
        })->with(['building', 'phones', 'activities'])->paginate();
    }

    public function searchByGeoLocation(Request $request)
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
