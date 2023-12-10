<?php

namespace App\Http\Requests;

use App\Models\MasterBalita;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BalitaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nik' => ['nik', 'max:255', Rule::unique(MasterBalita::class)->ignore($this->MasterBalita()->id)],
            'nama' => ['string', 'max:255'],
            'jk' => ['string', 'max:3'],
            'tgl_lahir' => ['date'],
            'bb_lahir' => ['string', 'max:15'],
            'tb_lahir' => ['string', 'max:15'],
            'tgl_pengukuran' => ['date'],
            'berat' => ['string', 'max:15'],
            'tinggi' => ['string', 'max:15'],
            'nama_ortu' => ['string', 'max:36'],
            'prov' => ['string', 'max:100'],
            'kab_kota' => ['string', 'max:100'],
            'kec' => ['string', 'max:100'],
            'desa_kel' => ['string', 'max:100'],
            'rt' => ['string', 'max:5'],
            'rw' => ['string', 'max:5'],
            'alamat' => ['string', 'max:255'],
        ];
    }
}
