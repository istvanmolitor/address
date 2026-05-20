<?php

namespace Molitor\Address\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Molitor\Address\Http\Requests\StoreCountryRequest;
use Molitor\Address\Http\Requests\UpdateCountryRequest;
use Molitor\Address\Http\Resources\CountryResource;
use Molitor\Address\Models\Country;
use Molitor\Address\Models\CountryTranslation;
use Molitor\Language\Models\Language;

class CountryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Country::query()->with('translations');
        $this->applyFilters($query, $request);

        $perPage = (int) $request->input('per_page', 10);
        $countries = $query
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'data' => CountryResource::collection($countries->items()),
            'meta' => [
                'current_page' => $countries->currentPage(),
                'last_page' => $countries->lastPage(),
                'per_page' => $countries->perPage(),
                'total' => $countries->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([]);
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $country = Country::query()->create([
            'code' => $validated['code'],
            'is_default' => (bool) ($validated['is_default'] ?? false),
        ]);

        $this->syncTranslation($country, $validated['name']);

        if ($country->is_default) {
            $this->clearOtherDefaults($country->id);
        }

        $country->load('translations');

        return response()->json([
            'data' => new CountryResource($country),
            'message' => __('address::messages.created'),
        ], 201);
    }

    public function show(Country $country): JsonResponse
    {
        $country->load('translations');

        return response()->json([
            'data' => new CountryResource($country),
        ]);
    }

    public function edit(Country $country): JsonResponse
    {
        $country->load('translations');

        return response()->json([
            'data' => new CountryResource($country),
        ]);
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        $validated = $request->validated();

        $country->update([
            'code' => $validated['code'],
            'is_default' => (bool) ($validated['is_default'] ?? false),
        ]);

        $this->syncTranslation($country, $validated['name']);

        if ($country->is_default) {
            $this->clearOtherDefaults($country->id);
        }

        $country->load('translations');

        return response()->json([
            'data' => new CountryResource($country),
            'message' => __('address::messages.updated'),
        ]);
    }

    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json([
            'message' => __('address::messages.deleted'),
        ]);
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function (Builder $searchQuery) use ($search): void {
                $searchQuery->where('code', 'like', "%{$search}%")
                    ->orWhereHas('translations', function (Builder $translationQuery) use ($search): void {
                        $translationQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $sort = (string) $request->input('sort', 'code');
        $direction = strtolower((string) $request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        if (in_array($sort, ['id', 'code', 'is_default', 'created_at', 'updated_at'], true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('code', 'asc');
        }
    }

    private function syncTranslation(Country $country, string $name): void
    {
        $languageId = $this->resolveLanguageId();

        CountryTranslation::query()->updateOrCreate(
            [
                'country_id' => $country->id,
                'language_id' => $languageId,
            ],
            [
                'name' => $name,
            ]
        );
    }

    private function resolveLanguageId(): int
    {
        $languageId = Language::query()
            ->where('code', (string) config('app.locale'))
            ->value('id');

        if ($languageId === null) {
            $languageId = Language::query()->value('id');
        }

        if ($languageId === null) {
            throw ValidationException::withMessages([
                'language' => __('address::messages.language_missing'),
            ]);
        }

        return (int) $languageId;
    }

    private function clearOtherDefaults(int $countryId): void
    {
        Country::query()
            ->where('id', '<>', $countryId)
            ->update(['is_default' => false]);
    }
}

