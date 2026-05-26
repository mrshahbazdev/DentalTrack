<?php

namespace App\Enums;

enum QrPrintFormat: string
{
    case StickerSmall = 'sticker_small';
    case StickerLarge = 'sticker_large';

    public function label(): string
    {
        return match ($this) {
            self::StickerSmall => 'Small Sticker (25x15mm)',
            self::StickerLarge => 'Large Sticker (50x50mm)',
        };
    }
}
