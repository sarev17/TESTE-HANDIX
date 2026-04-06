<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useToast } from "vue-toastification"

// Importando os componentes
import Navbar from './components/Navbar.vue'
import ContactTable from './components/ContactTable.vue'
import ContactModal from './components/ContactModal.vue'

const toast = useToast()
const api = axios.create({ baseURL: '/api/v1' })

const contacts = ref([])
const loading = ref(false)
const isModalOpen = ref(false)
const searchTerm = ref('')
const form = ref({ id: null, name: '', email: '', phone: '', notes: '' })

const load = async () => {
  loading.value = true
  try {
    const res = await api.get('/contacts', { params: { search: searchTerm.value } })
    contacts.value = res.data.data.data 
  } catch (e) {
    toast.error("Erro ao sincronizar dados.")
  } finally {
    loading.value = false
  }
}

// Debounce para busca
let timeout = null
watch(searchTerm, () => {
  clearTimeout(timeout); timeout = setTimeout(load, 500)
})

const openModal = (contact = null) => {
  form.value = contact ? { ...contact } : { id: null, name: '', email: '', phone: '', notes: '' }
  isModalOpen.value = true
}

const save = async () => {
  if (loading.value) return // Evita cliques duplos acidentais
  
  loading.value = true
  try {
    if (form.value.id) {
      await api.put(`/contacts/${form.value.id}`, form.value)
      toast.success("Dados sincronizados com sucesso!")
    } else {
      await api.post('/contacts', form.value)
      toast.success("Contato adicionado à rede!")
    }
    isModalOpen.value = false
    await load() 
  } catch (e) {
    if (e.response?.status === 422) {
      Object.values(e.response.data.errors).flat().forEach(msg => toast.error(msg))
    } else {
      toast.error("Erro na infraestrutura de rede.")
    }
  } finally {
    // O loading volta para false aqui, liberando o botão
    loading.value = false 
  }
}

const remove = async (id) => {
  if (!confirm('Deseja excluir?')) return
  try { await api.delete(`/contacts/${id}`); toast.info("Removido."); load() }
  catch (e) { toast.error("Erro ao excluir.") }
}

onMounted(load)
</script>

<template>
  <div class="min-h-screen bg-[#0a0f18] text-white font-sans">
    <Navbar @open-modal="openModal()" />
    
    <main class="max-w-5xl mx-auto py-12 px-6">
      <ContactTable 
        v-model="searchTerm"
        :contacts="contacts" 
        :loading="loading"
        @edit="openModal"
        @delete="remove"
      />
    </main>

    <ContactModal 
      v-model="form"
      :is-open="isModalOpen"
      :loading="loading"
      @close="isModalOpen = false"
      @save="save"
    />
  </div>
</template>