<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Response;

class PosterController extends Controller
{
    /**
     * يولّد بوستر SVG محلي للعمل (لون متدرّج + اسم العمل).
     */
    public function show(Title $title): Response
    {
        // لون ثابت مشتق من معرّف العمل
        $hue = ($title->id * 47) % 360;
        $c1 = "hsl({$hue}, 45%, 35%)";
        $c2 = "hsl(" . (($hue + 40) % 360) . ", 45%, 18%)";
        $icon = $title->isMovie() ? '🎬' : '📺';
        $name = htmlspecialchars($title->name, ENT_QUOTES);
        $year = $title->release_year;

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="400" height="600" viewBox="0 0 400 600">
          <defs>
            <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="{$c1}"/>
              <stop offset="1" stop-color="{$c2}"/>
            </linearGradient>
          </defs>
          <rect width="400" height="600" fill="url(#g)"/>
          <text x="200" y="250" font-size="90" text-anchor="middle">{$icon}</text>
          <text x="200" y="340" font-size="34" font-weight="700" fill="#ffffff"
                text-anchor="middle" font-family="Cairo, Arial, sans-serif">{$name}</text>
          <text x="200" y="390" font-size="22" fill="#ffffffaa"
                text-anchor="middle" font-family="Arial, sans-serif">{$year}</text>
        </svg>
        SVG;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
