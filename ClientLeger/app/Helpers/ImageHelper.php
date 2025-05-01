<?php

namespace App\Helpers;

class ImageHelper
{
    public static function encodeImage($imageName)
    {
        $imagePath = public_path('media/' . $imageName);
        if (file_exists($imagePath)) {
            // Get the mime type from the file
            $mimeType = mime_content_type($imagePath);
            if (!$mimeType) {
                // Fallback to getting mime type from extension
                $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $mimeType = self::getMimeTypeFromExtension($extension);
            }
            
            // Read and encode the image
            $imageData = file_get_contents($imagePath);
            if ($imageData === false) {
                return null;
            }
            
            return [
                'data' => base64_encode($imageData),
                'type' => $mimeType
            ];
        }
        return null;
    }

    private static function getMimeTypeFromExtension($extension)
    {
        $mimeTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        
        return $mimeTypes[$extension] ?? 'image/png';
    }

    public static function decodeImage($base64Data)
    {
        if ($base64Data) {
            return base64_decode($base64Data);
        }
        return null;
    }
} 