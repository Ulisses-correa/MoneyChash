<?php
/**
 * Ícones SVG (traço, 20x20) usados na navegação e nos cartões do painel.
 * Substituem os emojis por um conjunto único e consistente.
 */

function moneycash_icon(string $name): string
{
    $icons = [
        'home' => '<path d="M3 10.5 10 4l7 6.5"/><path d="M5 9v7a1 1 0 0 0 1 1h3v-4.5h2V17h3a1 1 0 0 0 1-1V9"/>',
        'bank' => '<path d="M3 9.5 10 4l7 5.5"/><path d="M4.5 9.5h11v7h-11z"/><path d="M3 17h14"/><path d="M7 9.5v7M10 9.5v7M13 9.5v7"/>',
        'trend-up' => '<path d="M3 14.5 8 9l3 3 6-6.5"/><path d="M13 5.5h4v4"/>',
        'receipt' => '<path d="M5 3h10v14l-2-1.3-1.5 1.3-1.5-1.3L8.5 17 7 15.7 5 17z"/><path d="M7.5 7h5M7.5 10h5"/>',
        'bar-chart' => '<path d="M4 17V10M9 17V4M14 17v-6.5M17 17H3"/>',
        'tag' => '<path d="M10.5 3H4.5A1.5 1.5 0 0 0 3 4.5v6l8.3 8.3a1.5 1.5 0 0 0 2.1 0l5.4-5.4a1.5 1.5 0 0 0 0-2.1L10.5 3Z"/><circle cx="7.2" cy="7.2" r="1.2"/>',
        'target' => '<circle cx="10" cy="10" r="7"/><circle cx="10" cy="10" r="3.6"/><circle cx="10" cy="10" r=".6" fill="currentColor"/>',
        'pie-chart' => '<path d="M10 3v7l5.6 3.6A7 7 0 1 1 10 3Z"/><path d="M13.5 3.8A7 7 0 0 1 17 10h-7Z"/>',
        'bell' => '<path d="M6 8a4 4 0 0 1 8 0c0 4 1.4 5 1.4 5H4.6S6 12 6 8Z"/><path d="M8.3 15.5a1.8 1.8 0 0 0 3.4 0"/>',
        'users' => '<circle cx="7.5" cy="7" r="2.6"/><path d="M2.8 16.5c.5-2.6 2.3-4 4.7-4s4.2 1.4 4.7 4"/><circle cx="14.5" cy="7.6" r="2.1"/><path d="M13.2 12.8c1.9.2 3.4 1.6 3.8 3.7"/>',
        'wallet' => '<path d="M3 6.5A1.5 1.5 0 0 1 4.5 5h9A1.5 1.5 0 0 1 15 6.5V8H4.5A1.5 1.5 0 0 1 3 6.5Z"/><path d="M3 8h12.5A1.5 1.5 0 0 1 17 9.5v4a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 13.5V8Z"/><circle cx="13.3" cy="11.3" r="1" fill="currentColor" stroke="none"/>',
        'arrow-right' => '<path d="M4 10h12M11 5l5 5-5 5"/>',
    ];

    $path = $icons[$name] ?? '';

    return '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}
