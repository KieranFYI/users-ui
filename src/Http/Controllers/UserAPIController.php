<?php

namespace KieranFYI\UserUI\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use KieranFYI\Logging\Http\Requests\LogSearchRequest;
use KieranFYI\Logging\Traits\LoggableResponse;
use KieranFYI\Misc\Traits\ResponseCacheable;
use KieranFYI\UserUI\Http\Requests\SearchRequest;
use KieranFYI\UserUI\Http\Requests\StoreOrUpdateRequest;
use KieranFYI\UserUI\Models\User;
use Throwable;

class UserAPIController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
    use LoggableResponse;
    use ResponseCacheable;

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
     * @throws Throwable
     */
    public function index(): JsonResponse
    {
        $this->cached();
        $users = User::paginate();
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
        $this->cached();
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
     * @throws Throwable
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $this->cached();
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

        return response()->json($users->paginate());
    }

    /**
     * Display a listing of the resource logs.
     *
     * @param LogSearchRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function logs(LogSearchRequest $request, User $user): JsonResponse
    {
        return $this->loggableResponse($request, $user);
    }

    /**\\
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
            'logs' => 'view',
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