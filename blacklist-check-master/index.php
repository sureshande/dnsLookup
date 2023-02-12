<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Analisar Blacklist</title>
</head>
<body>
 	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand center" href="#">Blacklist Check</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navig$
                <span class="navbar-toggler-icon"></span>
            </button>

    </nav>
            <div class="container py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container mt-4 align-self-center">
                            <h2>Verificar: </h2>
                            <hr>
                            <form action="check.php" method="post">
                                <div class="form-group">
                                    <label>IP: </label>
                                    <input type="text" pattern="((^|\.)((25[0-5]_*)|(2[0-4]\d_*)|(1\d\d_*)|([1-9]?\d_*))){4}_*$" name="ip" class="form-control" placeholder="Digite o IP">
                                </div>
                                <button type="submit" class="btn btn-success">Verificar</button>
                                <button type="reset" class="btn btn-danger">Limpar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

</form>
</body>
</html>