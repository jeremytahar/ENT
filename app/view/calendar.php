<?php
// Vérifier et afficher la réponse
if ($response) {
    // Décoder la réponse JSON
    $data = json_decode($response, true);
    
    // Sauvegarder les données dans un fichier JSON
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
    echo "Données sauvegardées avec succès !";
} else {
    echo "Aucune réponse reçue de l'API.";
}
?>