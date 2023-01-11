<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('last-chaos')
            ->table(
                config('last-chaos.database.schemas.auth') . '.bg_user',
                static function (Blueprint $table) {
                    $table->unsignedBigInteger('nox_user_id')->index();
                });
    }
};
