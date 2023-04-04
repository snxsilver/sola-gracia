(function () {
  "use strict";
  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  let tambahBorongan = select('.tambah-borongan')
  if (tambahBorongan) {
    function editBorongan() {
      let nominal = this.previousElementSibling
      let id = this.parentElement.parentElement.previousElementSibling.firstElementChild
      let tanggal = id.nextElementSibling

      nominal.disabled = !nominal.disabled
      id.disabled = !id.disabled
      tanggal.disabled = !tanggal.disabled
    }
    function hapusBorongan() {
      let targetRow = this.parentElement.parentElement.parentElement
      if (!targetRow.classList.contains('mt-3')) {
        targetRow.nextElementSibling.classList.remove('mt-3')
      }

      let targetId = this.parentElement.parentElement.previousElementSibling.firstElementChild
      if (targetId.name == 'bayarid[]') {
        let hapusField = select('.hapus-field')
        let inputId = document.createElement('input')
        inputId.type = 'hidden'
        inputId.name = 'hapusid[]'
        inputId.value = targetId.value

        hapusField.appendChild(inputId)
      }
      targetRow.remove()
    }
    function fungsiTambahBorongan() {
      let field = select('.borongan-field')

      let inputTanggal = document.createElement('input')
      inputTanggal.type = 'text'
      inputTanggal.className = 'form-control bo-datepicker'
      inputTanggal.name = 'tanggal[]'
      inputTanggal.readOnly = true
      inputTanggal.style = 'background: transparent'

      const colTanggal = document.createElement('div')
      colTanggal.className = 'col-4'

      let inputNominal = document.createElement('input')
      inputNominal.type = 'number'
      inputNominal.className = 'form-control'
      inputNominal.name = 'nominal[]'

      const divNominal = document.createElement('div')
      divNominal.className = 'd-flex align-items-center'

      const colNominal = document.createElement('div')
      colNominal.className = 'col-8'

      const row = document.createElement('div')
      row.className = 'row mt-3'

      colTanggal.appendChild(inputTanggal)

      // buttonAdd.appendChild(iplus)

      divNominal.appendChild(inputNominal)
      divNominal.appendChild(this)

      colNominal.appendChild(divNominal)

      row.appendChild(colTanggal)
      row.appendChild(colNominal)

      field.appendChild(row)

      let thisRow = this.parentElement.parentElement.parentElement
      let prevRow = thisRow.previousElementSibling
      let targetDiv = prevRow.lastElementChild.lastElementChild

      let iminus = document.createElement('i')
      iminus.className = 'fa fa-minus'

      let buttonAdd = document.createElement('button')
      buttonAdd.type = 'button'
      buttonAdd.className = 'btn btn-danger ml-2'
      buttonAdd.style = "margin: 0"
      buttonAdd.onclick = hapusBorongan

      buttonAdd.appendChild(iminus)

      targetDiv.appendChild(buttonAdd)
    }
    on('click', '.tambah-borongan', fungsiTambahBorongan)
    on('click', '.hapus-borongan', hapusBorongan, true)
    on('click', '.edit-borongan', editBorongan, true)
  }

  let aturHarian = select('.atur-harian')
  if (aturHarian) {
    function formatHari(tanggal) {
      var date = new Date(tanggal)
      var bulan = date.getMonth();
      var tanggal = date.getDate();
      var hari = date.getDay();

      switch (hari) {
        case 0: hari = "Mng"; break;
        case 1: hari = "Sen"; break;
        case 2: hari = "Sel"; break;
        case 3: hari = "Rab"; break;
        case 4: hari = "Kam"; break;
        case 5: hari = "Jum"; break;
        case 6: hari = "Sab"; break;
      }
      switch (bulan) {
        case 0: bulan = "Jan"; break;
        case 1: bulan = "Feb"; break;
        case 2: bulan = "Mar"; break;
        case 3: bulan = "Apr"; break;
        case 4: bulan = "Mei"; break;
        case 5: bulan = "Jun"; break;
        case 6: bulan = "Jul"; break;
        case 7: bulan = "Agt"; break;
        case 8: bulan = "Sep"; break;
        case 9: bulan = "Okt"; break;
        case 10: bulan = "Nov"; break;
        case 11: bulan = "Des"; break;
      }

      return hari + ", " + tanggal + " " + bulan
    }

    function firstDay(tanggal){
      var date = new Date(tanggal)
      var thisDate = date.getDay()
      switch (thisDate) {
        case 0: date = date; break;
        case 1: date = date.setDate(date.getDate() - 1); break;
        case 2: date = date.setDate(date.getDate() - 2); break;
        case 3: date = date.setDate(date.getDate() - 3); break;
        case 4: date = date.setDate(date.getDate() - 4); break;
        case 5: date = date.setDate(date.getDate() - 5); break;
        case 6: date = date.setDate(date.getDate() - 6); break;
      }

      return new Date(date)
    }

    on('click', '.atur-harian', function(){
      let date = select('input[name=setdate]')
      if (!date.value) {
        return
      }
      let tampil = select('input[name^=tampil]', true)
      let tanggal = select('input[name^=tanggal]', true)

      let x = 0
      tampil.forEach(function (el){
        let fd = firstDay(date.value)
        el.value = formatHari(fd.setDate(fd.getDate() + x ))
        x++
      })

      x = 1
      tanggal.forEach(function(el){
        let fd = firstDay(date.value)
        let d = new Date(fd.setDate(fd.getDate() + x ))
        let a = d.toISOString().split('T')[0]
        el.value = a
        x++
      })

      let target = select('.target_jadwal')
      target.classList.remove('d-none')
    })
  }

  let tambahMandor = select('.tambah-mandor')
  if (tambahMandor){
    // function fungsiTambah(){
      //   alert('hello')
      // }
      function hapusMandor() {
        let targetRow = this.parentElement.parentElement.parentElement.parentElement.parentElement
  
        let targetId = targetRow.firstElementChild
        if (targetId.name == 'bayarid[]') {
          if (targetId.value !== '' ){
            let hapusField = select('.hapus-field')
            let inputId = document.createElement('input')
            inputId.type = 'hidden'
            inputId.name = 'hapusid[]'
            inputId.value = targetId.value
  
            hapusField.appendChild(inputId)
          }
        }
        targetRow.remove()
      }
      
      on('click','.tambah-mandor', function(){
        // alert('hello')
        let targetRow = tambahMandor.parentElement.parentElement.parentElement.parentElement.parentElement.nextElementSibling.firstElementChild
        let targetField = select('.target-mandor')
        const clone = targetRow.cloneNode(true)
        const button = clone.lastElementChild.lastElementChild.lastElementChild.lastElementChild.lastElementChild
        button.onclick = hapusMandor
        clone.classList.remove('d-none')
        targetField.appendChild(clone)
    })


    on('click','.hapus-mandor', hapusMandor, true)
  }

  let invoiceField = select('.invoice-field')
  if (invoiceField){
    function hapusInvoice() {
      let targetRow = this.parentElement.parentElement.parentElement
      if (!targetRow.classList.contains('mt-3')) {
        targetRow.nextElementSibling.classList.remove('mt-3')
      }
      targetRow.remove()
    }
    function fungsiTambahInvoice(){
      let field = select('.invoice-field')

      let inputKet = document.createElement('textarea')
      inputKet.name = 'keterangan[]'
      inputKet.className = 'form-control'
      inputKet.placeholder = 'Masukkan Keterangan'
      inputKet.cols = '30'
      inputKet.rows = '3'

      const colKet = document.createElement('div')
      colKet.className = 'col-8'

      let inputNominal = document.createElement('input')
      inputNominal.type = 'number'
      inputNominal.className = 'form-control'
      inputNominal.placeholder = 'Masukkan Nominal Pembayaran'
      inputNominal.name = 'nominal[]'

      let spanRp = document.createElement('span')
      spanRp.className = 'input-group-text'
      spanRp.textContent = 'Rp'

      let divPrepend = document.createElement('div')
      divPrepend.className = 'input-group-prepend'

      let divInputGroup = document.createElement('div')
      divInputGroup.className = 'input-group'
      divInputGroup.style = 'margin-bottom: 0'

      let divFlex = document.createElement('div')
      divFlex.className = 'd-flex align-items-center'

      const colNominal = document.createElement('div')
      colNominal.className = 'col-4'

      const row = document.createElement('div')
      row.className = 'row mt-3'

      colKet.appendChild(inputKet)

      divPrepend.appendChild(spanRp)
      divInputGroup.appendChild(divPrepend)
      divInputGroup.appendChild(inputNominal)
      divFlex.appendChild(divInputGroup)
      divFlex.appendChild(this)

      colNominal.appendChild(divFlex)

      row.appendChild(colKet)
      row.appendChild(colNominal)

      field.appendChild(row)

      let thisRow = this.parentElement.parentElement.parentElement
      let prevRow = thisRow.previousElementSibling
      let targetDiv = prevRow.lastElementChild.lastElementChild

      let iminus = document.createElement('i')
      iminus.className = 'fa fa-minus'

      let buttonAdd = document.createElement('button')
      buttonAdd.type = 'button'
      buttonAdd.className = 'btn btn-danger ml-2'
      buttonAdd.style = "margin: 0"
      buttonAdd.onclick = hapusInvoice

      buttonAdd.appendChild(iminus)

      targetDiv.appendChild(buttonAdd)
    }
    on('click','.tambah-invoice', fungsiTambahInvoice)
    on('click','.hapus-invoice', hapusInvoice, true)
  }
})()