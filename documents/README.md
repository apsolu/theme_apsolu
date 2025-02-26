# Documents

## Description

Comprend les formulaires et pages publiques relatives aux différents documents à valeur légale que doit comprendre une
instance APSOLU :
- Mentions légales,
- Accessibilité,
- Nous contacter,
- Politique de confidentialité.

## Configuration
Pour éviter à un utilisateur de voir le chemin complet des pages publiques des documents, deux actions sont à effectuer :
1. Modifier la configuration Apache en activant le module ``alias`` afin de changer les url et les rendre plus accessibiles :
   - Ex: ``Alias "/accessibilite" "/applis/apsolu/theme/apsolu/documents/public/accessibility.php"``
2. Modifier les liens dans footer.mustache.


