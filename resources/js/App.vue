<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from "vue-toastification"

const toast = useToast()
const api = axios.create({ baseURL: '/api/v1' })

const contacts = ref([])
const loading = ref(false)
const form = ref({ name: '', email: '', phone: '', notes: '' })

// Carrega os dados tratando o aninhamento: res.data.data.data
const load = async () => {
  loading.value = true
  try {
    const res = await api.get('/contacts')
    contacts.value = res.data.data.data 
  } catch (e) {
    toast.error("Erro ao carregar a lista de contatos.")
  } finally {
    loading.value = false
  }
}

const save = async () => {
  loading.value = true
  try {
    if (form.value.id) {
      await api.put(`/contacts/${form.value.id}`, form.value)
      toast.success("Contato atualizado com sucesso! 🎉")
    } else {
      await api.post('/contacts', form.value)
      toast.success("Contato criado com sucesso! ✨")
    }
    
    form.value = { name: '', email: '', phone: '', notes: '' }
    load()
  } catch (e) {
    // Tratamento de erro de validação (422) conforme a estrutura da sua API
    if (e.response && e.response.status === 422) {
      const apiErrors = e.response.data.errors
      toast.warning(e.response.data.message || "Erro de validação")

      // Percorre os erros de cada campo e exibe no toast
      Object.keys(apiErrors).forEach(field => {
        apiErrors[field].forEach(msg => {
          toast.error(`${field}: ${msg}`)
        })
      })
    } else {
      toast.error("Ocorreu um erro inesperado.")
    }
  } finally {
    loading.value = false
  }
}

const edit = (contact) => {
  form.value = { ...contact }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const remove = async (id) => {
  if (!confirm('Deseja realmente excluir este contato?')) return
  
  try {
    await api.delete(`/contacts/${id}`)
    toast.info("Contato removido com sucesso.")
    load()
  } catch (e) {
    toast.error("Erro ao tentar excluir o contato.")
  }
}

onMounted(load)
</script>

<template>
  <div class="max-w-4xl mx-auto p-6 font-sans">
    <header class="mb-10 text-center">
      <div class="flex justify-center items-center gap-3 mb-2">
        <span class="text-4xl">📇</span>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight text-white">Gestão de Contatos</h1>
      </div>
      <p class="text-gray-400">Organize sua rede de contatos de forma simples e eficiente.</p>
    </header>

    <section class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-10">
      <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <span v-if="form.id" class="text-blue-600">✏️ Editar</span>
        <span v-else class="text-green-600">➕ Novo</span>
        Contato
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-1">
          <label class="text-sm font-semibold text-gray-700">Nome Completo</label>
          <input v-model="form.name" placeholder="Ex: André Veras" 
            class="w-full border-gray-300 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
        </div>

        <div class="space-y-1">
          <label class="text-sm font-semibold text-gray-700">E-mail</label>
          <input v-model="form.email" type="email" placeholder="seu@email.com" 
            class="w-full border-gray-300 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
        </div>

        <div class="space-y-1">
          <label class="text-sm font-semibold text-gray-700">Telefone</label>
          <input v-model="form.phone" placeholder="(88) 9.8170-0168" 
            class="w-full border-gray-300 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
        </div>

        <div class="space-y-1">
          <label class="text-sm font-semibold text-gray-700">Observações</label>
          <input v-model="form.notes" placeholder="Notas adicionais..." 
            class="w-full border-gray-300 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
        </div>
      </div>

      <div class="mt-6 flex justify-end gap-3">
        <button v-if="form.id" @click="form = { name: '', email: '', phone: '', notes: '' }" 
          class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
          Cancelar
        </button>
        <button @click="save" :disabled="loading"
          class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-bold shadow-lg transition-all active:scale-95 disabled:opacity-50">
          {{ loading ? 'Processando...' : (form.id ? 'Atualizar Contato' : 'Salvar Contato') }}
        </button>
      </div>
    </section>

    <section class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
      <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-800">Seus Contatos</h2>
        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
          Total: {{ contacts.length }}
        </span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
              <th class="p-4">Nome</th>
              <th class="p-4">Contato</th>
              <th class="p-4 text-right">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="c in contacts" :key="c.id" class="hover:bg-blue-50/30 transition-colors group">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="h-9 w-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                    {{ c.name ? c.name.charAt(0).toUpperCase() : '?' }}
                  </div>
                  <span class="font-semibold text-gray-900">{{ c.name }}</span>
                </div>
              </td>
              <td class="p-4">
                <div class="text-sm text-gray-600">{{ c.email }}</div>
                <div class="text-xs text-gray-400 font-mono">{{ c.phone }}</div>
              </td>
              <td class="p-4 text-right space-x-3">
                <button @click="edit(c)" class="text-sm text-blue-600 hover:underline">Editar</button>
                <button @click="remove(c.id)" class="text-sm text-red-600 hover:underline">Excluir</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="loading && contacts.length === 0" class="p-12 text-center text-gray-500">
        Carregando contatos...
      </div>
      
      <div v-else-if="contacts.length === 0" class="p-12 text-center text-gray-400 italic">
        Nenhum contato encontrado.
      </div>
    </section>
  </div>
</template>