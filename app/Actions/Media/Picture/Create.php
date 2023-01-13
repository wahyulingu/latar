<?php

namespace App\Actions\Media\Picture;

use App\Actions\Action;
use App\Actions\File\Upload;
use App\Contracts\Model\HasFile;
use App\Contracts\Model\HasMedia;

class Create extends Action
{
    public function __construct(protected Upload $upload)
    {
    }

    protected function rules(): array
    {
        return [
            'picture' => ['required', 'image'],
            'path' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(HasMedia $model, array $input)
    {
        $data = $this->validate($input);

        /** @var HasFile */
        $picture = $model->media()->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        $this->upload->handle(
            model: $picture,
            input: [
                'file' => $data['picture'],
                'path' => sprintf('media/%s', $data['path'] ?? 'pictures'),

                'name' => 'auto generated file model',

                'description' => sprintf('file model for picture %s', $input['name']),
            ]
        );
    }
}
