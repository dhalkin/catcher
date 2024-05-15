<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Symbol extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['symbol'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'symbol' => ['required', Rule::unique('symbols')->ignore($this->id)],
        ];
    }
}
