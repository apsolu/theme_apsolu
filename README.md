# theme_apsolu

[![Build Status](https://github.com/apsolu/theme_apsolu/workflows/Moodle%20Plugin%20CI/badge.svg?branch=master)](https://github.com/apsolu/theme_apsolu/actions)
[![Coverage Status](https://coveralls.io/repos/github/apsolu/theme_apsolu/badge.svg?branch=master)](https://coveralls.io/github/apsolu/theme_apsolu?branch=master)
[![Moodle Status](https://img.shields.io/badge/moodle-4.4-blue)](https://moodle.org)

## Description

Un thème basé sur le thème *Boost* de Moodle.

Ce thème ajoute :
* un profil utilisateur amélioré en intégrant des informations spécifiques à APSOLU,
* une page d'accueil qui intègre par défaut des informations relatives à l'offre de formation,
* des fonctionnalités permettant de personnaliser visuellement son instance APSOLU.

## Pré-requis
* Moodle doit être au minimum en version 3.11
* Le module principal d'APSOLU (*local_apsolu*) doit être installé
* Les thèmes *Boost* et *Classic* doivent être installés

## Installation du thème APSOLU

```bash
cd /your/moodle/path
git clone https://github.com/apsolu/theme_apsolu theme/apsolu
php admin/cli/upgrade.php
```

## Configuration du thème APSOLU dans Moodle
### 1. Activation et configuration de la page d'accueil

`APSOLU > Présentation > Page d'accueil`
* Activer "Utiliser la page d'accueil APSOLU"

#### 1a. Personnaliser les blocs

`APSOLU > Présentation > Page d'accueil`

Vous pouvez personnaliser l'ensemble des contenus affichés sur la page d'accueil :
* Section "accueil"
* Section "les activités"
* Section "s'inscrire"
* Section "se connecter"

#### 1b. Personnaliser les documents

`APSOLU > Documents`

Ce nouveau noeud permet de personnaliser le contenu des fenêtres modales présentes sur la page d'accueil :
* Mentions légales
* Politique de confidentialité
* Recommandations médicales
* Nous contacter

### 2. Personnalisation de l'instance
`Présentation > Thèmes > Apsolu`

Les options suivantes vous sont proposées :
* Personnaliser les couleurs
* Personnaliser la barre de liens
* Personnaliser les libellés du site (WIP)

## Crédits images
Ce plugin utilise par défaut des images d'arrière-plan.

Ces photographies ont pour auteur :
- [Marie-Lan Nguyen / Wikimedia Commons / CC-BY 3.0](https://commons.wikimedia.org/wiki/File:Kovalev_v_Szilagyi_2013_Fencing_WCH_SMS-IN_t194135.jpg) (`images/background_1.jpg`)
- [Clément Bucco-Lechat / Wikimedia Commons / CC-BY-SA 3.0](https://commons.wikimedia.org/wiki/File:USO-Sale_Sharks_-_20131205_-_Ballon_flottant.jpg) (`images/background_2.jpg`)
- [Tsutomu Takasu / Wikimedia Commons / CC-BY 2.0](https://commons.wikimedia.org/wiki/File:Flickr_-_tpower1978_-_Aeon_Cup_%282%29.jpg) (`images/background_3.jpg`)


## Reporting security issues

We take security seriously. If you discover a security issue, please bring it
to their attention right away!

Please **DO NOT** file a public issue, instead send your report privately to
[foss-security@univ-rennes2.fr](mailto:foss-security@univ-rennes2.fr).

Security reports are greatly appreciated and we will publicly thank you for it.
