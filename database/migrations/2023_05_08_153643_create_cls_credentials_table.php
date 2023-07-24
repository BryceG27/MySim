<?php

use App\Models\Credential;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClsCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable(false);
            $table->string('client_secret')->nullable(false);
            $table->string('access_token')->nullable(false)->default('access_token');
            $table->timestamps();
        });

        Credential::create([
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'CLIENT_SECRET'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credentials');
    }
}
