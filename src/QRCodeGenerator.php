<?php

declare(strict_types=1);

namespace iamjohndev;

class QRCodeGenerator
{
    private string $data;
    private int $size;
    private int $margin;

    public function __construct(string $data, int $size = 200, int $margin = 10)
    {
        $this->data = $data;
        $this->size = $size;
        $this->margin = $margin;
    }

    public function generateQRCode(string $filename): void
    {
        $qrCode = imagecreatetruecolor($this->size, $this->size);

        $backgroundColor = imagecolorallocate($qrCode, 255, 255, 255);
        $foregroundColor = imagecolorallocate($qrCode, 0, 0, 0);

        imagefill($qrCode, 0, 0, $backgroundColor);

        $qrCodeData = urlencode($this->data);
        $qrCodeData = 'https://chart.googleapis.com/chart?chs=' . $this->size . 'x' . $this->size . '&cht=qr&chl=' . $qrCodeData;

        $imageData = file_get_contents($qrCodeData);

        if ($imageData !== false) {
            $tmpImage = imagecreatefromstring($imageData);

            if ($tmpImage !== false) {
                imagecopyresampled(
                    $qrCode,
                    $tmpImage,
                    $this->margin,
                    $this->margin,
                    0,
                    0,
                    $this->size - 2 * $this->margin,
                    $this->size - 2 * $this->margin,
                    imagesx($tmpImage),
                    imagesy($tmpImage)
                );

                imagecolortransparent($qrCode, $backgroundColor);
                imagepng($qrCode, $filename);
            }
        }

        imagedestroy($qrCode);
    }
}
