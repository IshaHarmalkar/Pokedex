const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'pokemon', name: 'pokedex', component: () => import('pages/PokedexPage.vue') },

      {
        path: '/pokemon/:identifier',
        name: 'pokemon-details',
        component: () => import('pages/PokemonDetails.vue'),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
