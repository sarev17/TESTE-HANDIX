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

const validate = () => {
  errors.value = {}
  let valid = true

  if (!props.modelValue.name) {
    errors.value.name = "O nome é obrigatório"
    valid = false
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!props.modelValue.email) {
    errors.value.email = "O e-mail é obrigatório"
    valid = false
  } else if (!emailRegex.test(props.modelValue.email)) {
    errors.value.email = "E-mail inválido"
    valid = false
  }

  return valid
}

const handleSave = () => {
  if (validate()) {
    emit('save')
  } else {
    toast.warning("Corrija os erros antes de salvar")
  }
}

watch(() => props.isOpen, () => {
  errors.value = {}
})
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
            <button @click="$emit('close')" class="text-gray-500 hover:text-white text-2xl">&times;</button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Nome *</label>
              <input v-model="modelValue.name" 
                class="bg-[#0a0f18] border rounded-xl p-4 outline-none text-sm text-white transition-all"
                :class="errors.name ? 'border-red-500' : 'border-white/10 focus:border-blue-500'" />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">E-mail *</label>
              <input v-model="modelValue.email" type="email"
                class="bg-[#0a0f18] border rounded-xl p-4 outline-none text-sm text-white transition-all"
                :class="errors.email ? 'border-red-500' : 'border-white/10 focus:border-blue-500'" />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Telefone</label>
              <input 
                v-model="modelValue.phone" 
                v-maska 
                data-maska="(##) #####-####"
                placeholder="(00) 00000-0000"
                class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm text-white transition-all" 
              />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Notas</label>
              <input v-model="modelValue.notes" 
                class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm text-white transition-all" />
            </div>
          </div>

          <div class="mt-10 flex justify-end gap-4 border-t border-white/5 pt-6">
            <button @click="$emit('close')" class="text-gray-500 font-bold hover:text-white transition">Cancelar</button>
            <button @click="handleSave" :disabled="loading" 
              class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-3.5 rounded-xl font-black uppercase tracking-tighter transition-all flex items-center gap-2">
              {{ loading ? '...' : (modelValue.id ? 'Atualizar' : 'Salvar') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>