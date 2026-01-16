<?php
require_once('utils/vue_generique.php');
    Class Vue_commande extends VueGenerique{
        public function __construct(){
            parent::__construct();
        }

public function formulaire_début_commande(){

    echo '
        <form method="post" action="index.php?module=commande&action=traiter_debut_commande">
                <p>Cliquer pour commencer une nouvelle commande : </p>
                <input type="submit" value="Démarrer la commande">
        </form>
    ';
}

public function formulaire_commande($liste_prod){
echo '<form method="post" action="index.php?module=commande&action=ajout_produit" id="form-commande">';
    $elem = 0;
    foreach($liste_prod as $p){
        $id = $p['idProd'];
        echo '
            <input type="hidden" name="produits['.$elem.'][id]" value="'.$id.'">
            <input type="number" name="produits['.$elem.'][qte]" min="1" max="100" placeholder="0" oninput="refreshPanier()">
                <img src="'.$p["image"].'" alt="'.$p["nom"].'" width="100">

            <p>'.$p["nom"].' - '.($p["prix"]/100).' €</p>

        ';
        $elem += 1;
    }
    echo '<h3> Total commande : <span id="total-prix">0</span></h3>';
    echo'<button type="submit">Panier</button>';
    echo '</form>';
    echo '
    <script>
     function refreshPanier(){
        const formulaire = document.getElementById("form-commande");
        const données = new FormData(formulaire);

        fetch("index.php?module=commande&action=prix_total",{
            method: "POST",
            body : données
        })
        .then(response=>response.json())
        .then(data=> {
        document.getElementById("total-prix").innerText = data.total +"€";
        })
    }
    </script>
    ';
}
public function finaliser_commande($liste_commande){
    echo '
        <form method = "POST" action="index.php?module=stock&action=deduireStock" id="form-finCommande">
        <div id="listeCommande">
         <div class="TitreColonne">ID de la commande</div>
            <div class="TitreColonne">Prix de la commande</div>
            <div class="TitreColonne">Détails de la commande </div>
            <div class ="TitreColonne">Action</div>';
            $id=0;
            foreach($liste_commande as $c){

            echo'

                <div class="elt" name="commande" value='.$c["id"].'>'.$c['id'].'</div>
                <div class="elt">'.$c['total_commande'].'</div>
                <a href="index.php?module=historique&action=detailHistoClient&idCommande='.$c['id'].'" class="elt"> Détails </a>


                <button type="button" name="finCommande" onclick="finCommandeAJAX('.$c['id'].')">
                    Finaliser
                </button>


            ';
            $id+=1;
            }






        echo '</div>';

        echo '</form>';

        echo'

    <script>
    function finCommandeAJAX(id){
            const formulaire = document.getElementById("form-finCommande");
            const données = new FormData(formulaire);
            données.append("idCommande",id);


            fetch("index.php?module=commande&action=finCommande",{
                method: "POST",
                body : données
            })
                .then(response=> {
                    return fetch("index.php?module=stock&action=deduireStock&val=idCommande",{
                method: "POST",
                body : données
                });
            })
            .then(() => {
            location.reload();
            });

    }

    </script>


    ';
}

    public function afficher_confirmation_commande($prix) {
        echo "<p>Le prix total est de : " . $prix . " €</p>";
    }

    public function affiche(){
        return $this->getAffichage();
    }
    public function message($txt){
        echo "<p>".$txt."</p>";
    }

    }

?>
