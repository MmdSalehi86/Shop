<?php

class File
{
    public function get_upload_file($file_uploaded, $file_name, $is_product = false): string
    {
        static $char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
        $extension = pathinfo($file_name)['extension'];
        if ($is_product)
            $path = "../resource/product/";
        else
            $path = "../resource/user/";
        do {
            $new_file_name = "";
            for ($i = 0; $i < 15; $i++) {
                $new_file_name .= $char[rand(0, strlen($char) - 1)];
            }
            $new_file_name .= '.' . $extension;
        } while (is_file($path . $new_file_name));
        move_uploaded_file($file_uploaded, $path . $new_file_name);
        return $new_file_name;
    }
}