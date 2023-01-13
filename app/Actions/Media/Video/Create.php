<?php

namespace App\Actions\Media\Video;

use App\Actions\Action;
use App\Actions\File\Upload;
use App\Contracts\Model\HasFile;
use App\Contracts\Model\HasMedia;

class Create extends Action
{
    public function __construct(protected Upload $uploadFile)
    {
    }

    protected function rules(): array
    {
        return [
            'video' => ['required', 'file', 'mimetypes:video/avi,video/mpeg'],
            'path' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(HasMedia $model, array $input)
    {
        $data = $this->validate($input);

        /** @var HasFile */
        $video = $model->media()->create(
            attributes: [
                'name' => $data['name'],
                'description' => $data['description'],
            ]
        );

        $this->uploadFile->handle(
            model: $video,
            input: [
                'file' => $data['video'],
                'path' => sprintf('media/%s', $data['path'] ?? 'videos'),

                'name' => 'auto generated file model',

                'description' => sprintf('file model for video %s', $input['name']),
            ]
        );
    }
}
