<?php

namespace KieranFYI\UserUI\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use KieranFYI\Roles\Core\Traits\BuildsAccess;
use KieranFYI\UserUI\Http\Requests\SearchRequest;
use KieranFYI\UserUI\Http\Requests\StoreOrUpdateRequest;
use KieranFYI\UserUI\Models\User;
use Throwable;

class UserAPIController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use BuildsAccess;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::get();
        /** @var Carbon $updatedAt */
        $users->transform(function (User $user) {
            $this->buildAccess($user);
            return $user;
        });
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrUpdateRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrUpdateRequest $request): JsonResponse
    {
        $user = new User($request->validated());
        $user->save();
        $this->buildAccess($user);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $this->buildAccess($user);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreOrUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(StoreOrUpdateRequest $request, User $user): JsonResponse
    {
        $user->update(collect($request->validated())->filter()->toArray());
        $this->buildAccess($user);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json();
    }

    /**
     * Display a listing of the resource.
     *
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function search(SearchRequest $request): JsonResponse
    {
        /** @var Builder $users */
        $users = User::query();

        $validated = $request->validated();

        $users->when(!empty($validated['search']), function (Builder $builder) use ($validated) {
            $search = $validated['search'];
            $builder->where(function ($builder) use ($search) {
                $builder->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        });

        $paginator = $users->paginate();
        $paginator->getCollection()->transform(function (User $user) {
            $this->buildAccess($user);
            return $user;
        });

        return response()->json($paginator);
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'search' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'create', 'store', 'search'];
    }
}