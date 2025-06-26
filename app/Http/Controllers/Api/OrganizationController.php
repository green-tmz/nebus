<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchByGeoLocationRequest;
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

    public function searchByBuilding(Building $building): OrganizationsResource
    {
        return new OrganizationsResource(
            $building->organizations()->with(['phones', 'activities'])->paginate()
        );
    }

    public function searchByActivity(Activity $activity): OrganizationsResource
    {
        $activityIds = $activity->getDescendantsAndSelf()->pluck('id');

        $organization = Organization::whereHas('activities', function($query) use ($activityIds) {
            $query->whereIn('activity_id', $activityIds);
        })->with(['building', 'phones', 'activities'])->paginate();

        return new OrganizationsResource($organization);
    }

    public function searchByGeoLocation(SearchByGeoLocationRequest $request): OrganizationsResource
    {
        $validData = $request->validated();

        $buildings = Building::query()
            ->fromSub(function ($query) use ($validData) {
                $query->from('buildings')->selectRaw("*,
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    ) as distance",
                            [$validData['lat'], $validData['lng'], $validData['lat']]
                    );
            }, 'buildings_with_distance')
            ->where('distance', '<', $validData['radius'])
            ->orderBy('distance')
            ->pluck('id');

        $organization = Organization::whereIn('building_id', $buildings)
            ->with(['building', 'phones', 'activities'])
            ->paginate();

        return new OrganizationsResource($organization);
    }
}
