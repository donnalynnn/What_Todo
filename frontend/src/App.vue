<template>
  <div class="app-container">

    <Sidebar @filter-change="updateFilters" />

    <main class="main-content">
      <Home :filters="filters" />
    </main>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Sidebar from "./components/Sidebar.vue";
import Home from "./views/Home.vue";

export default defineComponent({
  name: "App",
  components: { Sidebar, Home },
  setup() {
    const filters = ref<{ date: string | null; priority: string | null }>({
      date: null,
      priority: null,
    });

    function updateFilters(newFilters: { date: string | null; priority: string | null }) {
      filters.value = newFilters;
    }

    return { filters, updateFilters };
  },
});
</script>
