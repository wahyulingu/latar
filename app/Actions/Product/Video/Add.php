<?php

namespace App\Actions\Product\Video;

use App\Actions\Action;
use App\Actions\Media\Video\Create;
use App\Contracts\Model\HasMedia;
use App\Contracts\Model\HasVideos;

class Add extends Action
{
    public function __construct(protected Create $createMediaVideo)
    {
    }

    protected function rules(): array
    {
        return [
            'video' => ['required', 'file', 'mimetypes:video/mpeg,video/mp4', 'max:20480'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(HasVideos $model, array $input)
    {
        $this->validate($input);

        /** @var HasMedia */
        $video = $model->videos()->create(
            attributes: [
                'name' => $input['name'],
                'description' => $input['description'],
            ]
        );

        $this->createMediaVideo->handle(
            model: $video,
            input: [
                'video' => $input['video'],

                'path' => 'product/videos',
                'name' => 'auto generated video model',

                'description' => sprintf('video model for product video %s', $input['name']),
            ]
        );
    }
}
