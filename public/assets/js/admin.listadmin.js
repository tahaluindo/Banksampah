/**
 * GET ALL NASABAH
 */
 let arrayAdmin = [];
 const getAllAdmin = async () => {
     $('#search-admin').val('');
     $('#list-admin-notfound').addClass('d-none'); 
     $('#list-admin-spinner').removeClass('d-none'); 
     let httpResponse = await httpRequestGet(`${APIURL}/admin/getadmin`);
     $('#list-admin-spinner').addClass('d-none'); 
     
     if (httpResponse.status === 404) {
         $('#list-admin-notfound').removeClass('d-none'); 
         $('#list-admin-notfound #text-notfound').html(`belum ada admin lain`); 
     }
     else if (httpResponse.status === 200) {
         let trAdmin  = '';
         let allAdmin = httpResponse.data.data;
         arrayAdmin   = httpResponse.data.data;
 
         allAdmin.forEach((n,i) => {
             let stringLastActive = 'belum login';
 
             if (n.last_active != n.created_at) {
                 let date  = new Date(parseInt(n.last_active) * 1000);
                 let hour  = date.toLocaleString("en-US",{ hour: 'numeric', minute: 'numeric' });
                 let day   = date.toLocaleString("en-US",{day: "numeric"});
                 let month = date.toLocaleString("en-US",{month: "long"});
                 let year  = date.toLocaleString("en-US",{year: "numeric"});
                 stringLastActive = `${day}-${month}-${year} ${hour}`;
             }
 
             trAdmin += `<tr class="text-xs">
                 <td class="align-middle text-center py-3">
                     <span class="font-weight-bold"> ${++i} </span>
                 </td>
                 <td class="align-middle text-center py-3">
                     <span class="font-weight-bold"> ${n.username} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold text-capitalize"> ${n.nama_lengkap} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold badge border ${(n.privilege === 'superadmin')? 'text-primary border-primary' : 'text-info border-info'} pb-1 rounded-sm"> ${n.privilege} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold badge border ${(n.is_active === 't' || n.is_active === '1')? 'text-success border-success' : 'text-warning border-warning'} pb-1 rounded-sm"> ${(n.is_active === 't' || n.is_active === '1')? 'yes' : 'no'} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold text-capitalize"> 
                         ${stringLastActive}
                     </span>
                 </td>
                 <td class="align-middle text-center">
                     <a href="" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer" onclick="hapusAdmin('${n.id}',event)">hapus</a>
                     <a href="" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer" data-toggle="modal" data-target="#modalAddEditAdmin" onclick="openModalAddEditAdm('editadmin','${n.id}')">edit</a>
                 </td>
             </tr>`;
         });
 
         $('#table-admin tbody').html(trAdmin);
     }
 };
 getAllAdmin();
 
 // Search admin
 $('#search-admin').on('keyup', function() {
     let elSugetion      = '';
     let adminFiltered = [];
     
     if ($(this).val() === "") {
         adminFiltered = arrayAdmin;
     } 
     else {
         adminFiltered = arrayAdmin.filter((n) => {
             return n.nama_lengkap.includes($(this).val().toLowerCase()) || n.username.includes($(this).val().toLowerCase());
         });
     }
 
     if (adminFiltered.length == 0) {
         $('#list-admin-notfound').removeClass('d-none'); 
         $('#list-admin-notfound #text-notfound').html(`admin tidak ditemukan`); 
     } 
     else {
         $('#list-admin-notfound').addClass('d-none'); 
         $('#list-admin-notfound #text-notfound').html(` `); 
 
         adminFiltered.forEach((n,i) => {
             let stringLastActive = 'belum login';
 
             if (n.last_active != n.created_at) {
                 let date  = new Date(parseInt(n.last_active) * 1000);
                 let hour  = date.toLocaleString("en-US",{ hour: 'numeric', minute: 'numeric' });
                 let day   = date.toLocaleString("en-US",{day: "numeric"});
                 let month = date.toLocaleString("en-US",{month: "long"});
                 let year  = date.toLocaleString("en-US",{year: "numeric"});
                 stringLastActive = `${day}-${month}-${year} ${hour}`;
             }
 
             elSugetion += `<tr class="text-xs">
                 <td class="align-middle text-center py-3">
                     <span class="font-weight-bold"> ${++i} </span>
                 </td>
                 <td class="align-middle text-center py-3">
                     <span class="font-weight-bold"> ${n.username} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold text-capitalize"> ${n.nama_lengkap} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold badge border ${(n.privilege === 'superadmin')? 'text-primary border-primary' : 'text-info border-info'} pb-1 rounded-sm"> ${n.privilege} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold badge border ${(n.is_active === 't' || n.is_active === '1')? 'text-success border-success' : 'text-warning border-warning'} pb-1 rounded-sm"> ${(n.is_active === 't' || n.is_active === '1')? 'yes' : 'no'} </span>
                 </td>
                 <td class="align-middle text-center">
                     <span class="font-weight-bold text-capitalize"> 
                         ${stringLastActive}
                     </span>
                 </td>
                 <td class="align-middle text-center">
                     <span id="btn-hapus" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer" onclick="hapusAdmin('${n.id}')">hapus</span>
                     <span id="btn-hapus" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer" data-toggle="modal" data-target="#modalAddEditAdmin" onclick="openModalAddEditAdm('editadmin','${n.id}')">edit</span>
                 </td>
             </tr>`;
         });    
     }
 
     $('#table-admin tbody').html(elSugetion);
 });
 
 // Edit modal when open
 const openModalAddEditAdm = (modalName,idadmin=null) => {
     let modalTitle = (modalName=='addadmin') ? 'tambah admin' : 'edit admin' ;
     
     $('#modalAddEditAdmin .modal-title').html(modalTitle);
     $('#formAddEditAdmin .form-check-input').prop('checked',false);
     // clear error message first
     $('#formAddEditAdmin .form-control').removeClass('is-invalid');
     $('#formAddEditAdmin .form-check-input').removeClass('is-invalid');
     $('#formAddEditAdmin .text-danger').html('');
 
     if (modalName == 'addadmin') {
         $('#modalAddEditAdmin .addadmin-item').removeClass('d-none');
         $('#modalAddEditAdmin .editadmin-item').addClass('d-none');        
         
         clearInputForm();
     } 
     else {
         $('#modalAddEditAdmin .addadmin-item').addClass('d-none');
         $('#modalAddEditAdmin .editadmin-item').removeClass('d-none');        
         
         $('#modalAddEditAdmin #list-admin-spinner').removeClass('d-none');
         getProfileAdmin(idadmin);
     }
 }
 
 /**
  * CRUD ADMIN
  */
 const crudAdmin = async (el,event) => {
     event.preventDefault();
     let form = new FormData(el);
 
     if (doValidate(form)) {
         let httpResponse = '';
         let modalTitle   = $('#modalAddEditAdmin .modal-title').html();
         let newTgl       = form.get('tgl_lahir').split('-');
         let isSuperAdmin = $('#formAddEditAdmin input[name=privilege]').val();
         form.set('tgl_lahir',`${newTgl[2]}-${newTgl[1]}-${newTgl[0]}`);
         form.set('privilege',`${(isSuperAdmin == '1') ? 'superadmin' : 'admin' }`);
 
         $('#formAddEditAdmin button#submit #text').addClass('d-none');
         $('#formAddEditAdmin button#submit #spinner').removeClass('d-none');
         if (modalTitle == 'edit admin') {
             form.set('is_active',$('#formAddEditAdmin input[name=is_active]').val());
             httpResponse = await httpRequestPut(`${APIURL}/admin/editadmin`,form);    
         } 
         else {
             httpResponse = await httpRequestPost(`${APIURL}/register/admin`,form);    
         }
         $('#formAddEditAdmin button#submit #text').removeClass('d-none');
         $('#formAddEditAdmin button#submit #spinner').addClass('d-none');
 
         if (httpResponse.status === 201) {
             getAllAdmin();
             if (modalTitle == 'tambah admin') {
                 clearInputForm();
             } 
 
             showAlert({
                 message: `<strong>Success...</strong> admin berhasil ${(modalTitle == 'tambah admin') ? 'ditambah' : 'diedit' }!`,
                 autohide: true,
                 type:'success'
             })
         }
         else if (httpResponse.status === 400) {
             if (httpResponse.message.username) {
                 $('#formAddEditAdmin #username').addClass('is-invalid');
                 $('#formAddEditAdmin #username-error').text(httpResponse.message.username);
             }
             if (httpResponse.message.notelp) {
                 $('#formAddEditAdmin #notelp').addClass('is-invalid');
                 $('#formAddEditAdmin #notelp-error').text(httpResponse.message.notelp);
             }
         }
     }
 }
 
 /**
  * GET PROFILE ADMIN
  */
  const getProfileAdmin = async (id) => {
 
     let httpResponse = await httpRequestGet(`${APIURL}/admin/getadmin?id=${id}`);
     $('#modalAddEditAdmin #list-admin-spinner').addClass('d-none');
     
     if (httpResponse.status === 200) {
         dataAdmin = httpResponse.data.data;
         
         for (const name in dataAdmin) {
             $(`#formAddEditAdmin input[name=${name}]`).val(dataAdmin[name]);
         }
     
         // tgl lahir
         let tglLahir = dataAdmin.tgl_lahir.split('-');
         $(`#formAddEditAdmin input[name=tgl_lahir]`).val(`${tglLahir[2]}-${tglLahir[1]}-${tglLahir[0]}`);
         // kelamin
         $(`#formAddEditAdmin input#kelamin-${dataAdmin.kelamin}`).prop('checked',true);
         // admin privilege
         if (dataAdmin.privilege == 'superadmin') {
             $(`#formAddEditAdmin input[name=privilege]`).val('1');
             $(`#formAddEditAdmin .toggle-privilege`).removeClass('bg-secondary').addClass('active bg-success');
         } 
         else {
             $(`#formAddEditAdmin input[name=privilege]`).val('0');
             $(`#formAddEditAdmin .toggle-privilege`).removeClass('active bg-success').addClass('bg-secondary');
         }
         
         // is account active
         if (dataAdmin.is_active == 't' || dataAdmin.is_active === '1') {
             $(`#formAddEditAdmin input[name=is_active]`).val('1');
             $(`#formAddEditAdmin .toggle-akunaktif`).removeClass('bg-secondary').addClass('active bg-success');
         } 
         else {
             $(`#formAddEditAdmin input[name=is_active]`).val('0');
             $(`#formAddEditAdmin .toggle-akunaktif`).removeClass('active bg-success').addClass('bg-secondary');
         }
 
         $('#newpass').val('');
     }
 };
 
 // clear input form
 const clearInputForm = () => {
     $(`#formAddEditAdmin input[name=privilege]`).val('');
     $('.toggle-privilege').removeClass('active bg-success').addClass('bg-secondary');
     $(`#formAddEditAdmin .form-control`).val('');
 }
 
 // change kelamin value
 $('#formAddEditAdmin .form-check-input').on('click', function(e) {
     $(`#formAddEditAdmin input[name=kelamin]`).val($(this).val());
     $('#formAddEditAdmin .form-check-input').prop('checked',false);
     $(this).prop('checked',true);
 });
 
 // change superadmin/account activate value
 $('#formAddEditAdmin input[type=checkbox]').on('click', function(e) {
     if ($(this).val() == '1') {
         $(this).val('0');
         $(this).parent().removeClass('active bg-success').addClass('bg-secondary');
     } 
     else {
         $(this).val('1');
         $(this).parent().removeClass('bg-secondary').addClass('active bg-success');
     }
 });
 
 /**
  * HAPUS ADMIN
  */
 const hapusAdmin = (id,event) => {
     event.preventDefault();
 
     Swal.fire({
         title: 'ANDA YAKIN?',
         text: `semua artikel yang ditulis admin ini akan ikut terhapus`,
         icon: 'warning',
         showCancelButton: true,
         confirmButtonText: 'iya',
     }).then((result) => {
         if (result.isConfirmed) {
             Swal.fire({
                 input: 'password',
                 inputAttributes: {
                     autocapitalize: 'off'
                 },
                 html:`<h5 class='mb-4'>Password</h5>`,
                 showCancelButton: true,
                 confirmButtonText: 'submit',
                 showLoaderOnConfirm: true,
                 preConfirm: (password) => {
                     let form = new FormData();
                     form.append('hashedpass',PASSADMIN);
                     form.append('password',password);
         
                     return axios
                     .post(`${APIURL}/admin/confirmdelete`,form, {
                         headers: {
                             // header options 
                         }
                     })
                     .then((response) => {
                         return httpRequestDelete(`${APIURL}/admin/deleteadmin?id=${id}`)
                         .then((e) => {
                             if (e.status == 201) {
                                 getAllAdmin();
                             }
                             else if (e.status == 400) {
                                 showAlert({
                                     message: `<strong>Gagal . . .</strong> ${e.message}`,
                                     autohide: true,
                                     type:'danger'
                                 })
                             }
                         })
                     })
                     .catch(error => {
                         if (error.response.status == 404) {
                             Swal.showValidationMessage(
                                 `password salah`
                             )
                         }
                         else if (error.response.status == 500) {
                             Swal.showValidationMessage(
                                 `terjadi kesalahan, coba sekali lagi`
                             )
                         }
                     })
                 },
                 allowOutsideClick: () => !Swal.isLoading()
             })
         }
     })
 }
 
 // form validation
 const doValidate = (form) => {
     let status     = true;
     let emailRules = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
 
     // clear error message first
     $('.form-control').removeClass('is-invalid');
     $('.form-check-input').removeClass('is-invalid');
     $('.text-danger').html('');
 
     // name validation
     if ($('#formAddEditAdmin #nama').val() == '') {
         $('#formAddEditAdmin #nama').addClass('is-invalid');
         $('#formAddEditAdmin #nama-error').html('*nama lengkap harus di isi');
         status = false;
     }
     else if ($('#formAddEditAdmin #nama').val().length > 40) {
         $('#formAddEditAdmin #nama').addClass('is-invalid');
         $('#formAddEditAdmin #nama-error').html('*maksimal 40 huruf');
         status = false;
     }
     // username validation
     if ($('#formAddEditAdmin #username').val() == '') {
         $('#formAddEditAdmin #username').addClass('is-invalid');
         $('#formAddEditAdmin #username-error').html('*username harus di isi');
         status = false;
     }
     else if ($('#formAddEditAdmin #username').val().length < 8 || $('#formAddEditAdmin #username').val().length > 20) {
         $('#formAddEditAdmin #username').addClass('is-invalid');
         $('#formAddEditAdmin #username-error').html('*minimal 8 huruf dan maksimal 20 huruf');
         status = false;
     }
     else if (/\s/.test($('#formAddEditAdmin #username').val())) {
         $('#formAddEditAdmin #username').addClass('is-invalid');
         $('#formAddEditAdmin #username-error').html('*tidak boleh ada spasi');
         status = false;
     }
 
     // add nasabah
     if (!$('#modalAddEditAdmin .addadmin-item').hasClass('d-none')) {
         // password validation
         if ($('#formAddEditAdmin #password').val() == '') {
             $('#formAddEditAdmin #password').addClass('is-invalid');
             $('#formAddEditAdmin #password-error').html('*password harus di isi');
             status = false;
         }
         else if ($('#formAddEditAdmin #password').val().length < 8 || $('#formAddEditAdmin #password').val().length > 20) {
             $('#formAddEditAdmin #password').addClass('is-invalid');
             $('#formAddEditAdmin #password-error').html('*minimal 8 huruf dan maksimal 20 huruf');
             status = false;
         }
         else if (/\s/.test($('#formAddEditAdmin #password').val())) {
             $('#formAddEditAdmin #password').addClass('is-invalid');
             $('#formAddEditAdmin #password-error').html('*tidak boleh ada spasi');
             status = false;
         }
     }
 
     // new pass 
     if ($('#modalAddEditAdmin #newpass').val() !== '') {   
         if ($('#modalAddEditAdmin #newpass').val().length < 8 || $('#modalAddEditAdmin #newpass').val().length > 20) {
             $('#modalAddEditAdmin #newpass').addClass('is-invalid');
             $('#modalAddEditAdmin #newpass-error').html('*minimal 8 huruf dan maksimal 20 huruf');
             status = false;
         }
         else if (/\s/.test($('#modalAddEditAdmin #newpass').val())) {
             $('#modalAddEditAdmin #newpass').addClass('is-invalid');
             $('#modalAddEditAdmin #newpass-error').html('*tidak boleh ada spasi');
             status = false;
         }
     }
 
     // tgl lahir validation
     if ($('#formAddEditAdmin #tgllahir').val() == '') {
         $('#formAddEditAdmin #tgllahir').addClass('is-invalid');
         $('#formAddEditAdmin #tgllahir-error').html('*tgl lahir harus di isi');
         status = false;
     }
     // kelamin validation
     if ($(`#formAddEditAdmin input[name=kelamin]`).val() == '') {
         $('.form-check-input').addClass('is-invalid');
         
         status = false;
     }
     // alamat validation
     if ($('#formAddEditAdmin #alamat').val().length > 255) {
         $('#formAddEditAdmin #alamat').addClass('is-invalid');
         $('#formAddEditAdmin #alamat-error').html('*maksimal 255 huruf');
         status = false;
     }
     // notelp validation
     if ($('#formAddEditAdmin #notelp').val().length > 14) {
         $('#formAddEditAdmin #notelp').addClass('is-invalid');
         $('#formAddEditAdmin #notelp-error').html('*maksimal 14 huruf');
         status = false;
     }
     else if ($('#formAddEditAdmin #notelp').val() != "" && !/^\d+$/.test($('#formAddEditAdmin #notelp').val())) {
         $('#formAddEditAdmin #notelp').addClass('is-invalid');
         $('#formAddEditAdmin #notelp-error').html('*hanya boleh angka');
         status = false;
     }
 
     return status;
 }