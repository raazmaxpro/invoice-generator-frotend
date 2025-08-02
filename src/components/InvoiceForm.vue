<template>
  <div class="invoice-form">
    <h2>Create Invoice</h2>

    <!-- Client Info -->
    <div>
      <label>Client Name:</label>
      <input v-model="form.client_name" type="text" placeholder="e.g. Raj Sen" />
    </div>

    <div>
      <label>Client Email:</label>
      <input v-model="form.client_email" type="email" placeholder="e.g. raj@example.com" />
    </div>

    <!-- Line Items -->
    <div class="text-red-500 text-2xl">Tailwind Working ✅</div>
    <h3>Items</h3>
    <div v-for="(item, index) in form.items" :key="index" class="item-row">
      <input v-model="item.description" placeholder="Description" />
      <input v-model.number="item.quantity" type="number" min="1" placeholder="Qty" />
      <input v-model.number="item.unit_price" type="number" min="0" placeholder="Unit Price" />
      <button @click="removeItem(index)">Remove</button>
    </div>

    <button @click="addItem">+ Add Item</button>

    <!-- Summary -->
    <div>
      <label>Tax Rate (%):</label>
      <input v-model.number="form.tax_rate" type="number" min="0" placeholder="e.g. 10" />
    </div>

    <div><strong>Subtotal:</strong> {{ subtotal }}</div>
    <div><strong>Total (with Tax):</strong> {{ total }}</div>

    <!-- Submit -->
    <button @click="submitInvoice">Save Invoice</button>
  </div>
</template>

<script>
export default {
  name: 'InvoiceForm',
  data() {
    return {
      form: {
        client_name: '',
        client_email: '',
        tax_rate: 10,
        items: [{ description: '', quantity: 1, unit_price: 0 }],
      },
    }
  },
  computed: {
    subtotal() {
      return this.form.items
        .reduce((sum, item) => {
          return sum + item.quantity * item.unit_price
        }, 0)
        .toFixed(2)
    },
    total() {
      const subtotal = parseFloat(this.subtotal)
      const taxAmount = subtotal * (this.form.tax_rate / 100)
      return (subtotal + taxAmount).toFixed(2)
    },
  },
  methods: {
    addItem() {
      this.form.items.push({ description: '', quantity: 1, unit_price: 0 })
    },
    removeItem(index) {
      this.form.items.splice(index, 1)
    },
    submitInvoice() {
      console.log('Submitting invoice:', this.form)
      // TODO: connect to Laravel backend
    },
  },
}
</script>

<style scoped>
.invoice-form {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}
input {
  display: block;
  margin-bottom: 10px;
  padding: 8px;
  width: 100%;
}
.item-row {
  margin-bottom: 10px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}
button {
  margin-top: 10px;
}
</style>
