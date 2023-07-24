<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('Name', 50);
            $table->string('Surname', 50);
            $table->string('email', 150)->unique();
            $table->string('Mobile', 30)->nullable();
            $table->string('Phone', 30)->nullable();
            $table->string('Address', 200)->nullable();
            $table->string('City', 100)->nullable();
            $table->string('County', 10)->nullable();
            $table->string('Country', 50)->nullable();
            $table->string('Lang', 2);
            $table->string('CompanyName', 100)->nullable();
            $table->string('CompanyPhone', 30)->nullable();
            $table->string('CompanyAddress', 200)->nullable();
            $table->string('CompanyCity', 100)->nullable();
            $table->string('CompanyCounty', 10)->nullable();
            $table->string('CompanyCountry', 50)->nullable();
            $table->string('CompanyVAT', 11)->nullable();
            $table->boolean('Active')->nullable(false)->default(1);
            $table->integer('UserType')->nullable(false)->default(2);
            $table->text('Notes')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Crea un SuperAdmin ad ogni lancio della migrazione

        User::create([
            'Name' => 'SuperAdmin',
            'Surname' => 'Atik',
            'email' => 'sviluppo@atik.it',
            'Mobile' => '',
            'Lang' => 'it',
            'password' => Hash::make('Sup3r%4dm1n22!'),
            'Active' => 1,
            'UserType' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
