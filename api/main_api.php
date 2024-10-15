<?php
header('Content-type: application/json;');
header("charset: UTF-8");

include "../lib/db.php";
$db = new DB();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    cart();
}

function cart()
{
    $input = file_get_contents('php://input');
    $json_input = json_decode($input, true);

    if (isset($json_input['request'])) {
        if ($json_input['request'] == 'update_cart_p') {
            update_cart_p($json_input);
        } elseif ($json_input['request'] == 'update_cart_m')
            update_cart_m($json_input);

        elseif ($json_input['request'] == 'add_cart')
            add_cart($json_input);

        else
            echo json_encode(["res_code" => 600]);
    }
}

function update_cart_p($json_input)
{
    global $db;
    $json_result['res_code'] = 200;

    $upd_result = $db->update_cart_p($json_input['product_id'], $json_input['user_id'], $json_input['tedad']);

    if (gettype($upd_result) == 'integer') {
        $json_result['res_code'] = $upd_result;

    } else {
        $json_result = array_merge($json_result, $upd_result);
    }
    echo json_encode($json_result);
}

function update_cart_m($json_input)
{
    global $db;
    $json_result['res_code'] = 200;

    $upd_result = $db->update_cart_m($json_input['product_id'], $json_input['user_id'], $json_input['tedad']);

    if (gettype($upd_result) == 'integer') {
        $json_result['res_code'] = $upd_result;

    } else {
        $json_result = array_merge($json_result, $upd_result);
    }
    echo json_encode($json_result);
}

function add_cart($json_input)
{
    global $db;

    $row = $db->sel_one_cart($json_input['user_id'], $json_input['product_id']);
    if ($row === 2) {
        $result = $db->insert_to_cart($json_input['product_id'], $json_input['user_id'], 1);
        if (gettype($result) == 'integer')
            echo json_encode(['res_code' => $result]);
        else
            echo json_encode([
                'res_code' => 200,
            ]);
    } else
        update_cart_p($json_input);
}
