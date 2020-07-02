<?php

use Illuminate\Database\Seeder;
use App\Invite;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invites')->delete();
        Invite::create(['invite_code' => '232323']);
    }
}
