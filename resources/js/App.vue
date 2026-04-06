<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useToast } from "vue-toastification"

const toast = useToast()
const api = axios.create({ baseURL: '/api/v1' })

const contacts = ref([])
const loading = ref(false)
const isModalOpen = ref(false)
const searchTerm = ref('') // Estado para o filtro
const form = ref({ id: null, name: '', email: '', phone: '', notes: '' })

// Sincronização com a API enviando o parâmetro de busca
const load = async () => {
  loading.value = true
  try {
    const res = await api.get('/contacts', {
      params: { search: searchTerm.value } // Enviando para o seu ContactService
    })
    contacts.value = res.data.data.data 
  } catch (e) {
    toast.error("Erro ao sincronizar dados.")
  } finally {
    loading.value = false
  }
}

// Watcher com "debounce" para buscar enquanto o usuário digita
let timeout = null
watch(searchTerm, () => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    load()
  }, 500) // Aguarda 500ms após a última tecla para disparar a busca
})

const openModal = (contact = null) => {
  form.value = contact ? { ...contact } : { id: null, name: '', email: '', phone: '', notes: '' }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const save = async () => {
  loading.value = true
  try {
    if (form.value.id) {
      await api.put(`/contacts/${form.value.id}`, form.value)
      toast.success("Registro atualizado!")
    } else {
      await api.post('/contacts', form.value)
      toast.success("Contato salvo!")
    }
    closeModal()
    load()
  } catch (e) {
    if (e.response?.status === 422) {
      Object.values(e.response.data.errors).flat().forEach(msg => toast.error(msg))
    } else {
      toast.error("Falha na operação.")
    }
  } finally {
    loading.value = false
  }
}

const remove = async (id) => {
  if (!confirm('Deseja excluir este registro?')) return
  try {
    await api.delete(`/contacts/${id}`)
    toast.info("Removido.")
    load()
  } catch (e) {
    toast.error("Erro ao excluir.")
  }
}

onMounted(load)
</script>

<template>
  <div class="min-h-screen bg-[#0a0f18] text-white font-sans selection:bg-blue-500/30">
    
    <nav class="border-b border-white/10 bg-[#0a0f18]/80 backdrop-blur-md sticky top-0 z-40">
      <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="flex gap-1">
            <span class="w-1 h-6 bg-blue-400 rounded-full animate-pulse"></span>
            <span class="w-1 h-8 bg-blue-500 rounded-full"></span>
            <span class="w-1 h-5 bg-blue-600 rounded-full"></span>
          </div>
          <span class="text-2xl font-black tracking-tighter uppercase">Handix</span>
        </div>
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all shadow-[0_0_20px_rgba(59,130,246,0.3)]">
          + Novo Contato
        </button>
      </div>
    </nav>

    <main class="max-w-5xl mx-auto py-12 px-6">
      
      <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <h2 class="text-3xl font-extrabold bg-gradient-to-r from-white to-gray-500 bg-clip-text text-transparent">Diretório de Rede</h2>
          <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.3em]">Gestão de Infraestrutura</p>
        </div>
        
        <div class="relative w-full md:w-96 group">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <span class="text-gray-500 group-focus-within:text-blue-500 transition-colors">🔍</span>
          </div>
          <input 
            v-model="searchTerm"
            type="text" 
            placeholder="Filtrar por nome ou e-mail..."
            class="w-full bg-[#111827] border border-white/10 rounded-2xl py-3.5 pl-12 pr-4 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all"
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
                <td class="p-6 text-right space-x-2">
                  <button @click="openModal(c)" class="bg-gray-800 hover:bg-blue-600 p-2.5 rounded-lg transition" title="Editar">✏️</button>
                  <button @click="remove(c.id)" class="bg-gray-800 hover:bg-red-600 p-2.5 rounded-lg transition" title="Excluir">🗑️</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div v-if="loading" class="p-10 text-center text-blue-500 animate-pulse font-black text-xs tracking-widest">
          SINCRONIZANDO DADOS...
        </div>
        <div v-else-if="contacts.length === 0" class="p-20 text-center text-gray-600 italic">
          Nenhum registro encontrado para esta busca.
        </div>
      </section>
    </main>

    <Transition name="fade">
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 backdrop-blur-sm bg-black/60">
        <div class="bg-[#111827] border border-white/10 w-full max-w-xl rounded-3xl shadow-2xl relative overflow-hidden">
          <div class="p-8 relative z-10">
            <h3 class="text-xl font-black uppercase tracking-tighter mb-8">
              {{ form.id ? 'Sincronizar' : 'Novo' }} <span class="text-blue-500">Registro</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="field in ['name', 'email', 'phone', 'notes']" :key="field" class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">{{ field }}</label>
                <input v-model="form[field]" class="bg-[#0a0f18] border border-white/10 rounded-xl p-4 focus:border-blue-500 outline-none text-sm" />
              </div>
            </div>

            <div class="mt-10 flex justify-end gap-4 border-t border-white/5 pt-6">
              <button @click="closeModal" class="text-gray-500 font-bold hover:text-white transition">Cancelar</button>
              <button @click="save" :disabled="loading" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-3.5 rounded-xl font-black uppercase tracking-tighter transition-all">
                {{ form.id ? 'Salvar Alterações' : 'Finalizar Registro' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>