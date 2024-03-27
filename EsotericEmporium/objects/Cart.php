<?php

include_once "utils/utils.php";
require_once "utils/DBConnection.php";
include_once 'objects/Item.php';
require_once "objects/Cart.php";
require_once "objects/Session.php";

class Cart{
    private $items = array();
    
    //add the item to the cart array
    public function AddItem(Item $item)
    {
        $this->items[] = $item;
    }

    //reset the cart's items array
    public function EmptyCart($pdo, $shouldRestock)
    {
        if($shouldRestock)
        {
            foreach($this->GetItems() as $item)
            {
                $id = $item->getItemID();
                $pdo->query( "UPDATE Item SET itemHidden = False WHERE itemID = $id");  
            }
        }
        $this->items = array();
    }


    //GETTERS

    public function GetItems() : array
    {
        return $this->items;
    }
}
?>