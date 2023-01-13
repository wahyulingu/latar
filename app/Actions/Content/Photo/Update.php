<?php

namespace App\Actions\Content\Photo;

use App\Actions\Action;
use App\Actions\Media\Picture\Update as PictureUpdate;
use App\Models\Content\ContentPhoto;

class Update extends Action
{
    public function __construct(protected PictureUpdate $pictureUpdate)
    {
    }

    protected function rules(): array
    {
        return [
            'photo' => ['nullable', 'image'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ContentPhoto $model, array $input): bool
    {
        $data = $this->validate($input);

        if (!empty($data['photo'])) {
            $this->pictureUpdate->handle($model->media, ['picture' => $data['photo']]);
        }

        if (!empty($data['name'])) {
            $model->name = $data['name'];
        }
        if (!empty($data['description'])) {
            $model->description = $data['description'];
        }

        return $model->save();
    }
}
