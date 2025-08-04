---
# Pokédex Frontend 🧩

This is the **frontend** of the full-stack Pokédex app, built with **Vue.js** and **Quasar Framework**. It connects to the Laravel backend and displays rich Pokémon data using components, animations, filters, and more.
---

### 🖥️ Tech Stack

- **Vue.js** (Options API)
- **Quasar Framework**
- **Axios** for API requests
- **Vue Router** for page navigation

---

### 🔍 Key Features

- Browse all Pokémon in a clean card layout
- Search Pokémon by name
- Filter by Pokémon types
- Compare two Pokémon side-by-side
- View complete evolution chains
- Fully responsive design with soft shadows & gradients
- Built with reusable, modular components

---

### 📁 Project Structure (Simplified)

```
frontend/
├── public/
├── src/
│   ├── components/         # Reusable UI components (Card, Input, etc.)
│   ├── pages/              # Main pages (Home, Compare, Types)
│   ├── router/             # Route config
│   ├── services/           # Axios instance & API wrappers
│   └── App.vue             # Root component
├── quasar.conf.js
└── package.json
```

---

### 🚀 Getting Started

#### 1. Install Quasar CLI (if you haven't)

```bash
npm install -g @quasar/cli
```

#### 2. Navigate to frontend directory

```bash
cd frontend
```

#### 3. Install dependencies

```bash
npm install
```

#### 4. Start the frontend dev server

```bash
quasar dev
```

#### 5. Make sure your Laravel backend is running

Make sure the Laravel server (e.g. at `http://localhost:8000`) is up, since the frontend depends on it for data.

---

### 🔧 Config Notes

- **API base URL** is usually set inside a centralized `api.js` file or an Axios plugin. Make sure it's pointing to your Laravel backend:

  ```js
  const api = axios.create({ baseURL: 'http://localhost:8000/api' })
  ```

---

## 📸 Screenshots

### Home Page

![Home](../../ScreenShots/frontend/Home.png)

### Random Pokémon

![Random](../../ScreenShots/frontend/RandomPokemon.png)

### Search Results

![Search](../../ScreenShots/frontend/Search_Pokemon.png)

### Compare Pokémon

![Compare](../../ScreenShots/frontend/ComparePokemon.png)

### Types Overview

![Types](../../ScreenShots/frontend/Types.png)

### Pokémon of a Specific Type (with Infinite Scroll)

![Type Pokémon](../../ScreenShots/frontend/TypePokemon.png)
![Infinite Scroll](../../ScreenShots/frontend/Infinite_scroll_for_type.png)

### Pokémon Without Evolution

![No Evolution](../../ScreenShots/frontend/Pokemon_No_Evolution.png)

---

### 🤝 Contributing

Feel free to clone and build on top of this Pokedex. 😆

---
