<script setup>
import { ref, watch } from 'vue'
import { useToast } from "vue-toastification"

const props = defineProps({
  isOpen: Boolean,
  loading: Boolean,
  modelValue: Object
})

const emit = defineEmits(['update:modelValue', 'close', 'save'])
const toast = useToast()
const errors = ref({})

/**
 * Validação de Front-end (Handix Standard)
 * Evita chamadas desnecessárias à API se os campos básicos estiverem errados.
 */
const validate = () => {
  errors.value = {}
  let valid = true

  if (!props.modelValue.name) {
    errors.value.name = "Nome é obrigatório"
    valid = false
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!props.modelValue.email) {
    errors.value.email = "E-mail é obrigatório"
    valid = false
  } else if (!emailRegex.test(props.modelValue.email)) {
    errors.value.email = "Formato de e-mail inválido"
    valid = false
  }

  return valid
}

const handleSave = () => {
  if (validate()) {
    emit('save')
  } else {
    toast.warning("Por favor, preencha os campos obrigatórios corretamente.")
  }
}

// Reseta o estado de erro sempre que o modal abre ou fecha
watch(() => props.isOpen, () => {
  errors.value = {}
})
</script>

<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 backdrop-blur-sm bg-black/60">
      <div class="bg-[#111827] border border-white/10 w-full max-w-xl rounded-3xl shadow-2xl relative overflow-hidden">
        
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl"></div>

        <div class="p-8 relative z-10">
          <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black uppercase tracking-tighter text-white">
              {{ modelValue.id ? 'Sincronizar' : 'Novo' }} <span class="text-blue-500">Registro</span>
            </h3>
            <button @click="$emit('close')" class="text-gray-500 hover:text-white text-2xl transition-colors">&times;</button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Nome Completo *</label>
              <input v-model="modelValue.name" 
                class="bg-[#0a0f18] border rounded-xl p-4 outline-none text-sm text-white transition-all"
                :class="errors.name ? 'border-red-500/50 ring-1 ring-red-500/20' : 'border-white/10 focus:border-blue-500'" 
                placeholder="Ex: André Veras" />
              <span v-if="errors.name" class="text-[10px] text-red-500 font-bold ml-1">{{ errors.name }}</span>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">E-mail Corporativo *</label>
              <input v-model="modelValue.email" type="email"
                class="bg-[#0a0f18] border rounded-xl p-4 outline-none text-sm text-white transition-all"
                :class="errors.email ? 'border-red-500/50 ring-1 ring-red-500/20' : 'border-white/10 focus:border-blue-500'" 
                placeholder="andre@handix.com.br" />
              <span v-if="errors.email" class="text-[10px] text-red-500 font-bold ml-1">{{ errors.email }}</span>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">WhatsApp / Fone</label>
              <input 
                v-model="modelValue.phone" 
                v-maska 
                data-maska="(##) #####-####"
                placeholder="(88) 99999-9999"
                class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm text-white transition-all" 
              />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Observações</label>
              <input v-model="modelValue.notes" 
                placeholder="Tags ou lembretes"
                class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm text-white transition-all" />
            </div>
          </div>

          <div class="mt-10 flex justify-end gap-4 border-t border-white/5 pt-6">
            <button @click="$emit('close')" class="text-gray-500 font-bold hover:text-white transition-colors px-4 py-2">
              Cancelar
            </button>
            
            <button 
              @click="handleSave" 
              :disabled="loading" 
              class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-3.5 rounded-xl font-black uppercase tracking-tighter transition-all flex items-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed shadow-lg shadow-blue-900/20 active:scale-95"
            >
              <span v-if="loading" class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
              
              <span>{{ modelValue.id ? 'Atualizar Conexão' : 'Concluir Registro' }}</span>
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

/* Garante que o spinner de loading não empurre o texto se o modal for muito estreito */
.animate-spin {
  flex-shrink: 0;
}
</style>