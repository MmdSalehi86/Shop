<?php
const __D_ADMIN__ = 'admin/';
const __ADMIN__ = 'admin.php';
const  __LIST_U__ = 'list_user.php';
const __LIST_P__ = 'list_product.php';
const __LIST_O__ = 'list_order.php';
const __EDIT_P__ = 'add_edit_product.php';
const __EDIT_O__ = 'add_edit_order.php';
const __SHOP__ = 'shop/';
const __SHOP_C__ = 'shop_cart.php';
const __AUTH__ = 'auth/';
const __LOG__ = 'login.php';
const __REG__ = 'register.php';
const __BDIR__ = '../';
const __LOC__ = 'location: ';

function alert_js($msg, $href = null, $_die = false)
{
    echo "<script>alert('$msg');";
    if ($href != null)
        echo "location.href='$href';";
    echo "</script>";
    if ($_die) die();
}

function get_full_time()
{
    include_once "jdf.php";
    return jdate("Y/m/d~H:i:s", '', '', 'Asia/Tehran', 'en');
}

class DB
{
    function __construct()
    {
        $this->connect();
    }

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'shop_db';
    private $conn;

    function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    public function get_conn_code(): int
    {
        return mysqli_connect_errno();
    }

    public function get_error()
    {
        return mysqli_connect_error();
    }

    public function close()
    {
        mysqli_close($this->conn);
    }

    public function sel_user_with_mobile_or_email($mobile, $email, $password = null)
    {
        $query = "SELECT * from users WHERE (email='$email' OR mobile='$mobile')";

        if (!empty($password)) {
            $query .= " AND password=$password";
            $result = mysqli_query($this->conn, $query);
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else
                return 2;   // user not found

        } else
            return mysqli_query($this->conn, $query);
    }

    public function sel_user($id = null)
    {
        $query = "SELECT * FROM users";
        if (!empty($id)) {
            $query .= " WHERE id=$id";
            return mysqli_query($this->conn, $query)->fetch_assoc();
        }
        return mysqli_query($this->conn, $query);
    }

    public function edit_user(array $user)
    {
        $query_duplicate = "SELECT COUNT(id) AS num FROM users WHERE id!=$user[id] AND (email='$user[email]' OR mobile='$user[mobile]')";

        $fetch_duplicate = mysqli_query($this->conn, $query_duplicate)->fetch_assoc();
        if ($fetch_duplicate['num'] > 0)
            return 2;   // duplicate mobile or email

        $query = "UPDATE users SET nam='$user[nam]', password='$user[password]', mobile='$user[mobile]',
            email='$user[email]', address='$user[address]'";
        if (isset($user['pic']))
            $query .= ", pic='$user[pic]'";
        $query .= " WHERE id=$user[id]";
        return mysqli_query($this->conn, $query);
    }

    public function delete_user($id)
    {
        $query_exist_order = "SELECT COUNT(user_id) AS num FROM product_order WHERE user_id=$id AND status=1";
        $fetch_exist_order = mysqli_query($this->conn, $query_exist_order)->fetch_assoc();
        if ($fetch_exist_order['num'] > 0)
            return 2;
        return mysqli_query($this->conn, "DELETE FROM users WHERE id=$id");
    }

