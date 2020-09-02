<?php

namespace App\Http\Requests;

use App\Client;
use Illuminate\Foundation\Http\FormRequest;

class ClientForm extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:200',
            'cpf' => 'required|cpf',
            'cep' => 'cep',
            'state_id' => 'required',
            'city' => 'required|max:200',
            'district' => 'max:200',
            'street' => 'max:200',
            'number' => ''
        ];
    }
    
    /**
     * Persist client data in database.
     *
     * @param \App\Client $client
     */
    public function persist(Client $client)
    {
        $client->name = $this->name;
        $client->cpf = $this->cpf;
        $client->cep = $this->cep;
        $client->state_id = $this->state_id;
        $client->city = $this->city;
        $client->district = $this->district;
        $client->street = $this->street;
        $client->number = $this->number;

        $client->save();
    }
}
