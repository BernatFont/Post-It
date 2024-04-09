import Cookies from 'js-cookie'
import store from "../store";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')
const GuestLayout = ()  => import('../layouts/Guest.vue');
const UserLayout = () => import('../layouts/AppPostIt.vue')

const Home = () => import('../views/home/index.vue')
const Feed = () => import('../views/user/feed.vue');
const Usuario = () => import('../views/user/perfil.vue');
// const Buscar = () => import('');
const Chats= () => import('../views/user/chats.vue');
const Chat= () => import('../views/user/chat.vue');
const Mensajes= () => import('../views/user/mensajes.vue');
const Notificaciones= () => import('../views/user/notificaciones.vue');
const CrearPublicacion= () => import('../views/user/crearPublicacion.vue');
const EditarPublicacion  = ()  => import('../views/user/actualizarPublicacion.vue');
const ViewSeguidores= () => import('../views/user/viewSeguidores.vue');
const ViewSeguidos= () => import('../views/user/viewSeguidos.vue');
const MostrarPublicacion= () => import('../views/user/publicacion.vue');

const PostsIndex  = ()  => import('../views/admin/posts/Index.vue');
const PostsCreate  = ()  => import('../views/admin/posts/Create.vue');
const PostsEdit  = ()  => import('../views/admin/posts/Edit.vue');
const ExercisesIndex  = ()  => import('../views/admin/exercises/Index.vue');
const ExercisesCreate  = ()  => import('../views/admin/exercises/Create.vue');
const ExercisesEdit  = ()  => import('../views/admin/exercises/Edit.vue');

function requireLogin(to, from, next) {
    let isLogin = false;
    isLogin = !!store.state.auth.authenticated;
    console.log(store.state.auth);
    if (isLogin) {
        next()
    } else {
        next('/') // Si no detecta el login redirige a la ruta indicada
    }
}

function guest(to, from, next) {
    let isLogin;
    isLogin = !!store.state.auth.authenticated;

    if (isLogin) {
        next('/inicio')
    } else {
        next()
    }
}


