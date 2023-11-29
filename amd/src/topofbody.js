/**
 * Module pour déplacer le headermessage dans la page.
 *
 * @module     theme_apsolu/topofbody
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core/log'], function(Log) {
    let selectors = {
        page: document.getElementById('page'),
        topofbody: document.getElementById('apsolu-topofbody'),
        pageHeader: document.getElementById('page-header')
    };

    let Topofbody = function() {
        if (selectors.topofbody) {
            selectors.page.insertBefore(selectors.topofbody, selectors.pageHeader);
        } else {
            Log.debug('Le div id="apsolu-topofbody" n\'existe pas dans la page.');
        }
    };

    return {
        'init': function() {
            return new Topofbody();
        }
    };
});
