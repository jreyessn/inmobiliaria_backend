<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class TicketsStoreRequest extends FormRequest
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
            "title" => "required",
            "contact_id" => "required|exists:contacts,id",
            "cc" => "nullable|string",
            "type_ticket_id" => "required|exists:type_tickets,id",
            "status_ticket_id" => "nullable|exists:status_tickets,id",
            "priority_id" => "required|exists:priorities,id",
            "group_id" => "nullable|exists:groups,id",
            "user_id" => "nullable|exists:user,id",
            "deadline_date" => "nullable|date",
            "deadline_time" => "nullable|string",
            "message" => "required|string",
            "files" => "nullable|array"
        ];
    }
}
