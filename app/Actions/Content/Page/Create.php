<?php

namespace App\Actions\Content\Page;

use App\Actions\Action;
use App\Models\Content\ContentCategory;
use App\Models\Content\ContentPage;
use App\Models\Profile\ProfileAuthor;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],

            'category_id' => [
                'required',
                sprintf('exists:%s,id', ContentCategory::class),
            ],
        ];
    }

    public function handle(ProfileAuthor $author, array $input): ContentPage
    {
        $data = $this->validate($input);

        return $author

            ->pages()
            ->create($data);
    }
}
