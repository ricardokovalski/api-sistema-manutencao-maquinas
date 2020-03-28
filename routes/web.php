<?php

use App\Entities\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (\App\Repositories\Roles\RoleRepository $repository) {

    //$role = Role::create(['name' => 'writer']);
    //$role = Role::findById(1);
    //dump($role);
    //$permission = Permission::create(['name' => 'edit articles']);
    //$permission = Permission::findById(1);
    //dump($permission);

    /*$role->givePermissionTo($permission);
    $permission->assignRole($role);*/

    //$role->revokePermissionTo($permission);
    //$permission->removeRole($role);

    //$user = User::find(1);
    //$user->revokePermissionTo($permission);

    /*dump($user->getPermissionsViaRoles()->pluck('name'));
    dump($user->getPermissionNames());
    dump($user->getRoleNames());
    dd($user->hasPermissionTo($permission->id));*/

    //dd($repository->findById(1));

    return view('welcome');
});
