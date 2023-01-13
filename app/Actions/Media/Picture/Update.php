<?php

namespace App\Actions\Media\Picture;

use App\Actions\Action;
use App\Actions\File\Update as FileUpdate;
use App\Models\Media\MediaPicture;

class Update extends Action
{
    public function __construct(protected FileUpdate $fileUpdate)
    {
    }

    protected function rules(): array
    {
        return [
            'picture' => ['nullable', 'image'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(MediaPicture $model, array $input): bool
    {
        $this->validate($input);

        if ($input['picture']) {
            $this->fileUpdate->handle($model->file, ['file' => $input['picture']]);
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
