<?php
    
    namespace FefoP\AdminPanel\Middlewares;
    
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Contracts\Auth\Factory as AuthFactory;
    
    class IsAdmin
    {
        /**
         * The guard factory instance.
         *
         * @var \Illuminate\Contracts\Auth\Factory
         */
        protected $auth;
        
        /**
         * Create a new middleware instance.
         *
         * @param  \Illuminate\Contracts\Auth\Factory  $auth
         *
         * @return void
         */
        public function __construct( AuthFactory $auth )
        {
            $this->auth = $auth;
        }
        
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
         *
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */
        public function handle( Request $request, Closure $next )
        {
            if ( ! $request->user()?->hasAnyPermission( [
                                                            'administrar usuarios',
                                                            'administrar roles',
                                                            'administrar permisos',
                                                        ] ) ) {
                return redirect( 'dashboard' );
            }
            
            return $next( $request );
        }
    }
