<?php

use Illuminate\Database\Migrations\Migration;
use App\Admin;
use Illuminate\Support\Facades\Hash;

class SeedFixedAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminEmail = 'ncs@jssaten.ac.in';
        $adminPassword = 'Jssaten.ac.in@011235';

        $admin = Admin::where('email', $adminEmail)->first();
        if (!$admin) {
            $admin = new Admin;
            $admin->name = 'Admin';
            $admin->email = $adminEmail;
            $admin->password = Hash::make($adminPassword);
            $admin->type = '1';
            $admin->save();
        } else {
            $admin->password = Hash::make($adminPassword);
            $admin->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Admin::where('email', 'ncs@jssaten.ac.in')->delete();
    }
}
