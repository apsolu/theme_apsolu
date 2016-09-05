// Recherche le formulaire de login alternatif.
var alternate_login_form = document.getElementById('apsolu-select-authentification-method');

if (alternate_login_form) {
    // Recherche le contenu principal.
    var maincontent = document.querySelectorAll('div[role=main] .loginbox');
    if (maincontent) {
        // Supprime la class sr-only pour rendre visible le formulaire alternatif.
        alternate_login_form.classList.remove('sr-only');

        // Remplace le contenu principal par le formulaire alternatif.
        var parentnode = maincontent[0].parentNode;
        parentnode.replaceChild(alternate_login_form, maincontent[0]);
    }
}
