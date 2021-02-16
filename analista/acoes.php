<?php
    require_once '../conexao.php';
    session_start();

    if(!isset($_SESSION['logado'])):
        header('Location: index.php');
    endif;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>InvistAí</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/96/000000/bad-idea.png">
    <link rel="stylesheet" href="../estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script src="../script.js"></script> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container-fluid">

        
        <!-- Menu de Navegação -->
        <div class="row">
            <div class="col-sm-12" id="navbar">
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php"><img src="https://img.icons8.com/fluent/24/000000/bad-idea.png"/> InvistAí<font size="2">(Analista)</font></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link"href="perfil.php">Perfil</a></li>
                                <li class="nav-item"><a class="nav-link active" aria-current="page" href="acoes.php">Ações</a></li>
                                <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
                                  <li class="nav-item"><a class="nav-link" href="#" onclick="confirmlogout()" id="logout"><?=$_SESSION['nome']?>-logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <h1>Ações</h1>
                <?php
                    $r = $db->query("SELECT * FROM acao WHERE inativado=0 ORDER BY ativo");
                    if($r->rowCount()>0) {
                        $linhas = $r->fetchAll(PDO::FETCH_ASSOC);
                        foreach($linhas as $l) {
                            echo "
                                <p><b>Ativo:</b> ".strtoupper($l['ativo'])."</p>
                                <p><b>Nome:</b> ".$l['nome']."</p>
                                <p><b>Setor:</b> ".$l['setor']."</p>
                                <p><b>Cotação:</b> R$ ".number_format($l['cotacaoAtual'],2)."</p>
                                <a href='updateAcao.php?ativo=".$l['ativo']."' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='inatAcao.php?ativo=".$l['ativo']."' class='btn btn-danger btn-sm'>Inativar</a>
                                <hr>
                            ";
                        }
                    } else {echo "<p class='text-muted'>Nenhuma ação ativa</p>";}
                ?>

                <h2>Ações inativadas</h2>
                <?php
                    $r = $db->query("SELECT * FROM acao WHERE inativado=1 ORDER BY ativo");
                    if($r->rowCount()>0) {
                        $linhas = $r->fetchAll(PDO::FETCH_ASSOC);
                        foreach($linhas as $l) {
                            echo "
                                <p class='text-muted'><b>Ativo:</b> ".strtoupper($l['ativo'])."</p>
                                <p class='text-muted'><b>Nome:</b> ".$l['nome']."</p>
                                <p class='text-muted'><b>Setor:</b> ".$l['setor']."</p>
                                <p class='text-muted'><b>Cotação:</b> R$ ".number_format($l['cotacaoAtual'],2)."</p>
                                <a href='ativAcao.php?ativo=".$l['ativo']."' class='btn btn-success btn-sm'>Ativar</a>
                                <hr>
                            ";
                        }
                    } else {echo "<p class='text-muted'>Nenhuma ação inativada</p>";}
                ?>
            </div>
        </div>


    </div>
</body>
</html>