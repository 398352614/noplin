<?php


namespace App\Resources;

class CardResource extends BaseResource
{
    public function toArray($request): array
    {
        $array = parent::toArray($request);
        $array['field_list'] = $this->fieldList;
        return $array;
    }

}