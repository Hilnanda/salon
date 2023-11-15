<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Isian: attribute harus diterima.',
    'active_url'           => 'Isian: attribute bukan URL yang valid.',
    'after'                => 'The: attribute harus tanggal setelah: date.',
    'after_or_equal'       => 'The: attribute harus tanggal setelah atau sama dengan: date.',
    'alpha'                => 'Atribut: hanya boleh berisi huruf.',
    'alpha_dash'           => 'Atribut: hanya boleh berisi huruf, angka, tanda hubung dan garis bawah.',
    'alpha_num'            => 'Atribut: hanya boleh berisi huruf dan angka.',
    'array'                => 'Isian: attribute harus berupa array.',
    'before'               => 'Isian: attribute harus tanggal sebelum: tanggal.',
    'before_or_equal'      => 'The: attribute harus tanggal sebelum atau sama dengan: date.',
    'between'              => [
        'numeric' => 'Isian: attribute harus antara: min dan: max.',
        'file'    => 'Isian: attribute harus antara: min dan: max kilobyte.',
        'string'  => 'Isian: attribute harus antara: min dan: max karakter.',
        'array'   => 'Isian: attribute harus antara: min dan: max item.',
    ],
    'boolean'              => 'Isian: atribut field harus benar atau salah.',
    'confirmed'            => 'Konfirmasi: atribut tidak cocok.',
    'date'                 => 'Isian: attribute bukan tanggal yang valid.',
    'date_format'          => 'Atribut: tidak cocok dengan format: format.',
    'different'            => 'Isian: attribute dan: other harus berbeda.',
    'digits'               => 'Isian: attribute harus: digit digit.',
    'digits_between'       => 'Isian: attribute harus antara: min dan: max digit.',
    'dimensions'           => 'Atribut: memiliki dimensi gambar yang tidak valid.',
    'distinct'             => 'Bidang: atribut memiliki nilai duplikat.',
    'email'                => 'Isian: attribute harus berupa alamat email yang valid.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'Isian: attribute harus berupa file.',
    'filled'               => 'Isian: atribut field harus mempunyai nilai.',
    'gt'                   => [
        'numeric' => 'Isian: attribute harus lebih besar dari: value.',
        'file'    => 'Isian: attribute harus lebih besar dari: value kilobytes.',
        'string'  => 'Isian: attribute harus lebih besar dari: karakter nilai.',
        'array'   => 'Isian: attribute harus memiliki lebih dari: item nilai.',
    ],
    'gte'                  => [
        'numeric' => 'Isian: attribute harus lebih besar dari atau sama dengan: value.',
        'file'    => 'Isian: attribute harus lebih besar dari atau sama dengan: value kilobytes.',
        'string'  => 'The: attribute harus lebih besar dari atau sama dengan karakter value.',
        'array'   => 'Isian: attribute harus memiliki: item nilai atau lebih.',
    ],
    'image'                => 'Isian: attribute harus berupa gambar.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'Bidang: atribut tidak ada di: lainnya.',
    'integer'              => 'Isian: attribute harus berupa integer.',
    'ip'                   => 'Isian: atribut harus berupa alamat IP yang valid.',
    'ipv4'                 => 'Isian: attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => 'Isian: atribut harus berupa alamat IPv6 yang valid.',
    'json'                 => 'Isian: attribute harus berupa string JSON yang valid.',
    'lt'                   => [
        'numeric' => 'Isian: attribute harus kurang dari: value.',
        'file'    => 'Isian: attribute harus kurang dari: value kilobyte.',
        'string'  => 'Isian: attribute harus kurang dari: karakter value.',
        'array'   => 'Isian: attribute harus kurang dari: item nilai.',
    ],
    'lte'                  => [
        'numeric' => 'Isian: attribute harus kurang dari atau sama dengan: value.',
        'file'    => 'Isian: attribute harus kurang dari atau sama dengan: value kilobytes.',
        'string'  => 'The: attribute harus kurang dari atau sama dengan karakter value.',
        'array'   => 'Atribut: tidak boleh lebih dari: item nilai.',
    ],
    'max'                  => [
        'numeric' => 'The: attribute tidak boleh lebih dari: max.',
        'file'    => 'Atribut: tidak boleh lebih dari: max kilobyte.',
        'string'  => 'The: attribute tidak boleh lebih dari: max karakter.',
        'array'   => 'Atribut: tidak boleh memiliki lebih dari: item maks.',
    ],
    'mimes'                => 'The: attribute harus berupa file bertipe:: values.',
    'mimetypes'            => 'The: attribute harus berupa file bertipe:: values.',
    'min'                  => [
        'numeric' => 'The: attribute harus minimal: min.',
        'file'    => 'Isian: attribute harus minimal: min kilobyte.',
        'string'  => 'Isian: attribute harus minimal: karakter min.',
        'array'   => 'The: attribute harus memiliki setidaknya: min item.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'Format: atribut tidak valid.',
    'numeric'              => 'Isian: attribute harus berupa angka.',
    'present'              => 'Isian: atribut field harus ada.',
    'regex'                => 'Format: atribut tidak valid.',
    'required'             => 'Isian: atribut field diperlukan.',
    'required_if'          => 'The: attribute field harus diisi jika: other is: value.',
    'required_unless'      => 'Bidang: atribut wajib diisi kecuali: lainnya ada di dalam: nilai.',
    'required_with'        => 'Isian: atribut harus diisi bila: ada nilai.',
    'required_with_all'    => 'Isian: atribut harus diisi bila: ada nilai.',
    'required_without'     => 'Isian: atribut harus diisi bila: nilai tidak ada.',
    'required_without_all' => 'The: attribute field diperlukan jika tidak ada dari: nilai yang ada.',
    'same'                 => 'The: attribute dan: other harus cocok.',
    'size'                 => [
        'numeric' => 'Isian: attribute harus berukuran: size.',
        'file'    => 'Atribut: harus: size kilobyte.',
        'string'  => 'Isian: attribute harus: ukuran karakter.',
        'array'   => 'Isian: attribute harus berisi: size item.',
    ],
    'string'               => 'Isian: attribute harus berupa string.',
    'timezone'             => 'Isian: attribute harus merupakan zona yang valid.',
    'unique'               => 'Isian: attribute telah diambil.',
    'uploaded'             => 'Atribut: gagal diunggah.',
    'url'                  => 'Format: atribut tidak valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
