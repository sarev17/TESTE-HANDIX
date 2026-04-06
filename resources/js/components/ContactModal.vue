<script setup>
const props = defineProps({
  isOpen: Boolean,
  loading: Boolean,
  modelValue: Object
})

const emit = defineEmits(['update:modelValue', 'close', 'save'])

const close = () => emit('close')
const save = () => emit('save')
</script>

<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 backdrop-blur-sm bg-black/60">
      <div class="bg-[#111827] border border-white/10 w-full max-w-xl rounded-3xl shadow-2xl relative overflow-hidden">
        <div class="p-8 relative z-10">
          <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black uppercase tracking-tighter text-white">
              {{ modelValue.id ? 'Sincronizar' : 'Novo' }} <span class="text-blue-500">Registro</span>
            </h3>
            <button @click="close" class="text-gray-500 hover:text-white text-2xl">&times;</button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="field in ['name', 'email', 'phone', 'notes']" :key="field" class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">{{ field }}</label>
              <input 
                v-model="modelValue[field]" 
                :type="field === 'email' ? 'email' : 'text'"
                class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm text-white transition-all" 
              />
            </div>
          </div>

          <div class="mt-10 flex justify-end gap-4 border-t border-white/5 pt-6">
            <button @click="close" class="text-gray-500 font-bold hover:text-white transition">Cancelar</button>
            <button @click="save" :disabled="loading" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-3.5 rounded-xl font-black uppercase tracking-tighter transition-all flex items-center gap-2">
              <span v-if="loading" class="animate-spin text-xs">🌀</span>
              {{ modelValue.id ? 'Salvar Alterações' : 'Concluir Registro' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>