<?php

namespace App\Traits;

trait ModelOperation
{
    /**
     * Fill the attributes data and save the model.
     *
     * @param  array $attributes
     * @return bool
     */
    public function saveData($attributes = [])
    {
        $fillableInputs = collect($attributes)->only($this->getFIllable());

        foreach ($fillableInputs as $key => $value) {
            $this->{$key} = $value;
        }

        return $this->save();
    }
}
