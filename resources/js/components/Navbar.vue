<script setup>
defineEmits(['open-modal'])
</script>

<template>
  <nav class="border-b border-white/10 bg-[#0a0f18]/80 backdrop-blur-md sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="flex gap-1">
          <span class="w-1 h-6 bg-blue-400 rounded-full animate-pulse"></span>
          <span class="w-1 h-8 bg-blue-500 rounded-full"></span>
          <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
        </div>
        <span class="text-2xl font-black tracking-tighter uppercase text-white">Handix</span>
      </div>
      <button @click="$emit('open-modal')" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all shadow-[0_0_20px_rgba(59,130,246,0.3)]">
        + Novo Contato
      </button>
    </div>
  </nav>
</template>