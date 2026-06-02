<?php

namespace App\Enums;

enum QrPrintFormat: string
{
    case StickerSmall = 'sticker_small';
    case StickerLarge = 'sticker_large';

    public function label(): string
    {
        return match ($this) {
            self::StickerSmall => 'Kleiner Aufkleber (25x15mm)',
            self::StickerLarge => 'Grosser Aufkleber (50x50mm)',
        };
    }
}
