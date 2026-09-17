/* =========================================
   MENU RESPONSIVE
========================================= */

const menuToggle = document.querySelector(".menu-toggle");
const menu = document.querySelector(".menu");


// Ouvrir / fermer le menu hamburger
menuToggle.addEventListener("click", function () {

    menu.classList.toggle("active");

});


// Fermer le menu lorsqu'on clique sur un lien
const menuLinks = document.querySelectorAll(".menu a");

menuLinks.forEach(function (link) {

    link.addEventListener("click", function () {

        menu.classList.remove("active");

    });

});


/* =========================================
   NAVIGATION ACTIVE AU SCROLL
========================================= */

const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".menu a");


window.addEventListener("scroll", function () {

    let sectionActuelle = "";

    sections.forEach(function (section) {

        const positionSection = section.offsetTop;

        if (window.scrollY >= positionSection - 200) {

            sectionActuelle = section.getAttribute("id");

        }

    });


    navLinks.forEach(function (link) {

        // On enlève active de tous les liens
        link.classList.remove("active");


        // On ajoute active au lien correspondant
        if (link.getAttribute("href") === "#" + sectionActuelle) {

            link.classList.add("active");

        }

    });

});


/* =========================================
   ANIMATIONS AU SCROLL
========================================= */

const elementsAnimes = document.querySelectorAll(
    ".competence, .parcours-item, .projet"
);


// Ajout de la classe de départ
elementsAnimes.forEach(function (element) {

    element.classList.add("animation-scroll");

});


// Création de l'observateur
const observer = new IntersectionObserver(
    function (entries) {

        entries.forEach(function (entry) {

            if (entry.isIntersecting) {

                // On affiche l'élément
                entry.target.classList.add("visible");

                // Une fois affiché, on arrête de le surveiller
                observer.unobserve(entry.target);

            }

        });

    },
    {
        threshold: 0.2
    }
);


// On surveille chaque élément
elementsAnimes.forEach(function (element) {

    observer.observe(element);

});


/* =========================================
   FORMULAIRE DE CONTACT
========================================= */

// Récupération du formulaire
const formulaire = document.querySelector(".contact-formulaire");


// Récupération des champs
const nom = document.querySelector("#nom");
const email = document.querySelector("#email");
const sujet = document.querySelector("#sujet");
const message = document.querySelector("#message");


// Récupération des zones d'erreur
const erreurNom = document.querySelector("#erreur-nom");
const erreurEmail = document.querySelector("#erreur-email");
const erreurSujet = document.querySelector("#erreur-sujet");
const erreurMessage = document.querySelector("#erreur-message");


// Message général du formulaire
const messageFormulaire = document.querySelector("#message-formulaire");


// Expression régulière pour vérifier l'adresse email
// Elle est déclarée ici pour être accessible partout
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


/* =========================================
   VALIDATION LORS DE L'ENVOI
========================================= */

formulaire.addEventListener("submit", function (event) {

    // Empêche l'envoi réel du formulaire pour le moment
    event.preventDefault();


    // Au départ, on considère le formulaire valide
    let formulaireValide = true;


    /* -----------------------------------------
       SUPPRESSION DES ANCIENNES ERREURS
    ----------------------------------------- */

    erreurNom.textContent = "";
    erreurEmail.textContent = "";
    erreurSujet.textContent = "";
    erreurMessage.textContent = "";

    nom.classList.remove("invalide");
    email.classList.remove("invalide");
    sujet.classList.remove("invalide");
    message.classList.remove("invalide");

    messageFormulaire.textContent = "";


    /* -----------------------------------------
       VÉRIFICATION DU NOM
    ----------------------------------------- */

    if (nom.value.trim() === "") {

        erreurNom.textContent =
            "Veuillez entrer votre nom.";

        nom.classList.add("invalide");

        formulaireValide = false;

    }


    /* -----------------------------------------
       VÉRIFICATION DE L'EMAIL
    ----------------------------------------- */

    if (email.value.trim() === "") {

        erreurEmail.textContent =
            "Veuillez entrer votre adresse email.";

        email.classList.add("invalide");

        formulaireValide = false;

    } else if (!emailRegex.test(email.value.trim())) {

        erreurEmail.textContent =
            "Veuillez entrer une adresse email valide.";

        email.classList.add("invalide");

        formulaireValide = false;

    }


    /* -----------------------------------------
       VÉRIFICATION DU SUJET
    ----------------------------------------- */

    if (sujet.value.trim() === "") {

        erreurSujet.textContent =
            "Veuillez entrer un sujet.";

        sujet.classList.add("invalide");

        formulaireValide = false;

    }


    /* -----------------------------------------
       VÉRIFICATION DU MESSAGE
    ----------------------------------------- */

    if (message.value.trim() === "") {

    erreurMessage.textContent =
        "Veuillez écrire un message.";

    message.classList.add("invalide");

    formulaireValide = false;

    } else if (message.value.trim().length < 10) {

        erreurMessage.textContent =
            "Votre message doit contenir au moins 10 caractères.";

        message.classList.add("invalide");

        formulaireValide = false;
    }


    /* -----------------------------------------
       SI TOUT EST CORRECT
    ----------------------------------------- */

    if (formulaireValide) {

         formulaire.submit();

    }

});


/* =========================================
   VALIDATION EN DIRECT
========================================= */


/* -----------------------------------------
   NOM
----------------------------------------- */

nom.addEventListener("input", function () {

    if (nom.value.trim() !== "") {

        erreurNom.textContent = "";

        nom.classList.remove("invalide");

    }

});


/* -----------------------------------------
   EMAIL
----------------------------------------- */

email.addEventListener("input", function () {

    if (emailRegex.test(email.value.trim())) {

        erreurEmail.textContent = "";

        email.classList.remove("invalide");

    }

});


/* -----------------------------------------
   SUJET
----------------------------------------- */

sujet.addEventListener("input", function () {

    if (sujet.value.trim() !== "") {

        erreurSujet.textContent = "";

        sujet.classList.remove("invalide");

    }

});


/* -----------------------------------------
   MESSAGE
----------------------------------------- */

message.addEventListener("input", function () {

    if (message.value.trim().length >= 10) {

        erreurMessage.textContent = "";

        message.classList.remove("invalide");
    }

});