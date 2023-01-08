<?php

namespace KieranFYI\UserUI\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use KieranFYI\UserUI\Events\RegisterUserInfoEvent;
use KieranFYI\UserUI\Events\RegisterUserSidebarEvent;
use KieranFYI\UserUI\Events\RegisterUserTabEvent;
use KieranFYI\UserUI\Http\Requests\StoreOrUpdateRequest;
use KieranFYI\UserUI\Policies\UserPolicy;
use KieranFYI\UserUI\Services\RegisterUserComponent;
use KieranFYI\UserUI\Services\RegisterUserTab;
use Throwable;
use TypeError;
use AppUser as User;

class UserController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(UserPolicy::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('users-ui::index', [
            'users' => User::paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('users-ui::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrUpdateRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrUpdateRequest $request): RedirectResponse
    {
        $user = new User($request->validated());
        $user->save();

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param User $user
     * @return View|RedirectResponse
     * @throws Throwable
     */
    public function show(Request $request, User $user): View|RedirectResponse
    {
        return view('users-ui::show', [
            'user' => $user,
            'tabs' => $this->tabs($user, $request->get('tab')),
            'infos' => $this->components(RegisterUserInfoEvent::class, $user),
            'sidebars' => $this->components(RegisterUserSidebarEvent::class, $user),
        ]);
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
        $validated = collect($request->validated())
            ->filter()
            ->toArray();
        $user->update($validated);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    /**
     * @param User $user
     * @param string|null $activeTab
     * @return Collection
     * @throws Throwable
     */
    private function tabs(User $user, string $activeTab = null): Collection
    {
        $rawTabs = event(RegisterUserTabEvent::class, [$user]);
        $tabs = collect();
        foreach ($rawTabs as $rawTab) {
            $rawTab = is_array($rawTab) ? $rawTab : [$rawTab];
            foreach ($rawTab as $tab) {
                throw_unless($tab instanceof RegisterUserTab, TypeError::class, self::class . '::handle(): ' . RegisterUserTabEvent::class . ' return must be of type ' . RegisterUserTab::class);

                /** @var RegisterUserTab $tab */
                $id = Str::slug($tab->name());

                $tabs->add([
                    'id' => $id,
                    'active' => $activeTab === $id,
                    'name' => $tab->name(),
                    'tab' => $tab,
                ]);
            }
        }

        return $tabs->sortBy('name');
    }

    /**
     * @param string $event
     * @param array $arguments
     * @return Collection
     * @throws Throwable
     */
    private function components(string $event, ...$arguments): Collection
    {
        $rawComponents = event($event, $arguments);
        $components = collect();
        foreach ($rawComponents as $rawComponent) {
            $rawComponent = is_array($rawComponent) ? $rawComponent : [$rawComponent];
            foreach ($rawComponent as $component) {
                throw_unless($component instanceof RegisterUserComponent, TypeError::class, self::class . '::handle(): ' . $event . ' return must be of type ' . RegisterUserTab::class);

                /** @var RegisterUserComponent $component */
                $components->add($component);
            }
        }

        return $components;
    }

}