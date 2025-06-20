<?php
function buscarFilmes($apiKey) {
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=$apiKey&language=pt-BR&with_companies=420&sort_by=popularity.desc";
    $resposta = file_get_contents($url);
    $dados = json_decode($resposta, true);
    return $dados['results'];
}
?>
