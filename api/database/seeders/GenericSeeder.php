<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

abstract class GenericSeeder extends Seeder
{
    /*
         * @var array Data to fill in dictionary
         *
         * $data = [
         *     Model::class => [
         *         id => [
         *             'attribute key' => 'attribute value',
         *             'attribute key' => 'attribute value',
         *         ],
         *         id => [
         *             'attribute key' => 'attribute value',
         *             'attribute key' => 'attribute value',
         *         ],
         *     ],
         * ];
         */
    protected array $data = [

    ];

    /*
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (empty($this->data)) {
            return;
        }
        foreach ($this->data as $class => $items) {
            /** @var Model $class */
            foreach ($items as $id => $attributes) {
                $model = $class::query()->firstOrNew(['id' => $id]);
                foreach ($attributes as $key => $attribute) {
                    $model->setAttribute($key, $attribute);
                }
                $model->save();
            }
        }
    }
}
