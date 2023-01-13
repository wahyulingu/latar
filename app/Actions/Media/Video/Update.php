<?php

namespace App\Actions\Media\Video;

use App\Actions\Action;
use App\Actions\File\Update as FileUpdate;
use App\Models\Media\MediaVideo;

class Update extends Action
{
    public function __construct(protected FileUpdate $fileUpdate)
    {
    }

    protected function rules(): array
    {
        return [
            'video' => ['required', 'file', 'mimetypes:video/avi,video/mpeg'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(MediaVideo $model, array $input): bool
    {
        $this->validate($input);

        if ($input['video']) {
            $this->fileUpdate->handle($model->file, ['file' => $input['video']]);
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
