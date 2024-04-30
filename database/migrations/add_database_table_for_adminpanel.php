<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::connection(config('adminpanel.database_connection'))
                  ->create(config('adminpanel.table'),
                      function (Blueprint $table) {
                          $table->bigIncrements('id');
                          $table->string('log_name')->nullable()->index();
                          $table->text('description');
                          $table->nullableMorphs('subject', 'subject');
                          $table->string('event')->nullable();
                          $table->nullableMorphs('causer', 'causer');
                          $table->json('properties')->nullable();
                          $table->uuid('batch_uuid')->nullable();
                          $table->timestamps();
                          $table->softDeletes();
                      });
        }

        public function down(): void
        {
            Schema::connection(config('adminpanel.database_connection'))
                  ->dropIfExists(config('adminpanel.table'));
        }
    };