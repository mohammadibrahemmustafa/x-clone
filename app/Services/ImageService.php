<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ImageService
{
    private const PATHS = [
        'avatar' => 'avatars',
    ];

    public function store(UploadedFile $file, string $type = 'avatar', string $disk = 'public'): string
    {
        if (! array_key_exists($type, self::PATHS)) {
            throw new \InvalidArgumentException("Invalid image type: $type");
        }

        $filename = Uuid::uuid4()->toString().'.'.$file->getClientOriginalExtension();
        Storage::disk($disk)->putFileAs(self::PATHS[$type], $file, $filename);

        return $filename;
    }

    public static function getPath(?string $filename, string $type = 'avatar', string $disk = 'public'): ?string
    {
        if (! array_key_exists($type, self::PATHS) || empty($filename)) {
            return null;
        }

        $path = self::PATHS[$type].'/'.$filename;

        return Storage::disk($disk)->exists($path) ?
            Storage::url($path) :
            null;
    }
}
