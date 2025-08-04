# 🧠 Pokédex Backend (Laravel API)

This is the backend of the Pokédex app, built with **Laravel**. It powers the frontend via a RESTful API and handles all the logic for fetching, processing, and serving Pokémon data.

---

## 🚀 Features

### ✅ RESTful Endpoints

-   `/api/pokemon/{identifier}`: Get details of a specific Pokémon by name or ID.
-   `/api/pokemon/type/{type}`: Fetch Pokémon filtered by type (supports infinite scroll with `limit` and `last_id`).
-   `/api/pokemon/search?q=`: Search Pokémon by name.
-   `/api/pokemon/evolution/{identifier}`: Get the evolution chain of a given Pokémon.
-   `/api/pokemon/random`: Returns a random Pokémon.
-   `/api/types`: Lists all Pokémon types.

### 🧬 Evolution System

-   Custom evolution logic using `evolution_chains`, `evolution_chain_pokemon`, and `pokemons` tables.
-   Backend handles different evolution paths and base forms.

### 📦 Database Seeding from PokéAPI

-   Seeds full Pokémon data from [PokéAPI](https://pokeapi.co/) using custom seeders.
-   Includes:

    -   Base Pokémon data
    -   Stats and sprites
    -   Evolution chains
    -   Missing special/alternate forms (e.g., Deoxys, Giratina)

### 🛠 Artisan Commands

-   `php artisan db:seed`: Seeds the database with initial data.
-   `php artisan migrate:fresh --seed`: Wipe and re-seed your database.

---

## 🧾 Models & Relationships

### Models

-   `Pokemon`
-   `PokemonType`
-   `EvolutionChain`
-   `EvolutionChainPokemon`

### Relationships

-   `Pokemon` hasMany `PokemonType`
-   `Pokemon` belongsToMany `EvolutionChain` through `EvolutionChainPokemon`

---

## 📁 Folder Structure Highlights

-   `app/Http/Controllers/Api`: API endpoints
-   `app/Models`: All core Eloquent models
-   `database/seeders`: Seeders fetching & saving Pokémon data
-   `routes/api.php`: API route definitions

---

## 🧪 Testing

Basic testing can be done via Postman or any API client:

```
GET http://localhost:8000/api/pokemon/pikachu
GET http://localhost:8000/api/pokemon/type/electric
```

---

## 💡 There's so much more that can be done. SO if you want to expand on this further, you can try this out.

-   Filtering by generation
-   Abilities and move data
-   Caching layers for performance
-   Similar Pokemon Logic -> Take multiple Props into consideration and calculate similarity
-   Berries / Machines / Compatibilty
-   Rate Pokemon or favourite Pokemons
-   CRON Jobs to add any new pokemon added to the pokeapi db.
-   Most of the caching is done via prefetching in this project. You can optimize by introducing robust caching on the Laravel backend (Redis, response caching, etc.).

---

## ❤️ Built With

-   Laravel 12
-   PokéAPI v2
-   PHP 8+
-   MySQL

---

It was a lot of fun building this up.🛠️
Journey Over Destination, As always.

> "Gotta build 'em all!"
