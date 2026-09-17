<?php

/* =========================
   CHARGEMENT DES LIBRAIRIES
========================= */

require __DIR__ . "/vendor/autoload.php";


/* =========================
   CHARGEMENT DES VARIABLES
   D'ENVIRONNEMENT
========================= */

/*
   En local :
   PHPDotenv charge le fichier .env.

   Sur Render :
   le fichier .env n'existe pas,
   donc safeLoad() évite une erreur.

   Render fournit directement
   RESEND_API_KEY.
*/

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


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

        echo "<p>" .
            htmlspecialchars(
                $erreur,
                ENT_QUOTES,
                "UTF-8"
            ) .
            "</p>";

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
   RÉCUPÉRATION DE LA CLÉ API
========================= */

/*
   Sur ton Mac :
   la clé vient du fichier .env.

   Sur Render :
   la clé vient des variables
   d'environnement configurées
   dans le tableau de bord.
*/

$apiKey = $_ENV["RESEND_API_KEY"]
    ?? getenv("RESEND_API_KEY");


/* =========================
   VÉRIFICATION DE LA CLÉ
========================= */

if (!$apiKey) {

    exit(
        "Configuration du service d'envoi manquante."
    );

}


/* =========================
   ENVOI AVEC RESEND
========================= */

try {

    /*
       Création du client Resend
    */

    $resend = Resend::client($apiKey);


    /*
       Envoi du message
    */

    $resend->emails->send([

        /*
           Adresse d'expédition
           de test Resend
        */

        "from" =>
            "Portfolio Benjamin <onboarding@resend.dev>",


        /*
           Adresse sur laquelle
           tu reçois les messages
        */

        "to" => [
            "benjaminkpomy@gmail.com"
        ],


        /*
           Quand tu cliqueras sur
           Répondre dans Gmail,
           la réponse sera envoyée
           à la personne qui a rempli
           le formulaire.
        */

        "reply_to" => $email,


        /*
           Sujet de l'email
        */

        "subject" =>
            "Portfolio - " . $sujet,


        /*
           Contenu HTML de l'email
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