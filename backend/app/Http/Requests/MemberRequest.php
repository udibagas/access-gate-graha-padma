<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'card_number' => 'required',
            'plate_number' => 'required',
            'group' => 'required|in:0,1',
            'active' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'card_mumber' => 'Nomor Kartu',
            'plate_number' => 'Plat Nomor',
            'group' => 'Group',
            'active' => 'Status',
            'address' => 'Alamat',
            'phone' => 'No. HP',
            'gender' => 'Jenis Kelamin',
            'id_number' => 'Nomor Identitas'
        ];
    }
}
