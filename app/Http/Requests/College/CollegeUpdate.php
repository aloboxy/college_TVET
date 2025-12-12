<?php

namespace App\Http\Requests\College;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Qs;
class CollegeUpdate extends FormRequest
{

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
            'name' => 'required|string|min:3',
            'dean'=>'sometimes|nullable'
        ];


    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['dean'] = $input['dean'] ? Qs::decodeHash($input['dean']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}
