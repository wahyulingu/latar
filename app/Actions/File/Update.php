<?php

namespace App\Actions\File;

use App\Actions\Action;
use App\Models\File;

class Update extends Action
{
    public function __construct(protected Upload $uploadAction)
    {
    }

    protected function rules(): array
    {
        return [
            'file' => ['nullable', 'file'],
            'path' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(File $model, array $input): bool
    {
        $this->validate($input);

        if ($input['file']) {
            File::fileSystemAdapter()->delete($model->path);

            $model->path = $this->uploadAction->upload($input['file'], $input['path'] ?? 'files');
        }

        if ($input['name']) {
            $model->name = $input['name'];
        }
        if ($input['description']) {
            $model->description = $input['description'];
        }

        return $model->save();
    }
}
