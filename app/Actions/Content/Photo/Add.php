<?php

namespace App\Actions\Content\Photo;

use App\Actions\Action;
use App\Actions\Media\Picture\Create;
use App\Contracts\Model\HasPhotos;
use App\Models\Content\ContentPhoto;

class Add extends Action
{
    public function __construct(protected Create $createMediaPicture)
    {
    }

    protected function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'max:1024'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function handle(HasPhotos $model, array $input)
    {
        $this->validate($input);

        /** @var ContentPhoto */
        $photo = $model->photos()->create(
            attributes: [
                'name' => $input['name'],
                'description' => $input['description'],
            ]
        );

        $this->createMediaPicture->handle(
            model: $photo,
            input: [
                'picture' => $input['photo'],

                'path' => 'content/photos',
                'name' => 'auto generated picture model',

                'description' => sprintf('picture model for content photo %s', $input['name']),
            ]
        );

        return $photo;
    }
}
