<?php

/* =========================
   CHARGEMENT DES LIBRAIRIES
========================= */

require __DIR__ . "/vendor/autoload.php";


/* =========================
   CHARGEMENT DU FICHIER .ENV
========================= */

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


/* =========================
   VÉRIFICATION DE LA MÉTHODE
========================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    exit("Méthode non autorisée.");

}


/* =========================
   RÉCUPÉRATION DES DONNÉES
========================= */

$nom = trim($_POST["nom"] ?? "");
$email = trim($_POST["email"] ?? "");
$sujet = trim($_POST["sujet"] ?? "");
$message = trim($_POST["message"] ?? "");


/* =========================
   VALIDATION
========================= */

$erreurs = [];


/* Nom */

if ($nom === "") {

    $erreurs[] = "Le nom est obligatoire.";

}


/* Email */

if ($email === "") {

    $erreurs[] = "L'adresse email est obligatoire.";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $erreurs[] = "L'adresse email n'est pas valide.";

}


/* Sujet */

if ($sujet === "") {

    $erreurs[] = "Le sujet est obligatoire.";

}


/* Message */

if ($message === "") {

    $erreurs[] = "Le message est obligatoire.";

} elseif (mb_strlen($message) < 10) {

    $erreurs[] = "Le message doit contenir au moins 10 caractères.";

}


/* =========================
   AFFICHAGE DES ERREURS
========================= */

if (!empty($erreurs)) {

    echo "<h1>Erreur</h1>";

    foreach ($erreurs as $erreur) {

        echo "<p>" . htmlspecialchars($erreur) . "</p>";

    }

    exit;

}


/* =========================
   PRÉPARATION DU MESSAGE
========================= */

/*
   On sécurise les données avant
   de les intégrer dans l'email HTML.
*/

$nomSecurise = htmlspecialchars(
    $nom,
    ENT_QUOTES,
    "UTF-8"
);

$emailSecurise = htmlspecialchars(
    $email,
    ENT_QUOTES,
    "UTF-8"
);

$sujetSecurise = htmlspecialchars(
    $sujet,
    ENT_QUOTES,
    "UTF-8"
);

$messageSecurise = nl2br(
    htmlspecialchars(
        $message,
        ENT_QUOTES,
        "UTF-8"
    )
);


/* =========================
   ENVOI AVEC RESEND
========================= */

try {

    /*
       Récupération de la clé API
       depuis le fichier .env
    */

    $resend = Resend::client(
        $_ENV["RESEND_API_KEY"]
    );


    /*
       Envoi du message
    */

    $resend->emails->send([

        /*
           Adresse d'expédition de test Resend
        */

        "from" => "Portfolio Benjamin <onboarding@resend.dev>",


        /*
           Adresse sur laquelle
           tu reçois les messages
        */

        "to" => [
            "benjaminkpomy@gmail.com"
        ],


        /*
           Si tu cliques sur Répondre
           dans Gmail, la réponse ira
           à la personne ayant rempli
           ton formulaire.
        */

        "reply_to" => $email,


        /*
           Sujet de l'email
        */

        "subject" => "Portfolio - " . $sujet,


        /*
           Contenu de l'email
        */

        "html" => "

            <h2>
                Nouveau message depuis le portfolio
            </h2>

            <p>
                <strong>Nom :</strong>
                {$nomSecurise}
            </p>

            <p>
                <strong>Email :</strong>
                {$emailSecurise}
            </p>

            <p>
                <strong>Sujet :</strong>
                {$sujetSecurise}
            </p>

            <p>
                <strong>Message :</strong>
            </p>

            <p>
                {$messageSecurise}
            </p>

        "

    ]);


/* =========================
   ERREUR PENDANT L'ENVOI
========================= */

} catch (Throwable $e) {

    exit(
        "Une erreur est survenue pendant l'envoi du message."
    );

}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Message envoyé | Benjamin Kpomy
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body>

    <main class="confirmation">

        <div class="confirmation-contenu">

            <h1>
                Message envoyé !
            </h1>

            <p>
                Merci
                <strong>
                    <?= htmlspecialchars(
                        $nom,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </strong>.
            </p>

            <p>
                Votre message a bien été envoyé.
            </p>

            <p>
                Je vous répondrai dès que possible.
            </p>

            <a href="index.html#contact">
                Retour au portfolio
            </a>

        </div>

    </main>

</body>

</html>