export default [
    {
        path: '/',
        name: 'welcome',
        component: Home
    },
    {
        path: '/inicio',
        name: "inicio",
        component: UserLayout,
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Feed'},
        children: [
            {
                path: '',
                name: 'feed',
                component: Feed,
                meta: { breadCrumb: 'Feed Index' }
            },
            {
                name: 'publicacion.create',
                path: 'create',
                component: CrearPublicacion,
                meta: { breadCrumb: 'Crear publicación' }
            },
            {
                name: 'publicacion.mostrar',
                path: 'mostrar/:id',
                component: MostrarPublicacion,
                meta: { breadCrumb: 'Mostrar publicación' }
            },
            {
                  name: 'publicacion.update',
                  path: 'update/:id',
                  component: EditarPublicacion,
                  meta: { breadCrumb: 'Modificar publicacion' }
            },
        ]
    },
    {
        path: '/usuario',
        component: UserLayout,
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Perfil' },
        children: [
            {
                name: '',
                path: ':username',
                meta: { breadCrumb: ''},
                children: [
                    {
                        name: 'usuario.mostrar',
                        path: '',
                        component: Usuario,
                        meta: { breadCrumb: 'Perfil usuario'},
                    },
                    // {
                    // name: 'usuario.editar',
                    //path: 'editar',
                    //component: Usuario,
                    //meta: { breadCrumb: 'Editar usuario'},
                    // },
                    {
                        name: 'usuario.seguidores',
                        path: 'seguidores',
                        component: ViewSeguidores,
                        meta: { breadCrumb: 'Vista de seguidores' }
                    },
                    {
                        name: 'usuario.seguidos',
                        path: 'seguidos',
                        component: ViewSeguidos,
                        meta: { breadCrumb: 'Vista de seguidos' }
                    },
                ]
            },

        ]
    },
    // {
    //     path: '/buscar',
    //     component: UserLayout,
    //     beforeEnter: requireLogin,
    //     meta: { breadCrumb: 'Buscar' },
    //     children: [
    //         {
    //             path: '',
    //             component: Buscar,
    //             meta: { breadCrumb: 'Buscador de Post y personas' }
    //         },
    //     ]
    // },
    {
        path: '/chats',
        component: UserLayout,
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Chats' },
        children: [
            {
                path: '',
                name: 'chats',
                component: Chats,
                meta: { breadCrumb: 'Chats Index' }
            },
            {
                name: 'mostrar.chat',
                path: ':id',
                component: Chat,
                meta: {breadCrumb: 'Mostrar Chat'}
            }
        ]
    },
    {
        path: '/notificaciones',
        component: UserLayout,
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Notificaciones' },
        children: [
            {
                path: '',
                name: 'notificaciones',
                component: Notificaciones,
                meta: { breadCrumb: 'Notificaciones Index' }
            },
        ]
    },
    {
        path: '/crear',
        component: UserLayout,
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Notificaciones' },
        children: [
            {
                path: '',
                component: Notificaciones,
                meta: { breadCrumb: 'Notificaciones Index' }
            },
        ]
    },

    {
        // Ruta inicial
        path: '/',
        // redirect: { name: 'login' },
        children: [
            {
                path: '',
                name: 'index',
                component: Home,
                beforeEnter: guest,
            },
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../views/login/Login.vue'),
                beforeEnter: guest,
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../views/register/index.vue'),
                beforeEnter: guest,
            },
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../views/auth/passwords/Email.vue'),
                beforeEnter: guest,
            },
            {
                path: 'reset-password/:token',
                name: 'auth.reset-password',
                component: () => import('../views/auth/passwords/Reset.vue'),
                beforeEnter: guest,
            },
            {
                path: 'posts',
                name: 'public-posts.index',
                component: () => import('../views/posts/index.vue'),
            },
            {
                path: 'posts/:id',
                name: 'public-posts.details',
                component: () => import('../views/posts/details.vue'),
            },
            {
                path: 'category/:id',
                name: 'category-posts.index',
                component: () => import('../views/category/posts.vue'),
            },
        ]
    },
    {
        path: '/admin',
        component: AuthenticatedLayout,
        // redirect: {
        //     name: 'admin.index'
        // },
        beforeEnter: requireLogin,
        meta: { breadCrumb: 'Dashboard' },
        children: [
            {
                name: 'admin.index',
                path: '',
                component: () => import('../views/admin/index.vue'),
                meta: { breadCrumb: 'Admin' }
            },
            {
                name: 'profile.index',
                path: 'profile',
                component: () => import('../views/admin/profile/index.vue'),
                meta: { breadCrumb: 'Profile' }
            },
            {
                name: 'posts.index',
                path: 'posts',
                component: PostsIndex,
                meta: { breadCrumb: 'Posts' }
            },
            {
                name: 'posts.create',
                path: 'posts/create',
                component: PostsCreate,
                meta: { breadCrumb: 'Add new post' }
            },
            {
                name: 'posts.edit',
                path: 'posts/edit/:id',
                component: PostsEdit,
                meta: { breadCrumb: 'Edit post' }
            },
            {
                name: 'exercises',
                path: 'exercises',
                meta: { breadCrumb: 'Exercises'},
                children: [
                    {
                        name: 'exercises.index',
                        path: '',
                        component: ExercisesIndex,
                        meta: { breadCrumb: 'View' }
                    },
                    {
                        name: 'exercises.create',
                        path: 'create',
                        component: ExercisesCreate,
                        meta: { breadCrumb: 'Add new exercise' ,
                        linked: false, }
                    },
                    {
                        name: 'exercises.edit',
                        path: 'edit/:id',
                        component: ExercisesEdit,
                        meta: {
                            breadCrumb: 'Edit exercise',
                            linked: false,
                        }
                    }
                ]
            },

            {
                name: 'categories',
                path: 'categories',
                meta: { breadCrumb: 'Categories'},
                children: [
                    {
                        name: 'categories.index',
                        path: '',
                        component: () => import('../views/admin/categories/Index.vue'),
                        meta: { breadCrumb: 'View category' }
                    },
                    {
                        name: 'categories.create',
                        path: 'create',
                        component: () => import('../views/admin/categories/Create.vue'),
                        meta: {
                            breadCrumb: 'Add new category' ,
                            linked: false,
                        }
                    },
                    {
                        name: 'categories.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/categories/Edit.vue'),
                        meta: {
                            breadCrumb: 'Edit category',
                            linked: false,
                        }
                    }
                ]
            },

            {
                name: 'permissions',
                path: 'permissions',
                meta: { breadCrumb: 'Permisos'},
                children: [
                    {
                        name: 'permissions.index',
                        path: '',
                        component: () => import('../views/admin/permissions/Index.vue'),
                        meta: { breadCrumb: 'Permissions' }
                    },
                    {
                        name: 'permissions.create',
                        path: 'create',
                        component: () => import('../views/admin/permissions/Create.vue'),
                        meta: {
                            breadCrumb: 'Create Permission',
                            linked: false,
                        }
                    },
                    {
                        name: 'permissions.edit',
                        path: 'edit/:id',
                        component: () => import('../views/admin/permissions/Edit.vue'),
                        meta: {
                            breadCrumb: 'Permission Edit',
                            linked: false,
                        }
                    }
                ]
            },

            //TODO Organizar rutas
            {
                name: 'roles.index',
                path: 'roles',
                component: () => import('../views/admin/roles/Index.vue'),
                meta: { breadCrumb: 'Roles' }
            },
            {
                name: 'roles.create',
                path: 'roles/create',
                component: () => import('../views/admin/roles/Create.vue'),
                meta: { breadCrumb: 'Create Role' }
            },
            {
                name: 'roles.edit',
                path: 'roles/edit/:id',
                component: () => import('../views/admin/roles/Edit.vue'),
                meta: { breadCrumb: 'Role Edit' }
            },
            {
                name: 'users.index',
                path: 'users',
                component: () => import('../views/admin/users/Index.vue'),
                meta: { breadCrumb: 'Users' }
            },
            {
                name: 'users.create',
                path: 'users/create',
                component: () => import('../views/admin/users/Create.vue'),
                meta: { breadCrumb: 'Add New' }
            },
            {
                name: 'users.edit',
                path: 'users/edit/:id',
                component: () => import('../views/admin/users/Edit.vue'),
                meta: { breadCrumb: 'User Edit' }
            },
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../views/errors/404.vue"),
    },
];
