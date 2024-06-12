<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WEB\UserUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{


    public function __construct()
    {
        $this->middleware(['permission:show_user'])->only(['index', 'show']);
        $this->middleware(['permission:update_user'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_user'])->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $userQuery = User::where(function ($query) {
            $search = request('search');
            if ($search) {
                $query
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                    ->orWhere('national_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('certificate_number', 'LIKE', '%' . $search . '%');
            }
        })
            ->where(function ($query) {
                $role = in_array(request('role'), ['admin', 'user']) ? request('role') : false;
                $status = in_array(request('status'), ['active', 'inactive']) ? request('status') : false;
                if ($role) {
                    $query->where('role', $role);
                }
                if ($status) {
                    $query->where('status', $status);
                }
            });

        if (request('get-report')) {
            $users = $userQuery->get();
            return $this->getReport($users, 'users');
        }
        $users = $userQuery->paginate(20);
        return view('user.index', compact('users'));
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user)
            return redirect()->back()->withErrors(['کاربر مورد نظر یافت نشد.']);
        $peromissions = Permission::get();
        return view('user.edit', compact('user', 'peromissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user)
            return response()->json(['message' => 'کاربر مورد نظر یافت نشد.', 'redirect' => route('users.index')], 400);

        if ($user->mobile == "09123456789") 
            return response()->json(['message' => "مشخصات " . $user->first_name . " " . $user->last_name . " قابل ویرایش نمی باشد!", 'redirect' => route('users.index')], 400);
        
        $user->update($request->except('permissions'));
        if ($request->role == "admin") {
            $user->syncPermissions($request->permissions, []);
        }
        
        return response()->json(['message' => 'مشخصات کاربر به روز رسانی گردید.', 'redirect' => route('users.index')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->withErrors(['کاربر مورد نظر شما با شناسه اختصاصی ' . $id . ' موجود نمی باشد.']);
        }
        if (auth()->id() == $id) {
            return redirect()->back()->withErrors(['قادر به حذف حساب کاربری خود نمی باشید.']);
        }
        $user->appointments()->delete();
        $user->delete();
        return redirect()->back()->with('message', 'کاربر با موفقیت حذف گردید.');
    }
}
