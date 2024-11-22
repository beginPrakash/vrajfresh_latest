<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_cart extends CI_Controller
{
    public function cart_detail()
    {
        $this->load->view('cart');
    }
    public function clear()
    {
        $this->cart->destroy();
    }
    public function remove()
    {
        $row_id = $_POST["row_id"];
        $data = array(
            'rowid' => $row_id,
            'qty' => 0
        );
        $this->cart->update($data);
    }
    public function update()
    {
        $row_id = $_POST['row_id'];
        $qty = $_POST['qty'];
        $data = array(
            'rowid' => $row_id,
            'qty' => $qty
        );
        $this->cart->update($data);
    }
}