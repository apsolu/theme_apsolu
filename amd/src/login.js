define([], function() {
    return {
        initialise : function(strings) {
            if (document.getElementById('page-login-index') === null) {
                // N'exécute pas le script en dehors de la page de login par défaut.
                return;
            }

            var authid1 = 'internal';
            var authid2 = 'institutional';

            var content = document.querySelector('#page-login-index #region-main .card-block');

            // Titre de la page.
            var h3 = document.createElement('h3');
            var text = document.createTextNode(strings[0]);
            h3.appendChild(text);
            h3.setAttribute('class', 'bg-secondary py-2 text-center');
            content.append(h3);

            // Prépare la liste d'onglets.
            var tabs = document.createElement('ul');
            tabs.setAttribute('class', 'nav nav-tabs');
            tabs.setAttribute('id', 'apsolu-login-tabs-ul');
            tabs.setAttribute('role', 'tablist');
            content.append(tabs);

            // Prépare le conteneur des panes.
            var div = document.createElement('div');
            div.setAttribute('class', 'tab-content py-3');
            div.setAttribute('id', 'apsolu-login-panes-div');
            content.append(div);

            // Transforme les panes.
            var panes = document.querySelectorAll('#page-login-index .card-body .justify-content-md-center .col-md-5');
            panes.forEach(function(pane) {
                if (pane.getElementsByClassName('potentialidplist').length == 0) {
                    // Pane pour l'authentification locale.
                    var id = authid1;
                    var label = strings[1];
                    var order = 2;
                } else {
                    // Pane pour l'authentification universitaire.
                    var id = authid2;
                    var label = strings[2];
                    var order = 1;

                    var localepane = document.getElementById(authid1+'-pane');
                    if (localepane == null) {
                        // Supprime quelques libellés inutiles pour l'authentification universitaire.
                        pane.firstElementChild.remove(); // Paragraphe sur le changement de mot de passe.
                        pane.firstElementChild.remove(); // Paragraphe sur l'activation des cookies.
                    } else {
                        // Déplace quelques libellés dans le pane "locale".
                        localepane.append(pane.firstElementChild); // Paragraphe sur le changement de mot de passe.
                        localepane.append(pane.firstElementChild); // Paragraphe sur l'activation des cookies.
                    }

                    pane.firstElementChild.remove(); // Supprime le libellé "Se connecter au moyen du compte".
                }

                // Défini les onglets.
                var tab = document.createElement('li');
                tab.setAttribute('class', 'nav-item');
                tab.style.order = order;

                var a = document.createElement('a');
                a.setAttribute('aria-controls', id);
                a.setAttribute('aria-selected', 'false');
                a.setAttribute('class', 'nav-link');
                a.setAttribute('data-toggle', 'tab');
                a.setAttribute('href', '#'+id+"-pane");
                a.setAttribute('role', 'tab');

                var text = document.createTextNode(label);

                a.appendChild(text);
                tab.append(a);
                tabs.append(tab);

                pane.setAttribute('aria-labelledby', id);
                pane.setAttribute('class', 'tab-pane fade col-md-10 offset-md-1');
                pane.setAttribute('id', id+'-pane');
                pane.setAttribute('role', 'tabpanel');

                div.append(pane);
            });

            // Défini l'onglet activé par défaut.
            if (window.location.hash == "#"+authid1) {
                var tab = document.querySelector('a[aria-controls='+authid1+']');
                tab.setAttribute('class', 'nav-link active show');

                var pane = document.getElementById(authid1+'-pane');
                pane.classList.add('active', 'show');
            } else {
                var tab = document.querySelector('a[aria-controls='+authid2+']');
                tab.setAttribute('class', 'nav-link active show');

                var pane = document.getElementById(authid2+'-pane');
                pane.classList.add('active', 'show');
            }
        }
    }
});
