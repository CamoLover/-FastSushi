<div id="remplissage">

</div>

<footer>

    <div id="branding">
        <p>FastSushi</p>
    </div>

    <div id="content">
        <div id="description">
            <p>Chez FastSushi, <br>chaque bouchée est une invitation au voyage.<br>
                Entre tradition et créativité, nos sushis sont préparés avec passion <br>et des ingrédients d'exception. Installez-vous, savourez, <br>et laissez la magie opérer.
            </p>
        </div>

        <div id="liens_utiles">
            <div id="menu_rapide">
                <p>Accueil</p>
                <p>Panier</p>
                <p>Historique des commandes</p>
            </div>

            <div id="stuff_legal">
                <p>TOS</p>
                <p>CGV</p>
                <p>CGU</p>
            </div>

            <div id="les_reseaux">
                <p>Insta</p>
                <p>Facebook</p>
                <p>TikTok</p>
            </div>
        </div>
    </div>

</footer>

<style>
@import url('https://fonts.googleapis.com/css2?family=Varela+Round&display=swap');

body {
    font-family: 'Varela Round', sans-serif;
    background-color: gray;
}

#remplissage{
    border: 1px solid;
    border-color: red;
    height: 1000px;
}
footer {
    background-color: black;
    color: red;
    text-align: center;
    padding: 20px;
    display: flex;
    flex-direction: column; 
    align-items: center;
}

#content {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin-bottom: 450px;
}

footer div { 
    padding: 10px;
    margin: 5px;
}

#description {
    width: 40%; 
    text-align: left;
    background-color: rgb(44, 44, 44);
    border-radius: 30px;
    color: red;
}

#liens_utiles {
    flex-grow: 1; 
    display: flex;
    justify-content: space-around;
    background-color: rgb(44, 44, 44);
    border-radius: 30px;
}

#menu_rapide{
    background-color: rgb(32, 32, 32);
    border-radius: 15px;
    color: red;
}

#stuff_legal{
    background-color: rgb(32, 32, 32);
    border-radius: 15px;
    color: red;
}

#les_reseaux{
    background-color: rgb(32, 32, 32);
    border-radius: 15px;
    color: red;
}

#branding{
    font-size: 400px;
    height: 10px;
    border: 1px solid;
}

</style>
