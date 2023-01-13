<?php

namespace App\Actions\Content\Category;

use App\Actions\Action;
use App\Models\Content\ContentCategory;

class Update extends Action
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(ContentCategory $articleCategory, array $input): bool
    {
        return $articleCategory->update($this->validate($input));
    }
}
