<script setup>
defineProps({
  contacts: Array,
  loading: Boolean,
  modelValue: String
})
defineEmits(['update:modelValue', 'edit', 'delete'])
</script>

<template>
  <div>
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div>
        <h2 class="text-3xl font-extrabold bg-gradient-to-r from-white to-gray-500 bg-clip-text text-transparent italic">Handix Network</h2>
      </div>
      <div class="relative w-full md:w-96">
        <input 
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
          type="text" 
          placeholder="🔍 Filtrar registros..."
          class="w-full bg-[#111827] border border-white/10 rounded-2xl py-3.5 px-6 text-sm focus:border-blue-500 outline-none text-white transition-all shadow-inner"
        />
      </div>
    </div>

    <section class="bg-[#111827] border border-white/5 rounded-2xl overflow-hidden shadow-2xl">
      <div class="overflow-x-auto text-sm">
        <table class="w-full text-left">
          <thead class="text-gray-500 text-[10px] font-black uppercase tracking-widest bg-white/[0.02] border-b border-white/5">
            <tr>
              <th class="p-6">Identificação</th>
              <th class="p-6">Comunicação</th>
              <th class="p-6">Observações</th>
              <th class="p-6 text-right">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5">
            <tr v-for="c in contacts" :key="c.id" class="hover:bg-blue-500/[0.03] transition-all group">
              <td class="p-6">
                <div class="flex items-center gap-4">
                  <div class="h-10 w-10 rounded-lg bg-blue-900/30 border border-blue-500/30 flex items-center justify-center text-blue-400 font-black">
                    {{ c.name?.charAt(0).toUpperCase() }}
                  </div>
                  <span class="font-bold text-gray-200">{{ c.name }}</span>
                </div>
              </td>
              <td class="p-6">
                <div class="text-gray-400 font-medium">{{ c.email }}</div>
                <div class="text-[11px] text-blue-500/60 font-mono mt-0.5">{{ c.phone }}</div>
              </td>
              <td class="p-6">
                <p class="text-gray-500 text-xs italic truncate max-w-[150px]" :title="c.notes">
                  {{ c.notes || '---' }}
                </p>
              </td>
              <td class="p-6 text-right space-x-2">
                <button @click="$emit('edit', c)" class="bg-gray-800 hover:bg-blue-600 p-2.5 rounded-lg transition text-white">✏️</button>
                <button @click="$emit('delete', c.id)" class="bg-gray-800 hover:bg-red-600 p-2.5 rounded-lg transition text-white">🗑️</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="contacts.length === 0 && !loading" class="p-20 text-center text-gray-600 italic">
        Nenhum registro encontrado.
      </div>
    </section>
  </div>
</template>