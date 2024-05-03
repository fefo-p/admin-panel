<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            $tables = config('permission.table_names');
            if (! Schema::hasColumn($tables['roles'], 'deleted_at'))
            {
                Schema::table($tables['roles'], function (Blueprint $table)
                {
                    $table->softDeletes();
                });
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            $tables = config('permission.table_names');
            Schema::table($tables['roles'], function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    };
