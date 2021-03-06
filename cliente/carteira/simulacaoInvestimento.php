<?php
    require_once '../../conexao.php';
    session_start();
    if(!isset($_SESSION['clienteLogado'])){header('Location: ../../acessoNegado.php');}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<title>InvistAí</title>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/96/000000/bad-idea.png">
    <link rel="stylesheet" href="../../estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="../../script.js"></script>
    <script type="text/javascript" src="../../pace.min.js"></script>
</head>
<body>
    <div class="container-fluid">

        <!-- Menu de Navegação -->
        <div class="row">
            <div class="col-sm-12" id="navbar">
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand"><img src="https://img.icons8.com/fluent/24/000000/bad-idea.png"> InvistAí<font size="2">(Cliente)</font></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link disabled">Home</a></li>
                                <li class="nav-item"><a class="nav-link disabled">Perfil</a></li>
                                <li class="nav-item"><a class="nav-link disabled">Ações</a></li>
                                <li class="nav-item"><a class="nav-link disabled"><?=$_SESSION['nome']?>-logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Investir na carteira <?=$_SESSION['idCarteira']?>:</h2>
                <?php
                    $r = $db->prepare("SELECT objetivo FROM carteira WHERE id=?");
                    $r->execute(array($_SESSION['idCarteira']));
                    $linhas = $r->fetchAll(PDO::FETCH_ASSOC);
                    foreach($linhas as $l) {echo "<h4 class='text-muted'>".$l['objetivo']."</h4>";}
                ?>

                <div class="table-responsive">
                    <table class='table table-striped'>
                        <thead>
                            <tr>
                                <th scope='col'>Ativo</th>
                                <th scope='col'>Setor</th>
                                <th scope='col'>Quantidade</th>
                                <th scope='col'>Cotação Atual</th>
                                <th scope='col'>Patrimônio Atualizado</th>
                                <th scope='col'>Participação Atual(%)</th>
                                <th scope='col'>Objetivo(%)</th>
                                <th scope='col'>Distância do Objetivo(%)</th>
                                <th scope='col'>Quantas Ações Comprar</th>  
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $_SESSION['valorInvestimento']; //Valor que usuário informa
                                $totPatrAtualizado = 0;
                                
                                $r = $db->prepare("SELECT * FROM carteira_acao WHERE idCarteira=?");
                                $r->execute(array($_SESSION['idCarteira']));
                                $linhas = $r->fetchAll(PDO::FETCH_ASSOC);
                                

                                //Pegar dados específicos da ação citada
                                foreach($linhas as $l) {
                                    $r = $db->prepare("SELECT * FROM acao WHERE ativo=?");
                                    $r->execute(array($l['ativoAcao']));
                                    $linhas2 = $r->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach($linhas2 as $l2) {
                                        $ativo = $l2['ativo'];
                                        $setor = $l2['setor'];
                                        $cotacaoAtual = $l2['cotacaoAtual'];
                                    }

                                    //Programar variáveis aqui
                                    $qtdAcoes = $l['qtdAcao']; //Ações em saldo do cliente no BD
                                    $patrAtualizado = $qtdAcoes * $cotacaoAtual;
                                    
                                    
                                    //Pegar totalPatrimonioAtualizado da carteira
                                    $r = $db->prepare("SELECT * FROM carteira_acao WHERE idCarteira=?");
                                    $r->execute(array($_SESSION['idCarteira']));
                                    $linhas3 = $r->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($linhas3 as $l3) {
                                        $r = $db->prepare("SELECT * FROM acao WHERE ativo=?");
                                        $r->execute(array($l['ativoAcao']));
                                        $linhas4 = $r->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($linhas4 as $l4) {$cotacaoAtualAcao = $l4['cotacaoAtual'];}
                                        $totPatrAtualizado += $l3['qtdAcao'] * $cotacaoAtualAcao;
                                    }
                                    $participacaoAtual = ($patrAtualizado * 100) / $totPatrAtualizado; //Pra inserir no BD, ele precisa ser arredondado na 2ª casa decimal, conforme 3ª casa decimal


                                    //Pegar distância do objetivo (Se for maior que zero, aí ñ indica/compra ações)
                                    $distanciaDoObjetivo = $participacaoAtual - $l['objetivo'];
                                    

                                    //Pegar Quantas Ações Comprar
                                    $qtdAcoesComprar = ($l['objetivo']*($_SESSION['valorInvestimento']/100)) / $cotacaoAtual;
                                    if($distanciaDoObjetivo >= 0) {$qtdAcoesComprar = 0;}


                                    echo "
                                        <tr>
                                            <td class='setx'>".strtoupper($ativo)."</td>
                                            <td class='set'>".$setor."</td>
                                            <td class='set'>".$qtdAcoes."</td>
                                            <td class='setx'>R$ ".$cotacaoAtual."</td>
                                            <td class='setx'>R$ ".number_format($patrAtualizado,2,".",",")."</td>                                            
                                            <td class='set'>".number_format($participacaoAtual,2,".",",")." %</td>                                            
                                            <td class='set'>".number_format($l['objetivo'],2,".",",")." %</td>
                                            <td class='set'>".number_format($distanciaDoObjetivo,2,".",",")." %</td>
                                            <td class='set'>".(int)$qtdAcoesComprar."</td>
                                        </tr>
                                    ";
                                }
                            ?>
                        </tbody>
                    </table>
                    <span class='btn btn-dark btn-sm'>Total do Patr Atualizado: <span class='badge bg-success'>R$ <?=number_format($totPatrAtualizado,2,".",",")?></span></span>
                    <span class='btn btn-dark btn-sm'>Sobra dos Aportes: <span class='badge bg-secondary'>R$ <?=number_format(($_SESSION['valorInvestimento']-$totPatrAtualizado),2,".",",")?></span></span>
                    <br><br>

                </div>
                <form action="confInvestimento.php" method="post">
                    <a href="canInvestimento.php" class="btn btn-danger">Cancelar</a>
                    <input type="hidden" name="confInvestimento" value=1>
                    <button type="submit" class="btn btn-success">Realizar Investimento</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>