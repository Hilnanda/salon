<?php

return array (
  'areYouSure' => 'Apakah Anda yakin?',
  'deleteWarning' => 'Anda tidak akan dapat memulihkan catatan yang dihapus!',
  'fieldRequired' => 'bidang wajib diisi',
  'alreadyTaken' => 'telah diambil. Coba yang lain. ',
  'nexmoKeyRequired' => 'Kunci Nexmo diperlukan untuk Status Aktif',
  'nexmoSecretRequired' => 'Rahasia Nexmo diperlukan untuk Status Aktif',
  'nexmoFromRequired' => 'Nexmo From diperlukan untuk Status Aktif',
  'coupon' => 
  array (
    'required' => 'Kode kupon tidak boleh kosong',
    'serviceRequired' => 'Tambahkan setidaknya satu layanan ke keranjang.',
    'customerRequired' => 'Pilih pelanggan untuk melanjutkan.',
  ),
  'bookingTime' => 
  array (
    'startTime' => 
    array (
      'dateFormat' => 'Waktu Buka harus dalam format 09:00.',
      'requiredIf' => 'Open Time diperlukan ketika: other is: value.',
    ),
    'endTime' => 
    array (
      'dateFormat' => 'Waktu Tutup harus dalam format 09:00.',
    ),
    'slotDuration' => 
    array (
      'integer' => 'Durasi Slot harus berupa bilangan bulat.',
      'requiredIf' => 'Durasi Slot diperlukan bila: lainnya adalah: nilai.',
      'min' => 'Nilai Minimum Durasi Slot harus 1.',
    ),
    'maxBooking' => 
    array (
      'integer' => 'Jumlah Pemesanan Maksimum harus berupa bilangan bulat.',
      'requiredIf' => 'Jumlah Pemesanan Maksimum diperlukan ketika: other is: value.',
      'min' => 'Nilai Minimum dari Jumlah Pemesanan Maksimal harus 0.',
    ),
  ),
  'payment' => 
  array (
    'requiredIf' => 'Bidang: atribut harus diisi saat status aktif',
  ),
);
