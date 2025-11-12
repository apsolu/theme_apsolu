<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_apsolu;

use coding_exception;

/**
 * Classe pour analyser le contraste des couleurs.
 *
 * Les calculs sont réalisés d'après l'algorithme du w3c "http://www.w3.org/TR/WCAG20-GENERAL/G18.html".
 *
 * Une partie du code est inspirée du fichier lib/editor/tiny/plugins/accessibilitychecker/amd/src/checker.js présent dans Moodle.
 *
 * @package    theme_apsolu
 * @copyright  2025 Université Rennes 2
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Color {
    /**
     * Convertit une couleur exprimée en hexadécimal en tableau RGB.
     *
     * Exemple: #000 -> [0, 0, 0].
     *
     * @throws coding_exception A coding exception is thrown when $color is not a valid color.
     *
     * @param string $color Couleur exprimée en hexadécimal.
     *
     * @return array Un tableau contenant 3 valeurs (R,G,B).
     */
    public static function explode_rgb_hexa_color(string $color): array {
        $length = strlen($color);

        if ($length === 3) {
            return [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
        }

        if ($length === 6) {
            return [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
        }

        throw new coding_exception(sprintf('"%s" is not a valid color.', $color));
    }

    /**
     * Calcule la luminance d'une couleur exprimée en hexadécimal.
     *
     * @param string $color Couleur exprimée en hexadécimale. Ex: F4.
     *
     * @return float Retourne la luminance.
     */
    public static function get_color_luminance(string $color): float {
        $color = hexdec($color) / 255.0;

        if ($color <= 0.03928) {
            return $color / 12.92;
        }

        return pow((($color + 0.055) / 1.055), 2.4);
    }

    /**
     * Calcule la luminance d'une couleur RGB exprimée en hexadécimal.
     *
     * @throws coding_exception A coding exception is thrown when $color is not an hexadecimal color.
     *
     * @param string $color Couleur RGB exprimée en hexadécimale. Ex: #F467AA.
     *
     * @return float Retourne la luminance.
     */
    public static function get_luminance_from_rgb_hexa_color(string $color): float {
        if (str_starts_with($color, '#') === true) {
            $color = substr($color, 1);
        }

        if (ctype_xdigit($color) === false) {
            throw new coding_exception(sprintf('"%s" is not an hexadecimal color.', $color));
        }

        $color = strtolower($color);

        // Valide la couleur.
        [$red, $green, $blue] = self::explode_rgb_hexa_color($color);

        $red = self::get_color_luminance($red) * 0.2126;
        $green = self::get_color_luminance($green) * 0.7152;
        $blue = self::get_color_luminance($blue) * 0.0722;

        return $red + $green + $blue;
    }

    /**
     * Calcule le ratio entre la couleur du texte et la couleur de fond.
     *
     * @param string $foreground Couleur RGB du texte exprimée en hexadécimale. Ex: #F467AA.
     * @param string $background Couleur RGB du fond exprimée en hexadécimale. Ex: #F467AA.
     *
     * @return float Retourne le ratio.
     */
    public static function get_ratio(string $foreground, string $background): float {
        $foregroundluminance = self::get_luminance_from_rgb_hexa_color($foreground);
        $backgroundluminance = self::get_luminance_from_rgb_hexa_color($background);

        if ($foregroundluminance > $backgroundluminance) {
            return ($foregroundluminance + 0.05) / ($backgroundluminance + 0.05);
        }

        return ($backgroundluminance + 0.05) / ($foregroundluminance + 0.05);
    }

    /**
     * Indique si les couleurs sélectionnées ont un constrate suffisant.
     *
     * @param string $foreground Couleur RGB du texte exprimée en hexadécimale. Ex: #F467AA.
     * @param string $background Couleur RGB du fond exprimée en hexadécimale. Ex: #F467AA.
     * @param string $level Niveau d'accessibilité exigé (AA ou AAA).
     *
     * @return bool Retourne true si le niveau de constrate est suffisant.
     */
    public static function has_sufficient_contrast(string $foreground, string $background, string $level = 'AA'): bool {
        if ($level === 'AAA') {
            return self::get_ratio($foreground, $background) > 7;
        }

        return self::get_ratio($foreground, $background) > 4.5;
    }
}
