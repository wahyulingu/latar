<?php

namespace App\Actions\Content\Article;

use App\Actions\Action;
use App\Models\Content\ContentArticle;
use App\Models\Content\ContentCategory;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:255'],

            'category_id' => [
                'nullable',
                sprintf('exists:%s,id', ContentCategory::class),
            ],
        ];
    }

    public function handle(ContentArticle $model, array $input): bool
    {
        $data = $this->validate($input);

        !empty($data['title']) && $model->title = $data['title'];

        !empty($data['content']) && $model->description = $data['content'];

        !empty($data['description']) && $model->description = $data['description'];

        !empty($data['category_id']) && $model->category_id = $data['category_id'];

        return $model->save();
    }
}
