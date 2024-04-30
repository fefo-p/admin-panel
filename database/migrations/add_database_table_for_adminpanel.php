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
                          $table->string('log_name')->nullable();
                          $table->text('description');
                          $table->nullableMorphs('subject', 'subject');
                          $table->nullableMorphs('causer', 'causer');
                          $table->json('properties')->nullable();
                          $table->timestamps();
                          $table->index('log_name');
                      });
        }

        public function down(): void
        {
            Schema::connection(config('adminpanel.database_connection'))
                  ->dropIfExists(config('adminpanel.table'));
        }
    };