    public function insert_user($name, $mobile, $email, $address, $pic, $password, $access)
    {
        $result = $this->sel_user_with_mobile_or_email($mobile, $email);
        if ($result->num_rows > 0)
            return 2; //  Duplicate Username
        $result = mysqli_query($this->conn, "INSERT INTO users (nam , mobile, email, address, pic, access, password)
        VALUES ('$name', '$mobile', '$email', '$address' , '$pic' , $access , '$password')");

        return !$result ? false : mysqli_query($this->conn, "SELECT id from users Where email='$email'")->fetch_assoc();
    }


    public function sel_product($id = null)
    {
        $query = "SELECT * FROM product";
        if ($id != null)
            $query .= " WHERE id=$id";
        return mysqli_query($this->conn, $query);
    }

    public function sel_product_with_status($status)
    {
        $sql = "SELECT * FROM product Where status=";
        if ($status)
            $sql .= "1";
        else
            $sql .= "0";
        return mysqli_query($this->conn, $sql);
    }

    public function insert_update_product(array $product)
    {
        if (isset($product['id'])) {
            $check_order_status1 = "SELECT COUNT(product_id) AS num FROM product_order WHERE product_id=$product[id] AND status=1";
            $fetch_order_status = mysqli_query($this->conn, $check_order_status1)->fetch_assoc();

            if ($fetch_order_status['num'] != 0 and $product['status'] == 0)
                return 2;    //  An active order was found for this product

            $query = "UPDATE product SET title='$product[name]' , price='$product[price]' , tedad=$product[tedad] ,
            description='$product[description]' , status=$product[status]";
            if (isset($product['pic']))
                $query .= ", pic='$product[pic]'";
            $query .= " WHERE id=$product[id]";

            $result = mysqli_query($this->conn, $query);
            if ($result)
                return $this->delete_order_status0($product['id'], $product['tedad']);
            else
                return false;

        } else {
            if (!isset($product['pic']))
                $product['pic'] = null;
            $query = "INSERT INTO product (title, price , tedad , description, pic, status)
            VALUES ('$product[name]', $product[price], $product[tedad], '$product[description]', '$product[pic]', $product[status])";
            return mysqli_query($this->conn, $query);
        }
    }


    public function update_tedad_product($id, $tedad, $delete_order_status0 = true)
    {
        $query = "UPDATE product SET tedad=$tedad";
        if ($tedad == 0) {
            $query .= ", status=0";
        }
        $query .= " WHERE id=$id";
        $result = mysqli_query($this->conn, $query);
        if ($result and $delete_order_status0) {
            return $this->delete_order_status0($id, $tedad);

        } else
            return false;
    }

    public function delete_product($id)
    {
        $count = mysqli_query($this->conn, "SELECT COUNT(*) AS num FROM product WHERE id=$id");
        if ($count->fetch_assoc()['num'] == 0)
            return 2;  // if product not found
        $count_order = mysqli_query($this->conn, "SELECT COUNT(*) AS num FROM product_order WHERE product_id=$id AND status=1");
        if ($count_order->fetch_assoc()['num'] != 0)
            return 3;   // find order for product ,   if found, do not delete it
        return mysqli_query($this->conn, "DELETE FROM product WHERE id=$id");
    }


    function delete_order_status0($pr_id, $tedad)
    {
        $result_product_status = mysqli_query($this->conn, "SELECT status FROM product Where id=$pr_id")->fetch_assoc();
        if ($result_product_status['status'] == 0)
            $tedad = 0;
        $delete_order = "DELETE FROM product_order WHERE product_id=$pr_id AND status=0 AND tedad>$tedad";
        return mysqli_query($this->conn, $delete_order);
    }

    public function sel_order($user_id = null, $product_id = null, $date_order = null)
    {
        if (isset($user_id) and isset($product_id) and isset($date_order)) {
            $query = "SELECT title, nam, mobile, product_order.price, product_order.tedad, product_order.status, date_order
        FROM product_order, product, users
        WHERE product_id=product.id AND user_id=users.id
        AND users.id=$user_id AND product.id=$product_id AND date_order='$date_order';";
            return mysqli_query($this->conn, $query)->fetch_assoc();
        }
        $query = "SELECT title, user_id, nam, mobile, product_id, product_order.price, date_order, product_order.tedad, product_order.status
        FROM product_order, product, users
        WHERE product_id=product.id AND user_id=users.id";
        return mysqli_query($this->conn, $query);
    }


    public function update_order($user_id, $product_id, $date_order, $new_user_id, $new_product_id, $new_date, $tedad, $status)
    {
        $check_user = mysqli_query($this->conn, "SELECT COUNT(*) AS num FROM users WHERE id=$user_id");
        $check_product = mysqli_query($this->conn, "SELECT price, tedad, status FROM product WHERE id=$new_product_id");
        $fetch_check_user = $check_user->fetch_assoc();
        $fetch_check_product = $check_product->fetch_assoc();

        if ($check_product->num_rows == 0 or $fetch_check_user['num'] == 0)
            return 2;      // user or product not found

        elseif ($fetch_check_product['tedad'] < $tedad) {
            return 3;      //  if the number of products was more than the order

        } elseif ($fetch_check_product['status'] == 0)
            return 4;    // disable product
        $price = $fetch_check_product['price'];
        $price *= $tedad;

        $query_update_order = "UPDATE product_order SET user_id=$new_user_id, product_id=$new_product_id, date_order='$new_date',
        price=$price, tedad=$tedad, status=$status WHERE user_id=$user_id AND product_id=$product_id AND date_order='$date_order'";
        if (!mysqli_query($this->conn, $query_update_order))
            return false;
        if ($status == 1)
            $this->update_tedad_product($new_product_id, $fetch_check_product['tedad'] - $tedad);
        return true;
    }

    public function delete_order($date_order, $user_id, $product_id)
    {
        $result = $this->sel_order($user_id, $product_id, $date_order);
        if ($result['status'] == 1)
            return 2;   //  if it had been paid
        return mysqli_query($this->conn, "DELETE FROM product_order
        WHERE user_id=$user_id AND product_id=$product_id AND date_order='$date_order'");
    }


    // new method
    public function insert_order($date, $pr_id, $us_id, $tedad, $status)
    {
        $result_exist_user = mysqli_query($this->conn, "SELECT COUNT(id) AS num FROM users WHERE id=$us_id")->fetch_assoc();
        if ($result_exist_user['num'] == 0)
            return 2;     // user not found
        $result_exist_pr = mysqli_query($this->conn, "SELECT tedad, status FROM product WHERE id=$pr_id GROUP BY status, tedad");
        if ($result_exist_pr->num_rows == 0)
            return 3;     // product not found
        $result_pr = $result_exist_pr->fetch_assoc();
        if ($result_pr['status'] == 0)
            return 4;
        elseif ($result_pr['tedad'] < $tedad)
            return 5;     //  if the number of products was more than the order

        $price_pr = mysqli_query($this->conn, "SELECT price FROM product WHERE id=$pr_id")->fetch_assoc()['price'];
        $price = $tedad * $price_pr;
        $query_insert = "INSERT INTO product_order (date_order, product_id, user_id, tedad, status, price)
        VALUES ('$date', $pr_id, $us_id, $tedad, $status, $price)";
        if (mysqli_query($this->conn, $query_insert)) {
            return $this->update_tedad_product($pr_id, $result_pr['tedad'] - $tedad);
        } else
            return false;
    }


    public function update_cart_p($product_id, $user_id, $tedad = 1)  // new method
    {
        $get_product_sql = "SELECT tedad, price FROM product Where id=$product_id AND status=1";
        $result_pr = mysqli_query($this->conn, $get_product_sql);

        $duplicate_order_sql = "SELECT tedad FROM product_order WHERE product_id=$product_id AND user_id=$user_id AND status=0";
        $result_order = mysqli_query($this->conn, $duplicate_order_sql);

        if ($result_pr->num_rows == 0)
            return 2;
        else
            $row_result_pr = $result_pr->fetch_assoc();

        if ($result_order->num_rows == 0)
            return 3;   // order not found
        else
            $row_result_order = $result_order->fetch_assoc();

        if ($row_result_pr['tedad'] <= $row_result_order['tedad'])
            return 4;    // بیشتر بودن تعداد

        $price = $row_result_pr['price'] * $tedad;   // get total price

        $sql = "UPDATE product_order SET tedad=tedad+$tedad, price=price+$price
            WHERE product_id=$product_id AND user_id=$user_id AND status=0";
        $update_result = mysqli_query($this->conn, $sql);
        if (!$update_result)
            return false;
        else
            return $this->sel_one_cart($user_id, $product_id);
    }

    public function update_cart_m($product_id, $user_id, $tedad = 1) //new method
    {
        $get_product_sql = "SELECT tedad, price FROM product Where id=$product_id AND status=1";
        $result_pr = mysqli_query($this->conn, $get_product_sql);

        $duplicate_order_sql = "SELECT tedad FROM product_order WHERE product_id=$product_id AND user_id=$user_id AND status=0";
        $result_order = mysqli_query($this->conn, $duplicate_order_sql);

        if ($result_pr->num_rows == 0)
            return 2;
        else
            $row_result_pr = $result_pr->fetch_assoc();

        if ($result_order->num_rows == 0)
            return 3;   // order not found
        elseif ($result_order->fetch_assoc()['tedad'] == 1)
            return 4;  // min tedad 1

        $price = $row_result_pr['price'] * $tedad;   // get total price

        $sql = "UPDATE product_order SET tedad=tedad-$tedad, price=price-$price
            WHERE product_id=$product_id AND user_id=$user_id AND status=0";
        $update_result = mysqli_query($this->conn, $sql);
        if (!$update_result)
            return false;
        else
            return $this->sel_one_cart($user_id, $product_id);
    }

    public function insert_to_cart($product_id, $user_id, $tedad = 1)
    {
        $get_product_sql = "SELECT tedad, price FROM product Where id=$product_id AND status=1";
        $result = mysqli_query($this->conn, $get_product_sql);
        if ($result->num_rows == 0)
            return 2;   // product not found

        $row_product = $result->fetch_assoc();
        $price = $row_product['price'] * $tedad;

        $full_time = get_full_time();
        $sql = "INSERT INTO product_order (user_id, product_id, date_order, tedad, price, status)
            VALUES ($user_id, $product_id, '$full_time', $tedad, $price, 0)";
        return mysqli_query($this->conn, $sql);
    }

    public function delete_cart($user_id, $product_id)
    {
        $delete_cart_sql = "DELETE FROM product_order WHERE user_id=$user_id AND product_id=$product_id AND status=0";
        return mysqli_query($this->conn, $delete_cart_sql);
    }

    public function sel_cart($user_id)
    {
        $select_sql = "SELECT product_order.status, date_order, product_id, product_order.tedad, product_order.price, title FROM product_order, product
        WHERE user_id=$user_id AND product_id=id";
        return mysqli_query($this->conn, $select_sql);
    }

    function sel_one_cart($user_id, $product_id)
    {
        $select_sql = "SELECT product_order.status, date_order, product_id, product_order.tedad, product_order.price, title FROM product_order, product
        WHERE user_id=$user_id AND product_id=id AND product_id=$product_id AND product_order.status=0";
        $result = mysqli_query($this->conn, $select_sql);
        if ($result->num_rows == 0)
            return 2;   // cart not found
        return $result->fetch_assoc();
    }

    public function sel_statistics(): array
    {
        $query_best_user = "SELECT nam , mobile, COUNT(user_id) AS tedad FROM users, product_order
        WHERE id=user_id AND product_order.status=1 GROUP BY user_id ORDER BY tedad DESC LIMIT 1;";
        $result_best_user = mysqli_query($this->conn, $query_best_user)->fetch_assoc();

        $query_order = "SELECT COUNT(user_id) AS tedad, SUM(price) AS total FROM product_order";
        $result_order = mysqli_query($this->conn, $query_order)->fetch_assoc();

        $query_disable_p = "SELECT COUNT(id) AS tedad FROM product WHERE status=0";
        $result_disable_p = mysqli_query($this->conn, $query_disable_p)->fetch_assoc();

        $query_enable_p = "SELECT COUNT(id) AS tedad FROM product WHERE status=1";
        $result_enable_p = mysqli_query($this->conn, $query_enable_p)->fetch_assoc();

        $query_user = "SELECT COUNT(id) AS tedad FROM users WHERE access=0";
        $result_user = mysqli_query($this->conn, $query_user)->fetch_assoc();

        $query_admin = "SELECT COUNT(id) AS tedad FROM users WHERE access=1";
        $result_admin = mysqli_query($this->conn, $query_admin)->fetch_assoc();

        $query_owner = "SELECT COUNT(id) AS tedad FROM users WHERE access=2";
        $result_owner = mysqli_query($this->conn, $query_owner)->fetch_assoc();

        return [
            'best_user' => $result_best_user,
            'order' => $result_order,
            'd_product' => $result_disable_p,
            'e_product' => $result_enable_p,
            'user' => $result_user,
            'admin' => $result_admin,
            'owner' => $result_owner,
        ];
    }

    public function buy_product($user_id, $product_id)
    {
        $query = "SELECT tedad AS num FROM product_order WHERE user_id=$user_id AND product_id=$product_id";
        $tedad_order = mysqli_query($this->conn, $query)->fetch_assoc()['num'];

        $query = "SELECT tedad AS num FROM product WHERE id=$product_id";
        $tedad_product = mysqli_query($this->conn, $query)->fetch_assoc()['num'];

        $this->update_tedad_product($product_id, $tedad_product - $tedad_order, false);
        
        //die("log");
        $query = "UPDATE product_order SET status=1 WHERE user_id=$user_id AND product_id=$product_id";
        mysqli_query($this->conn, $query);

        $this->delete_order_status0($product_id, $tedad_product - $tedad_order);
    }
}