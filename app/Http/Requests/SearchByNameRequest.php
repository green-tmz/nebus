<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchByNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Zа-яА-Я0-9\s\-"\']+$/u',
            ],
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле названия организации обязательно для заполнения',
            'name.min' => 'Название организации должно содержать минимум :min символа',
            'name.max' => 'Название организации не должно превышать :max символов',
            'name.regex' => 'Название организации содержит недопустимые символы',
            'per_page.integer' => 'Количество элементов на странице должно быть целым числом',
            'per_page.min' => 'Минимальное количество элементов на странице: :min',
            'per_page.max' => 'Максимальное количество элементов на странице: :max',
            'page.integer' => 'Номер страницы должен быть целым числом',
            'page.min' => 'Минимальный номер страницы: :min',
        ];
    }
}
