<!DOCTYPE html>
<html>
<head>
    <title>Menu - Zamawianie jedzenia online</title>
</head>
<body>

<h1>Menu - Zamawianie jedzenia online</h1>

<h2>Menu</h2>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_order";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

// Sprawdzenie, czy funkcje już istnieją, zanim zostaną zdefiniowane
if (!function_exists('getMenuItemsFromDatabase')) {
    function getMenuItemsFromDatabase($conn) {
        $sql = "SELECT id, name, price FROM menu_items";
        $result = $conn->query($sql);

        $menu = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $menu[] = $row;
            }
        }
        return json_encode($menu);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] === 'add_to_cart' && isset($_POST['item_id'])) {
        $itemId = $_POST['item_id'];
        $message = addToCartInDatabase($conn, $itemId);
        echo '<p>' . $message . '</p>';
    } elseif ($_POST['action'] === 'place_order') {
        $message = placeOrderInDatabase($conn);
        echo '<p>' . $message . '</p>';
    } elseif ($_POST['action'] === 'reset_cart') {
        $message = resetCart($conn);
        echo '<p>' . $message . '</p>';
    } elseif ($_POST['action'] === 'reset_orders') {
        $message = resetOrders($conn);
        echo '<p>' . $message . '</p>';
    }
}

// Funkcja addToCartInDatabase zadeklarowana tylko raz, aby uniknąć błędu redefinicji
function addToCartInDatabase($conn, $itemId) {
    $sql = "INSERT INTO cart (item_id) VALUES ('$itemId')";
    return ($conn->query($sql) === TRUE) ? "Produkt o ID $itemId został dodany do koszyka." : "Błąd dodawania produktu do koszyka: " . $conn->error;
}

// Funkcje związane z pozostałymi akcjami

function placeOrderInDatabase($conn) {
    $sql = "INSERT INTO orders (items) SELECT item_id FROM cart";
    if ($conn->query($sql) === TRUE) {
        $sqlClearCart = "DELETE FROM cart";
        return ($conn->query($sqlClearCart) === TRUE) ? "Zamówienie zostało złożone pomyślnie!" : "Błąd podczas czyszczenia koszyka: " . $conn->error;
    }
    return "Błąd składania zamówienia: " . $conn->error;
}

function resetCart($conn) {
    $sql = "DELETE FROM cart";
    return ($conn->query($sql) === TRUE) ? "Koszyk został zresetowany." : "Błąd podczas resetowania koszyka: " . $conn->error;
}

function resetOrders($conn) {
    $sql = "DELETE FROM orders";
    return ($conn->query($sql) === TRUE) ? "Lista zamówień została zresetowana." : "Błąd podczas resetowania listy zamówień: " . $conn->error;
}

?>

<h2>Dodaj do koszyka</h2>
<form method="post" action="">
    <input type="hidden" name="action" value="add_to_cart">
    ID produktu: <input type="text" name="item_id"><br><br>
    <input type="submit" value="Dodaj do koszyka">
</form>

<h2>Składanie zamówienia</h2>
<form method="post" action="">
    <input type="hidden" name="action" value="place_order">
    <input type="submit" value="Złóż zamówienie">
</form>

<h2>Koszyk</h2>
<form method="post" action="">
    <input type="hidden" name="action" value="reset_cart">
    <input type="submit" value="Resetuj koszyk">
</form>

<h2>Lista zamówień</h2>
<form method="post" action="">
    <input type="hidden" name="action" value="reset_orders">
    <input type="submit" value="Resetuj listę zamówień">
</form>

</body>
</html>
