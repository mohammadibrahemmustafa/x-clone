<?php

namespace Tests\Unit;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function test_store_image_successfully()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $service = new ImageService;
        $filename = $service->store($file, 'avatar', 'public');

        Storage::disk('public')->assertExists("avatars/{$filename}");
        $this->assertTrue(Uuid::isValid(pathinfo($filename, PATHINFO_FILENAME)));
        $this->assertEquals('jpg', pathinfo($filename, PATHINFO_EXTENSION));
    }

    public function test_store_image_with_invalid_type_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid image type: invalid_type');

        $file = UploadedFile::fake()->image('test.jpg');
        $service = new ImageService;
        $service->store($file, 'invalid_type');
    }

    public function test_get_path_returns_correct_url_if_file_exists()
    {
        Storage::fake('public');

        $filename = 'test-image.jpg';
        $path = 'avatars/'.$filename;

        Storage::disk('public')->put($path, 'dummy content');

        $url = ImageService::getPath($filename, 'avatar', 'public');

        $this->assertNotNull($url);
        $this->assertStringContainsString(Storage::url($path), $url);
    }

    public function test_get_path_returns_null_if_file_does_not_exist()
    {
        Storage::fake('public');

        $url = ImageService::getPath('non-existent.jpg', 'avatar', 'public');

        $this->assertNull($url);
    }

    public function test_get_path_returns_null_for_invalid_type()
    {
        $url = ImageService::getPath('test.jpg', 'invalid_type', 'public');

        $this->assertNull($url);
    }

    public function test_get_path_returns_null_for_empty_filename()
    {
        $url = ImageService::getPath('', 'avatar', 'public');

        $this->assertNull($url);
    }
}
