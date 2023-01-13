<?php

namespace App\Actions\File;

use App\Actions\Action;
use App\Contracts\Model\HasFile;
use App\Models\File;
use Illuminate\Http\UploadedFile;

class Upload extends Action
{
    protected function rules(): array
    {
        return [
            'file' => ['required', 'file'],
            'path' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function upload(UploadedFile $file, string $path): string
    {
        return $file->storePublicly($path, ['disk' => File::fileDisk()]);
    }

    public function handle(HasFile $model, array $input)
    {
        $this->validate($input);

        $model->file()->create(
            attributes: [
                'name' => $input['name'],
                'description' => $input['description'],

                'path' => $this->upload($input['file'], $input['path'] ?? 'files'),
            ]
        );
    }
}
