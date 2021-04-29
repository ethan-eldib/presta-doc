window.onload = () => {
    // Gestion des liens "supprimer"
    let links = document.querySelectorAll("[data-delete]")

    // On boucle sur links
    for (link of links) {
        // On écoute le clic
        link.addEventListener("click", function (e) {
            // On empêche la navigation (désactive le lien href)
            e.preventDefault()

            // On demande confirmation
            if (confirm("Voulez-vous supprimer ce document ?")) {
                // On envoie une requete Ajax vers le href du lien avec la methode DELETE
                // this fait réference au lien sur lequel on a cliqué
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.token })
                }).then(
                    // On récupère la réponse en JSON
                    response => response.json()
                ).then(data => {
                    if (data.success) {
                        this.parentElement.remove()
                    } else {
                        alert(data.error)
                    }
                }).catch(e => alert(e))
            }
        })
    }
}