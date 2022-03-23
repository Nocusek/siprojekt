<html>

<head>
    <!-- <style>
        table :hover {
            background-color: RGBA(243, 111, 39, 0.2) !important;
        }
    </style> -->
</head>

</html>

<?php

function generateProductsQuery($whereCondition)
{
    // Pobieranie wszystkich nazw produktów, cen etc.
    return "SELECT o.idOrders, m.name AS pizza, o.number, m.price * o.number * (1 - (d.discount/100)) AS price FROM orders o
    INNER JOIN ordersdetails od ON od.idOrders = o.idOrders
    INNER JOIN menu m ON o.idProduct = m.id 
    LEFT OUTER JOIN discounts d ON d.code = od.discountCode
    $whereCondition ORDER BY o.idOrders";
}

function generateDetailsQuery($whereCondition)
{
    // DISTINCT żeby pobrać tylko jeden wiersz
    // Pobieranie jedego wiersza dla każdego zamówienia do wyświetlenia miasta, adresu etc.
    return "SELECT DISTINCT o.idOrders, od.discountCode, od.name, od.city, od.address, od.phone, u.email FROM ordersdetails od
    INNER JOIN orders o ON od.idOrders = o.idOrders
    INNER JOIN users u ON o.idUser = u.id
    $whereCondition ORDER BY o.idOrders";
}
/**
 * Prints table of orders
 * 
 * @param array $dataArr array from table `orders`
 * @param array $countArr array that contains how many products each order has
 * @param sth  $detailsSth statement handler from table `ordersdetails`
 * @param bool  $button1Visibility boolean that indicates button1 visibility
 * @param string  $retPath name of file that should be loaded ather logic file of a button
 * @param string  $button1Status status that will be set after pressing button1 ('Anulowano'. 'Zrealizowano', 'W trakcie realizacji')
 * @param bool  $confirmButton boolean that indicates button2 visibility
 * @param string  $button2Status status that will be set after pressing button2 ('Anulowano'. 'Zrealizowano', 'W trakcie realizacji')
 */
function printTable($dataArr, $countArr, $detailsSth, $retPath = "", $button1Visibility = false, $button1Status = "", $button2Visibility = false, $button2Status = "")
{

    $prevOrderId = -1;
    $orderPriceSum = 0;
    $currentOrderElement = 1; // Do wyświetlenia podsumowania zamówienia
    echo '<table class="center table" style="width: 80%; margin-left:auto; margin-right:auto">
            <tr>
                <th class="tableUnderline">Produkt</th>
                <th class="tableUnderline">Ilość</th>
                <th class="tableUnderline">Cena</th>
                <th class="tableUnderline">ID zam.</th>
                <th class="tableUnderline">Kod prom.</th>
                <th class="tableUnderline">Imię</th>
                <th class="tableUnderline">Miasto</th>
                <th class="tableUnderline">Adres</th>
                <th class="tableUnderline">Telefon</th>
                <th class="tableUnderline">Email</th>';
    if ($button1Visibility || $button2Visibility) {
        // Wyświetlić tylko jeśli będą jakieś przyciski
        echo '<th class="tableUnderline">Akcja</th>';
    }

    echo '</tr>';
    foreach ($dataArr as $row) {

        $orderCount = 1;

        foreach ($countArr as $key => $val) {
            if ($val['idOrders'] == $row['idOrders']) {
                $orderCount = $countArr[$key]['count'] + 1;
                break;
            }
        }
        $price = number_format($row['price'], 2);

        echo "
              <tr>
                  <td class='align-middle'>{$row['pizza']}</td>
                  <td class='align-middle'>{$row['number']}</td>
                  <td class='align-middle'>$price zł</td>
                  ";

        $orderPriceSum  += $row['price'];

        if ($prevOrderId != $row['idOrders']) {
            $prevOrderId = $row['idOrders'];

            $rowDetails = $detailsSth->fetch();
            echo "
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$row['idOrders']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['discountCode']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['name']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['city']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['address']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['phone']}</td>
                  <td rowspan='$orderCount'class='align-middle tableUnderline'>{$rowDetails['email']}</td>";

            if ($button1Visibility || $button2Visibility) {
                echo "<td rowspan='$orderCount' class='align-middle tableUnderline'>";
                if ($button1Visibility) {
                    echo "  
                    <form method='post' action='edit_orders_logic.php?status=$button1Status&retPath=$retPath'> 
                    <button name='orderID' class='btn btn-outline-success align-middle' style='width: 50px' type='submit'  value='{$row['idOrders']}'>✔</button>
                    </form>  ";
                }
                if ($button2Visibility) {
                    echo "  
                    <form method='post' action='edit_orders_logic.php?status=$button2Status&retPath=$retPath'> 
                      <button name='orderID' value='{$row['idOrders']}' class='btn btn-outline-danger align-middle' style='width: 50px; margin-top: 5px' type='submit'>✘</button>
                      </form> ";
                }
                echo "</td>";
            }
        }
        echo "</tr>";
        $currentOrderElement++;
        if ($currentOrderElement == $orderCount) {
            $orderPriceSum  = number_format($orderPriceSum, 2);
            echo " <td class='align-middle tableUnderline' ><h4>Suma:</h4> </td> ";
            echo " <td colspan='2'class='align-middle tableUnderline' ><h4>$orderPriceSum  zł</h4> </td> ";
            $orderPriceSum  = 0;
            $currentOrderElement = 1;
        }
    }
    echo '</table>';
}
