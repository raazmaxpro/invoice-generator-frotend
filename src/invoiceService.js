// src/invoiceService.js

import axios from 'axios'

const API_BASE_URL = 'http://127.0.0.1:8000/api'

export async function generateInvoicePDF(invoiceData) {
  console.log('Sending invoice data to backend:', invoiceData)

  try {
    const response = await axios.post(`${API_BASE_URL}/generate-pdf`, invoiceData, {
      responseType: 'blob', // PDF expected
    })

    // Create Blob for download
    const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'invoice.pdf')
    document.body.appendChild(link)
    link.click()
    console.log('PDF downloaded successfully')
  } catch (error) {
    console.error('Error generating invoice PDF:', error)
    alert('Failed to generate PDF.')
  }
}
