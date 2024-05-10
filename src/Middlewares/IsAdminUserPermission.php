<?php

    namespace FefoP\AdminPanel\Middlewares;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Contracts\Auth\Factory as AuthFactory;

    class IsAdminUserPermission
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
        public function __construct(AuthFactory $auth)
        {
            $this->auth = $auth;
        }

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request                                                                           $request
         * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
         *
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */
        public function handle(Request $request, Closure $next)
        {
            if ( !Auth::user()?->hasAnyPermission([
                                                          'adminpanel.usuario.crear',
                                                          'adminpanel.usuario.ver',
                                                          'adminpanel.usuario.editar',
                                                          'adminpanel.usuario.borrar',
                                                          'adminpanel.rol.crear',
                                                          'adminpanel.rol.ver',
                                                          'adminpanel.rol.editar',
                                                          'adminpanel.rol.borrar',
                                                          'adminpanel.permiso.crear',
                                                          'adminpanel.permiso.ver',
                                                          'adminpanel.permiso.editar',
                                                          'adminpanel.permiso.borrar',
                                                      ])
            ) {
                return redirect('dashboard');
            }

            return $next($request);
        }
    }
