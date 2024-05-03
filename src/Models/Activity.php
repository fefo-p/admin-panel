<?php

    namespace FefoP\AdminPanel\Models;

    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Database\Eloquent\Model;

    class Activity extends Model
    {
        protected        $table    = null;
        protected string $log_file = 'logs/activity.log';
        protected        $fillable = [
            'ip',
            'user_id',
            'user_uuid',
            'user_cuil',
            'subject_type',
            'subject_id',
            'subject_cuil',
            'event',
            'properties',
        ];

        public function __construct(array $attributes = [])
        {
            parent::__construct($attributes);
            $this->table = config('adminpanel.table');
        }

        public function log($message): void
        {
            Log::build([
                           'driver' => 'single',
                           'path'   => storage_path($this->log_file),
                       ])
               ->info($message);
        }

        public static function write(array $attributes): self
        {
            return self::create([
                                    'ip'           => $attributes[ 'ip' ],
                                    'user_id'      => Auth::id(),
                                    'user_uuid'    => Auth::user()->uuid,
                                    'user_cuil'    => Auth::user()->cuil,
                                    'subject_type' => $attributes[ 'subject_type' ],
                                    'subject_id'   => $attributes[ 'subject_id' ],
                                    'subject_cuil' => $attributes[ 'subject_cuil' ],
                                    'event'        => $attributes[ 'event' ],
                                    'properties'   => $attributes[ 'properties' ],
                                ]);
        }
    }
