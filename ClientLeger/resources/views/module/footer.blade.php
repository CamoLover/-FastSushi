<div id="remplissage">

</div>

<footer>

    

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
                <p>Instagram</p>
                <p>Facebook</p>
                <p>TikTok</p>
            </div>
        </div>
    </div>
    <div id="imgfoot">
     <img src="{{asset('media/FS_Footer.png')}}" alt="" width="100%">
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
    text-align: center;
    padding: 20px;
    display: flex;
    flex-direction: column; 
    align-items: center;
    margin-left: -15px;
    margin-right: -15px;
}

#content {
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    flex-direction: column;
}

footer div { 
    padding: 10px;
    margin: 5px;
}

#description {
    flex-grow: 1; 
    display: flex;
    justify-content: center;
    color: #ccc5b9;
    border-radius: 30px;
    text-align: center;
}

#liens_utiles {
    flex-grow: 1; 
    display: flex;
    justify-content: space-around;
    
    border-radius: 30px;
}

#menu_rapide{
    background-color: rgb(32, 32, 32);
    border-radius: 15px;
    color: #ccc5b9;}

#stuff_legal{
    border-radius: 15px;
    color: #ccc5b9;
    background-color: rgb(32, 32, 32);
}

#les_reseaux{
    border-radius: 15px;
    color: #ccc5b9;
    background-color: rgb(32, 32, 32);
}

#imgfoot{
    margin-top: -5px;
    margin-bottom: -50px;
    padding: 0;
    margin-left: -15px;
    margin-right: -20px;
}

</style>
