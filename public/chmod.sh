#!/bin/bash

# Récupérer le répertoire de départ
startdir=$1

# Vérifier si le répertoire est spécifié
if [ -z "$startdir" ]; then
  echo "Veuillez spécifier le répertoire de départ."
  exit 1
fi

# Changer récursivement les permissions des fichiers et des dossiers
find "$startdir" -type f -exec chmod 0644 {} \;
find "$startdir" -type d -exec chmod 0755 {} \;

# Afficher un message de confirmation
echo "Les permissions ont été modifiées avec succès pour les fichiers en 0644 et les dossiers en 0755."
