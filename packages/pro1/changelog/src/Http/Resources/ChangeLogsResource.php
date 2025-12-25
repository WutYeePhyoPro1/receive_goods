<?php

namespace Pro1\Changelog\Http\Resources;

use App\Models\User;
use Pro1\Changelog\Models\SubChange;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class ChangeLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $change_type_id = $request->change_type_id;
        return [
            "id"=>$this->id,
            "title"=>$this->title,
            "description"=>$this->description,
            "user_id"=>$this->user_id,
            "requester_id"=>$this->requester_id,
            "created_at"=>$this->created_at->format("M d, Y"),
            "updated_at"=>$this->updated_at->format("d m Y"),
            "created_at_diff"=>$this->created_at->diffForHumans(),


            "user"=>User::where("id",$this->user_id)->select(["id","name"])->first(),

            'subchanges' => SubChange::where("change_log_id",$this->id)
                ->when($change_type_id, function ($query) use ($change_type_id) {
                    $query->where("change_type_id", $change_type_id);
                })
                ->latest()->get()->map(function ($subchange,$idx) {
                return [
                    'id' => $subchange->id,
                    'title' => $subchange->title ?? "Change".++$idx ,
                    'description' => Str::limit(strip_tags(html_entity_decode($subchange->description)), 250),
                    'changetype' => $subchange->changetype
                ];
            })->toArray(),
            "isAgreed" => $this->isAgreed() ? \Carbon\Carbon::parse($this->isAgreed())->format("M d, Y h:i:s") : '',
        ];
    }
}
