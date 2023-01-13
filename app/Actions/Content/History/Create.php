<?php

namespace App\Actions\Content\History;

use App\Actions\Action;
use App\Models\Content\ContentHistory;
use App\Models\Content\ContentMaster;

class Create extends Action
{
    protected function rules(): array
    {
        return [
            'price' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(ContentMaster $articleMaster, array $input): ContentHistory
    {
        $data = $this->validate($input);

        return $articleMaster

            ->histories()
            ->create($data);
    }
}
