<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchByGeoLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:0.1|max:1000',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'lat.required' => 'Широта (lat) обязательна для поиска',
            'lat.numeric' => 'Широта должна быть числом',
            'lat.between' => 'Широта должна быть между -90 и 90 градусами',

            'lng.required' => 'Долгота (lng) обязательна для поиска',
            'lng.numeric' => 'Долгота должна быть числом',
            'lng.between' => 'Долгота должна быть между -180 и 180 градусами',

            'radius.numeric' => 'Радиус должен быть числом',
            'radius.min' => 'Минимальный радиус поиска: 0.1 км',
            'radius.max' => 'Максимальный радиус поиска: 1000 км',

            'per_page.integer' => 'Количество элементов на странице должно быть целым числом',
            'per_page.min' => 'Минимальное количество элементов на странице: :min',
            'per_page.max' => 'Максимальное количество элементов на странице: :max',

            'page.integer' => 'Номер страницы должен быть целым числом',
            'page.min' => 'Минимальный номер страницы: :min',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'radius' => $this->radius ?? 10, // Значение по умолчанию
        ]);
    }
}
