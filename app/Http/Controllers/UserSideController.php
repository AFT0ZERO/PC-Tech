<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountService;
use App\Services\CatalogService;
use Illuminate\Http\Request;
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


    public function updateAccount(Request $request, User $user)
    {
        $request->validate([
            'fname' => ['required', 'min:3'],
            'lname' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'numeric'],
            'gender' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        $this->accountService->update(
            $user,
            [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'gender' => $request->gender,
            ],
            $request->file('image')
        );

        session()->flash('success', 'User updated successfully!');
        return to_route('account');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!$this->accountService->changePassword($user, $request->current_password, $request->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        return to_route('account')->with('password_success', 'Password changed successfully!');
    }



}
