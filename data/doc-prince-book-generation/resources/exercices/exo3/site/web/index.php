<?php
require __DIR__ . '/../library/Account.php';
session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header("Location: login.php");
    exit;
}


//
// Process : add or take money
$error = false;
if (filter_has_var(INPUT_POST, 'type') && filter_has_var(INPUT_POST, 'amount')) {
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $type = filter_input(INPUT_POST, 'type');
    $account = $_SESSION['account'];
    switch ($type) {
        case 'take':
            try {
                $account->takeMoney($amount);
            } catch (\Exception $e) {
                $error = $e;
            }
            break;
        case 'add':
        default:
            $account->addMoney($amount);
            break;
    }
}


//
// Process : reset the account
if (filter_has_var(INPUT_POST, 'reset')) {
    $balance = filter_input(INPUT_POST, 'reset', FILTER_VALIDATE_FLOAT);
    $_SESSION['account']->setBalance($balance);
}

?>
<html>
    <head>
        <link href="css/bootstrap.css" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Welcome</h1>
                <a href="logout">logout</a>
            </header>

            <section class="alert alert-info">Vous avez <?php echo $_SESSION['account']->getBalance(); ?> euro sur votre compte.</section>
            <?php
            if ($error) {
                printf('<section class="alert alert-error">%s</section>', $error->getMessage());
            }
            ?>
            <section>
                <p>
                    Bonjour <strong><?php echo $_SESSION['username']; ?></strong>.
                    Que voulez-vous faire ?
                </p>
                <form method="post" action="">
                    <div>
                        <label for="type">Operation</label>
                        <select name="type" id="type">
                            <option value='add'>Ajouter</option>
                            <option value="take">Retirer</option>
                        </select>
                    </div>
                    <div>
                        <label for="amount">Montant</label>
                        <input type="text" name="amount" id="amount"/>
                    </div>
                    <button class="btn btn-primary">Go</button>
                </form>
            </section>

            <hr />
            <section>
                Vous pouvez aussi reinitialiser votre compte :
                <form method="post" action="">
                    <div>
                        <label for="reset">Nouveau solde</label>
                        <input type="text" name="reset" id="reset" />
                    </div>
                    <button class="btn btn-danger">Reinitialiser</button>
                </form>
            </section>
        </div>
    </body>
</html>