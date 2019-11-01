<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'role' => $this->role,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'by_father_name' => $this->by_father_name,
            'code_name' => $this->code_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'nationality' => $this->nationality,
            'role_status' => $this->role_status,
            'passport' => $this->passport,
            'passport_valid_from' => $this->passport_valid_from,
            'passport_valid_to' => $this->passport_valid_to,
            'work_perm_start' => $this->work_perm_start,
            'phone' => $this->phone,
            'phone_with_talegram' => $this->phone_with_talegram,
            'bank_account' => $this->bank_account,
            'bank_account_other' => $this->bank_account_other,
            'contragent_id' => $this->contragent_id,
            'email' => $this->email,
            'image' => $this->getImageUrl($this->image),
            'social_id' => $this->social_id,
            'password' => $this->password,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        
        ];
    }
}
