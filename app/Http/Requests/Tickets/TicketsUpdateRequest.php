<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class TicketsUpdateRequest extends FormRequest
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

        if($this->method() == 'PATCH'){
            
            $fields = [];

            if($this->has("priority_id")){
                $fields = array_merge($fields, ['priority_id' => "nullable|exists:priorities,id"]);
            }
            
            if($this->has("system_id")){
                $fields = array_merge($fields, ['system_id' => "nullable|exists:systems,id"]);
            }
            
            if($this->has("user_id")){
                $fields = array_merge($fields, ['user_id' => "nullable|exists:users,id"]);
            }
            
            if($this->has("status_ticket_id")){
                $fields = array_merge($fields, ['status_ticket_id' => "nullable|exists:status_tickets,id"]);
            }
            
            if($this->has("type_ticket_id")){
                $fields = array_merge($fields, ['type_ticket_id' => "nullable|exists:status_tickets,id"]);
            }
            
            return $fields;

        }
        else{
            
            return [
                "title" => "required|string",
                "contact_id" => "nullable|exists:contacts,id",
                "type_ticket_id" => "required|exists:type_tickets,id",
                "status_ticket_id" => "required|exists:status_tickets,id",
                "priority_id" => "required|exists:priorities,id",
                "group_id" => "nullable|exists:groups,id",
                "system_id" => "nullable|exists:systems,id",
                "user_id" => "nullable|array",
                "deadline" => "nullable",
            ];

        }
    }
}
