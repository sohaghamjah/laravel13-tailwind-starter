<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class ImageHelper
{
    /**
     * Get Image Manager instance for Intervention Image v4.0
     *
     * @return ImageManager
     */
    private static function getImageManager()
    {
        return ImageManager::usingDriver(Driver::class);
    }

    /**
     * Optimize image size based on width
     *
     * @param \Intervention\Image\Interfaces\ImageInterface $image
     * @param int $width
     * @return \Intervention\Image\Interfaces\ImageInterface
     */
    public static function optimizeImageSize($image, $width)
    {
        $resolution_break_point = [2048, 2340, 2730, 3276, 4096, 5460, 8192];
        $reduce_percentage = [12.5, 25, 37.5, 50, 62.5, 75];

        if ($width > 0 && $width < 2048) {
            try {
                $image = $image->scale(width: 300);
            } catch (\Exception $e) {
                Log::error('Image resize failed: ' . $e->getMessage());
            }
        } elseif ($width > 5460 && $width <= 6140) {
            try {
                $image = $image->scale(width: 2048);
            } catch (\Exception $e) {
                Log::error('Image resize failed: ' . $e->getMessage());
            }
        } else {
            for ($i = 0; $i < count($resolution_break_point); $i++) {
                if ($i != count($resolution_break_point) - 1) {
                    if ($width >= $resolution_break_point[$i] && $width <= $resolution_break_point[$i + 1]) {
                        $new_width = ceil($width - (($width * $reduce_percentage[$i]) / 100));
                        try {
                            $image = $image->scale(width: $new_width);
                        } catch (\Exception $e) {
                            Log::error('Image resize failed: ' . $e->getMessage());
                        }
                    }
                }
            }
            if ($width > 8192) {
                try {
                    $image = $image->scale(width: 2048);
                } catch (\Exception $e) {
                    Log::error('Image resize failed: ' . $e->getMessage());
                }
            }
        }

        return $image;
    }

    /**
     * Upload a single image with optimization
     *
     * @param UploadedFile $image
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return array
     */
    public static function uploadImage($image, $path = 'images', $width = null, $height = null, $quality = 80)
    {
        try {
            if (!$image || !$image->isValid()) {
                return ['success' => false, 'message' => 'Invalid image file'];
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(20) . '.' . $extension;
            $fullPath = $path . '/' . $filename;

            // Get image manager and decode image
            $manager = self::getImageManager();
            $processedImage = $manager->decodePath($image->getPathname());

            // Get original dimensions
            $originalWidth = $processedImage->width();
            $originalHeight = $processedImage->height();

            // Optimize based on width if no specific dimensions provided
            if ($width === null && $height === null) {
                $processedImage = self::optimizeImageSize($processedImage, $originalWidth);
            } else if ($width !== null && $height !== null) {
                $processedImage = $processedImage->resize($width, $height);
            } else if ($width !== null) {
                // Maintain aspect ratio
                $processedImage = $processedImage->scale(width: $width);
            } else if ($height !== null) {
                // Maintain aspect ratio
                $processedImage = $processedImage->scale(height: $height);
            }

            // Determine format and encode
            $format = self::getFormatFromExtension($extension);
            $encodedImage = $processedImage->encodeUsingFormat($format, quality: $quality);

            // Store the file
            $stored = Storage::disk('public')->put($fullPath, $encodedImage);

            if ($stored) {
                return [
                    'success' => true,
                    'path' => $fullPath,
                    'filename' => $filename,
                    'full_url' => Storage::url($fullPath),
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $encodedImage->size(),
                    'mime_type' => $image->getMimeType(),
                    'width' => $processedImage->width(),
                    'height' => $processedImage->height()
                ];
            }

            return ['success' => false, 'message' => 'Failed to store image'];

        } catch (Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Image upload failed: ' . $e->getMessage()];
        }
    }

    /**
     * Get Format enum from file extension
     *
     * @param string $extension
     * @return Format
     */
    private static function getFormatFromExtension($extension)
    {
        return match(strtolower($extension)) {
            'jpg', 'jpeg' => Format::JPEG,
            'png' => Format::PNG,
            'gif' => Format::GIF,
            'webp' => Format::WEBP,
            'bmp' => Format::BMP,
            'avif' => Format::AVIF,
            default => Format::JPEG,
        };
    }

    /**
     * Upload multiple images
     *
     * @param array $images
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return array
     */
    public static function uploadMultipleImages($images, $path = 'images', $width = null, $height = null, $quality = 80)
    {
        $uploadedImages = [];

        foreach ($images as $image) {
            $result = self::uploadImage($image, $path, $width, $height, $quality);
            if ($result['success']) {
                $uploadedImages[] = $result;
            }
        }

        return $uploadedImages;
    }

    /**
     * Update image (delete old, upload new)
     *
     * @param UploadedFile $newImage
     * @param string|null $oldImagePath
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return array
     */
    public static function updateImage($newImage, $oldImagePath = null, $path = 'images', $width = null, $height = null, $quality = 80)
    {
        try {
            // Upload new image first
            $result = self::uploadImage($newImage, $path, $width, $height, $quality);

            // If upload successful and old image exists, delete it
            if ($result['success'] && $oldImagePath) {
                self::deleteImage($oldImagePath);
            }

            return $result;

        } catch (Exception $e) {
            Log::error('Image update failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Image update failed: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a single image
     *
     * @param string $imagePath
     * @return bool
     */
    public static function deleteImage($imagePath)
    {
        try {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);

                // Also delete thumbnails if exists
                $dirname = dirname($imagePath);
                $thumbPath = $dirname . '/thumbnails/' . basename($imagePath);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }

                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple images
     *
     * @param array $imagePaths
     * @return array
     */
    public static function deleteMultipleImages($imagePaths)
    {
        $results = [];
        foreach ($imagePaths as $imagePath) {
            $results[$imagePath] = self::deleteImage($imagePath);
        }
        return $results;
    }

    /**
     * Upload image with thumbnail generation
     *
     * @param UploadedFile $image
     * @param string $path
     * @param array $thumbSizes
     * @param int $quality
     * @return array
     */
    public static function uploadImageWithThumbnails($image, $path = 'images', $thumbSizes = [], $quality = 80)
    {
        try {
            if (!$image || !$image->isValid()) {
                return ['success' => false, 'message' => 'Invalid image file'];
            }

            // Default thumbnail sizes
            if (empty($thumbSizes)) {
                $thumbSizes = [
                    'small' => ['width' => 150, 'height' => 150],
                    'medium' => ['width' => 300, 'height' => 300],
                    'large' => ['width' => 600, 'height' => 600]
                ];
            }

            // Generate unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(20) . '.' .  $extension;
            $fullPath = $path . '/' . $filename;

            // Get image manager and decode image
            $manager = self::getImageManager();
            $originalImage = $manager->decodePath($image->getPathname());

            // Save original with optimization
            $originalWidth = $originalImage->width();
            $optimizedImage = self::optimizeImageSize($originalImage, $originalWidth);
            $format = self::getFormatFromExtension($extension);
            $encodedOriginal = $optimizedImage->encodeUsingFormat($format, quality: $quality);
            Storage::disk('public')->put($fullPath, $encodedOriginal);

            // Create thumbnails directory
            $thumbDir = $path . '/thumbnails';
            if (!Storage::disk('public')->exists($thumbDir)) {
                Storage::disk('public')->makeDirectory($thumbDir);
            }

            // Generate thumbnails
            $thumbnails = [];
            foreach ($thumbSizes as $key => $size) {
                $thumbFilename = 'thumb_' . $key . '_' . $filename;
                $thumbPath = $thumbDir . '/' . $thumbFilename;

                // Decode original image again for thumbnail
                $thumbImage = $manager->decodePath($image->getPathname());
                $thumbImage->resize($size['width'], $size['height']);
                $encodedThumb = $thumbImage->encodeUsingFormat($format, quality: $quality);

                Storage::disk('public')->put($thumbPath, $encodedThumb);

                $thumbnails[$key] = [
                    'path' => $thumbPath,
                    'url' => Storage::url($thumbPath),
                    'width' => $size['width'],
                    'height' => $size['height']
                ];
            }

            return [
                'success' => true,
                'original' => [
                    'path' => $fullPath,
                    'filename' => $filename,
                    'url' => Storage::url($fullPath),
                    'size' => $encodedOriginal->size(),
                    'width' => $optimizedImage->width(),
                    'height' => $optimizedImage->height()
                ],
                'thumbnails' => $thumbnails
            ];

        } catch (Exception $e) {
            Log::error('Image with thumbnails upload failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()];
        }
    }

    /**
     * Delete image with thumbnails
     *
     * @param string $originalPath
     * @return bool
     */
    public static function deleteImageWithThumbnails($originalPath)
    {
        try {
            // Delete original
            self::deleteImage($originalPath);

            // Delete thumbnails directory if exists
            $dirname = dirname($originalPath);
            $thumbDir = $dirname . '/thumbnails';
            if (Storage::disk('public')->exists($thumbDir)) {
                Storage::disk('public')->deleteDirectory($thumbDir);
            }

            return true;
        } catch (Exception $e) {
            Log::error('Image with thumbnails deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get image information
     *
     * @param string $imagePath
     * @return array|null
     */
    public static function getImageInfo($imagePath)
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return null;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $manager = self::getImageManager();
            $image = $manager->decodePath($fullPath);

            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'mime_type' => $image->mimetype(),
                'size' => Storage::disk('public')->size($imagePath),
                'path' => $imagePath,
                'url' => Storage::url($imagePath)
            ];

        } catch (Exception $e) {
            Log::error('Get image info failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Resize existing image
     *
     * @param string $imagePath
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return bool
     */
    public static function resizeExistingImage($imagePath, $width = null, $height = null, $quality = 80)
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return false;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

            $manager = self::getImageManager();
            $image = $manager->decodePath($fullPath);

            if ($width !== null && $height !== null) {
                $image->resize($width, $height);
            } else if ($width !== null) {
                $image->scale(width: $width);
            } else if ($height !== null) {
                $image->scale(height: $height);
            }

            $format = self::getFormatFromExtension($extension);
            $encodedImage = $image->encodeUsingFormat($format, quality: $quality);
            Storage::disk('public')->put($imagePath, $encodedImage);

            return true;

        } catch (Exception $e) {
            Log::error('Resize existing image failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert image to WebP format
     *
     * @param string $imagePath
     * @param int $quality
     * @return array|null
     */
    public static function convertToWebP($imagePath, $quality = 80)
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return null;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $pathInfo = pathinfo($imagePath);
            $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';

            $manager = self::getImageManager();
            $image = $manager->decodePath($fullPath);

            $encodedImage = $image->encodeUsingFormat(Format::WEBP, quality: $quality);
            Storage::disk('public')->put($webpPath, $encodedImage);

            return [
                'success' => true,
                'path' => $webpPath,
                'url' => Storage::url($webpPath),
                'size' => $encodedImage->size()
            ];

        } catch (Exception $e) {
            Log::error('Convert to WebP failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Add watermark to image
     *
     * @param string $imagePath
     * @param string $watermarkPath
     * @param string $position
     * @param int $quality
     * @return bool
     */
    public static function addWatermark($imagePath, $watermarkPath, $position = 'bottom-right', $quality = 80)
    {
        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return false;
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $watermarkFullPath = Storage::disk('public')->path($watermarkPath);
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

            $manager = self::getImageManager();
            $image = $manager->decodePath($fullPath);

            // Add watermark
            $alignment = match($position) {
                'top-left' => \Intervention\Image\Alignment::TOP_LEFT,
                'top-right' => \Intervention\Image\Alignment::TOP_RIGHT,
                'bottom-left' => \Intervention\Image\Alignment::BOTTOM_LEFT,
                'bottom-right' => \Intervention\Image\Alignment::BOTTOM_RIGHT,
                'center' => \Intervention\Image\Alignment::CENTER,
                default => \Intervention\Image\Alignment::BOTTOM_RIGHT,
            };

            $image->insert($watermarkFullPath, alignment: $alignment);

            $format = self::getFormatFromExtension($extension);
            $encodedImage = $image->encodeUsingFormat($format, quality: $quality);
            Storage::disk('public')->put($imagePath, $encodedImage);

            return true;

        } catch (Exception $e) {
            Log::error('Add watermark failed: ' . $e->getMessage());
            return false;
        }
    }
}
