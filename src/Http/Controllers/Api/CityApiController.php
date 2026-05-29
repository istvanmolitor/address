<?php

namespace Molitor\Address\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Molitor\Address\Http\Requests\StoreCityRequest;
use Molitor\Address\Http\Requests\UpdateCityRequest;
use Molitor\Address\Http\Resources\CityResource;
use Molitor\Address\Repositories\CityRepositoryInterface;

class CityApiController extends Controller
{
    public function __construct(
        private CityRepositoryInterface $cityRepository
    ) {}
    public function index(Request $request): JsonResponse
    {
        $query = City::query()->with('country.translations');
        $this->applyFilters($query, $request);

        $cities = $query
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => CityResource::collection($cities->items()),
            'meta' => [
                'current_page' => $cities->currentPage(),
                'last_page' => $cities->lastPage(),
                'per_page' => $cities->perPage(),
                'total' => $cities->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $city = $this->cityRepository->create(
            (int) $validated['country_id'],
            $validated['name'],
            $validated['zip_code'] ?? null,
            (bool) ($validated['is_valid'] ?? true),
        );

        $city->load('country.translations');

        return response()->json([
            'data' => new CityResource($city),
            'message' => __('address::messages.created'),
        ], 201);
    }

    public function show(City $city): JsonResponse
    {
        $city->load('country.translations');

        return response()->json([
            'data' => new CityResource($city),
        ]);
    }

    public function edit(City $city): JsonResponse
    {
        $city->load('country.translations');

        return response()->json([
            'data' => new CityResource($city),
        ]);
    }

    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        $validated = $request->validated();

        $city->update($validated);

        $city->load('country.translations');

        return response()->json([
            'data' => new CityResource($city),
            'message' => __('address::messages.updated'),
        ]);
    }

    public function destroy(City $city): JsonResponse
    {
        $city->delete();

        return response()->json([
            'message' => __('address::messages.deleted'),
        ]);
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function (Builder $searchQuery) use ($search): void {
                $searchQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('zip_code', 'like', "%{$search}%");
            });
        }

        $sort = (string) $request->input('sort', 'name');
        $direction = strtolower((string) $request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        if (in_array($sort, ['id', 'name', 'zip_code', 'country_id', 'created_at', 'updated_at'], true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name', 'asc');
        }
    }
}
