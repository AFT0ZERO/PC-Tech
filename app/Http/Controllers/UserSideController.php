<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\AccountService;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Auth;

class UserSideController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private CatalogService $catalogService,
    ) {
    }

    public function landing()
    {
        $data = $this->catalogService->getLandingData();

        return view('userSide.pages.landing', $data);
    }

    public function category($id = null)
    {
        $search_param = request()->query('search');
        $data = $this->catalogService->getCategoryData($id, $search_param);

        return view('userSide.pages.category', $data);
    }

    public function singlePage($id)
    {
        $data = $this->catalogService->getSinglePageData($id);

        return view('userSide.pages.singleProduct', $data);
    }

    public function about()
    {
        $data = $this->catalogService->getStaticPageData('about');

        return view('userSide.pages.about', $data);
    }

    public function contact()
    {
        $data = $this->catalogService->getStaticPageData('contact');

        return view('userSide.pages.contact', $data);
    }

    public function faqs()
    {
        $data = $this->catalogService->getFaqsPageData();

        return view('userSide.pages.faqs', $data);
    }

    public function account()
    {
        $categories = $this->catalogService->getNavbarCategories();

        return view('userSide.pages.userAccount', ['categories' => $categories]);
    }

    public function updateAccount(UpdateAccountRequest $request, User $user)
    {
        $this->accountService->update(
            $user,
            $request->safe()->only(['fname', 'lname', 'email', 'mobile', 'gender']),
            $request->file('image')
        );

        session()->flash('success', 'User updated successfully!');

        return to_route('account');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $this->accountService->changePassword($user, $request->current_password, $request->password);

        return to_route('account')->with('password_success', 'Password changed successfully!');
    }
}
