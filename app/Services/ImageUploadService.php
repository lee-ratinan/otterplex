<?php

namespace App\Services;

use CodeIgniter\Files\FileSizeUnit;
use CodeIgniter\HTTP\Files\UploadedFile;

class ImageUploadService
{
    /**
     * Handles validation, cropping, and saving an uploaded image to WebP format.
     *
     * Note: To convert to WebP on local Macbook with brew install webp, use the following command in Terminal:
     * > for file in *.png; do cwebp -q 80 "$file" -o "${file%.*}.webp"; done
     * > for file in *.jpg; do cwebp -q 80 "$file" -o "${file%.*}.webp"; done
     *
     * @param UploadedFile $file The CI4 uploaded file instance
     * @param string $uploadPath Target folder path (relative to WRITEPATH or absolute)
     * @param string $finalFileName Target file name without extension (e.g., 'logo_my-business')
     * @param array $dimensions Target dimension [width, height] (e.g., [500, 500])
     * @param array $constraints Validation limits ['max_size' => 600, 'max_width' => 500, 'max_height' => 500]
     * @param int $quality WebP output quality (default: 95)
     * @return array Status array with keys: success, file_name, errors
     */
    public function uploadAndCropToWebp(
        UploadedFile $file,
        string $uploadPath,
        string $finalFileName,
        array $dimensions,
        array $constraints = [],
        int $quality = 95
    ): array {

        $maxSize   = $constraints['max_size'] ?? 600; // in KB
        $maxWidth  = $constraints['max_width'] ?? 500;
        $maxHeight = $constraints['max_height'] ?? 500;

        // 1. Check if the file was actually uploaded and is valid
        if (!$file->isValid() || $file->hasMoved()) {
            return [
                'success'   => false,
                'file_name' => null,
                'errors'    => ['file' => 'The file is invalid or has already been moved.']
            ];
        }

        // 2. Perform manual validation checks to bypass the UploadedFile object bug
        $errors = [];

        // Validate Mime Type
        $fileType = $file->getClientMimeType();
        $allowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($fileType, $allowedMimes, true)) {
            $errors[] = 'The file format is invalid. Only JPG, PNG, and WebP are allowed.';
        }

        // Validate File Size (bytes to KB conversion)
        $fileSizeKb = $file->getSizeByMetricUnit(FileSizeUnit::KB);;
        if ($fileSizeKb > $maxSize) {
            $errors[] = "The file size exceeds the maximum limit of {$maxSize} KB.";
        }

        // Validate Dimensions
        list($width, $height) = getimagesize($file->getPathname());
        if ($width > $maxWidth || $height > $maxHeight) {
            $errors[] = "The image dimensions exceed the maximum allowed limits of {$maxWidth}x{$maxHeight} pixels.";
        }

        // Return early if any manual validation check fails
        if (!empty($errors)) {
            return [
                'success'   => false,
                'file_name' => null,
                'errors'    => $errors
            ];
        }

        // 3. Proportional scale generation
        $targetW              = $dimensions[0];
        $targetH              = $dimensions[1];

        // Create GD source image based on type
        switch ($fileType) {
            case 'image/png':
                $source = imagecreatefrompng($file->getPathname());
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($file->getPathname());
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($file->getPathname());
                break;
            default:
                return [
                    'success'   => false,
                    'file_name' => null,
                    'errors'    => ['type' => 'Unsupported image type.']
                ];
        }

        if (!$source) {
            return [
                'success'   => false,
                'file_name' => null,
                'errors'    => ['process' => 'Failed to process image source file.']
            ];
        }

        $targetRatio = $targetW / $targetH;
        $srcRatio    = $width / $height;

        if ($srcRatio > $targetRatio) {
            $scaledH = $targetH;
            $scaledW = intval($targetH * $srcRatio);
        } else {
            $scaledW = $targetW;
            $scaledH = intval($targetW / $srcRatio);
        }

        // Create canvas for scaling
        $scaled = imagecreatetruecolor($scaledW, $scaledH);

        // Handle transparency for scaling canvas
        if ($fileType === 'image/png' || $fileType === 'image/webp') {
            imagealphablending($scaled, false);
            imagesavealpha($scaled, true);
        }

        imagecopyresampled($scaled, $source, 0, 0, 0, 0, $scaledW, $scaledH, $width, $height);

        // 4. Center Crop implementation
        $cropX = intval(($scaledW - $targetW) / 2);
        $cropY = intval(($scaledH - $targetH) / 2);

        $final = imagecreatetruecolor($targetW, $targetH);

        // Handle transparency/background setup for final canvas
        if ($fileType === 'image/png' || $fileType === 'image/webp') {
            imagealphablending($final, false);
            imagesavealpha($final, true);
            $transparent = imagecolorallocatealpha($final, 0, 0, 0, 127);
            imagefill($final, 0, 0, $transparent);
        } else {
            $white = imagecolorallocate($final, 255, 255, 255);
            imagefill($final, 0, 0, $white);
        }

        imagecopyresampled($final, $scaled, 0, 0, $cropX, $cropY, $targetW, $targetH, $targetW, $targetH);

        // Ensure directory existence
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // 5. Output to WebP format
        $outputFileName = $finalFileName . '.webp';
        $fullOutputPath = rtrim($uploadPath, '/') . '/' . $outputFileName;

        imagewebp($final, $fullOutputPath, $quality);

        // Memory optimization
        imagedestroy($source);
        imagedestroy($scaled);
        imagedestroy($final);

        return [
            'success'   => true,
            'file_name' => $outputFileName,
            'errors'    => []
        ];
    }
}