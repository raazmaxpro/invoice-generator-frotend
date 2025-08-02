<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 to-black text-white p-8 font-sans">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10">
      <!-- Left: Invoice Form -->
      <div class="bg-white/5 backdrop-blur-md p-6 rounded-xl border border-white/10 shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-white">Invoice Generator</h2>

        <!-- Company & Client -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <input
            v-model="companyName"
            type="text"
            placeholder="Your Company Name"
            class="input-style"
          />
          <input v-model="clientName" type="text" placeholder="Client Name" class="input-style" />
        </div>

        <!-- Line Items -->
        <div class="space-y-4 mb-6">
          <div v-for="(item, index) in items" :key="index" class="grid grid-cols-12 gap-2">
            <input v-model="item.name" placeholder="Item Name" class="input-style col-span-5" />
            <input
              v-model.number="item.qty"
              type="number"
              placeholder="Qty"
              class="input-style col-span-2"
            />
            <input
              v-model.number="item.price"
              type="number"
              placeholder="Price"
              class="input-style col-span-3"
            />
            <button
              @click="removeItem(index)"
              class="text-red-400 hover:text-red-200 col-span-2 text-sm"
            >
              Remove
            </button>
          </div>
          <button
            @click="addItem"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded text-white"
          >
            + Add Item
          </button>
        </div>

        <!-- Totals -->
        <div class="space-y-3 text-white">
          <div class="flex justify-between">
            <span>Subtotal:</span>
            <span>₹ {{ subtotal.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between">
            <span>Tax ({{ taxRate }}%):</span>
            <span>₹ {{ taxAmount.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between font-bold">
            <span>Total (with GST):</span>
            <span>₹ {{ total.toFixed(2) }}</span>
          </div>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex gap-4">
          <button class="px-4 py-2 bg-green-600 hover:bg-green-500 rounded text-white">Save</button>
          <button
            @click="generatePdf"
            class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded text-white"
          >
            Print
          </button>
        </div>
      </div>

      <!-- Right: Live Preview -->
      <div class="bg-white/5 backdrop-blur-md p-6 rounded-xl border border-white/10 shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-white">Invoice Preview</h2>
        <div class="space-y-4 text-white text-sm">
          <p><strong>From:</strong> {{ companyName || 'Your Company Name' }}</p>
          <p><strong>To:</strong> {{ clientName || 'Client Name' }}</p>

          <div class="border-t border-white/10 pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            <ul class="space-y-1">
              <li
                v-for="(item, index) in items"
                :key="index"
                class="flex justify-between text-white/80"
              >
                <span>{{ item.name || 'Item ' + (index + 1) }}</span>
                <span>₹ {{ (item.qty * item.price).toFixed(2) || '0.00' }}</span>
              </li>
            </ul>
          </div>

          <div class="border-t border-white/10 pt-4 space-y-1">
            <p>Subtotal: ₹ {{ subtotal.toFixed(2) }}</p>
            <p>Tax ({{ taxRate }}%): ₹ {{ taxAmount.toFixed(2) }}</p>
            <p class="font-bold text-lg">Total: ₹ {{ total.toFixed(2) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

const companyName = ref('')
const clientName = ref('')
const items = ref([{ name: '', qty: 1, price: 0 }])
const taxRate = ref(18)

const addItem = () => {
  items.value.push({ name: '', qty: 1, price: 0 })
}

const removeItem = (index) => {
  items.value.splice(index, 1)
}

const subtotal = computed(() => items.value.reduce((sum, item) => sum + item.qty * item.price, 0))

const taxAmount = computed(() => subtotal.value * (taxRate.value / 100))
const total = computed(() => subtotal.value + taxAmount.value)

const generatePdf = async () => {
  try {
    const payload = {
      client_name: clientName.value,
      client_email: 'client@example.com', // You can add email input if needed
      items: items.value.map((item) => ({
        description: item.name,
        quantity: item.qty,
        price: item.price,
      })),
      subtotal: subtotal.value,
      gst: taxRate.value,
      total: total.value,
    }

    const response = await axios.post('http://127.0.0.1:8000/api/generate-pdf', payload, {
      responseType: 'blob',
    })

    // Download the PDF file
    const blob = new Blob([response.data], { type: 'application/pdf' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = 'invoice.pdf'
    link.click()

    console.log('PDF downloaded successfully.')
  } catch (error) {
    console.error('Error generating PDF:', error)
  }
}
</script>

<style scoped>
.input-style {
  @apply w-full px-4 py-2 rounded-lg bg-white/10 border border-white/10 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-blue-400;
}
</style>
