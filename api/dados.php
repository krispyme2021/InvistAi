<?php
require_once '../conexao.php';

$url1 = "https://api-cotacao-b3.labdo.it/api/sysinfo";
$ac = json_decode(file_get_contents($url1));
$ultimaCotacao = $ac->dt_ultimo_pregao;

$url = "https://api-cotacao-b3.labdo.it/api/empresa";
$acao = json_decode(file_get_contents($url));
$c = 1;


foreach($acao as $a):
    $ativo = explode(",",$a->cd_acao);
    if($ativo[0]!="") {
        $ativoBD = $ativo[0];
        $nome = strtolower($a->nm_empresa);
        $setor = strtolower($a->segmento);

        $r = $db->prepare("INSERT INTO acao(ativo,nome,setor,dtUltimaCotacao) VALUES (?,?,?,?)");
        $r->execute(array($ativoBD,$nome,$setor,$ultimaCotacao));

        echo "Ativo: ".$ativo[0]."<br>Nome: $a->nm_empresa<br>Setor: $a->segmento<hr>";
    }
    if($c==20){break;} else{$c++;}
endforeach;



}