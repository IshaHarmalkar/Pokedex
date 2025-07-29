const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'pokemon', name: 'pokedex', component: () => import('pages/PokedexPage.vue') },
      { path: 'compare', name: 'compare', component: () => import('pages/ComparePage.vue') },
      { path: 'random', name: 'random', component: () => import('pages/RandomPage.vue') },
      { path: 'test', name: 'test', component: () => import('pages/TestPage.vue') },

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
