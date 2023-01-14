<?php

namespace KieranFYI\UserUI\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use KieranFYI\Misc\Traits\ResponseCacheable;
use KieranFYI\UserUI\Events\RegisterUserInfoEvent;
use KieranFYI\UserUI\Events\RegisterUserSidebarEvent;
use KieranFYI\UserUI\Events\RegisterUserTabEvent;
use KieranFYI\UserUI\Http\Requests\StoreOrUpdateRequest;
use KieranFYI\UserUI\Models\User;
use KieranFYI\UserUI\Services\RegisterUserComponent;
use KieranFYI\UserUI\Services\RegisterUserTab;
use Throwable;
use TypeError;

class UserController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
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
     * @return View
     * @throws Throwable
     */
    public function index(): View
    {
        $this->cached();
        return view('users-ui::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     * @throws Throwable
     */
    public function create(): View
    {
        $this->cached();
        return view('users-ui::create');
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
        $this->cached();
        return view('users-ui::show', [
            'user' => $user,
            'tabs' => $this->tabs($user, $request->get('tab')),
            'infos' => $this->components(RegisterUserInfoEvent::class, $user),
            'sidebars' => $this->components(RegisterUserSidebarEvent::class, $user),
        ]);
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
            if (is_null($rawTab)) {
                continue;
            }

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
            if (is_null($rawComponent)) {
                continue;
            }

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