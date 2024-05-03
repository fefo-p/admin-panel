<?php

    use App\Models\User;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create(config('adminpanel.table'), function (Blueprint $table) {
                $table->id();
                $table->timestamp('created_at', 0)->nullable();
                $table->string('ip');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->char('user_uuid', 36);
                $table->unsignedBigInteger('user_cuil');
                $table->morphs('subject');
                $table->unsignedBigInteger('subject_cuil')->nullable();
                $table->string('event');
                $table->json('properties');

                $table->timestamp('updated_at', 0)->nullable();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists(config('adminpanel.table'));
        }
    };
