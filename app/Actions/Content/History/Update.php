<?php

namespace App\Actions\Content\History;

use App\Actions\Action;
use App\Models\Content\ContentHistory;
use App\Models\Content\ContentMaster;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'price' => ['nullable', 'numeric'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],

            'article_id' => [
                'nullable',
                sprintf('exists:%s,id', ContentMaster::class),
            ],
        ];
    }

    public function handle(ContentHistory $model, array $input): bool
    {
        $this->validate($input);

        if (!empty($input['price'])) {
            $model->price = $input['price'];
        }

        if (!empty($input['name'])) {
            $model->name = $input['name'];
        }

        if (!empty($input['description'])) {
            $model->description = $input['description'];
        }

        if (!empty($input['article_id'])) {
            $model->article_id = $input['article_id'];
        }

        return $model->save();
    }
}
