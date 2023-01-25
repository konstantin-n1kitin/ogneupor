<?php
$result_str = "";
$base_sql = "SELECT ID_Channel, MeasureDate, Value, State FROM Currents	WHERE  ID_Channel = 67 or ID_Channel = 328";

$sql_order_by = " ORDER BY ID_Channel";
$sql = sprintf("%s%s", $base_sql, $sql_order_by); //Готовый SQL запрос
try {
    //set_time_limit(10);
    $db = new PDO ('odbc:DRIVER={SQL Server};SERVER=' . 'askuserver2' . ';database=' . 'oup' . ';Uid=' . 'sa' . ';Pwd=' . 'metallurg' . ';');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $id_channel = $row[0];                        // ID
        $dt = date('d-m-Y H:i:s', strtotime($row[1]));// DT
        $value = sprintf('%.2f', $row[2]);            // Value
        $state = $row[3];                             //State
        $state_array[$id_channel] = $state;
        $data[$id_channel] = $value;
        if ($state == 0)
            $state = '&nbsp;';
        $result_str = "$result_str$id_channel=$value|$state;";
    }
    $db = null;
} catch (PDOException $err) {
    return -3; //Ошибка связи с базой данных
}

echo $result_str;