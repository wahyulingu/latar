<?php

namespace App\Actions\Content\Video;

use App\Actions\Action;
use App\Actions\Media\Video\Update as VideoUpdate;
use App\Models\Content\ContentVideo;

class Update extends Action
{
    public function __construct(protected VideoUpdate $videoUpdate)
    {
    }

    protected function rules(): array
    {
        return [
            'video' => ['nullable', 'image'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function handle(ContentVideo $model, array $input): bool
    {
        $this->validate($input);

        if ($input['video']) {
            $this->videoUpdate->handle($model->media, ['video' => $input['video']]);
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
