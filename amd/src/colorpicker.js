/**
 * Module pour placer les éléments text dans le même parent que leur colorpickers respectifs
 * avant de les instancier dans le formulaire Customize.
 *
 * @module     theme_apsolu/colorpicker
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {
    return {
        moveColorpickers: function() {
            // On récupère les input text.
            const brandcolor1 = document.getElementById('80');
            const brandcolor2 = document.getElementById('81');
            const brandcolor3 = document.getElementById('82');
            // On récupère les colorpickers.
            const colorpicker1 = document.getElementById("colorpicker_1");
            const colorpicker2 = document.getElementById('colorpicker_2');
            const colorpicker3 = document.getElementById("colorpicker_3");

            // On place le colorpicker directement à la suite de l'element text.
            brandcolor1.before(colorpicker1);
            brandcolor2.before(colorpicker2);
            brandcolor3.before(colorpicker3);

            // On instancie le colorpicker pour chaque input text concerné.
            const ids = [80, 81, 82];
            for (let id of ids) {
                M.util.init_colour_picker(Y, id, null);
            }
        },
    };
});
